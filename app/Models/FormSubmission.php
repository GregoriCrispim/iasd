<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    public const LABELS = [
        'estudo-biblico' => 'Estudo bíblico',
        'oracao-visita' => 'Oração e visita',
        'contato' => 'Contato',
        'secretaria' => 'Secretaria',
        'talento' => 'Meu Talento',
        'teste-de-dons' => 'Teste de dons',
    ];

    protected $fillable = [
        'submitted_at',
        'form_key',
        'route_name',
        'path',
        'success',
        'visitor_key',
        'ip_hash',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'accept_language',
        'referer',
        'country_code',
        'is_bot',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'meta' => 'array',
            'is_bot' => 'boolean',
            'success' => 'boolean',
        ];
    }

    public function scopeHuman(Builder $query): Builder
    {
        return $query->where('is_bot', false);
    }

    public function label(): string
    {
        return self::LABELS[$this->form_key] ?? $this->form_key;
    }
}
