<?php

namespace App\Http\Controllers\Doutores;

use App\Support\DoutoresAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PerfilSwitchController extends DoutoresController
{
    public function switch(Request $request): RedirectResponse
    {
        $user = $this->user();
        $available = DoutoresAuth::availablePerfis($user);

        $data = $request->validate([
            'perfil' => ['required', 'string', Rule::in($available)],
        ]);

        DoutoresAuth::setActivePerfil($request, $data['perfil']);

        $this->log('perfil.trocar', 'sessao', 'Perfil ativo alterado para '.$data['perfil'].'.', $user->email);

        return redirect()
            ->route(DoutoresAuth::homeRouteFor($data['perfil']))
            ->with('success', 'Perfil alterado.');
    }
}
