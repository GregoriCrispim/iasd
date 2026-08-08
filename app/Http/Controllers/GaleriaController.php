<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Services\GalleryImageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class GaleriaController extends Controller
{
    private const THUMB_WIDTH = GalleryImageProcessor::THUMB_MAX_WIDTH;

    private const CARROSSEL_EVENTOS = 3;

    private const CARROSSEL_FOTOS = 10;

    private const FOTOS_POR_LOTE = 24;

    /**
     * Lista as programações (eventos) disponíveis na galeria.
     */
    public function index()
    {
        $albums = GalleryAlbum::query()
            ->published()
            ->with(['coverPhoto'])
            ->withCount('photos')
            ->orderByDesc('event_date')
            ->orderByDesc('id')
            ->get();

        $eventos = $albums->map(fn (GalleryAlbum $album) => $album->toPublicSummary())->all();

        $months = [];
        foreach ($eventos as $evento) {
            if (! $evento['rawDate'] || isset($months[$evento['monthKey']])) {
                continue;
            }
            $months[$evento['monthKey']] = $evento['monthLabel'];
        }

        return view('pages.galeria.index', [
            'eventos' => $eventos,
            'months' => $months,
            'carrossel' => $this->buildCarrossel($albums->take(self::CARROSSEL_EVENTOS)),
        ]);
    }

    /**
     * Exibe as fotos de uma programação específica.
     */
    public function show(string $evento)
    {
        $album = GalleryAlbum::query()
            ->published()
            ->where('slug', $evento)
            ->with(['coverPhoto', 'photos'])
            ->firstOrFail();

        return response()
            ->view('pages.galeria.evento', [
                'evento' => $album->toPublicSummary(),
                'fotos' => $album->photos
                    ->filter(fn (GalleryPhoto $photo) => is_file($photo->absolutePath()))
                    ->map(fn (GalleryPhoto $photo) => $photo->toPublicArray())
                    ->values()
                    ->all(),
                'fotosPorLote' => self::FOTOS_POR_LOTE,
            ])
            // A busca facial usa a câmera (getUserMedia) na própria origem.
            ->header('Permissions-Policy', 'camera=(self)');
    }

    /**
     * Gera (e cacheia em disco) uma miniatura otimizada da foto, servindo o
     * original como fallback caso a geração não seja possível no servidor.
     */
    public function thumb(Request $request)
    {
        $photoId = (int) $request->query('photo', 0);
        abort_if($photoId < 1, 404);

        $photo = GalleryPhoto::query()
            ->with('album')
            ->findOrFail($photoId);

        abort_unless($photo->album && $photo->album->is_published, 404);

        $original = $photo->absolutePath();
        abort_unless(is_file($original), 404);

        try {
            // Só grava miniatura no disco novo se o upload existir; senão serve
            // o arquivo legado direto (evita criar pastas vazias na HostGator).
            if ($photo->uploadFileExists()) {
                $thumbRelative = $photo->variantPath('thumb')
                    ?? 'thumbs/'.$photo->gallery_album_id.'/'.pathinfo($photo->basename(), PATHINFO_FILENAME).'.webp';
                $thumbAbsolute = Storage::disk(GalleryPhoto::DISK)->path($thumbRelative);

                if (! is_file($thumbAbsolute)) {
                    $thumbDir = dirname($thumbAbsolute);
                    if (! is_dir($thumbDir)) {
                        @mkdir($thumbDir, 0755, true);
                    }
                    $this->generateThumbnail($original, $thumbAbsolute, self::THUMB_WIDTH);
                }

                if (is_file($thumbAbsolute)) {
                    return response()->file($thumbAbsolute, [
                        'Content-Type' => 'image/webp',
                        'Cache-Control' => 'public, max-age=31536000, immutable',
                    ]);
                }
            }

            return response()->file($original, [
                'Cache-Control' => 'public, max-age=31536000, immutable',
            ]);
        } catch (\Throwable) {
            $fallback = $photo->legacyPublicUrl() ?? $photo->publicUrl();

            return redirect()->to($fallback, 302);
        }
    }

    /**
     * Baixa as fotos da programação compactadas em .zip. Se a query "files[]"
     * for informada, apenas as fotos selecionadas entram no pacote.
     */
    public function download(Request $request, string $evento)
    {
        $album = GalleryAlbum::query()
            ->published()
            ->where('slug', $evento)
            ->with('photos')
            ->firstOrFail();

        abort_unless(class_exists(ZipArchive::class), 500, 'Compactação de arquivos indisponível neste servidor.');

        $photos = $album->photos;
        $selecionadas = $request->query('files', []);

        if (is_array($selecionadas) && count($selecionadas) > 0) {
            // O JS identifica as fotos pelo nome original; o basename é o UUID
            // gravado em disco. Aceitar os dois evita um .zip vazio.
            $photos = $photos->filter(fn (GalleryPhoto $p) => in_array($p->basename(), $selecionadas, true)
                || in_array($p->original_filename, $selecionadas, true))->values();
        }

        abort_if($photos->isEmpty(), 404);

        $zipPath = tempnam(sys_get_temp_dir(), 'galeria_zip_');

        $zip = new ZipArchive;
        $zip->open($zipPath, ZipArchive::OVERWRITE);

        foreach ($photos as $photo) {
            $absolute = $photo->absolutePath();
            if (! is_file($absolute)) {
                continue;
            }
            $zip->addFile($absolute, $photo->downloadName());
        }
        $zip->close();

        $nomeZip = Str::slug($album->title ?: $album->slug).'.zip';

        return response()->download($zipPath, $nomeZip)->deleteFileAfterSend(true);
    }

    /**
     * @param  Collection<int, GalleryAlbum>  $albums
     * @return list<array{eventoId:string,eventoTitle:string,eventoDate:?string,imageUrl:string}>
     */
    private function buildCarrossel($albums): array
    {
        $pool = [];

        foreach ($albums as $album) {
            $photos = GalleryPhoto::query()
                ->where('gallery_album_id', $album->id)
                ->inRandomOrder()
                ->limit(4)
                ->get();

            if ($photos->isEmpty()) {
                continue;
            }

            foreach ($photos as $photo) {
                $pool[] = [
                    'eventoId' => $album->slug,
                    'eventoTitle' => $album->title,
                    'eventoDate' => $album->dateShort(),
                    // Versão de overlay (~1920px): o carrossel é grande e precisa
                    // de mais qualidade do que a miniatura da grade.
                    'imageUrl' => $photo->displayUrl(),
                ];
            }
        }

        shuffle($pool);

        return array_slice($pool, 0, self::CARROSSEL_FOTOS);
    }

    /**
     * Gera uma miniatura .webp reduzida via GD. Retorna false (sem lançar
     * exceção) se a extensão GD/WebP não estiver disponível no servidor,
     * caso em que o chamador cai de volta para a imagem original.
     */
    private function generateThumbnail(string $source, string $destination, int $maxWidth): bool
    {
        $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));

        $loaders = [
            'webp' => 'imagecreatefromwebp',
            'jpg' => 'imagecreatefromjpeg',
            'jpeg' => 'imagecreatefromjpeg',
            'png' => 'imagecreatefrompng',
            'gif' => 'imagecreatefromgif',
        ];

        $loader = $loaders[$ext] ?? null;
        if (! $loader || ! function_exists($loader) || ! function_exists('imagewebp')) {
            return false;
        }

        $image = @$loader($source);
        if ($image === false) {
            return false;
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $side = max($width, $height);

        if ($side <= $maxWidth) {
            $ok = @imagewebp($image, $destination, 72);
            imagedestroy($image);

            return $ok;
        }

        $ratio = $maxWidth / $side;
        $resized = imagescale(
            $image,
            max(1, (int) round($width * $ratio)),
            max(1, (int) round($height * $ratio)),
            GalleryImageProcessor::scaleMode()
        );
        imagedestroy($image);

        if ($resized === false) {
            return false;
        }

        $ok = @imagewebp($resized, $destination, 72);
        imagedestroy($resized);

        return $ok;
    }
}
