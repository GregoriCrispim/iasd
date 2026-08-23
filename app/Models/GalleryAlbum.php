<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class GalleryAlbum extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'event_date',
        'description',
        'is_published',
        'cover_photo_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'is_published' => 'boolean',
        ];
    }

    public function photos(): HasMany
    {
        return $this->hasMany(GalleryPhoto::class)->orderBy('sort_order')->orderBy('id');
    }

    public function coverPhoto(): BelongsTo
    {
        return $this->belongsTo(GalleryPhoto::class, 'cover_photo_id');
    }

    public function faceDescriptors(): HasMany
    {
        return $this->hasMany(GalleryFaceDescriptor::class, 'gallery_album_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * Capa em tamanho de exibição, usada como imagem de compartilhamento.
     */
    public function coverUrl(): ?string
    {
        return $this->coverPhotoModel()?->displayUrl();
    }

    /**
     * Capa em miniatura, para os cartões de álbum do site e do painel.
     */
    public function coverThumbUrl(): ?string
    {
        return $this->coverPhotoModel()?->thumbUrl();
    }

    protected function coverPhotoModel(): ?GalleryPhoto
    {
        $cover = $this->relationLoaded('coverPhoto')
            ? $this->coverPhoto
            : $this->coverPhoto()->first();

        if ($cover) {
            return $cover;
        }

        return $this->relationLoaded('photos')
            ? $this->photos->first()
            : $this->photos()->first();
    }

    public function dateShort(): ?string
    {
        return $this->event_date?->format('d/m/Y');
    }

    public function dateLong(): ?string
    {
        if (! $this->event_date) {
            return null;
        }

        $date = $this->event_date->copy()->locale('pt_BR');

        return $date->translatedFormat('d').' de '.ucfirst($date->translatedFormat('F')).' de '.$date->format('Y');
    }

    public function monthKey(): string
    {
        return $this->event_date?->format('Y-m') ?? '';
    }

    public function monthLabel(): string
    {
        if (! $this->event_date) {
            return '';
        }

        $date = $this->event_date->copy()->locale('pt_BR');

        return ucfirst($date->translatedFormat('F')).' de '.$date->format('Y');
    }

    /**
     * Build a stable slug from date + title (compatible with legacy folder names).
     */
    public static function makeSlug(string $title, ?Carbon $eventDate = null, ?int $ignoreId = null): string
    {
        $titlePart = Str::slug($title) ?: 'album';
        $base = $eventDate
            ? $eventDate->format('Y-m-d').'_'.$titlePart
            : $titlePart;

        $slug = $base;
        $i = 2;

        while (
            static::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn (Builder $q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    /**
     * Progresso da indexação facial do álbum (para a galeria pública).
     *
     * @return array{
     *   total:int,
     *   scanned:int,
     *   pending:int,
     *   ready:int,
     *   no_face:int,
     *   failed:int,
     *   percent:int,
     *   complete:bool
     * }
     */
    public function faceIndexProgress(): array
    {
        $rows = $this->photos()
            ->selectRaw('faces_status, COUNT(*) as aggregate')
            ->groupBy('faces_status')
            ->pluck('aggregate', 'faces_status');

        $ready = (int) ($rows['ready'] ?? 0);
        $noFace = (int) ($rows['no_face'] ?? 0);
        $failed = (int) ($rows['failed'] ?? 0);
        $pending = (int) ($rows['pending'] ?? 0);
        // Status nulo legado conta como pendente.
        foreach ($rows as $status => $count) {
            if ($status === null || $status === '') {
                $pending += (int) $count;
            }
        }

        $total = $ready + $noFace + $failed + $pending;
        $scanned = $ready + $noFace + $failed;
        $percent = $total > 0 ? (int) floor(($scanned / $total) * 100) : 0;

        return [
            'total' => $total,
            'scanned' => $scanned,
            'pending' => $pending,
            'ready' => $ready,
            'no_face' => $noFace,
            'failed' => $failed,
            'percent' => min(100, max(0, $percent)),
            'complete' => $total > 0 && $pending === 0,
        ];
    }

    /**
     * Álbum pronto para busca facial pública (todas as fotos foram analisadas).
     */
    public function isFaceSearchReady(): bool
    {
        return (bool) ($this->faceIndexProgress()['complete'] ?? false);
    }

    /**
     * Shape expected by the public gallery Blade/JS (legacy array contract).
     *
     * @return array{id:string,title:string,rawDate:string,dateShort:?string,dateLong:?string,monthKey:string,monthLabel:string,coverUrl:?string,coverThumbUrl:?string,photoCount:int}
     */
    public function toPublicSummary(): array
    {
        return [
            'id' => $this->slug,
            'title' => $this->title,
            'rawDate' => $this->event_date?->format('Y-m-d') ?? '',
            'dateShort' => $this->dateShort(),
            'dateLong' => $this->dateLong(),
            'monthKey' => $this->monthKey(),
            'monthLabel' => $this->monthLabel(),
            'coverUrl' => $this->coverUrl(),
            'coverThumbUrl' => $this->coverThumbUrl(),
            'photoCount' => $this->photos_count ?? $this->photos()->count(),
        ];
    }
}
