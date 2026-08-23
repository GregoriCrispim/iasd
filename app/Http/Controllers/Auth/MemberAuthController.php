<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MemberAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if ($this->activeSiteUser()) {
            return redirect()->intended(route('galeria'));
        }

        $this->forgetInvalidWebSession();

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        if ($this->activeSiteUser()) {
            return redirect()->intended(route('galeria'));
        }

        $this->forgetInvalidWebSession();

        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()
            ->where('email', $data['email'])
            ->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        if (! $user->canUseSiteAuth()) {
            throw ValidationException::withMessages([
                'email' => 'Sua conta está inativa. Fale com a equipe de comunicação.',
            ]);
        }

        Auth::guard('web')->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->to($this->safeRedirectTarget($request, route('galeria')));
    }

    public function showRegister(): View|RedirectResponse
    {
        if ($this->activeSiteUser()) {
            return redirect()->intended(route('galeria'));
        }

        $this->forgetInvalidWebSession();

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        if ($this->activeSiteUser()) {
            return redirect()->intended(route('galeria'));
        }

        $this->forgetInvalidWebSession();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => [
                'required',
                'email',
                'max:190',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (User::query()->where('email', $value)->exists()) {
                        $fail('Este e-mail já possui conta. Faça login.');
                    }
                },
            ],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'phone' => ['required', 'string', 'max:30'],
            'birth_date' => ['required', 'date', 'before:today'],
            'congregation' => ['required', 'string', 'in:'.implode(',', array_keys(User::MEMBERSHIP_LINKS))],
            'guardian_consent' => ['nullable', 'boolean'],
            'accept_terms' => ['accepted'],
        ], [
            'congregation.required' => 'Selecione seu vínculo com a igreja.',
            'congregation.in' => 'Selecione uma opção válida de vínculo.',
            'accept_terms.accepted' => 'É necessário aceitar os termos para se cadastrar.',
        ]);

        $birthDate = Carbon::parse($data['birth_date']);
        $isMinor = $birthDate->age < 18;

        if ($isMinor && ! $request->boolean('guardian_consent')) {
            throw ValidationException::withMessages([
                'guardian_consent' => 'Para menores de 18 anos, é necessária a declaração de consentimento do responsável legal.',
            ]);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'],
            'birth_date' => $birthDate->format('Y-m-d'),
            'congregation' => $data['congregation'],
            'is_church_member' => User::isBaptizedMembershipLink($data['congregation']),
            'is_active' => true,
        ]);

        $user->syncRoles(['member']);

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return redirect()
            ->to($this->safeRedirectTarget($request, route('galeria')))
            ->with('success', 'Cadastro concluído! Bem-vindo(a), '.$user->name.'.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        // Não invalida a sessão inteira: a autenticação do painel (admin) deve permanecer.
        $request->session()->regenerateToken();

        return redirect()->route('galeria')->with('success', 'Você saiu da sua conta.');
    }

    protected function activeSiteUser(): ?User
    {
        $user = Auth::guard('web')->user();

        return $user instanceof User && $user->canUseSiteAuth() ? $user : null;
    }

    /**
     * Remove autenticação residual no guard web quando a conta não pode usar o site.
     */
    protected function forgetInvalidWebSession(): void
    {
        if (Auth::guard('web')->check() && ! $this->activeSiteUser()) {
            Auth::guard('web')->logout();
        }
    }

    protected function safeRedirectTarget(Request $request, string $fallback): string
    {
        $candidate = $request->query('redirect', $request->input('redirect'));

        if (! is_string($candidate) || $candidate === '') {
            return $request->session()->pull('url.intended', $fallback);
        }

        $path = parse_url($candidate, PHP_URL_PATH) ?: '';
        if ($path === '' || ! str_starts_with($path, '/') || str_starts_with($path, '//')) {
            return $fallback;
        }

        // Evita open redirect e rotas de autenticação.
        if (str_starts_with($path, '/admin') || $path === '/entrar' || $path === '/cadastrar' || $path === '/sair') {
            return $fallback;
        }

        return $path.(parse_url($candidate, PHP_URL_QUERY) ? '?'.parse_url($candidate, PHP_URL_QUERY) : '');
    }
}
