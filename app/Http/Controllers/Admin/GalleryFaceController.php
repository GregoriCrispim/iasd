<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Services\Face\FaceDescriptorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryFaceController extends Controller
{
    public function __construct(private readonly FaceDescriptorService $descriptors) {}

    /**
     * Tela retomável de indexação facial de um álbum.
     */
    public function index(GalleryAlbum $album): View
    {
        $album->loadCount([
            'photos',
            'photos as ready_count' => fn ($q) => $q->where('faces_status', 'ready'),
            'photos as pending_count' => fn ($q) => $q->whereIn('faces_status', ['pending', 'failed']),
        ]);

        return view('admin.galeria.faces', [
            'album' => $album,
            'modelVersion' => (string) config('face.version', 'v1'),
        ]);
    }

    /**
     * Lista as fotos que precisam ser processadas no navegador.
     * ?scope=pending (padrão) ou ?scope=all (reprocessa tudo).
     */
    public function queue(Request $request, GalleryAlbum $album): JsonResponse
    {
        $scope = $request->query('scope') === 'all' ? 'all' : 'pending';

        $query = $album->photos()->orderBy('sort_order')->orderBy('id');

        if ($scope === 'pending') {
            $query->whereIn('faces_status', ['pending', 'failed'])
                ->orWhere(function ($q) use ($album) {
                    $q->where('gallery_album_id', $album->id)->whereNull('faces_status');
                });
        }

        $photos = $query->get()->map(fn (GalleryPhoto $photo) => [
            'id' => $photo->id,
            // Full-res: a variante display perde rostos pequenos/distantes.
            'url' => $photo->publicUrl(),
            'status' => $photo->faces_status,
        ])->values()->all();

        return response()->json([
            'model_version' => (string) config('face.version', 'v1'),
            'scope' => $scope,
            'photos' => $photos,
        ]);
    }

    /**
     * Grava (ou substitui) os descritores de uma foto enviados pelo navegador.
     */
    public function store(Request $request, GalleryAlbum $album, GalleryPhoto $photo): JsonResponse
    {
        abort_unless($photo->gallery_album_id === $album->id, 404);

        $data = $request->validate([
            'status' => ['required', 'in:ready,no_face,failed'],
            'reason' => ['nullable', 'string', 'max:200'],
            'faces' => ['array'],
            'faces.*.descriptor' => ['required', 'array', 'size:128'],
            'faces.*.descriptor.*' => ['numeric'],
            'faces.*.score' => ['nullable', 'numeric'],
            'faces.*.box' => ['nullable', 'array'],
        ]);

        $modelVersion = (string) config('face.version', 'v1');
        $status = $data['status'];
        $stored = 0;

        try {
            if ($status === 'ready' && ! empty($data['faces'])) {
                $stored = $this->descriptors->replaceForPhoto($photo, $data['faces'], $modelVersion);
                if ($stored === 0) {
                    $status = 'no_face';
                }
            } else {
                // no_face / failed: remove descritores antigos desta foto.
                $photo->faceDescriptors()->delete();
                if ($status === 'ready') {
                    $status = 'no_face';
                }
            }
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Não foi possível gravar os descritores desta foto.',
            ], 422);
        }

        $meta = $photo->meta_json ?? [];
        $meta['faces_reason'] = $data['reason'] ?? null;
        $meta['faces_model_version'] = $modelVersion;

        $photo->forceFill([
            'faces_status' => $status,
            'faces_scanned_at' => now(),
            'meta_json' => $meta,
        ])->save();

        return response()->json([
            'photo_id' => $photo->id,
            'status' => $status,
            'faces' => $stored,
        ]);
    }
}
