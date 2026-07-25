<?php

namespace App\Console\Commands;

use App\Models\GalleryPhoto;
use App\Services\GalleryImageProcessor;
use Illuminate\Console\Command;

class OtimizarGaleria extends Command
{
    protected $signature = 'galeria:otimizar
                            {--album= : Processa apenas um álbum (id)}
                            {--limit=0 : Máximo de fotos por execução (0 = todas)}
                            {--force : Reprocessa também as fotos já otimizadas}';

    protected $description = 'Reduz e converte para WebP as fotos da galeria, gerando miniatura e versão de exibição';

    public function handle(GalleryImageProcessor $processor): int
    {
        if (! GalleryImageProcessor::isSupported()) {
            $this->error('Este PHP não tem GD com suporte a WebP. Habilite a extensão `gd` e rode novamente.');

            return self::FAILURE;
        }

        $query = GalleryPhoto::query()->orderBy('id');

        if (! $this->option('force')) {
            $query->whereNull('optimized_at');
        }

        if ($album = $this->option('album')) {
            $query->where('gallery_album_id', (int) $album);
        }

        if (($limit = (int) $this->option('limit')) > 0) {
            $query->limit($limit);
        }

        $total = $query->count();

        if ($total === 0) {
            $this->info('Nenhuma foto pendente de otimização.');

            return self::SUCCESS;
        }

        $this->info("Otimizando {$total} foto(s)...");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $done = 0;
        $failed = 0;
        $bytesBefore = 0;
        $bytesAfter = 0;

        foreach ($query->cursor() as $photo) {
            $before = (int) ($photo->meta_json['original_size_bytes'] ?? $photo->size_bytes);

            try {
                $result = $processor->process($photo->path);
            } catch (\Throwable $exception) {
                $result = null;
                $this->newLine();
                $this->warn("Foto #{$photo->id}: {$exception->getMessage()}");
            }

            if ($result === null) {
                $failed++;
                $bar->advance();
                continue;
            }

            $photo->update([
                'path' => $result['path'],
                'mime_type' => $result['mime_type'],
                'size_bytes' => $result['size_bytes'],
                'width' => $result['width'],
                'height' => $result['height'],
                'optimized_at' => now(),
                'meta_json' => array_merge(is_array($photo->meta_json) ? $photo->meta_json : [], [
                    'original_size_bytes' => $before,
                    'variants' => [
                        'thumb' => $result['thumb'],
                        'display' => $result['display'],
                    ],
                ]),
            ]);

            $bytesBefore += $before;
            $bytesAfter += $result['size_bytes'];
            $done++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Otimizadas: {$done}".($failed > 0 ? " · Ignoradas: {$failed}" : ''));

        if ($bytesBefore > 0) {
            $saved = max(0, $bytesBefore - $bytesAfter);
            $percent = round(($saved / $bytesBefore) * 100, 1);
            $this->line(sprintf(
                'Antes: %s · Depois: %s · Economia: %s (%s%%)',
                $this->humanBytes($bytesBefore),
                $this->humanBytes($bytesAfter),
                $this->humanBytes($saved),
                $percent
            ));
        }

        return self::SUCCESS;
    }

    private function humanBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes.' B';
        }

        if ($bytes < 1048576) {
            return round($bytes / 1024, 1).' KB';
        }

        return round($bytes / 1048576, 1).' MB';
    }
}
