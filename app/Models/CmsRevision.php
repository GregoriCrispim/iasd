<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsRevision extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING_MANAGER = 'pending_manager';
    public const STATUS_PENDING_SUPER_ADMIN = 'pending_super_admin';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'cms_block_id',
        'html',
        'meta_json',
        'status',
        'created_by',
        'submitted_at',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'meta_json' => 'array',
            'submitted_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function block(): BelongsTo
    {
        return $this->belongsTo(CmsBlock::class, 'cms_block_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(CmsApproval::class, 'cms_revision_id');
    }
}
