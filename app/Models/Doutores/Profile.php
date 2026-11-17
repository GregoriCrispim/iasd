<?php

namespace App\Models\Doutores;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    public const STATUS_ATIVO = 'ativo';

    public const STATUS_PENDENTE = 'pendente';

    public const STATUS_INATIVO = 'inativo';

    protected $table = 'doutores_profiles';

    protected $fillable = [
        'user_id',
        'cpf',
        'status',
        'genero',
        'telefone',
        'endereco',
        'bairro',
        'cep',
        'cidade',
        'hospital_preferido',
        'dia_preferido',
        'horario_preferido',
        'igreja',
        'cargo',
        'cargos',
        'observacoes',
        'coordenador_responsavel_id',
        'termos_aceitos_em',
        'foto_path',
    ];

    protected function casts(): array
    {
        return [
            'cargos' => 'array',
            'termos_aceitos_em' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coordenadorResponsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordenador_responsavel_id');
    }

    public function isAtivo(): bool
    {
        return $this->status === self::STATUS_ATIVO;
    }

    public function isPendente(): bool
    {
        return $this->status === self::STATUS_PENDENTE;
    }
}