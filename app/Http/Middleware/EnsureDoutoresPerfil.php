<?php

namespace App\Http\Middleware;

use App\Support\DoutoresAuth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureDoutoresPerfil
{
    /**
     * @param  list<string>  $perfis  Perfis ativos permitidos (administrador, coordenador, voluntario).
     *                                Use "manager" as alias for administrador|coordenador.
     */
    public function handle(Request $request, Closure $next, string ...$perfis): Response
    {
        $user = $request->user('doutores');

        if (! $user) {
            return redirect()->route('doutores.login');
        }

        $user->loadMissing('roles', 'doutoresProfile');

        if (! DoutoresAuth::canLoginDoutores($user)) {
            Auth::guard('doutores')->logout();

            $request->session()->forget(DoutoresAuth::SESSION_PERFIL_KEY);

            return redirect()->route('doutores.login')
                ->with('error', 'Sem acesso ao módulo Doutores da Esperança.');
        }

        $active = DoutoresAuth::getActivePerfil($request);
        $available = DoutoresAuth::availablePerfis($user);

        if ($active === null || ! in_array($active, $available, true)) {
            $active = DoutoresAuth::defaultPerfil($user);
            if ($active) {
                DoutoresAuth::setActivePerfil($request, $active);
            }
        }

        if ($perfis === []) {
            return $next($request);
        }

        $allowed = [];
        foreach ($perfis as $perfil) {
            if ($perfil === 'manager') {
                $allowed[] = DoutoresAuth::PERFIL_ADMIN;
                $allowed[] = DoutoresAuth::PERFIL_COORD;
            } else {
                $allowed[] = $perfil;
            }
        }

        if ($active === null || ! in_array($active, $allowed, true)) {
            abort(403, 'Perfil Doutores insuficiente para esta área.');
        }

        return $next($request);
    }
}
