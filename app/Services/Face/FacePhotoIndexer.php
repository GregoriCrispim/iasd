<?php

namespace App\Services\Face;

use App\Models\GalleryPhoto;
use Symfony\Component\Process\Process;

/**
 * Indexa rostos de uma foto no servidor via Node (legado face-api + canvas).
 * Na HostGator o fluxo suportado é só browser com @vladmandic/human (v3).
 */
class FacePhotoIndexer
{
    public function __construct(private readonly FaceDescriptorService $descriptors) {}

    /**
     * Processa uma foto e persiste o resultado (ready / no_face / failed).
     *
     * @return array{status:string,faces:int}
     */
    public function process(GalleryPhoto $photo): array
    {
        $absolute = $photo->absolutePath();
        if (! is_file($absolute)) {
            return $this->markFailed($photo, 'Arquivo da foto não encontrado no disco.');
        }

        $modelsDir = $this->modelsAbsolutePath();
        if ($modelsDir === null) {
            return $this->markFailed($photo, 'Modelos face-api não encontrados no servidor.');
        }

        $script = base_path('scripts/face-index-photo.mjs');
        if (! is_file($script)) {
            return $this->markFailed($photo, 'Script de indexação facial ausente.');
        }

        $node = $this->nodeBinary();
        if ($node === '') {
            return $this->markFailed($photo, 'Binário Node.js não encontrado no servidor.');
        }

        $cfg = config('face.detection.photo', []);

        $process = new Process([
            $node,
            $script,
            '--image='.$absolute,
            '--models='.$modelsDir,
            '--minScore='.(string) ($cfg['min_score'] ?? 0.30),
            '--minSizeRatio='.(string) ($cfg['min_size_ratio'] ?? 0.01),
            '--maxFaces='.(string) ($cfg['max_faces'] ?? 80),
            '--maxSide='.(string) ($cfg['analysis_max_side'] ?? 1536),
        ], base_path(), null, null, 180);

        $process->run();

        $payload = json_decode($process->getOutput(), true);
        if (! is_array($payload)) {
            $err = trim($process->getErrorOutput() ?: $process->getOutput()) ?: 'Falha ao indexar a foto.';

            return $this->markFailed($photo, mb_substr($err, 0, 200));
        }

        if (! ($payload['ok'] ?? false)) {
            return $this->markFailed($photo, (string) ($payload['reason'] ?? 'Falha na detecção facial.'));
        }

        $status = (string) ($payload['status'] ?? 'failed');
        $faces = is_array($payload['faces'] ?? null) ? $payload['faces'] : [];
        $modelVersion = (string) config('face.version', 'v3');
        $stored = 0;

        try {
            if ($status === 'ready' && $faces !== []) {
                $stored = $this->descriptors->replaceForPhoto($photo, $faces, $modelVersion);
                if ($stored === 0) {
                    $status = 'no_face';
                }
            } else {
                $photo->faceDescriptors()->delete();
                if ($status !== 'no_face') {
                    $status = 'no_face';
                }
            }
        } catch (\Throwable $e) {
            report($e);

            return $this->markFailed($photo, 'Não foi possível gravar os descritores desta foto.');
        }

        $meta = $photo->meta_json ?? [];
        $meta['faces_reason'] = $payload['reason'] ?? null;
        $meta['faces_model_version'] = $modelVersion;
        $meta['faces_indexed_by'] = 'server';

        $photo->forceFill([
            'faces_status' => $status,
            'faces_scanned_at' => now(),
            'meta_json' => $meta,
        ])->save();

        return ['status' => $status, 'faces' => $stored];
    }

    public function isAvailable(): bool
    {
        return is_file(base_path('scripts/face-index-photo.mjs'))
            && $this->modelsAbsolutePath() !== null
            && $this->nodeBinary() !== '';
    }

    /**
     * @return array{status:string,faces:int}
     */
    protected function markFailed(GalleryPhoto $photo, string $reason): array
    {
        $meta = $photo->meta_json ?? [];
        $meta['faces_reason'] = $reason;
        $meta['faces_indexed_by'] = 'server';

        $photo->faceDescriptors()->delete();
        $photo->forceFill([
            'faces_status' => 'failed',
            'faces_scanned_at' => now(),
            'meta_json' => $meta,
        ])->save();

        return ['status' => 'failed', 'faces' => 0];
    }

    protected function modelsAbsolutePath(): ?string
    {
        $url = (string) config('face.models_url', '/models/face-api/1.7.15');
        $relative = ltrim(parse_url($url, PHP_URL_PATH) ?: $url, '/');
        $absolute = public_path($relative);

        return is_dir($absolute) ? $absolute : null;
    }

    protected function nodeBinary(): string
    {
        $configured = (string) (config('face.node_binary') ?: env('FACE_NODE_BINARY', ''));
        if ($configured !== '' && is_executable($configured)) {
            return $configured;
        }

        foreach (['node', '/usr/bin/node', '/usr/local/bin/node'] as $candidate) {
            $process = new Process([$candidate, '-v']);
            $process->setTimeout(5);
            $process->run();
            if ($process->isSuccessful()) {
                return $candidate;
            }
        }

        return '';
    }
}
