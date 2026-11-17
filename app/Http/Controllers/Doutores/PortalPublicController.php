<?php

namespace App\Http\Controllers\Doutores;

use App\Http\Controllers\Controller;
use App\Models\Doutores\ComentarioPublicacao;
use App\Models\Doutores\PortalConfig;
use App\Models\Doutores\Publicacao;
use App\Support\DoutoresLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Portal público do Doutores da Esperança: lista/detalhe de publicações e comentários.
 */
class PortalPublicController extends Controller
{
    public function index(): View
    {
        return view('doutores.public.publicacoes-index', [
            'config' => PortalConfig::query()->find('portal'),
            'publicacoes' => Publicacao::query()
                ->where('status', 'publicado')
                ->withCount(['comentarios as comentarios_aprovados_count' => fn ($q) => $q->where('status', 'aprovado')])
                ->latest()
                ->paginate(9),
        ]);
    }

    public function show(Publicacao $publicacao): View
    {
        abort_unless($publicacao->status === 'publicado', 404);

        $publicacao->load(['comentarios' => fn ($q) => $q->where('status', 'aprovado')->latest()]);

        return view('doutores.public.publicacoes-show', [
            'publicacao' => $publicacao,
        ]);
    }

    public function comentar(Request $request, Publicacao $publicacao): RedirectResponse
    {
        abort_unless($publicacao->status === 'publicado', 404);

        $data = $request->validate([
            'nome' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['nullable', 'email', 'max:190'],
            'mensagem' => ['required', 'string', 'min:3', 'max:2000'],
        ], [
            'nome.required' => 'Informe seu nome.',
            'mensagem.required' => 'Escreva sua mensagem.',
        ]);

        $comentario = ComentarioPublicacao::create([
            'publicacao_id' => $publicacao->id,
            'nome' => $data['nome'],
            'email' => $data['email'] ?? null,
            'mensagem' => $data['mensagem'],
            'status' => 'pendente',
        ]);

        DoutoresLogger::record(
            'comentario.recebido',
            'publicacao',
            "Comentário #{$comentario->id} recebido (aguardando moderação).",
            null,
            $data['nome'],
            "publicacao:{$publicacao->id}"
        );

        return redirect()
            ->route('doutores.public.publicacoes.show', $publicacao)
            ->with('success', 'Obrigado! Seu comentário foi enviado e aparecerá após a moderação.');
    }
}
