<?php

namespace App\Http\Controllers\Doutores;

use App\Models\Doutores\Aviso;
use App\Models\Doutores\AvisoLeitura;
use App\Models\Doutores\Inscricao;
use App\Models\Doutores\Plantao;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AvisoController extends DoutoresController
{
    /**
     * Avisos destinados ao voluntário: plantões em que está inscrito, para todos ou para a sua equipe.
     *
     * @return Builder<Aviso>
     */
    public static function avisosDoVoluntario(User $user): Builder
    {
        $plantoes = Plantao::query()
            ->whereHas('inscricoes', fn (Builder $q) => $q
                ->where('user_id', $user->id)
                ->whereIn('status', [Inscricao::STATUS_PENDENTE, Inscricao::STATUS_CONFIRMADA]))
            ->get(['id', 'equipes']);

        $equipePorPlantao = [];
        foreach ($plantoes as $plantao) {
            foreach ($plantao->equipes ?? [] as $equipe) {
                foreach ($equipe['membros'] ?? [] as $membro) {
                    if ((int) ($membro['user_id'] ?? 0) === (int) $user->id) {
                        $equipePorPlantao[$plantao->id] = (int) ($equipe['numero'] ?? 0);
                    }
                }
            }
        }

        return Aviso::query()
            ->whereIn('plantao_id', $plantoes->pluck('id'))
            ->where(function (Builder $q) use ($equipePorPlantao) {
                $q->whereNull('equipe_numero');
                foreach ($equipePorPlantao as $plantaoId => $numero) {
                    $q->orWhere(fn (Builder $w) => $w->where('plantao_id', $plantaoId)->where('equipe_numero', $numero));
                }
            });
    }

    /** Gestores: avisos enviados. */
    public function index(Request $request): View
    {
        $query = Aviso::query()
            ->with(['plantao', 'autor'])
            ->withCount('leituras')
            ->whereHas('plantao', fn (Builder $q) => $this->scopeByHospital($q))
            ->when($request->filled('plantao_id'), fn (Builder $q) => $q->where('plantao_id', $request->integer('plantao_id')));

        return view('doutores.avisos.index', [
            'avisos' => $query->latest()->paginate(25)->withQueryString(),
        ]);
    }

    public function create(Request $request): View
    {
        $plantoes = $this->scopeByHospital(Plantao::query())
            ->where('status', '!=', Plantao::STATUS_CANCELADO)
            ->orderByDesc('data')
            ->limit(100)
            ->get();

        return view('doutores.avisos.create', [
            'plantoes' => $plantoes,
            'plantaoId' => $request->integer('plantao_id') ?: null,
            'equipeNumero' => $request->integer('equipe_numero') ?: null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'plantao_id' => ['required', 'integer', 'exists:doutores_plantoes,id'],
            'equipe_numero' => ['nullable', 'integer', 'min:1', 'max:999'],
            'titulo' => ['required', 'string', 'max:255'],
            'conteudo' => ['nullable', 'string', 'max:5000'],
        ]);

        $plantao = Plantao::query()->findOrFail($data['plantao_id']);
        $this->assertHospital($plantao->hospital);

        $aviso = Aviso::create($data + ['autor_id' => $this->user()->id]);

        $this->log('aviso.criado', 'aviso', "Aviso #{$aviso->id} criado para o plantão #{$plantao->id}.", null, "plantao:{$plantao->id}");

        return redirect()->route('doutores.avisos.index')->with('success', 'Aviso publicado.');
    }

    public function destroy(Aviso $aviso): RedirectResponse
    {
        $aviso->loadMissing('plantao');
        $this->assertHospital($aviso->plantao?->hospital);

        $aviso->delete();

        $this->log('aviso.removido', 'aviso', "Aviso #{$aviso->id} removido.", null, null, 'warning');

        return back()->with('success', 'Aviso removido.');
    }

    /** Voluntário: seus avisos. */
    public function areaIndex(): View
    {
        $user = $this->user();

        $avisos = self::avisosDoVoluntario($user)
            ->with('plantao')
            ->latest()
            ->paginate(20);

        $lidos = AvisoLeitura::query()
            ->where('user_id', $user->id)
            ->whereIn('aviso_id', $avisos->pluck('id'))
            ->pluck('lido_em', 'aviso_id');

        return view('doutores.area.avisos', ['avisos' => $avisos, 'lidos' => $lidos]);
    }

    public function markRead(Aviso $aviso): RedirectResponse
    {
        $user = $this->user();

        abort_unless(self::avisosDoVoluntario($user)->whereKey($aviso->id)->exists(), 403);

        AvisoLeitura::query()->firstOrCreate(
            ['aviso_id' => $aviso->id, 'user_id' => $user->id],
            ['lido_em' => now()]
        );

        return back()->with('success', 'Aviso marcado como lido.');
    }
}
