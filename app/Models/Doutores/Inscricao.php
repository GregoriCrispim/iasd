<?php

namespace App\Models\Doutores;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inscricao extends Model
{
    public const STATUS_PENDENTE = 'pendente';

    public const STATUS_CONFIRMADA = 'confirmada';

    public const STATUS_RECUSADA = 'recusada';

    public const STATUS_CANCELADA = 'cancelada';

    /** @var array<string, string> */
    public const STATUSES = [
        self::STATUS_PENDENTE => 'Pendente',
        self::STATUS_CONFIRMADA => 'Confirmada',
        self::STATUS_RECUSADA => 'Recusada',
        self::STATUS_CANCELADA => 'Cancelada',
    ];

    protected $table = 'doutores_inscricoes';

    protected $fillable = [
        'user_id',
        'plantao_id',
        'hospital',
        'cidade',
        'data',
        'modalidade',
        'turno',
        'papel',
        'status',
        'confirmada_por_voluntario_em',
        'justificativa',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'date',
            'confirmada_por_voluntario_em' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plantao(): BelongsTo
    {
        return $this->belongsTo(Plantao::class, 'plantao_id');
    }
}