<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
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

        if (!is_string($email) || $email === '') {
            throw ValidationException::withMessages([
                'email' => 'O e-mail é obrigatório.',
            ]);
        }

        $remember = $request->boolean('remember');

        if (!Auth::attempt(['email' => $email, 'password' => $credentials['password']], $remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();

        if (!$user instanceof User || !$user->hasAnyRoleName(['super_admin', 'manager', 'collaborator', 'fotografia'])) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Sua conta não tem acesso ao painel.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
