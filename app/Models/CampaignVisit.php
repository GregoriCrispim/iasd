<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignVisit extends Model
{
    protected $fillable = [
        'campaign_id',
        'visited_at',
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
            'visited_at' => 'datetime',
            'query' => 'array',
            'is_bot' => 'boolean',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function scopeHuman(Builder $query): Builder
    {
        return $query->where('is_bot', false);
    }
}
