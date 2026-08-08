<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsPage extends Model
{
    protected $fillable = [
        'route_name',
        'view_path',
        'label',
        'section_slug',
        'is_active',
        'cms_enabled',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'cms_enabled' => 'boolean',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'cms_page_user')
            ->withPivot(['can_access', 'can_edit', 'can_approve'])
            ->withTimestamps();
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(CmsBlock::class, 'cms_page_id');
    }
}
