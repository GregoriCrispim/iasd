<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $email = $request->input('email') ?? $request->input('login');

        $credentials = $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (! is_string($email) || $email === '') {
            throw ValidationException::withMessages([
                'email' => 'O e-mail é obrigatório.',
            ]);
        }

        $user = User::query()
            ->admins()
            ->where('email', $email)
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        if (! $user->canAccessAdminPanel()) {
            throw ValidationException::withMessages([
                'email' => 'Sua conta não tem acesso ao painel.',
            ]);
        }

        Auth::guard('admin')->login($user, $request->boolean('remember'));

        // Regenera o ID da sessão sem invalidá-la, para preservar a sessão do site (web).
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        // Não invalida a sessão inteira: a autenticação de membro (web) deve permanecer.
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
