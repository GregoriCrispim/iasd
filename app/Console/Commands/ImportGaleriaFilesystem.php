<?php

namespace App\Console\Commands;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Services\GalleryImageProcessor;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportGaleriaFilesystem extends Command
{
    protected $signature = 'galeria:import-filesystem
                            {--path=img/galeria/fotos : Caminho relativo a public/}
                            {--dry-run : Apenas lista o que seria importado}
                            {--force : Reimporta pastas cujo slug já existe (pula fotos já mapeadas pelo nome original)}';

    protected $description = 'Importa álbuns e fotos de public/img/galeria/fotos para o disco galeria + banco';

    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    public function handle(GalleryImageProcessor $processor): int
    {
        $relative = trim((string) $this->option('path'), '/');
        $baseDir = public_path($relative);
        $dryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');

        if (! is_dir($baseDir)) {
            $this->error("Pasta não encontrada: {$baseDir}");

            return self::FAILURE;
        }

        $folders = array_values(array_filter(scandir($baseDir) ?: [], function ($item) use ($baseDir) {
            return $item !== '.' && $item !== '..' && is_dir($baseDir.DIRECTORY_SEPARATOR.$item);
        }));

        if (empty($folders)) {
            $this->warn('Nenhuma pasta de evento encontrada.');

            return self::SUCCESS;
        }

        if (! $dryRun && ! GalleryImageProcessor::isSupported()) {
            $this->warn('GD/WebP indisponível neste PHP: as fotos entram sem otimização. Rode `galeria:otimizar` depois.');
        }

        $this->info(($dryRun ? '[dry-run] ' : '').'Importando '.count($folders).' pasta(s)...');

        $albumsCreated = 0;
        $photosImported = 0;
        $skipped = 0;

        foreach ($folders as $folder) {
            [$eventDate, $title] = $this->parseFolderName($folder);
            $slug = $folder;

            $existing = GalleryAlbum::query()->where('slug', $slug)->first();

            if ($existing && ! $force) {
                $this->line("  · {$folder}: já existe (use --force para completar fotos faltantes)");
                $skipped++;
                continue;
            }

            $files = $this->imageFiles($baseDir.DIRECTORY_SEPARATOR.$folder);
            if (empty($files)) {
                $this->warn("  · {$folder}: sem imagens, ignorado");
                continue;
            }

            if ($dryRun) {
                $this->line("  · {$folder} → \"{$title}\" (".count($files).' fotos)');
                continue;
            }

            $album = $existing ?? GalleryAlbum::create([
                'title' => $title,
                'slug' => $slug,
                'event_date' => $eventDate,
                'description' => null,
                'is_published' => true,
                'created_by' => null,
            ]);

            if (! $existing) {
                $albumsCreated++;
            }

            $maxOrder = (int) $album->photos()->max('sort_order');
            $coverCandidate = null;
            $importedThisAlbum = 0;

            foreach ($files as $file) {
                $source = $baseDir.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$file;

                $already = $album->photos()
                    ->where('original_filename', $file)
                    ->exists();

                if ($already) {
                    continue;
                }

                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $filename = Str::uuid()->toString().'.'.$ext;
                $path = $album->id.'/'.$filename;

                Storage::disk(GalleryPhoto::DISK)->put($path, file_get_contents($source));

                $width = null;
                $height = null;
                $sizeInfo = @getimagesize($source);
                if (is_array($sizeInfo)) {
                    $width = $sizeInfo[0] ?? null;
                    $height = $sizeInfo[1] ?? null;
                }

                $originalSize = filesize($source) ?: 0;

                $attributes = [
                    'gallery_album_id' => $album->id,
                    'path' => $path,
                    'original_filename' => $file,
                    'mime_type' => mime_content_type($source) ?: 'image/jpeg',
                    'size_bytes' => $originalSize,
                    'width' => $width,
                    'height' => $height,
                    'sort_order' => ++$maxOrder,
                    'faces_status' => 'pending',
                    'uploaded_by' => null,
                ];

                $optimized = null;

                try {
                    $optimized = $processor->process($path);
                } catch (\Throwable $exception) {
                    $this->warn("    ! {$file}: falha ao otimizar ({$exception->getMessage()})");
                }

                if ($optimized !== null) {
                    $attributes = array_merge($attributes, [
                        'path' => $optimized['path'],
                        'mime_type' => $optimized['mime_type'],
                        'size_bytes' => $optimized['size_bytes'],
                        'width' => $optimized['width'],
                        'height' => $optimized['height'],
                        'optimized_at' => now(),
                        'meta_json' => [
                            'original_size_bytes' => $originalSize,
                            'variants' => [
                                'thumb' => $optimized['thumb'],
                                'display' => $optimized['display'],
                            ],
                        ],
                    ]);
                }

                $photo = GalleryPhoto::create($attributes);

                if ($coverCandidate === null && str_starts_with(mb_strtolower($file), 'capa')) {
                    $coverCandidate = $photo;
                }

                $importedThisAlbum++;
                $photosImported++;
            }

            if ($coverCandidate) {
                $album->update(['cover_photo_id' => $coverCandidate->id]);
            } elseif (! $album->cover_photo_id) {
                $first = $album->photos()->first();
                if ($first) {
                    $album->update(['cover_photo_id' => $first->id]);
                }
            }

            $this->line("  ✓ {$folder}: +{$importedThisAlbum} (total ".$album->photos()->count().')');
        }

        if ($dryRun) {
            $this->info('Dry-run concluído. Nada foi gravado.');
        } else {
            $this->info("Concluído: {$albumsCreated} álbum(ns) novo(s), {$photosImported} foto(s) importada(s), {$skipped} pasta(s) pulada(s).");
        }

        return self::SUCCESS;
    }

    /**
     * @return array{0:?Carbon,1:string}
     */
    private function parseFolderName(string $folder): array
    {
        $parts = explode('_', $folder, 2);
        $rawDate = $parts[0] ?? '';
        $title = isset($parts[1]) ? str_replace('-', ' ', $parts[1]) : $folder;

        $eventDate = null;
        if ($rawDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawDate)) {
            $eventDate = Carbon::createFromFormat('Y-m-d', $rawDate)->startOfDay();
        }

        return [$eventDate, $title !== '' ? $title : $folder];
    }

    /**
     * @return list<string>
     */
    private function imageFiles(string $dir): array
    {
        if (! is_dir($dir)) {
            return [];
        }

        $files = array_values(array_filter(scandir($dir) ?: [], function ($item) use ($dir) {
            if ($item === '.' || $item === '..' || ! is_file($dir.DIRECTORY_SEPARATOR.$item)) {
                return false;
            }

            return in_array(strtolower(pathinfo($item, PATHINFO_EXTENSION)), self::IMAGE_EXTENSIONS, true);
        }));

        sort($files);

        return $files;
    }
}
