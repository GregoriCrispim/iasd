<?php

namespace App\Http\Controllers\Doutores;

use App\Models\Doutores\Polo;
use App\Models\Doutores\Profile;
use App\Models\Role;
use App\Models\User;
use App\Support\DoutoresAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Usuários do portal Doutores (somente administradores do módulo).
 *
 * Gerencia papéis doutores_*. O mesmo e-mail pode ter papéis do CMS/site;
 * este controller não remove nem altera esses papéis.
 */
class UsuarioController extends DoutoresController
{
    public const ROLE_LABELS = [
        DoutoresAuth::ROLE_ADMIN => 'Administrador',
        DoutoresAuth::ROLE_COORD => 'Coordenador de polo',
        DoutoresAuth::ROLE_VOL => 'Voluntário',
    ];

    public function index(Request $request): View
    {
        $this->abortUnlessAdmin();

        $q = trim((string) $request->input('q', ''));

        $usuarios = $this->doutoresUsersQuery()
            ->with(['roles', 'doutoresProfile', 'doutoresPolos'])
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%");
            }))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $edit = null;
        $editId = $request->integer('editar');
        if ($editId > 0) {
            $edit = $this->doutoresUsersQuery()->with(['roles', 'doutoresProfile', 'doutoresPolos'])->find($editId);
        }

        return view('doutores.usuarios.index', [
            'usuarios' => $usuarios,
            'edit' => $edit,
            'q' => $q,
            'roleLabels' => self::ROLE_LABELS,
            'polos' => Polo::query()->orderBy('nome')->get(['id', 'nome']),
            'autoId' => $this->user()->id,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->abortUnlessAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $existing = User::query()->where('email', $value)->with('roles')->first();
                    if ($existing && DoutoresAuth::canAccessDoutoresLogin($existing)) {
                        $fail('Já existe uma conta Doutores com este e-mail.');
                    }
                },
            ],
            'password' => ['nullable', 'string', 'min:8'],
            'doutores_roles' => ['required', 'array', 'min:1'],
            'doutores_roles.*' => ['string', Rule::in(DoutoresAuth::doutoresRoleNames())],
            'polo_ids' => ['nullable', 'array'],
            'polo_ids.*' => ['integer', 'exists:doutores_polos,id'],
        ], [
            'doutores_roles.required' => 'Selecione ao menos um papel.',
            'doutores_roles.min' => 'Selecione ao menos um papel.',
        ]);

        $roles = array_values(array_unique($data['doutores_roles']));
        $senha = $data['password'] ?? null;
        $senhaGerada = false;
        if (! $senha) {
            $senha = Str::password(12, symbols: false);
            $senhaGerada = true;
        }

        $actor = $this->user();

        $novo = DB::transaction(function () use ($data, $roles, $senha, $actor) {
            foreach ($roles as $r) {
                Role::query()->firstOrCreate(['name' => $r, 'guard_name' => 'web']);
            }

            $novo = User::query()->where('email', $data['email'])->first();
            if ($novo) {
                $novo->update([
                    'name' => $data['name'],
                    'password' => $senha,
                    'is_active' => true,
                ]);
            } else {
                $novo = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $senha,
                    'is_active' => true,
                    'created_by' => $actor->id,
                ]);
            }

            $novo->syncDoutoresRoles($roles);
            $this->syncPolos($novo, $roles, $data['polo_ids'] ?? []);

            Profile::query()->updateOrCreate(
                ['user_id' => $novo->id],
                ['status' => Profile::STATUS_ATIVO, 'termos_aceitos_em' => now()]
            );

            return $novo;
        });

        $this->log('usuario.criado', 'usuario', 'Usuário criado com papéis: '.implode(', ', $roles).'.', $novo->email);

        $msg = 'Usuário criado.';
        if ($senhaGerada) {
            $msg .= ' Senha temporária: '.$senha.' (anote e repasse ao usuário).';
        }

        return redirect()->route('doutores.usuarios.index')->with('success', $msg);
    }

    public function update(Request $request, User $usuario): RedirectResponse
    {
        $this->abortUnlessAdmin();
        $this->assertHasDoutoresAccess($usuario);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'status' => ['required', Rule::in([Profile::STATUS_ATIVO, Profile::STATUS_PENDENTE, Profile::STATUS_INATIVO])],
            'doutores_roles' => ['required', 'array', 'min:1'],
            'doutores_roles.*' => ['string', Rule::in(DoutoresAuth::doutoresRoleNames())],
            'polo_ids' => ['nullable', 'array'],
            'polo_ids.*' => ['integer', 'exists:doutores_polos,id'],
        ], [
            'doutores_roles.required' => 'Selecione ao menos um papel.',
            'doutores_roles.min' => 'Selecione ao menos um papel.',
        ]);

        $roles = array_values(array_unique($data['doutores_roles']));
        $self = $usuario->id === $this->user()->id;

        if ($self && (! in_array(DoutoresAuth::ROLE_ADMIN, $roles, true) || $data['status'] !== Profile::STATUS_ATIVO)) {
            return back()->withInput()->withErrors([
                'doutores_roles' => 'Você não pode remover seu próprio acesso de administrador.',
            ]);
        }

        DB::transaction(function () use ($usuario, $data, $roles) {
            foreach ($roles as $r) {
                Role::query()->firstOrCreate(['name' => $r, 'guard_name' => 'web']);
            }

            $payload = ['name' => $data['name'], 'email' => $data['email']];
            if (! empty($data['password'])) {
                $payload['password'] = $data['password'];
            }
            $usuario->update($payload);
            $usuario->syncDoutoresRoles($roles);
            $this->syncPolos($usuario, $roles, $data['polo_ids'] ?? []);

            Profile::query()->updateOrCreate(['user_id' => $usuario->id], ['status' => $data['status']]);
        });

        $this->log('usuario.atualizado', 'usuario', 'Usuário atualizado. Papéis: '.implode(', ', $roles).'.', $usuario->email);

        return redirect()->route('doutores.usuarios.index')->with('success', 'Usuário atualizado.');
    }

    public function destroy(User $usuario): RedirectResponse
    {
        $this->abortUnlessAdmin();
        $this->assertHasDoutoresAccess($usuario);

        if ($usuario->id === $this->user()->id) {
            return back()->with('error', 'Você não pode remover a própria conta.');
        }

        $email = $usuario->email;
        $usuario->loadMissing('roles');
        $hasSiteRoles = $usuario->roles
            ->pluck('name')
            ->contains(fn (string $name) => ! in_array($name, DoutoresAuth::doutoresRoleNames(), true));

        if ($hasSiteRoles) {
            // Conta híbrida: remove só o acesso Doutores e preserva a conta do CMS/site.
            $usuario->syncDoutoresRoles([]);
            $usuario->doutoresPolos()->detach();
            $usuario->doutoresProfile?->delete();
            $this->log('usuario.removido', 'usuario', 'Acesso Doutores removido (conta do site preservada).', $email, null, 'warning');

            return redirect()->route('doutores.usuarios.index')->with('success', 'Acesso Doutores removido. A conta do site foi preservada.');
        }

        $usuario->delete();

        $this->log('usuario.removido', 'usuario', 'Usuário removido.', $email, null, 'warning');

        return redirect()->route('doutores.usuarios.index')->with('success', 'Usuário removido.');
    }

    /**
     * Usuários que têm algum papel doutores_* (podem também ter papéis do CMS/site).
     */
    protected function doutoresUsersQuery()
    {
        return User::query()->whereHas('roles', fn ($q) => $q->whereIn('name', DoutoresAuth::doutoresRoleNames()));
    }

    /**
     * Exige que a conta tenha acesso Doutores (papéis do CMS/site são irrelevantes aqui).
     */
    protected function assertHasDoutoresAccess(User $usuario): void
    {
        $usuario->loadMissing('roles');

        abort_unless(DoutoresAuth::canAccessDoutoresLogin($usuario), 404);
    }

    /**
     * @param  list<string>  $roles
     * @param  list<int|string>  $poloIds
     */
    protected function syncPolos(User $user, array $roles, array $poloIds): void
    {
        $ids = in_array(DoutoresAuth::ROLE_COORD, $roles, true)
            ? array_values(array_unique(array_map('intval', $poloIds)))
            : [];

        $user->doutoresPolos()->sync($ids);
    }
}
