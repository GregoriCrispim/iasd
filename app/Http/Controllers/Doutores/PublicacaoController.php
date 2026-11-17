<?php

namespace App\Http\Controllers\Doutores;

use App\Models\Doutores\ComentarioPublicacao;
use App\Models\Doutores\Publicacao;
use App\Support\DoutoresStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PublicacaoController extends DoutoresController
{
    public function index(): View
    {
        $this->abortUnlessAdmin();

        return view('doutores.publicacoes.index', [
            'publicacoes' => Publicacao::query()
                ->with('autor')
                ->withCount([
                    'comentarios',
                    'comentarios as comentarios_pendentes_count' => fn ($q) => $q->where('status', 'pendente'),
                ])
                ->latest()
                ->paginate(20),
        ]);
    }

    public function create(): View
    {
        $this->abortUnlessAdmin();

        return view('doutores.publicacoes.form', ['publicacao' => new Publicacao(['status' => 'publicado'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->abortUnlessAdmin();

        $data = $this->validated($request);
        $imagens = $this->storeImages($request);

        $publicacao = Publicacao::create([
            'titulo' => $data['titulo'],
            'conteudo' => $data['conteudo'] ?? null,
            'status' => $data['status'],
            'imagens' => $imagens ?: null,
            'autor_id' => $this->user()->id,
        ]);

        $this->log('publicacao.criada', 'publicacao', "Publicação '{$publicacao->titulo}' criada.", null, "publicacao:{$publicacao->id}");

        return redirect()->route('doutores.publicacoes.edit', $publicacao)->with('success', 'Publicação criada.');
    }

    public function edit(Publicacao $publicacao): View
    {
        $this->abortUnlessAdmin();

        $publicacao->load(['comentarios' => fn ($q) => $q->latest()]);

        return view('doutores.publicacoes.form', ['publicacao' => $publicacao]);
    }

    public function update(Request $request, Publicacao $publicacao): RedirectResponse
    {
        $this->abortUnlessAdmin();

        $data = $this->validated($request);
        $imagens = $publicacao->imagens ?? [];

        foreach ((array) $request->input('remover_imagens', []) as $remover) {
            if (in_array($remover, $imagens, true)) {
                DoutoresStorage::deletePublic($remover);
                $imagens = array_values(array_diff($imagens, [$remover]));
            }
        }

        $imagens = array_merge($imagens, $this->storeImages($request));

        $publicacao->update([
            'titulo' => $data['titulo'],
            'conteudo' => $data['conteudo'] ?? null,
            'status' => $data['status'],
            'imagens' => $imagens ?: null,
        ]);

        $this->log('publicacao.atualizada', 'publicacao', "Publicação #{$publicacao->id} atualizada.", null, "publicacao:{$publicacao->id}");

        return redirect()->route('doutores.publicacoes.edit', $publicacao)->with('success', 'Publicação atualizada.');
    }

    public function destroy(Publicacao $publicacao): RedirectResponse
    {
        $this->abortUnlessAdmin();

        foreach ($publicacao->imagens ?? [] as $path) {
            DoutoresStorage::deletePublic($path);
        }

        $titulo = $publicacao->titulo;
        $publicacao->delete();

        $this->log('publicacao.removida', 'publicacao', "Publicação '{$titulo}' removida.", null, null, 'warning');

        return redirect()->route('doutores.publicacoes.index')->with('success', 'Publicação removida.');
    }

    /** Moderação de comentários públicos. */
    public function moderarComentario(Request $request, ComentarioPublicacao $comentario): RedirectResponse
    {
        $this->abortUnlessAdmin();

        $data = $request->validate([
            'status' => ['required', Rule::in(['aprovado', 'rejeitado', 'pendente'])],
        ]);

        $comentario->update(['status' => $data['status']]);

        $this->log('comentario.moderado', 'publicacao', "Comentário #{$comentario->id} marcado como {$data['status']}.", $comentario->nome, "publicacao:{$comentario->publicacao_id}");

        return back()->with('success', 'Comentário atualizado.');
    }

    public function destroyComentario(ComentarioPublicacao $comentario): RedirectResponse
    {
        $this->abortUnlessAdmin();

        $comentario->delete();

        $this->log('comentario.removido', 'publicacao', "Comentário #{$comentario->id} removido.", $comentario->nome, null, 'warning');

        return back()->with('success', 'Comentário removido.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validated(Request $request): array
    {
        return $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'conteudo' => ['nullable', 'string', 'max:20000'],
            'status' => ['required', Rule::in(['publicado', 'rascunho'])],
            'imagens' => ['nullable', 'array', 'max:10'],
            'imagens.*' => ['image', 'max:4096'],
            'remover_imagens' => ['nullable', 'array'],
            'remover_imagens.*' => ['string'],
        ]);
    }

    /**
     * @return list<string>
     */
    protected function storeImages(Request $request): array
    {
        $paths = [];

        foreach ($request->file('imagens', []) as $file) {
            // Disco público em public/img/doutores/uploads — mesma estratégia da galeria (sem storage:link).
            $paths[] = DoutoresStorage::storePublic($file, 'publicacoes');
        }

        return $paths;
    }
}
