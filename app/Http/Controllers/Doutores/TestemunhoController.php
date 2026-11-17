<?php

namespace App\Http\Controllers\Doutores;

use App\Models\Doutores\Inscricao;
use App\Models\Doutores\Plantao;
use App\Models\Doutores\Testemunho;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestemunhoController extends DoutoresController
{
    /** Testemunhos do voluntário e plantões em que ainda pode registrar um. */
    public function index(): View
    {
        $user = $this->user();

        $testemunhos = Testemunho::query()->with('plantao')->where('user_id', $user->id)->latest()->get();

        $disponiveis = Plantao::query()
            ->whereDate('data', '<=', now()->toDateString())
            ->where('status', '!=', Plantao::STATUS_CANCELADO)
            ->whereHas('inscricoes', fn (Builder $q) => $q
                ->where('user_id', $user->id)
                ->where('status', Inscricao::STATUS_CONFIRMADA))
            ->whereNotIn('id', $testemunhos->pluck('plantao_id'))
            ->orderByDesc('data')
            ->get();

        return view('doutores.area.testemunhos', [
            'testemunhos' => $testemunhos,
            'disponiveis' => $disponiveis,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $this->user();

        $data = $request->validate([
            'plantao_id' => ['required', 'integer', 'exists:doutores_plantoes,id'],
            'texto' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $participou = Inscricao::query()
            ->where('user_id', $user->id)
            ->where('plantao_id', $data['plantao_id'])
            ->where('status', Inscricao::STATUS_CONFIRMADA)
            ->exists();

        if (! $participou) {
            return back()->withInput()->with('error', 'Você só pode registrar testemunho de plantões em que participou.');
        }

        if (Testemunho::query()->where('user_id', $user->id)->where('plantao_id', $data['plantao_id'])->exists()) {
            return back()->with('error', 'Você já registrou um testemunho para este plantão. Edite o existente.');
        }

        $testemunho = Testemunho::create([
            'user_id' => $user->id,
            'plantao_id' => $data['plantao_id'],
            'texto' => $data['texto'],
        ]);

        $this->log('testemunho.criado', 'testemunho', "Testemunho #{$testemunho->id} registrado.", $user->email, "plantao:{$data['plantao_id']}");

        return redirect()->route('doutores.area.testemunhos')->with('success', 'Testemunho registrado. Obrigado por compartilhar!');
    }

    public function update(Request $request, Testemunho $testemunho): RedirectResponse
    {
        $user = $this->user();
        abort_unless((int) $testemunho->user_id === (int) $user->id, 403);

        $data = $request->validate([
            'texto' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $testemunho->update(['texto' => $data['texto']]);

        $this->log('testemunho.atualizado', 'testemunho', "Testemunho #{$testemunho->id} atualizado.", $user->email);

        return redirect()->route('doutores.area.testemunhos')->with('success', 'Testemunho atualizado.');
    }
}
