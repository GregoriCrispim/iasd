<?php

namespace App\Models\Doutores;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plantao extends Model
{
    public const STATUS_ABERTO = 'aberto';

    public const STATUS_FECHADO = 'fechado';

    public const STATUS_REALIZADO = 'realizado';

    public const STATUS_CANCELADO = 'cancelado';

    /** @var array<string, string> */
    public const STATUSES = [
        self::STATUS_ABERTO => 'Aberto',
        self::STATUS_FECHADO => 'Inscrições encerradas',
        self::STATUS_REALIZADO => 'Realizado',
        self::STATUS_CANCELADO => 'Cancelado',
    ];

    /** @var array<string, string> */
    public const TIPOS = [
        'visita_mensal' => 'Visita mensal',
        'visita_especial' => 'Visita especial',
        'evento' => 'Evento',
        'treinamento' => 'Treinamento',
    ];

    protected $table = 'doutores_plantoes';

    protected $fillable = [
        'data',
        'hora_inicio',
        'hora_fim',
        'hospital',
        'endereco',
        'tipo',
        'voluntarios',
        'equipes',
        'status',
        'observacoes',
        'inscricao_inicio',
        'inscricao_fim',
        'quartos_visitados',
        'visitas_realizadas',
        'livros_distribuidos',
        'resultados_registrados_em',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'date',
            'voluntarios' => 'array',
            'equipes' => 'array',
            'inscricao_inicio' => 'datetime',
            'inscricao_fim' => 'datetime',
            'resultados_registrados_em' => 'datetime',
        ];
    }

    public function inscricoes(): HasMany
    {
        return $this->hasMany(Inscricao::class, 'plantao_id');
    }

    public function avisos(): HasMany
    {
        return $this->hasMany(Aviso::class, 'plantao_id');
    }

    public function testemunhos(): HasMany
    {
        return $this->hasMany(Testemunho::class, 'plantao_id');
    }

    /**
     * Mantém o JSON "voluntarios" com os IDs das inscrições confirmadas.
     */
    public function syncVoluntarios(): void
    {
        $this->voluntarios = $this->inscricoes()
            ->where('status', Inscricao::STATUS_CONFIRMADA)
            ->pluck('user_id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();
        $this->save();
    }

    public function inscricoesAbertas(): bool
    {
        if ($this->status !== self::STATUS_ABERTO) {
            return false;
        }

        $now = now();

        if ($this->inscricao_inicio && $this->inscricao_inicio->isFuture()) {
            return false;
        }

        if ($this->inscricao_fim && $this->inscricao_fim->lt($now)) {
            return false;
        }

        return $this->data === null || $this->data->copy()->endOfDay()->gte($now);
    }
}
