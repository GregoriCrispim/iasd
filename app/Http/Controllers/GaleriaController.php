<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use ZipArchive;

class GaleriaController extends Controller
{
    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    private const FOTOS_DIR = 'img/galeria/fotos';

    private const THUMBS_DIR = 'img/galeria/thumbs';

    private const THUMB_WIDTH = 480;

    private const CARROSSEL_EVENTOS = 3;

    private const CARROSSEL_FOTOS = 10;

    private const FOTOS_POR_LOTE = 40;

    /**
     * Lista as programações (eventos) disponíveis na galeria.
     */
    public function index()
    {
        $baseDir = public_path(self::FOTOS_DIR);
        $eventos = [];

        if (is_dir($baseDir)) {
            foreach (scandir($baseDir) as $item) {
                if ($item === '.' || $item === '..' || !is_dir($baseDir.DIRECTORY_SEPARATOR.$item)) {
                    continue;
                }

                $eventos[] = $this->buildEventoSummary($item);
            }
        }

        usort($eventos, fn ($a, $b) => strcmp($b['rawDate'], $a['rawDate']));

        $months = [];
        foreach ($eventos as $evento) {
            if (!$evento['rawDate'] || isset($months[$evento['monthKey']])) {
                continue;
            }
            $months[$evento['monthKey']] = $evento['monthLabel'];
        }

        return view('pages.galeria.index', [
            'eventos' => $eventos,
            'months' => $months,
            'carrossel' => $this->buildCarrossel(array_slice($eventos, 0, self::CARROSSEL_EVENTOS)),
        ]);
    }

    /**
     * Exibe as fotos de uma programação específica.
     */
    public function show(string $evento)
    {
        $eventoDir = public_path(self::FOTOS_DIR.'/'.$evento);

        abort_unless(is_dir($eventoDir), 404);

        return view('pages.galeria.evento', [
            'evento' => $this->buildEventoSummary($evento),
            'fotos' => $this->listPhotos($evento),
            'fotosPorLote' => self::FOTOS_POR_LOTE,
        ]);
    }

    /**
     * Gera (e cacheia em disco) uma miniatura otimizada da foto, servindo o
     * original como fallback caso a geração não seja possível no servidor.
     */
    public function thumb(Request $request)
    {
        $evento = (string) $request->query('evento', '');
        $foto = (string) $request->query('foto', '');

        abort_if($evento === '' || $foto === '', 404);
        abort_if(str_contains($evento, '..') || str_contains($foto, '..'), 404);

        $original = public_path(self::FOTOS_DIR."/{$evento}/{$foto}");
        abort_unless(is_file($original), 404);

        // Em shared hosting o GD/WebP costuma estourar memória; se falhar,
        // redireciona para o arquivo estático (já funciona em produção).
        try {
            $thumbDir = public_path(self::THUMBS_DIR."/{$evento}");
            $thumbPath = $thumbDir.'/'.$foto;

            if (! is_file($thumbPath)) {
                if (! is_dir($thumbDir)) {
                    @mkdir($thumbDir, 0755, true);
                }
                $this->generateThumbnail($original, $thumbPath, self::THUMB_WIDTH);
            }

            $servePath = is_file($thumbPath) ? $thumbPath : $original;

            return response()->file($servePath, [
                'Cache-Control' => 'public, max-age=31536000, immutable',
            ]);
        } catch (\Throwable) {
            return redirect()->to($this->photoAssetUrl($evento, $foto), 302);
        }
    }

    /**
     * Baixa as fotos da programação compactadas em .zip. Se a query "files[]"
     * for informada, apenas as fotos selecionadas entram no pacote.
     */
    public function download(Request $request, string $evento)
    {
        abort_if(str_contains($evento, '..'), 404);

        $eventoDir = public_path(self::FOTOS_DIR.'/'.$evento);
        abort_unless(is_dir($eventoDir), 404);

        abort_unless(class_exists(ZipArchive::class), 500, 'Compactação de arquivos indisponível neste servidor.');

        $todas = $this->imageFiles($eventoDir);

        $selecionadas = $request->query('files', []);
        $arquivos = is_array($selecionadas) && count($selecionadas) > 0
            ? array_values(array_intersect($todas, $selecionadas))
            : $todas;

        abort_if(empty($arquivos), 404);

        $zipPath = tempnam(sys_get_temp_dir(), 'galeria_zip_');

        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::OVERWRITE);
        foreach ($arquivos as $arquivo) {
            $zip->addFile($eventoDir.DIRECTORY_SEPARATOR.$arquivo, $arquivo);
        }
        $zip->close();

        $nomeZip = Str::slug($this->buildEventoSummary($evento)['title'] ?: $evento).'.zip';

