<?php

namespace App\Models\Doutores;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SolicitacaoEstudo extends Model
{
    /** @var array<string, string> */
    public const STATUSES = [
        'pendente' => 'Pendente',
        'encaminhado' => 'Encaminhado',
        'em_andamento' => 'Em andamento',
        'concluido' => 'Concluído',
        'cancelado' => 'Cancelado',
    ];

    protected $table = 'doutores_solicitacoes_estudo';

    protected $fillable = [
        'nome_paciente',
        'telefone',
        'email',
        'endereco',
        'cidade',
        'hospital',
        'data_solicitacao',
        'dia_preferido',
        'horario_preferido',
        'observacoes',
        'status',
        'atribuido_a',
        'solicitado_por_id',
        'solicitado_por_nome',
        'plantao_id',
        'encaminhado_em',
        'em_andamento_em',
        'concluido_em',
    ];

    protected function casts(): array
    {
        return [
            'data_solicitacao' => 'date',
            'encaminhado_em' => 'datetime',
            'em_andamento_em' => 'datetime',
            'concluido_em' => 'datetime',
        ];
    }

    public function solicitadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'solicitado_por_id');
    }

    public function plantao(): BelongsTo
    {
        return $this->belongsTo(Plantao::class, 'plantao_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(SolicitacaoEstudoLog::class, 'solicitacao_id');
    }
}