<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsApproval extends Model
{
    protected $fillable = [
        'cms_revision_id',
        'approver_id',
        'stage',
        'decision',
        'comment',
    ];

    public function revision(): BelongsTo
    {
        return $this->belongsTo(CmsRevision::class, 'cms_revision_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
