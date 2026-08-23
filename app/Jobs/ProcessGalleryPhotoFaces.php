<?php

namespace App\Jobs;

use App\Models\GalleryPhoto;
use App\Services\Face\FacePhotoIndexer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ProcessGalleryPhotoFaces implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $timeout = 240;

    public function __construct(public int $photoId) {}

    public function handle(FacePhotoIndexer $indexer): void
    {
        if (! config('face.enabled', true)) {
            return;
        }

        $photo = GalleryPhoto::query()->find($this->photoId);
        if (! $photo) {
            return;
        }

        // Evita reprocessar o que o navegador do admin já concluiu.
        if (! in_array($photo->faces_status, ['pending', 'failed', null], true)) {
            return;
        }

        if (! $indexer->isAvailable()) {
            return;
        }

        $indexer->process($photo);
    }

    public function failed(?Throwable $exception): void
    {
        $photo = GalleryPhoto::query()->find($this->photoId);
        if (! $photo || ! in_array($photo->faces_status, ['pending', 'failed', null], true)) {
            return;
        }

        $meta = $photo->meta_json ?? [];
        $meta['faces_reason'] = $exception
            ? mb_substr($exception->getMessage(), 0, 200)
            : 'Falha na fila de indexação facial.';
        $meta['faces_indexed_by'] = 'server';

        $photo->forceFill([
            'faces_status' => 'failed',
            'faces_scanned_at' => now(),
            'meta_json' => $meta,
        ])->save();
    }
}
