<?php

namespace App\Http\Controllers\Doutores;

use App\Http\Controllers\Controller;
use App\Models\Doutores\Profile;
use App\Models\User;
use App\Support\DoutoresAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Autenticação própria do portal Doutores da Esperança (guard `doutores`),
 * totalmente separada do painel do CMS (`admin`) e do site de membros (`web`).
 */
class AuthController extends Controller
{
    public function showLogin(Request $request): View|RedirectResponse
    {
        if (Auth::guard('doutores')->check()) {
            /** @var User $user */
            $user = Auth::guard('doutores')->user();
            $perfil = DoutoresAuth::getActivePerfil($request) ?? DoutoresAuth::defaultPerfil($user);

            return redirect()->route(DoutoresAuth::homeRouteFor($perfil));
        }

        return view('doutores.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'O e-mail é obrigatório.',
        ]);

        $user = User::query()
            ->whereHas('roles', fn ($q) => $q->whereIn('name', DoutoresAuth::doutoresRoleNames()))
            ->where('email', $data['email'])
            ->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user->load('roles', 'doutoresProfile');

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'email' => 'Sua conta está inativa.',
            ]);
        }

        if (! DoutoresAuth::canLoginDoutores($user)) {
            $pendente = $user->doutoresProfile?->status === Profile::STATUS_PENDENTE;

            throw ValidationException::withMessages([
                'email' => $pendente
                    ? 'Seu cadastro ainda aguarda aprovação da coordenação.'
                    : 'Sua conta não tem acesso ao portal Doutores da Esperança.',
            ]);
        }

        Auth::guard('doutores')->login($user, $request->boolean('remember'));

        // Preserva as demais sessões (admin / web); só renova o ID da sessão.
        $request->session()->regenerate();

        $perfil = DoutoresAuth::defaultPerfil($user);
        if ($perfil) {
            DoutoresAuth::setActivePerfil($request, $perfil);
        }

        $home = route(DoutoresAuth::homeRouteFor($perfil));

        // Só reaproveita o destino pretendido se for do próprio portal (evita herdar URLs do /admin).
        $intended = (string) $request->session()->pull('url.intended', '');
        $path = parse_url($intended, PHP_URL_PATH) ?: '';
        if ($path === '/doutores' || str_starts_with($path, '/doutores/')) {
            return redirect()->to($intended);
        }

        return redirect()->to($home);
    }

    public function escolherPerfil(Request $request): View
    {
        /** @var User $user */
        $user = Auth::guard('doutores')->user();

        return view('doutores.auth.escolher-perfil', [
            'perfis' => DoutoresAuth::availablePerfis($user),
            'ativo' => DoutoresAuth::getActivePerfil($request),
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('doutores')->logout();
        $request->session()->forget(DoutoresAuth::SESSION_PERFIL_KEY);

        // Não invalida a sessão inteira: admin e web continuam independentes.
        $request->session()->regenerateToken();

        return redirect()->route('doutores.login');
    }
}
