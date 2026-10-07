<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VolunteerApplicationChoice extends Model
{
    public const MODALITY_LIDERANCA = 'lideranca';

    public const MODALITY_EQUIPE = 'equipe';

    protected $fillable = [
        'volunteer_application_id',
        'ministry_slug',
        'modality',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(VolunteerApplication::class, 'volunteer_application_id');
    }

    public function ministryLabel(): string
    {
        $entry = config('ministries.'.$this->ministry_slug);

        if (is_array($entry)) {
            return (string) ($entry['name'] ?? $this->ministry_slug);
        }

        return (string) ($entry ?? $this->ministry_slug);
    }

    public function modalityLabel(): string
    {
        return $this->modality === self::MODALITY_LIDERANCA ? 'Liderança' : 'Equipe';
    }

    public function isLideranca(): bool
    {
        return $this->modality === self::MODALITY_LIDERANCA;
    }
}
