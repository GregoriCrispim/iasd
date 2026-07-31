<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaceSearchConsent extends Model
{
    protected $fillable = [
        'user_id',
        'gallery_album_id',
        'terms_version',
        'source',
        'is_guardian_declared',
        'ip_hash',
        'consented_at',
    ];

    protected function casts(): array
    {
        return [
            'is_guardian_declared' => 'boolean',
            'consented_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }
}
