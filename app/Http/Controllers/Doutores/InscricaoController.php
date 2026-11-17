<?php

namespace App\Http\Controllers\Doutores;

use App\Models\Doutores\Dominio;
use App\Models\Doutores\Inscricao;
use App\Models\Doutores\Plantao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InscricaoController extends DoutoresController
{
    /** Inscrições do voluntário logado. */
    public function index(): View
    {
        $user = $this->user();

        $inscricoes = Inscricao::query()
            ->with('plantao')
            ->where('user_id', $user->id)
            ->orderByDesc('data')
            ->orderByDesc('id')
            ->paginate(20);

        return view('doutores.area.inscricoes', ['inscricoes' => $inscricoes]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $this->user();
        $profile = $this->profileFor($user);

        $data = $request->validate([
            'plantao_id' => ['required', 'integer', 'exists:doutores_plantoes,id'],
            'papel' => ['nullable', 'string', 'max:100'],
            'turno' => ['nullable', 'string', 'max:100'],
            'modalidade' => ['nullable', 'string', 'max:40'],
        ]);

        if ($profile->termos_aceitos_em === null) {
            return redirect()->route('doutores.area.termos')
                ->with('error', 'Aceite os termos de voluntariado antes de se inscrever em um plantão.');
        }

        $plantao = Plantao::query()->findOrFail($data['plantao_id']);

        if (! $plantao->inscricoesAbertas()) {
            return back()->with('error', 'As inscrições para este plantão estão encerradas.');
        }

        $existente = Inscricao::query()
            ->where('user_id', $user->id)
            ->where('plantao_id', $plantao->id)
            ->whereIn('status', [Inscricao::STATUS_PENDENTE, Inscricao::STATUS_CONFIRMADA])
            ->exists();

        if ($existente) {
            return back()->with('error', 'Você já está inscrito neste plantão.');
        }

        $funcoes = Dominio::opcoes('funcao');
        if (! empty($data['papel']) && $funcoes !== [] && ! in_array($data['papel'], $funcoes, true)) {
            return back()->withInput()->with('error', 'Função inválida.');
        }

        $inscricao = Inscricao::create([
            'user_id' => $user->id,
            'plantao_id' => $plantao->id,
            'hospital' => $plantao->hospital,
            'cidade' => $profile->cidade,
            'data' => $plantao->data,
            'modalidade' => $data['modalidade'] ?? 'presencial',
            'turno' => $data['turno'] ?? null,
            'papel' => $data['papel'] ?? $profile->cargo,
            'status' => Inscricao::STATUS_PENDENTE,
        ]);

        $this->log('inscricao.criada', 'inscricao', "Inscrição #{$inscricao->id} no plantão #{$plantao->id}.", $user->email, "plantao:{$plantao->id}");

        return redirect()->route('doutores.area.inscricoes')->with('success', 'Inscrição enviada! Aguarde a confirmação da coordenação.');
    }

    /** Cancelamento pelo próprio voluntário. */
    public function destroy(Request $request, Inscricao $inscricao): RedirectResponse
    {
        $user = $this->user();
        abort_unless((int) $inscricao->user_id === (int) $user->id, 403);

        $data = $request->validate([
            'justificativa' => ['nullable', 'string', 'max:1000'],
        ]);

        if (in_array($inscricao->status, [Inscricao::STATUS_CANCELADA, Inscricao::STATUS_RECUSADA], true)) {
            return back()->with('error', 'Esta inscrição já foi encerrada.');
        }

        $inscricao->update([
            'status' => Inscricao::STATUS_CANCELADA,
            'justificativa' => $data['justificativa'] ?? $inscricao->justificativa,
        ]);
        $inscricao->plantao?->syncVoluntarios();

        $this->log('inscricao.cancelada', 'inscricao', "Inscrição #{$inscricao->id} cancelada pelo voluntário.", $user->email, $inscricao->plantao_id ? "plantao:{$inscricao->plantao_id}" : null, 'warning');

        return back()->with('success', 'Inscrição cancelada.');
    }

    /** Voluntário confirma que irá comparecer. */
    public function confirmAttendance(Inscricao $inscricao): RedirectResponse
    {
        $user = $this->user();
        abort_unless((int) $inscricao->user_id === (int) $user->id, 403);

        if (! in_array($inscricao->status, [Inscricao::STATUS_PENDENTE, Inscricao::STATUS_CONFIRMADA], true)) {
            return back()->with('error', 'Esta inscrição não permite confirmação de presença.');
        }

        $inscricao->update(['confirmada_por_voluntario_em' => now()]);

        $this->log('inscricao.presenca_confirmada', 'inscricao', "Presença confirmada na inscrição #{$inscricao->id}.", $user->email, $inscricao->plantao_id ? "plantao:{$inscricao->plantao_id}" : null);

        return back()->with('success', 'Presença confirmada. Até lá!');
    }

    /** Coordenação/administração altera o status da inscrição. */
    public function updateStatus(Request $request, Inscricao $inscricao): RedirectResponse
    {
        $this->abortUnlessManager();

        $inscricao->loadMissing('plantao', 'user');
        $this->assertHospital($inscricao->plantao?->hospital ?? $inscricao->hospital);

        $data = $request->validate([
            'status' => ['required', Rule::in([Inscricao::STATUS_CONFIRMADA, Inscricao::STATUS_RECUSADA, Inscricao::STATUS_PENDENTE])],
            'justificativa' => ['nullable', 'string', 'max:1000'],
        ]);

        $inscricao->update([
            'status' => $data['status'],
            'justificativa' => $data['justificativa'] ?? $inscricao->justificativa,
        ]);
        $inscricao->plantao?->syncVoluntarios();

        $this->log('inscricao.status', 'inscricao', "Inscrição #{$inscricao->id} marcada como {$data['status']}.", $inscricao->user?->email, $inscricao->plantao_id ? "plantao:{$inscricao->plantao_id}" : null);

        return back()->with('success', 'Inscrição atualizada.');
    }
}
