<?php

namespace App\Http\Controllers\Doutores;

use App\Models\Doutores\Dominio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DominioController extends DoutoresController
{
    public function index(Request $request): View
    {
        $this->abortUnlessAdmin();

        $categoria = $request->string('categoria')->toString();
        if (! array_key_exists($categoria, Dominio::CATEGORIAS)) {
            $categoria = array_key_first(Dominio::CATEGORIAS);
        }

        return view('doutores.dominios.index', [
            'categoria' => $categoria,
            'categorias' => Dominio::CATEGORIAS,
            'itens' => Dominio::query()->where('categoria', $categoria)->orderBy('descricao')->get(),
            'contagens' => Dominio::query()->selectRaw('categoria, count(*) as total')->groupBy('categoria')->pluck('total', 'categoria'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->abortUnlessAdmin();

        $data = $request->validate([
            'categoria' => ['required', Rule::in(array_keys(Dominio::CATEGORIAS))],
            'descricao' => ['required', 'string', 'max:255'],
        ]);

        $data['descricao'] = trim($data['descricao']);

        if (Dominio::query()->where('categoria', $data['categoria'])->where('descricao', $data['descricao'])->exists()) {
            return back()->withInput()->with('error', 'Este item já existe nesta categoria.');
        }

        $item = Dominio::create($data + ['ativo' => true]);

        $this->log('dominio.criado', 'dominio', "Item '{$item->descricao}' adicionado em {$item->categoria}.");

        return redirect()->route('doutores.dominios.index', ['categoria' => $data['categoria']])->with('success', 'Item adicionado.');
    }

    public function update(Request $request, Dominio $dominio): RedirectResponse
    {
        $this->abortUnlessAdmin();

        $data = $request->validate([
            'descricao' => ['required', 'string', 'max:255'],
            'ativo' => ['nullable', 'boolean'],
        ]);

        $descricao = trim($data['descricao']);

        $duplicado = Dominio::query()
            ->where('categoria', $dominio->categoria)
            ->where('descricao', $descricao)
            ->where('id', '!=', $dominio->id)
            ->exists();

        if ($duplicado) {
            return back()->with('error', 'Já existe um item com essa descrição.');
        }

        $dominio->update([
            'descricao' => $descricao,
            'ativo' => $request->boolean('ativo'),
        ]);

        $this->log('dominio.atualizado', 'dominio', "Item #{$dominio->id} atualizado.");

        return redirect()->route('doutores.dominios.index', ['categoria' => $dominio->categoria])->with('success', 'Item atualizado.');
    }

    public function destroy(Dominio $dominio): RedirectResponse
    {
        $this->abortUnlessAdmin();

        $categoria = $dominio->categoria;
        $descricao = $dominio->descricao;
        $dominio->delete();

        $this->log('dominio.removido', 'dominio', "Item '{$descricao}' removido de {$categoria}.", null, null, 'warning');

        return redirect()->route('doutores.dominios.index', ['categoria' => $categoria])->with('success', 'Item removido.');
    }
}
