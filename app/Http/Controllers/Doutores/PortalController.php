<?php

namespace App\Http\Controllers\Doutores;

use App\Models\Doutores\PortalConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortalController extends DoutoresController
{
    public function edit(): View
    {
        $this->abortUnlessAdmin();

        return view('doutores.portal.edit', ['config' => $this->config()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->abortUnlessAdmin();

        $data = $request->validate([
            'titulo_hero' => ['nullable', 'string', 'max:255'],
            'subtitulo_hero' => ['nullable', 'string', 'max:2000'],
            'texto_sobre' => ['nullable', 'string', 'max:10000'],
            'contato_email' => ['nullable', 'email', 'max:255'],
            'contato_telefone' => ['nullable', 'string', 'max:255'],
            'contato_endereco' => ['nullable', 'string', 'max:255'],
        ]);

        $this->config()->update($data);

        $this->log('portal.atualizado', 'portal', 'Configurações do portal atualizadas.');

        return back()->with('success', 'Portal atualizado.');
    }

    protected function config(): PortalConfig
    {
        return PortalConfig::query()->firstOrCreate(['id' => 'portal']);
    }
}
