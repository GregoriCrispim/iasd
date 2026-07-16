<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsBlock extends Model
{
    protected $fillable = [
        'cms_page_id',
        'block_key',
        'label',
        'published_revision_id',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPage::class, 'cms_page_id');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(CmsRevision::class, 'cms_block_id');
    }

    public function publishedRevision(): BelongsTo
    {
        return $this->belongsTo(CmsRevision::class, 'published_revision_id');
    }
}
