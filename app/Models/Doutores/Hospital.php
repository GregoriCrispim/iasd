<?php

namespace App\Models\Doutores;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hospital extends Model
{
    protected $table = 'doutores_hospitais';

    protected $fillable = [
        'polo_id',
        'nome',
        'cep',
        'endereco',
        'cidade',
        'uf',
        'status',
    ];

    public function polo(): BelongsTo
    {
        return $this->belongsTo(Polo::class, 'polo_id');
    }
}