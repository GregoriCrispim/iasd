<?php

namespace App\Models\Doutores;

use Illuminate\Database\Eloquent\Model;

class Dominio extends Model
{
    /** @var array<string, string> */
    public const CATEGORIAS = [
        'funcao' => 'Funções',
        'igreja' => 'Igrejas',
        'dia_preferido' => 'Dias preferidos',
        'turno_preferido' => 'Turnos preferidos',
    ];

    /**
     * Descrições ativas de uma categoria.
     *
     * @return list<string>
     */
    public static function opcoes(string $categoria): array
    {
        return static::query()
            ->where('categoria', $categoria)
            ->where('ativo', true)
            ->orderBy('descricao')
            ->pluck('descricao')
            ->all();
    }

    protected $table = 'doutores_dominios';

    protected $fillable = [
        'categoria',
        'descricao',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
        ];
    }
}