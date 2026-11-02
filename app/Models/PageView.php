<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $fillable = [
        'viewed_at',
        'method',
        'path',
        'route_name',
        'status',
        'visitor_key',
        'ip_hash',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'accept_language',
        'referer',
        'country_code',
        'query',
        'is_bot',
    ];

    protected function casts(): array
    {
        return [
            'viewed_at' => 'datetime',
            'query' => 'array',
            'is_bot' => 'boolean',
            'status' => 'integer',
        ];
    }

    public function scopeHuman(Builder $query): Builder
    {
        return $query->where('is_bot', false);
    }
}
