<?php

namespace App\Models\Doutores;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComentarioPublicacao extends Model
{
    protected $table = 'doutores_comentarios_publicacao';

    protected $fillable = [
        'publicacao_id',
        'nome',
        'email',
        'mensagem',
        'status',
    ];

    public function publicacao(): BelongsTo
    {
        return $this->belongsTo(Publicacao::class, 'publicacao_id');
    }
}