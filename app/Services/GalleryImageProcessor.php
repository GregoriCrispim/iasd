<?php

namespace App\Services;

use App\Models\GalleryPhoto;
use GdImage;
use Illuminate\Support\Facades\Storage;

/**
 * Otimiza as fotos da galeria usando apenas GD (nativo do PHP), sem depender de
 * pacotes extras — o deploy por FTP não envia a pasta vendor.
 *
 * De cada foto enviada resultam três arquivos:
 *   - master  : o arquivo que fica guardado e alimenta o download em .zip
 *   - display : versão para a lightbox do site
 *   - thumb   : miniatura das grades do site e do painel
 *
 * Quando o servidor não tem GD/WebP, todos os métodos falham em silêncio e a
 * foto original é preservada como está.
 */
class GalleryImageProcessor
{
    // O arquivo guardado alimenta o download em .zip e é o material do
    // departamento de fotografia: fica em resolução alta e qualidade alta.
    public const MASTER_MAX_SIDE = 4096;

    public const DISPLAY_MAX_WIDTH = 1920;

    public const THUMB_MAX_WIDTH = 420;

    private const MASTER_QUALITY = 92;

    private const DISPLAY_QUALITY = 86;

    private const THUMB_QUALITY = 72;

    private const LOADERS = [
        'jpg' => 'imagecreatefromjpeg',
        'jpeg' => 'imagecreatefromjpeg',
        'png' => 'imagecreatefrompng',
        'gif' => 'imagecreatefromgif',
        'webp' => 'imagecreatefromwebp',
    ];

    public static function isSupported(): bool
    {
        return function_exists('imagecreatetruecolor')
            && function_exists('imagewebp')
            && function_exists('imagescale');
    }

    /**
     * IMG_LANCZOS só existe em algumas compilações do GD; sem esse cuidado o
     * redimensionamento quebra com "Undefined constant" e nenhuma derivada é
     * gerada, deixando o site servir as fotos em tamanho original.
     */
    public static function scaleMode(): int
    {
        return defined('IMG_LANCZOS') ? (int) constant('IMG_LANCZOS') : IMG_BICUBIC;
    }

