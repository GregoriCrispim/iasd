<?php

namespace App\Http\Controllers\Doutores;

use App\Models\Doutores\Anexo;
use App\Models\Doutores\Aviso;
use App\Models\Doutores\Dominio;
use App\Models\Doutores\Hospital;
use App\Models\Doutores\Inscricao;
use App\Models\Doutores\Plantao;
use App\Support\DoutoresStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VoluntarioAreaController extends DoutoresController
{
    public function inicio(): View
    {
        $user = $this->user();
        $profile = $this->profileFor($user);

        $inscricoes = Inscricao::query()
            ->with('plantao')
            ->where('user_id', $user->id)
            ->whereIn('status', [Inscricao::STATUS_PENDENTE, Inscricao::STATUS_CONFIRMADA])
            ->where(fn ($q) => $q->whereNull('data')->orWhereDate('data', '>=', now()->toDateString()))
            ->orderBy('data')
            ->limit(5)
            ->get();

        $avisosNaoLidos = AvisoController::avisosDoVoluntario($user)
            ->whereDoesntHave('leituras', fn ($q) => $q->where('user_id', $user->id))
            ->count();

        $plantoesAbertos = Plantao::query()
            ->where('status', Plantao::STATUS_ABERTO)
            ->whereDate('data', '>=', now()->toDateString())
            ->count();

        $anexosPendentes = Anexo::query()
            ->where('user_id', $user->id)
            ->where('status', 'rejeitado')
            ->count();

        return view('doutores.area.inicio', [
            'profile' => $profile,
            'inscricoes' => $inscricoes,
            'avisosNaoLidos' => $avisosNaoLidos,
            'plantoesAbertos' => $plantoesAbertos,
            'anexosRejeitados' => $anexosPendentes,
        ]);
    }

    public function perfil(): View
    {
        $user = $this->user();

        return view('doutores.area.perfil', [
            'profile' => $this->profileFor($user),
            'targetUser' => $user,
            'hospitais' => Hospital::query()->where('status', 'ativo')->orderBy('nome')->pluck('nome'),
            'dias' => Dominio::opcoes('dia_preferido'),
            'turnos' => Dominio::opcoes('turno_preferido'),
            'igrejas' => Dominio::opcoes('igreja'),
            'funcoes' => Dominio::opcoes('funcao'),
        ]);
    }

    public function updatePerfil(Request $request): RedirectResponse
    {
        $user = $this->user();
        $profile = $this->profileFor($user);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'genero' => ['nullable', Rule::in(['feminino', 'masculino', 'outro'])],
            'cep' => ['nullable', 'string', 'max:9'],
            'endereco' => ['nullable', 'string', 'max:255'],
            'bairro' => ['nullable', 'string', 'max:255'],
            'cidade' => ['nullable', 'string', 'max:255'],
            'hospital_preferido' => ['nullable', 'string', 'max:255'],
            'dia_preferido' => ['nullable', 'string', 'max:255'],
            'horario_preferido' => ['nullable', 'string', 'max:255'],
            'igreja' => ['nullable', 'string', 'max:255'],
            'cargo' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'max:3072'],
        ]);

        $user->update(['name' => $data['name']]);

        $payload = collect($data)->except(['name', 'foto'])->all();
        $payload['cep'] = isset($payload['cep']) ? preg_replace('/\D/', '', $payload['cep']) : null;
        $payload['telefone'] = isset($payload['telefone']) ? preg_replace('/\D/', '', $payload['telefone']) : null;

        if ($request->hasFile('foto')) {
            if ($profile->foto_path) {
                DoutoresStorage::deletePublic($profile->foto_path);
            }
            $payload['foto_path'] = DoutoresStorage::storePublic($request->file('foto'), 'fotos');
        }

        $profile->update($payload);

        $this->log('voluntario.perfil_atualizado', 'voluntario', 'Perfil atualizado pelo próprio voluntário.', $user->email);

        return redirect()->route('doutores.area.perfil')->with('success', 'Perfil atualizado.');
    }

    public function termos(): View
    {
        return view('doutores.area.termos', [
            'profile' => $this->profileFor($this->user()),
        ]);
    }

    public function aceitarTermos(Request $request): RedirectResponse
    {
        $request->validate(['aceito' => ['accepted']], [
            'aceito.accepted' => 'É necessário aceitar os termos para continuar.',
        ]);

        $user = $this->user();
        $profile = $this->profileFor($user);
        $profile->update(['termos_aceitos_em' => now()]);

        $this->log('voluntario.termos_aceitos', 'voluntario', 'Termos de voluntariado aceitos.', $user->email);

        return redirect()->route('doutores.area.inicio')->with('success', 'Termos aceitos. Obrigado!');
    }
}
