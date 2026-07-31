<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MemberInvite;
use App\Models\MemberInviteUse;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MemberAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if ($this->activeMember()) {
            return redirect()->intended(route('galeria'));
        }

        $this->forgetNonMemberWebSession();

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        if ($this->activeMember()) {
            return redirect()->intended(route('galeria'));
        }

        $this->forgetNonMemberWebSession();

        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()
            ->members()
            ->where('email', $data['email'])
            ->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        if (! $user->isActiveMember()) {
            throw ValidationException::withMessages([
                'email' => 'Sua conta de membro está inativa. Fale com a equipe de comunicação.',
            ]);
        }

        Auth::guard('web')->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->to($this->safeRedirectTarget($request, route('galeria')));
    }

    public function showRegister(Request $request): View|RedirectResponse
    {
        if ($this->activeMember()) {
            return redirect()->intended(route('galeria'));
        }

        $this->forgetNonMemberWebSession();

        return view('auth.register', [
            'prefillCode' => (string) $request->query('convite', ''),
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        if ($this->activeMember()) {
            return redirect()->intended(route('galeria'));
        }

        $this->forgetNonMemberWebSession();

        $data = $request->validate([
            'invite_code' => ['required', 'string', 'max:40'],
            'name' => ['required', 'string', 'max:120'],
            'email' => [
                'required',
                'email',
                'max:190',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    // Unicidade apenas entre contas de membro; o mesmo e-mail pode existir no painel.
                    if (User::query()->members()->where('email', $value)->exists()) {
                        $fail('Este e-mail já possui conta de membro. Faça login.');
                    }
                },
            ],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'phone' => ['required', 'string', 'max:30'],
            'birth_date' => ['required', 'date', 'before:today'],
            'congregation' => ['nullable', 'string', 'max:120'],
            'is_church_member' => ['nullable', 'boolean'],
            'guardian_consent' => ['nullable', 'boolean'],
            'accept_terms' => ['accepted'],
        ], [
            'accept_terms.accepted' => 'É necessário aceitar os termos para se cadastrar.',
        ]);

        $birthDate = Carbon::parse($data['birth_date']);
        $isMinor = $birthDate->age < 18;

        if ($isMinor && ! $request->boolean('guardian_consent')) {
            throw ValidationException::withMessages([
                'guardian_consent' => 'Para menores de 18 anos, é necessária a declaração de consentimento do responsável legal.',
            ]);
        }

        try {
            $user = DB::transaction(function () use ($data, $request, $birthDate) {
                $invite = MemberInvite::query()
                    ->where('code_hash', MemberInvite::hashCode($data['invite_code']))
                    ->lockForUpdate()
                    ->first();

                if (! $invite || ! $invite->isUsable()) {
                    throw ValidationException::withMessages([
                        'invite_code' => 'Código de convite inválido, expirado ou esgotado.',
                    ]);
                }

                // Conta de membro é sempre um registro novo, independente do painel.
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'phone' => $data['phone'],
                    'birth_date' => $birthDate->format('Y-m-d'),
                    'congregation' => $data['congregation'] ?? null,
                    'is_church_member' => $request->boolean('is_church_member'),
                    'is_active' => true,
                ]);

                $user->syncRoles(['member']);

                $invite->increment('uses_count');
                if ($invite->uses_count >= $invite->max_uses) {
                    $invite->forceFill(['is_active' => false])->save();
                }

                MemberInviteUse::create([
                    'member_invite_id' => $invite->id,
                    'user_id' => $user->id,
                    'used_at' => now(),
                ]);

                return $user;
            });
        } catch (ValidationException $e) {
            throw $e;
        }

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

    protected function activeMember(): ?User
    {
        $user = Auth::guard('web')->user();

        return $user instanceof User && $user->isActiveMember() ? $user : null;
    }

    /**
     * Remove autenticação residual no guard web quando não é membro ativo.
     */
    protected function forgetNonMemberWebSession(): void
    {
        if (Auth::guard('web')->check() && ! $this->activeMember()) {
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