        return response()->download($zipPath, $nomeZip)->deleteFileAfterSend(true);
    }

    private function buildEventoSummary(string $folder): array
    {
        $parts = explode('_', $folder, 2);
        $rawDate = $parts[0] ?? '';
        $title = isset($parts[1]) ? str_replace('-', ' ', $parts[1]) : $folder;

        $files = $this->imageFiles(public_path(self::FOTOS_DIR.'/'.$folder));

        $capa = null;
        foreach ($files as $file) {
            if (str_starts_with(mb_strtolower($file), 'capa')) {
                $capa = $file;
                break;
            }
        }
        $cover = $capa ?? ($files[0] ?? null);

        $date = null;
        $dateShort = null;
        $dateLong = null;
        $monthKey = '';
        $monthLabel = '';

        if ($rawDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawDate)) {
            $date = Carbon::createFromFormat('Y-m-d', $rawDate)->startOfDay()->locale('pt_BR');
            $dateShort = $date->format('d/m/Y');
            $dateLong = $date->translatedFormat('d').' de '.ucfirst($date->translatedFormat('F')).' de '.$date->format('Y');
            $monthKey = $date->format('Y-m');
            $monthLabel = ucfirst($date->translatedFormat('F')).' de '.$date->format('Y');
        }

        return [
            'id' => $folder,
            'title' => $title !== '' ? $title : $folder,
            'rawDate' => $rawDate,
            'dateShort' => $dateShort,
            'dateLong' => $dateLong,
            'monthKey' => $monthKey,
            'monthLabel' => $monthLabel,
            // Usa asset estático (confiável na Hostgator). A rota /thumb pode falhar
            // quando o GD/WebP estoura memória ao gerar miniaturas.
            'coverUrl' => $cover ? $this->photoAssetUrl($folder, $cover) : null,
            'photoCount' => count($files),
        ];
    }

    /**
     * Monta a amostra de fotos aleatórias dos últimos eventos usada no
     * carrossel da página inicial da galeria.
     */
    private function buildCarrossel(array $eventosRecentes): array
    {
        $pool = [];

        foreach ($eventosRecentes as $evento) {
            $files = $this->imageFiles(public_path(self::FOTOS_DIR.'/'.$evento['id']));
            if (empty($files)) {
                continue;
            }

            shuffle($files);
            $amostra = array_slice($files, 0, min(count($files), 4));

            foreach ($amostra as $file) {
                $pool[] = [
                    'eventoId' => $evento['id'],
                    'eventoTitle' => $evento['title'],
                    'eventoDate' => $evento['dateShort'],
                    'thumbUrl' => $this->photoAssetUrl($evento['id'], $file),
                ];
            }
        }

        shuffle($pool);

        return array_slice($pool, 0, self::CARROSSEL_FOTOS);
    }

    private function listPhotos(string $evento): array
    {
        $files = $this->imageFiles(public_path(self::FOTOS_DIR.'/'.$evento));

        return array_map(fn ($file) => [
            'name' => $file,
            'url' => $this->photoAssetUrl($evento, $file),
            'thumbUrl' => $this->photoAssetUrl($evento, $file),
        ], $files);
    }

    /**
     * Monta URL pública com cada segmento do path corretamente percent-encoded
     * (pastas com espaços/acentos quebram se passadas cruas para asset()).
     */
    private function photoAssetUrl(string $evento, string $file): string
    {
        $relative = self::FOTOS_DIR.'/'.$evento.'/'.$file;
        $encoded = implode('/', array_map('rawurlencode', explode('/', $relative)));

        return asset($encoded);
    }

    private function imageFiles(string $dir): array
    {
        if (!is_dir($dir)) {
            return [];
        }

        $files = array_values(array_filter(scandir($dir), function ($item) use ($dir) {
            if ($item === '.' || $item === '..' || !is_file($dir.DIRECTORY_SEPARATOR.$item)) {
                return false;
            }

            return in_array(strtolower(pathinfo($item, PATHINFO_EXTENSION)), self::IMAGE_EXTENSIONS, true);
        }));

        sort($files);

        return $files;
    }

    /**
     * Gera uma miniatura .webp reduzida via GD. Retorna false (sem lançar
     * exceção) se a extensão GD/WebP não estiver disponível no servidor,
     * caso em que o chamador cai de volta para a imagem original.
     */
    private function generateThumbnail(string $source, string $destination, int $maxWidth): bool
    {
        if (strtolower(pathinfo($source, PATHINFO_EXTENSION)) !== 'webp'
            || !function_exists('imagecreatefromwebp')
            || !function_exists('imagewebp')) {
            return false;
        }

        $image = @imagecreatefromwebp($source);
        if ($image === false) {
            return false;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        if ($width <= $maxWidth) {
            $ok = @imagewebp($image, $destination, 82);
            imagedestroy($image);

            return $ok;
        }

        $newHeight = (int) round($height * ($maxWidth / $width));
        $resized = imagescale($image, $maxWidth, $newHeight, IMG_LANCZOS);
        imagedestroy($image);

        if ($resized === false) {
            return false;
        }

        $ok = @imagewebp($resized, $destination, 82);
        imagedestroy($resized);

        return $ok;
    }
}
