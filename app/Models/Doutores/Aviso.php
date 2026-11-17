<?php

namespace App\Models\Doutores;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aviso extends Model
{
    protected $table = 'doutores_avisos';

    protected $fillable = [
        'plantao_id',
        'equipe_numero',
        'titulo',
        'conteudo',
        'autor_id',
    ];

    public function plantao(): BelongsTo
    {
        return $this->belongsTo(Plantao::class, 'plantao_id');
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    public function leituras(): HasMany
    {
        return $this->hasMany(AvisoLeitura::class, 'aviso_id');
    }
}