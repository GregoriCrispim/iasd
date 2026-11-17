<?php

namespace App\Models\Doutores;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitacaoEstudoLog extends Model
{
    protected $table = 'doutores_solicitacao_estudo_logs';

    protected $fillable = [
        'solicitacao_id',
        'status',
        'executado_por',
        'executado_por_perfil',
        'observacoes',
    ];

    public function solicitacao(): BelongsTo
    {
        return $this->belongsTo(SolicitacaoEstudo::class, 'solicitacao_id');
    }
}