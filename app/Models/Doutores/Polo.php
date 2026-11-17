<?php

namespace App\Models\Doutores;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Polo extends Model
{
    protected $table = 'doutores_polos';

    protected $fillable = [
        'nome',
        'cep',
        'endereco',
        'cidade',
        'estado',
        'telefone',
        'email',
        'coordenador',
        'quantidade_voluntarios',
        'status',
    ];

    public function hospitais(): HasMany
    {
        return $this->hasMany(Hospital::class, 'polo_id');
    }

    public function coordenadores(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'doutores_coordenador_polo', 'polo_id', 'user_id')
            ->withTimestamps();
    }
}
