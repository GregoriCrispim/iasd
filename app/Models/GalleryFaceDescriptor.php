<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Um descriptor facial de 128 dimensões extraído de uma foto do álbum.
 * O vetor é armazenado sempre criptografado na coluna `descriptor`.
 */
class GalleryFaceDescriptor extends Model
{
    protected $fillable = [
        'gallery_album_id',
        'gallery_photo_id',
        'face_index',
        'box_x',
        'box_y',
        'box_w',
        'box_h',
        'score',
        'model_version',
        'descriptor',
    ];

    protected $hidden = [
        'descriptor',
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }

    public function photo(): BelongsTo
    {
        return $this->belongsTo(GalleryPhoto::class, 'gallery_photo_id');
    }
}
