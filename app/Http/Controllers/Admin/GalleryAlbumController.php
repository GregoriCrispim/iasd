<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessGalleryPhotoFaces;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\User;
use App\Services\GalleryImageProcessor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GalleryAlbumController extends Controller
{
    public function index(Request $request): View
    {
        $albums = GalleryAlbum::query()
            ->withCount('photos')
            ->with('coverPhoto')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(fn ($q) => $q->where('title', 'like', $term)->orWhere('slug', 'like', $term));
            })
            ->orderByDesc('event_date')
            ->orderByDesc('id')
            ->paginate(30)
            ->withQueryString();

        return view('admin.galeria.index', ['albums' => $albums]);
    }

    public function create(Request $request): RedirectResponse
    {
        $this->authorizeAlbumManage($request);

        return redirect()->route('admin.galeria.index', ['novo' => 1]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAlbumManage($request);

        /** @var User $user */
        $user = $request->user();
        $data = $this->validateAlbum($request);

        $eventDate = isset($data['event_date']) ? Carbon::parse($data['event_date']) : null;

        $album = GalleryAlbum::create([
            'title' => $data['title'],
            'slug' => GalleryAlbum::makeSlug($data['title'], $eventDate),
            'event_date' => $data['event_date'] ?? null,
            'description' => $data['description'] ?? null,
            'is_published' => $this->albumIsPublished($request),
            'created_by' => $user->id,
        ]);

        return redirect()
            ->route('admin.galeria.show', $album)
            ->with('success', 'Álbum criado. Agora você pode enviar fotos.');
    }

    public function show(GalleryAlbum $album): View
    {
        $album->load(['photos', 'coverPhoto']);

        $photos = $album->photos->map(fn (GalleryPhoto $photo) => [
            'id' => $photo->id,
            'url' => $photo->displayUrl(),
            'thumb_url' => $photo->thumbUrl(),
            'filename' => $photo->original_filename ?: $photo->basename(),
            'is_cover' => $album->cover_photo_id === $photo->id,
        ])->values()->all();

        return view('admin.galeria.show', [
            'album' => $album,
            'photos' => $photos,
        ]);
    }

    public function edit(Request $request, GalleryAlbum $album): RedirectResponse
    {
        $this->authorizeAlbumManage($request);

        return redirect()->route('admin.galeria.index', ['editar' => $album->id]);
    }

    public function update(Request $request, GalleryAlbum $album): RedirectResponse
    {
        $this->authorizeAlbumManage($request);

        $data = $this->validateAlbum($request, $album);

        $eventDate = isset($data['event_date']) ? Carbon::parse($data['event_date']) : null;
        $titleChanged = $album->title !== $data['title'];
        $dateChanged = ($album->event_date?->format('Y-m-d') ?? null) !== ($data['event_date'] ?? null);

        $payload = [
            'title' => $data['title'],
            'event_date' => $data['event_date'] ?? null,
            'description' => $data['description'] ?? null,
            'is_published' => $this->albumIsPublished($request),
        ];

        if ($titleChanged || $dateChanged) {
            $payload['slug'] = GalleryAlbum::makeSlug($data['title'], $eventDate, $album->id);
        }

        $album->update($payload);

        $redirect = $request->input('_return') === 'show'
            ? redirect()->route('admin.galeria.show', $album)
            : redirect()->route('admin.galeria.index');

        return $redirect->with('success', 'Álbum atualizado.');
    }

    public function destroy(Request $request, GalleryAlbum $album): RedirectResponse
    {
        $this->authorizeAlbumManage($request);

        $albumId = $album->id;

        foreach ($album->photos as $photo) {
            $this->deletePhotoFiles($photo);
        }

        $album->update(['cover_photo_id' => null]);
        $album->delete();

        Storage::disk(GalleryPhoto::DISK)->deleteDirectory((string) $albumId);
        Storage::disk(GalleryPhoto::DISK)->deleteDirectory('thumbs/'.$albumId);
        Storage::disk(GalleryPhoto::DISK)->deleteDirectory('display/'.$albumId);

        return redirect()->route('admin.galeria.index')->with('success', 'Álbum removido.');
    }

    public function upload(Request $request, GalleryAlbum $album, GalleryImageProcessor $processor): RedirectResponse|JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $request->validate([
            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:15360',
            ],
        ], [
            'photos.required' => 'Selecione ao menos uma foto.',
            'photos.*.image' => 'Cada arquivo deve ser uma imagem válida.',
            'photos.*.mimes' => 'Formatos aceitos: JPEG, PNG, GIF ou WebP.',
            'photos.*.mimetypes' => 'Formatos aceitos: JPEG, PNG, GIF ou WebP.',
            'photos.*.max' => 'Cada foto deve ter no máximo 15 MB.',
        ]);

        $maxOrder = (int) $album->photos()->max('sort_order');
        $uploaded = 0;
        $skipped = 0;
        $createdPhotos = [];

        foreach ($request->file('photos', []) as $file) {
            if (!$file || !$file->isValid()) {
                $skipped++;
                continue;
            }

            // Extra safety: reject non-images even if MIME spoofed past basic checks.
            $pathReal = $file->getRealPath();
            $sizeInfo = is_string($pathReal) ? @getimagesize($pathReal) : false;
            if (!is_array($sizeInfo)) {
                $skipped++;
                continue;
            }

            $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
                $skipped++;
                continue;
            }

            $filename = Str::uuid()->toString().'.'.$ext;
            $path = $album->id.'/'.$filename;

            Storage::disk(GalleryPhoto::DISK)->putFileAs((string) $album->id, $file, $filename);

            $originalSize = $file->getSize() ?: 0;

            $attributes = [
                'gallery_album_id' => $album->id,
                'path' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType() ?: ($sizeInfo['mime'] ?? 'image/jpeg'),
                'size_bytes' => $originalSize,
                'width' => $sizeInfo[0] ?? null,
                'height' => $sizeInfo[1] ?? null,
                'sort_order' => ++$maxOrder,
                'faces_status' => 'pending',
                'uploaded_by' => $user->id,
            ];

            // A otimização nunca deve impedir o envio: se falhar, a foto fica
            // guardada como veio e `galeria:otimizar` a processa depois.
            $optimized = null;

            try {
                $optimized = $processor->process($path);
            } catch (\Throwable $exception) {
                report($exception);
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

            if (!$album->cover_photo_id) {
                $album->update(['cover_photo_id' => $photo->id]);
            }

            $createdPhotos[] = [
                'id' => $photo->id,
                'url' => $photo->displayUrl(),
                'index_url' => $photo->publicUrl(),
                'thumb_url' => $photo->thumbUrl(),
                'filename' => $photo->original_filename,
                'is_cover' => $album->cover_photo_id === $photo->id,
            ];

            if (config('face.enabled', true)) {
                ProcessGalleryPhotoFaces::dispatch($photo->id)->afterResponse();
            }

            $uploaded++;
        }

        if ($uploaded === 0) {
            $message = 'Nenhuma imagem válida foi enviada.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return back()->with('error', $message);
        }

        $message = $uploaded === 1
            ? '1 foto enviada.'
            : "{$uploaded} fotos enviadas.";

        if ($skipped > 0) {
            $message .= " {$skipped} arquivo(s) ignorado(s).";
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'uploaded' => $uploaded,
                'skipped' => $skipped,
                'photos' => $createdPhotos,
            ]);
        }

        return back()->with('success', $message);
    }

    public function setCover(GalleryAlbum $album, GalleryPhoto $photo): RedirectResponse
    {
        abort_unless($photo->gallery_album_id === $album->id, 404);

        $album->update(['cover_photo_id' => $photo->id]);

        return back()->with('success', 'Capa do álbum atualizada.');
    }

    public function destroyPhoto(GalleryAlbum $album, GalleryPhoto $photo): RedirectResponse
    {
        abort_unless($photo->gallery_album_id === $album->id, 404);

        if ($album->cover_photo_id === $photo->id) {
            $album->update(['cover_photo_id' => null]);
        }

        $this->deletePhotoFiles($photo);
        $photo->delete();

        if (!$album->cover_photo_id) {
            $next = $album->photos()->first();
            if ($next) {
                $album->update(['cover_photo_id' => $next->id]);
            }
        }

        return back()->with('success', 'Foto removida.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validateAlbum(Request $request, ?GalleryAlbum $album = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'event_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:5000'],
            'is_published' => ['nullable'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('gallery_albums', 'slug')->ignore($album?->id),
            ],
        ]);
    }

    /**
     * Resolve the "Visível no site" switch (hidden 0 + checkbox 1).
     */
    protected function albumIsPublished(Request $request): bool
    {
        $value = $request->input('is_published');

        if (is_array($value)) {
            foreach ($value as $item) {
                if ($item === true || $item === 1 || $item === '1' || $item === 'true' || $item === 'on') {
                    return true;
                }
            }

            return false;
        }

        return $request->boolean('is_published');
    }

    protected function deletePhotoFiles(GalleryPhoto $photo): void
    {
        Storage::disk(GalleryPhoto::DISK)->delete($photo->allFilePaths());
    }

    protected function authorizeAlbumManage(Request $request): void
    {
        /** @var User|null $user */
        $user = $request->user();

        if (! $user || ! $user->canManageGalleryAlbums()) {
            abort(403);
        }
    }
}
