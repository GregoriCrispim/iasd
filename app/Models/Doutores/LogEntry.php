<?php

namespace App\Models\Doutores;

use Illuminate\Database\Eloquent\Model;

class LogEntry extends Model
{
    protected $table = 'doutores_logs';

    protected $fillable = [
        'registrado_em',
        'acao',
        'categoria',
        'descricao',
        'executado_por',
        'executado_por_perfil',
        'usuario_alvo',
        'documento_alvo',
        'endereco_ip',
        'severidade',
    ];

    protected function casts(): array
    {
        return [
            'registrado_em' => 'datetime',
        ];
    }
}