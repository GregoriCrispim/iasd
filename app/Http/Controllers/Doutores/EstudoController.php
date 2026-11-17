<?php

namespace App\Http\Controllers\Doutores;

use App\Models\Doutores\Plantao;
use App\Models\Doutores\SolicitacaoEstudo;
use App\Models\Doutores\SolicitacaoEstudoLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EstudoController extends DoutoresController
{
    public function index(Request $request): View
    {
        $query = $this->scopeByHospital(SolicitacaoEstudo::query(), 'hospital', true)
            ->when($request->filled('status'), fn (Builder $q) => $q->where('status', $request->string('status')->toString()))
            ->when($request->filled('hospital'), fn (Builder $q) => $q->where('hospital', $request->string('hospital')->toString()))
            ->when($request->filled('q'), function (Builder $q) use ($request) {
                $term = '%'.$request->string('q')->toString().'%';
                $q->where(fn (Builder $w) => $w->where('nome_paciente', 'like', $term)
                    ->orWhere('telefone', 'like', $term)
                    ->orWhere('email', 'like', $term));
            });

        return view('doutores.estudos.index', [
            'estudos' => $query->latest()->paginate(25)->withQueryString(),
            'hospitais' => $this->hospitalNames(true),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nome_paciente' => ['required', 'string', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'endereco' => ['nullable', 'string', 'max:255'],
            'cidade' => ['nullable', 'string', 'max:255'],
            'hospital' => ['nullable', 'string', 'max:255'],
            'dia_preferido' => ['nullable', 'string', 'max:100'],
            'horario_preferido' => ['nullable', 'string', 'max:100'],
            'observacoes' => ['nullable', 'string', 'max:5000'],
        ]);

        $this->assertHospital($data['hospital'] ?? null);

        $user = $this->user();

        $estudo = SolicitacaoEstudo::create($data + [
            'data_solicitacao' => now()->toDateString(),
            'status' => 'pendente',
            'solicitado_por_id' => $user->id,
            'solicitado_por_nome' => $user->name,
        ]);

        $this->registrarLog($estudo, 'pendente', 'Solicitação registrada.');
        $this->log('estudo.criado', 'estudo', "Solicitação de estudo #{$estudo->id} registrada.", $estudo->nome_paciente);

        return redirect()->route('doutores.estudos.show', $estudo)->with('success', 'Solicitação registrada.');
    }

    public function show(SolicitacaoEstudo $estudo): View
    {
        $this->authorizeEstudo($estudo);

        $estudo->load(['logs' => fn ($q) => $q->latest(), 'plantao', 'solicitadoPor']);

        $plantoes = $this->scopeByHospital(Plantao::query())
            ->whereDate('data', '>=', now()->subDays(30)->toDateString())
            ->orderBy('data')
            ->limit(60)
            ->get();

        return view('doutores.estudos.show', [
            'estudo' => $estudo,
            'plantoes' => $plantoes,
        ]);
    }

    /** Atualiza o status (com histórico) e dados de atribuição. */
    public function update(Request $request, SolicitacaoEstudo $estudo): RedirectResponse
    {
        $this->authorizeEstudo($estudo);

        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(SolicitacaoEstudo::STATUSES))],
            'atribuido_a' => ['nullable', 'string', 'max:255'],
            'plantao_id' => ['nullable', 'integer', 'exists:doutores_plantoes,id'],
            'observacoes_log' => ['nullable', 'string', 'max:2000'],
        ]);

        if (! empty($data['plantao_id'])) {
            $plantao = Plantao::query()->findOrFail($data['plantao_id']);
            $this->assertHospital($plantao->hospital);
        }

        $update = [
            'status' => $data['status'],
            'atribuido_a' => $data['atribuido_a'] ?? null,
            'plantao_id' => $data['plantao_id'] ?? null,
        ];

        $mudou = $estudo->status !== $data['status'];

        if ($mudou) {
            match ($data['status']) {
                'encaminhado' => $update['encaminhado_em'] = now(),
                'em_andamento' => $update['em_andamento_em'] = now(),
                'concluido' => $update['concluido_em'] = now(),
                default => null,
            };
        }

        $estudo->update($update);

        if ($mudou || ! empty($data['observacoes_log'])) {
            $this->registrarLog($estudo, $data['status'], $data['observacoes_log'] ?? null);
        }

        $this->log('estudo.status', 'estudo', "Solicitação #{$estudo->id} → {$data['status']}.", $estudo->nome_paciente);

        return back()->with('success', 'Solicitação atualizada.');
    }

    public function destroy(SolicitacaoEstudo $estudo): RedirectResponse
    {
        $this->abortUnlessAdmin();

        $nome = $estudo->nome_paciente;
        $estudo->delete();

        $this->log('estudo.removido', 'estudo', "Solicitação de estudo removida ({$nome}).", $nome, null, 'warning');

        return redirect()->route('doutores.estudos.index')->with('success', 'Solicitação removida.');
    }

    protected function authorizeEstudo(SolicitacaoEstudo $estudo): void
    {
        if ($estudo->hospital === null || $estudo->hospital === '') {
            return; // sem hospital: visível a todos os gestores para triagem
        }

        $this->assertHospital($estudo->hospital);
    }

    protected function registrarLog(SolicitacaoEstudo $estudo, string $status, ?string $observacoes): void
    {
        SolicitacaoEstudoLog::create([
            'solicitacao_id' => $estudo->id,
            'status' => $status,
            'executado_por' => $this->user()->name,
            'executado_por_perfil' => $this->perfilAtivo(),
            'observacoes' => $observacoes,
        ]);
    }
}
