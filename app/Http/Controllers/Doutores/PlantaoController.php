<?php

namespace App\Http\Controllers\Doutores;

use App\Models\Doutores\Dominio;
use App\Models\Doutores\Hospital;
use App\Models\Doutores\Inscricao;
use App\Models\Doutores\Plantao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PlantaoController extends DoutoresController
{
    public function index(Request $request): View
    {
        $query = $this->scopeByHospital(Plantao::query())
            ->withCount([
                'inscricoes as inscricoes_ativas_count' => fn (Builder $q) => $q->whereIn('status', [Inscricao::STATUS_PENDENTE, Inscricao::STATUS_CONFIRMADA]),
                'inscricoes as inscricoes_pendentes_count' => fn (Builder $q) => $q->where('status', Inscricao::STATUS_PENDENTE),
            ])
            ->when($request->filled('status'), fn (Builder $q) => $q->where('status', $request->string('status')->toString()))
            ->when($request->filled('hospital'), fn (Builder $q) => $q->where('hospital', $request->string('hospital')->toString()))
            ->when($request->filled('de'), fn (Builder $q) => $q->whereDate('data', '>=', $request->string('de')->toString()))
            ->when($request->filled('ate'), fn (Builder $q) => $q->whereDate('data', '<=', $request->string('ate')->toString()));

        return view('doutores.plantoes.index', [
            'plantoes' => $query->orderByDesc('data')->orderByDesc('id')->paginate(25)->withQueryString(),
            'hospitais' => $this->hospitalNames(),
        ]);
    }

    public function create(): View
    {
        return view('doutores.plantoes.create', $this->formData(new Plantao([
            'status' => Plantao::STATUS_ABERTO,
            'tipo' => 'visita_mensal',
        ])));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $this->assertHospital($data['hospital']);
        $data = $this->completeAddress($data);

        $plantao = Plantao::create($data);

        $this->log('plantao.criado', 'plantao', "Plantão #{$plantao->id} criado em {$plantao->hospital}.", null, "plantao:{$plantao->id}");

        return redirect()->route('doutores.plantoes.show', $plantao)->with('success', 'Plantão criado.');
    }

    public function show(Plantao $plantao): View
    {
        $this->assertHospital($plantao->hospital);

        $plantao->load(['inscricoes.user', 'avisos', 'testemunhos.user']);

        return view('doutores.plantoes.show', ['plantao' => $plantao]);
    }

    public function edit(Plantao $plantao): View
    {
        $this->assertHospital($plantao->hospital);

        return view('doutores.plantoes.edit', $this->formData($plantao));
    }

    public function update(Request $request, Plantao $plantao): RedirectResponse
    {
        $this->assertHospital($plantao->hospital);

        $data = $this->validated($request);
        $this->assertHospital($data['hospital']);
        $data = $this->completeAddress($data);

        $plantao->update($data);

        $this->log('plantao.atualizado', 'plantao', "Plantão #{$plantao->id} atualizado.", null, "plantao:{$plantao->id}");

        return redirect()->route('doutores.plantoes.show', $plantao)->with('success', 'Plantão atualizado.');
    }

    public function destroy(Plantao $plantao): RedirectResponse
    {
        $this->assertHospital($plantao->hospital);

        $id = $plantao->id;
        $plantao->delete();

        $this->log('plantao.removido', 'plantao', "Plantão #{$id} removido.", null, "plantao:{$id}", 'warning');

        return redirect()->route('doutores.plantoes.index')->with('success', 'Plantão removido.');
    }

    public function registerResults(Request $request, Plantao $plantao): RedirectResponse
    {
        $this->assertHospital($plantao->hospital);

        $data = $request->validate([
            'quartos_visitados' => ['required', 'integer', 'min:0', 'max:100000'],
            'visitas_realizadas' => ['required', 'integer', 'min:0', 'max:100000'],
            'livros_distribuidos' => ['required', 'integer', 'min:0', 'max:100000'],
            'marcar_realizado' => ['nullable', 'boolean'],
        ]);

        $update = [
            'quartos_visitados' => $data['quartos_visitados'],
            'visitas_realizadas' => $data['visitas_realizadas'],
            'livros_distribuidos' => $data['livros_distribuidos'],
            'resultados_registrados_em' => now(),
        ];

        if ($request->boolean('marcar_realizado', true)) {
            $update['status'] = Plantao::STATUS_REALIZADO;
        }

        $plantao->update($update);

        $this->log('plantao.resultados', 'plantao', "Resultados do plantão #{$plantao->id} registrados.", null, "plantao:{$plantao->id}");

        return redirect()->route('doutores.plantoes.show', $plantao)->with('success', 'Resultados registrados.');
    }

    public function equipes(Plantao $plantao): View
    {
        $this->assertHospital($plantao->hospital);

        $plantao->load('inscricoes.user');

        return view('doutores.plantoes.equipes', [
            'plantao' => $plantao,
            'equipes' => $plantao->equipes ?? [],
        ]);
    }

    /**
     * Sorteio simples: agrupa as inscrições ativas por função (papel) e, opcionalmente,
     * divide cada grupo em equipes com no máximo N pessoas.
     */
    public function gerarEquipes(Request $request, Plantao $plantao): RedirectResponse
    {
        $this->assertHospital($plantao->hospital);

        $data = $request->validate([
            'acao' => ['required', Rule::in(['gerar', 'limpar'])],
            'tamanho' => ['nullable', 'integer', 'min:1', 'max:200'],
            'somente_confirmadas' => ['nullable', 'boolean'],
        ]);

        if ($data['acao'] === 'limpar') {
            $plantao->update(['equipes' => null]);
            $this->log('plantao.equipes_limpas', 'plantao', "Equipes do plantão #{$plantao->id} removidas.", null, "plantao:{$plantao->id}");

            return back()->with('success', 'Equipes removidas.');
        }

        $statuses = $request->boolean('somente_confirmadas')
            ? [Inscricao::STATUS_CONFIRMADA]
            : [Inscricao::STATUS_PENDENTE, Inscricao::STATUS_CONFIRMADA];

        $inscricoes = $plantao->inscricoes()
            ->with('user:id,name')
            ->whereIn('status', $statuses)
            ->get()
            ->sortBy(fn (Inscricao $i) => mb_strtolower($i->user?->name ?? ''));

        if ($inscricoes->isEmpty()) {
            return back()->with('error', 'Não há inscrições ativas para montar equipes.');
        }

        $tamanho = (int) ($data['tamanho'] ?? 0);
        $porPapel = $inscricoes->groupBy(fn (Inscricao $i) => trim((string) $i->papel) !== '' ? trim((string) $i->papel) : 'Sem função')
            ->sortKeys();

        $equipes = [];
        $numero = 1;

        foreach ($porPapel as $papel => $grupo) {
            $blocos = $tamanho > 0 ? $grupo->values()->chunk($tamanho) : collect([$grupo->values()]);

            foreach ($blocos as $bloco) {
                $equipes[] = [
                    'numero' => $numero,
                    'nome' => 'Equipe '.$numero.' — '.$papel,
                    'papel' => $papel,
                    'membros' => $bloco->map(fn (Inscricao $i) => [
                        'user_id' => $i->user_id,
                        'nome' => $i->user?->name,
                        'inscricao_id' => $i->id,
                        'papel' => $i->papel,
                        'status' => $i->status,
                    ])->values()->all(),
                ];
                $numero++;
            }
        }

        $plantao->update(['equipes' => $equipes]);
        $plantao->syncVoluntarios();

        $this->log('plantao.equipes_geradas', 'plantao', "Equipes do plantão #{$plantao->id} geradas (".count($equipes).' equipes).', null, "plantao:{$plantao->id}");

        return redirect()->route('doutores.plantoes.equipes', $plantao)->with('success', count($equipes).' equipe(s) gerada(s).');
    }

    /**
     * Plantões disponíveis para o voluntário (área do voluntário).
     */
    public function areaIndex(Request $request): View
    {
        $user = $this->user();
        $profile = $this->profileFor($user);

        $query = Plantao::query()
            ->where('status', Plantao::STATUS_ABERTO)
            ->whereDate('data', '>=', now()->toDateString())
            ->when($request->filled('hospital'), fn (Builder $q) => $q->where('hospital', $request->string('hospital')->toString()))
            ->orderBy('data');

        $plantoes = $query->get();

        $minhas = Inscricao::query()
            ->where('user_id', $user->id)
            ->whereIn('plantao_id', $plantoes->pluck('id'))
            ->get()
            ->keyBy('plantao_id');

        return view('doutores.area.plantoes', [
            'plantoes' => $plantoes,
            'minhas' => $minhas,
            'profile' => $profile,
            'hospitais' => Hospital::query()->where('status', 'ativo')->orderBy('nome')->pluck('nome'),
            'funcoes' => Dominio::opcoes('funcao'),
            'turnos' => Dominio::opcoes('turno_preferido'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function formData(Plantao $plantao): array
    {
        $hospitais = $this->hospitalNames(true);

        if ($plantao->exists && $plantao->hospital && ! in_array($plantao->hospital, $hospitais, true)) {
            $hospitais[] = $plantao->hospital;
        }

        return [
            'plantao' => $plantao,
            'hospitais' => $hospitais,
            'hospitaisInfo' => Hospital::query()->whereIn('nome', $hospitais)->get(['nome', 'endereco', 'cidade'])->keyBy('nome'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function validated(Request $request): array
    {
        return $request->validate([
            'data' => ['required', 'date'],
            'hora_inicio' => ['nullable', 'date_format:H:i'],
            'hora_fim' => ['nullable', 'date_format:H:i'],
            'hospital' => ['required', 'string', 'max:255'],
            'endereco' => ['nullable', 'string', 'max:255'],
            'tipo' => ['required', Rule::in(array_keys(Plantao::TIPOS))],
            'status' => ['required', Rule::in(array_keys(Plantao::STATUSES))],
            'observacoes' => ['nullable', 'string', 'max:5000'],
            'inscricao_inicio' => ['nullable', 'date'],
            'inscricao_fim' => ['nullable', 'date', 'after_or_equal:inscricao_inicio'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function completeAddress(array $data): array
    {
        if (empty($data['endereco'])) {
            $data['endereco'] = Hospital::query()->where('nome', $data['hospital'])->value('endereco');
        }

        return $data;
    }
}