    /**
     * Processa a foto já gravada no disco da galeria.
     *
     * @return array{path:string,mime_type:string,size_bytes:int,width:int,height:int,thumb:?string,display:?string}|null
     *                                                                                                                   null quando não foi possível otimizar; nesse caso nada é alterado.
     */
    public function process(string $relativePath): ?array
    {
        if (! self::isSupported()) {
            return null;
        }

        $disk = Storage::disk(GalleryPhoto::DISK);
        $source = $disk->path($relativePath);

        if (! is_file($source)) {
            return null;
        }

        $extension = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION));
        if (! isset(self::LOADERS[$extension])) {
            return null;
        }

        if (! $this->ensureMemoryFor($source)) {
            return null;
        }

        // GIF pode ser animado e o GD lê apenas o primeiro quadro: o arquivo
        // original é preservado e só as derivadas estáticas são geradas.
        $keepOriginal = $extension === 'gif';

        @set_time_limit(120);

        $image = $this->load($source, $extension);
        if (! $image instanceof GdImage) {
            return null;
        }

        try {
            if (in_array($extension, ['jpg', 'jpeg'], true)) {
                $image = $this->applyExifOrientation($image, $source);
            }

            if (! $keepOriginal && max(imagesx($image), imagesy($image)) > self::MASTER_MAX_SIDE) {
                $image = $this->scaleToLongestSide($image, self::MASTER_MAX_SIDE);
            }

            $masterPath = $relativePath;
            $masterMime = 'image/gif';

            if (! $keepOriginal) {
                $masterMime = 'image/webp';
                $masterPath = $this->replaceExtension($relativePath, 'webp');

                if (! $this->writeWebp($image, $disk->path($masterPath), null, self::MASTER_QUALITY)) {
                    return null;
                }

                if ($masterPath !== $relativePath) {
                    $disk->delete($relativePath);
                }
            }

            $album = strtok($relativePath, '/') ?: '0';
            $basename = pathinfo($masterPath, PATHINFO_FILENAME);

            $thumbPath = 'thumbs/'.$album.'/'.$basename.'.webp';
            if (! $this->writeWebp($image, $disk->path($thumbPath), self::THUMB_MAX_WIDTH, self::THUMB_QUALITY, true)) {
                $thumbPath = null;
            }

            $displayPath = null;
            if (imagesx($image) > self::DISPLAY_MAX_WIDTH || imagesy($image) > self::DISPLAY_MAX_WIDTH) {
                $displayPath = 'display/'.$album.'/'.$basename.'.webp';
                if (! $this->writeWebp($image, $disk->path($displayPath), self::DISPLAY_MAX_WIDTH, self::DISPLAY_QUALITY, false)) {
                    $displayPath = null;
                }
            }

            clearstatcache(true, $disk->path($masterPath));

            return [
                'path' => $masterPath,
                'mime_type' => $masterMime,
                'size_bytes' => (int) (@filesize($disk->path($masterPath)) ?: 0),
                'width' => imagesx($image),
                'height' => imagesy($image),
                'thumb' => $thumbPath,
                'display' => $displayPath,
            ];
        } finally {
            imagedestroy($image);
        }
    }

    private function load(string $source, string $extension): ?GdImage
    {
        $loader = self::LOADERS[$extension] ?? null;
        if (! $loader || ! function_exists($loader)) {
            return null;
        }

        $image = @$loader($source);
        if (! $image instanceof GdImage) {
            return null;
        }

        if ($extension !== 'jpg' && $extension !== 'jpeg') {
            @imagepalettetotruecolor($image);
            imagealphablending($image, false);
            imagesavealpha($image, true);
        }

        return $image;
    }

    /**
     * Grava uma cópia WebP reduzida.
     *
     * @param  bool  $byLongestSide  true = limita o maior lado (miniaturas);
     *                               false = limita a largura (versão de overlay).
     */
    private function writeWebp(GdImage $image, string $absoluteTarget, ?int $maxSide, int $quality, bool $byLongestSide = false): bool
    {
        $this->ensureDirectory($absoluteTarget);

        $resized = null;
        $target = $image;

        if ($maxSide !== null) {
            $width = imagesx($image);
            $height = imagesy($image);
            $current = $byLongestSide ? max($width, $height) : $width;

            if ($current > $maxSide) {
                $ratio = $maxSide / $current;
                $newW = max(1, (int) round($width * $ratio));
                $newH = max(1, (int) round($height * $ratio));
                $resized = @imagescale($image, $newW, $newH, self::scaleMode());

                if ($resized instanceof GdImage) {
                    imagealphablending($resized, false);
                    imagesavealpha($resized, true);
                    $target = $resized;
                }
            }
        }

        $ok = @imagewebp($target, $absoluteTarget, $quality);

        if ($resized instanceof GdImage) {
            imagedestroy($resized);
        }

        if (! $ok) {
            @unlink($absoluteTarget);
        }

        return (bool) $ok;
    }

    private function scaleToLongestSide(GdImage $image, int $maxSide): GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $ratio = $maxSide / max($width, $height);

        $scaled = @imagescale(
            $image,
            max(1, (int) round($width * $ratio)),
            max(1, (int) round($height * $ratio)),
            self::scaleMode()
        );

        if (! $scaled instanceof GdImage) {
            return $image;
        }

        imagealphablending($scaled, false);
        imagesavealpha($scaled, true);
        imagedestroy($image);

        return $scaled;
    }

    /**
     * Aplica a rotação registrada no EXIF: o WebP gerado não carrega metadados,
     * então sem isso fotos de câmera sairiam deitadas.
     */
    private function applyExifOrientation(GdImage $image, string $source): GdImage
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $exif = @exif_read_data($source);
        $orientation = is_array($exif) ? (int) ($exif['Orientation'] ?? 1) : 1;

        $rotation = match ($orientation) {
            3 => 180,
            5, 6, 7 => -90,
            8 => 90,
            default => 0,
        };

        $flip = match ($orientation) {
            2, 5 => IMG_FLIP_HORIZONTAL,
            4, 7 => IMG_FLIP_VERTICAL,
            default => null,
        };

        if ($rotation !== 0) {
            $rotated = @imagerotate($image, $rotation, 0);
            if ($rotated instanceof GdImage) {
                imagedestroy($image);
                $image = $rotated;
            }
        }

        if ($flip !== null && function_exists('imageflip')) {
            @imageflip($image, $flip);
        }

        return $image;
    }

    /**
     * Garante memória suficiente antes de abrir a imagem: sem isso, uma foto
     * grande derruba o processo com erro fatal e o envio se perde.
     */
    private function ensureMemoryFor(string $source): bool
    {
        $info = @getimagesize($source);
        if (! is_array($info)) {
            return false;
        }

        // GD trabalha com 4 bytes por pixel e mantém duas cópias no redimensionamento.
        $needed = (int) ((int) $info[0] * (int) $info[1] * 4 * 2.2) + (32 * 1024 * 1024);

        if ($this->memoryLimit() >= $needed) {
            return true;
        }

        @ini_set('memory_limit', (int) ceil($needed / 1048576).'M');

        return $this->memoryLimit() >= $needed;
    }

    private function memoryLimit(): int
    {
        $raw = trim((string) ini_get('memory_limit'));

        if ($raw === '' || $raw === '-1') {
            return PHP_INT_MAX;
        }

        $multiplier = match (strtolower(substr($raw, -1))) {
            'g' => 1024 ** 3,
            'm' => 1024 ** 2,
            'k' => 1024,
            default => 1,
        };

        return (int) $raw * $multiplier;
    }

    private function ensureDirectory(string $absolutePath): void
    {
        $directory = dirname($absolutePath);

        if (! is_dir($directory)) {
            @mkdir($directory, 0755, true);
        }
    }

    private function replaceExtension(string $relativePath, string $extension): string
    {
        $directory = pathinfo($relativePath, PATHINFO_DIRNAME);
        $filename = pathinfo($relativePath, PATHINFO_FILENAME);

        return ($directory !== '.' && $directory !== '' ? $directory.'/' : '').$filename.'.'.$extension;
    }
}
