<?php

namespace App\Models\Doutores;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publicacao extends Model
{
    protected $table = 'doutores_publicacoes';

    protected $fillable = [
        'titulo',
        'conteudo',
        'imagens',
        'autor_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'imagens' => 'array',
        ];
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(ComentarioPublicacao::class, 'publicacao_id');
    }
}