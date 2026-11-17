<?php

namespace App\Models\Doutores;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvisoLeitura extends Model
{
    protected $table = 'doutores_aviso_leituras';

    public $timestamps = false;

    protected $fillable = [
        'aviso_id',
        'user_id',
        'lido_em',
    ];

    protected function casts(): array
    {
        return [
            'lido_em' => 'datetime',
        ];
    }

    public function aviso(): BelongsTo
    {
        return $this->belongsTo(Aviso::class, 'aviso_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}