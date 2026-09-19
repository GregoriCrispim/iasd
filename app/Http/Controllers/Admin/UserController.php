<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $authUser = $this->authUser();

        $users = $this->scopedQuery($authUser)
            ->with(['roles', 'manager'])
            ->when($request->filled('q'), function (Builder $query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(fn (Builder $q) => $q->where('name', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->orderBy('name')
            ->paginate(30)
            ->withQueryString();

        $editUser = null;
        $editRoleOptions = [];
        $editCurrentRole = null;
        $editAttached = collect();
        $editAvailable = collect();
        $canManagePagePerms = false;

        $editId = $request->integer('editar');
        if ($editId <= 0 && old('_form') === 'edit') {
            $editId = (int) old('_user_id');
        }

        if ($editId > 0) {
            $candidate = $this->scopedQuery($authUser)->with(['roles', 'manager'])->find($editId);
            if ($candidate && $authUser->canManageUser($candidate)) {
                $editUser = $candidate;
                $editRoleOptions = $this->roleOptions($authUser, $editUser);
                $editCurrentRole = $editUser->roles->pluck('name')->first();
                $canManagePagePerms = $this->canManagePagePerms($authUser, $editUser);

                if ($canManagePagePerms) {
                    $editAttached = $editUser->pages()->orderBy('label')->get();
                    $editAvailable = $this->availablePages($authUser)
                        ->whereNotIn('id', $editAttached->pluck('id'));
                }
            }
        }

        $createAvailablePages = collect();
        $canAssignCreatePagePerms = $authUser->hasFullAdminAccess() || $authUser->isManager();
        if ($canAssignCreatePagePerms) {
            $createAvailablePages = $this->availablePages($authUser);
        }

        return view('admin.users.index', [
            'users' => $users,
            'authUser' => $authUser,
            'roleOptions' => $this->roleOptions($authUser),
            'defaultRole' => $this->defaultRole($authUser),
            'canAssignAdvanced' => $this->canAssignAdvancedFields($authUser),
            'editUser' => $editUser,
            'editRoleOptions' => $editRoleOptions,
            'editCurrentRole' => $editCurrentRole,
            'editAttached' => $editAttached,
            'editAvailable' => $editAvailable,
            'canManagePagePerms' => $canManagePagePerms,
            'createAvailablePages' => $createAvailablePages,
            'canAssignCreatePagePerms' => $canAssignCreatePagePerms,
        ]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.users.index', ['novo' => 1]);
    }

    public function store(Request $request): RedirectResponse
    {
        $authUser = $this->authUser();

        try {
            $data = $this->validateData($request, null, $authUser);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e->redirectTo(route('admin.users.index', ['novo' => 1]));
        }

        [$role, $managerId] = $this->resolveAssignment($authUser, $data);

        $this->ensureRoleExists($role);

        $pageSync = [];
        if ($this->roleUsesPagePermissions($role) && ($authUser->hasFullAdminAccess() || $authUser->isManager())) {
            $pageSync = $this->validatedPageLinks($request, $authUser);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'created_by' => $authUser->id,
            'manager_id' => $managerId === false ? null : $managerId,
        ]);

        $user->syncRoles([$role]);

        if ($pageSync !== []) {
            $user->pages()->syncWithoutDetaching($pageSync);
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuário criado.');
    }

    public function edit(Request $request, User $user): RedirectResponse
    {
        $authUser = $this->authUser();
        $this->authorizeManage($authUser, $user);

        return redirect()->route('admin.users.index', array_filter([
            'editar' => $user->id,
            'q' => $request->query('q'),
        ]));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $authUser = $this->authUser();
        $this->authorizeManage($authUser, $user);

        try {
            $data = $this->validateData($request, $user, $authUser);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e->redirectTo(route('admin.users.index', ['editar' => $user->id]));
        }

        [$role, $managerId] = $this->resolveAssignment($authUser, $data, $user);

        $this->ensureRoleExists($role);

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if ($managerId !== false) {
            $payload['manager_id'] = $managerId;
        }

        if (! empty($data['password'])) {
            $payload['password'] = $data['password'];
        }

        $user->update($payload);
        $user->syncRoles([$role]);

        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $authUser = $this->authUser();

        if (! $authUser->hasFullAdminAccess()) {
            abort(403);
        }

        if ($user->isProtectedFromDeletion()) {
            return back()->with('error', 'O Super Admin não pode ser excluído.');
        }

        if ($user->id === $authUser->id) {
            return back()->with('error', 'Você não pode excluir o próprio usuário.');
        }

        // Admin não exclui outro Admin — somente o Super Admin.
        if ($authUser->isAdmin() && $user->isAdmin()) {
            return back()->with('error', 'Apenas o Super Admin pode excluir um Admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuário removido.');
    }

    /* ---------------- Page permissions ---------------- */

    public function pages(Request $request, User $user): RedirectResponse
    {
        $authUser = $this->authUser();
        $this->authorizeManage($authUser, $user);

        if (! $this->canManagePagePerms($authUser, $user)) {
            abort(403);
        }

        return redirect()->route('admin.users.index', ['editar' => $user->id]);
    }

    public function attachPage(Request $request, User $user): RedirectResponse
    {
        $authUser = $this->authUser();
        $this->authorizeManage($authUser, $user);

        if (! $this->canManagePagePerms($authUser, $user)) {
            abort(403);
        }

        $data = $request->validate([
            'cms_page_id' => ['required', 'exists:cms_pages,id'],
            'can_access' => ['nullable', 'boolean'],
            'can_edit' => ['nullable', 'boolean'],
            'can_approve' => ['nullable', 'boolean'],
        ]);

        if (! $this->availablePages($authUser)->contains('id', (int) $data['cms_page_id'])) {
            abort(403);
        }

        $user->pages()->syncWithoutDetaching([
            $data['cms_page_id'] => [
                'can_access' => $request->boolean('can_access'),
                'can_edit' => $request->boolean('can_edit'),
                'can_approve' => $request->boolean('can_approve'),
            ],
        ]);

        return redirect()->route('admin.users.index', ['editar' => $user->id])->with('success', 'Página vinculada.');
    }

    public function updatePage(Request $request, User $user, CmsPage $page): RedirectResponse
    {
        $authUser = $this->authUser();
        $this->authorizeManage($authUser, $user);

        if (! $this->canManagePagePerms($authUser, $user)) {
            abort(403);
        }

        $request->validate([
            'can_access' => ['nullable', 'boolean'],
            'can_edit' => ['nullable', 'boolean'],
            'can_approve' => ['nullable', 'boolean'],
        ]);

        $user->pages()->updateExistingPivot($page->id, [
            'can_access' => $request->boolean('can_access'),
            'can_edit' => $request->boolean('can_edit'),
            'can_approve' => $request->boolean('can_approve'),
        ]);

        return redirect()->route('admin.users.index', ['editar' => $user->id])->with('success', 'Permissões atualizadas.');
    }

    public function detachPage(Request $request, User $user, CmsPage $page): RedirectResponse
    {
        $authUser = $this->authUser();
        $this->authorizeManage($authUser, $user);

        if (! $this->canManagePagePerms($authUser, $user)) {
            abort(403);
        }

        $user->pages()->detach($page->id);

        return redirect()->route('admin.users.index', ['editar' => $user->id])->with('success', 'Página removida do usuário.');
    }

    /* ---------------- Helpers ---------------- */

    protected function authUser(): User
    {
        $user = Auth::guard('admin')->user() ?? request()->user('admin');

        if (! $user instanceof User) {
            abort(403);
        }

        $user->loadMissing('roles');

        return $user;
    }

    protected function ensureRoleExists(string $role): void
    {
        Role::query()->firstOrCreate([
            'name' => $role,
            'guard_name' => 'web',
        ]);
    }

    protected function scopedQuery(User $authUser): Builder
    {
        $query = User::query();

        if ($authUser->hasFullAdminAccess()) {
            return $query;
        }

        if ($authUser->isManager() || $authUser->isFotografiaLider()) {
            return $query->where(fn (Builder $b) => $b->where('manager_id', $authUser->id)->orWhere('id', $authUser->id));
        }

        return $query->where('id', $authUser->id);
    }

    protected function authorizeManage(User $authUser, User $target): void
    {
        if (! $authUser->canManageUser($target)) {
            abort(403);
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected function validateData(Request $request, ?User $user, User $authUser): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail) use ($user): void {
                    $query = User::query()->where('email', $value);
                    if ($user) {
                        $query->where('id', '!=', $user->id);
                    }
                    if ($query->exists()) {
                        $fail('Este e-mail já está em uso.');
                    }
                },
            ],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:5'],
        ];

        if ($this->canAssignAdvancedFields($authUser) || count($this->roleOptions($authUser, $user)) > 1) {
            $rules['role'] = ['required', Rule::in(array_keys($this->roleOptions($authUser, $user)))];
        } elseif (count($this->roleOptions($authUser, $user)) === 1) {
            $rules['role'] = ['nullable', Rule::in(array_keys($this->roleOptions($authUser, $user)))];
        }

        return $request->validate($rules);
    }

    /**
     * Hierarquia automática: gestor/líder vinculam subordinados a si.
     * Super admin / Admin não definem responsável pelo formulário (false = não alterar no update).
     *
     * @param  array<string, mixed>  $data
     * @return array{0: string, 1: int|null|false}
     */
    protected function resolveAssignment(User $authUser, array $data, ?User $target = null): array
    {
        if ($authUser->isManager()) {
            return ['collaborator', $authUser->id];
        }

        if ($authUser->isFotografiaLider()) {
            return ['fotografia_colaborador', $authUser->id];
        }

        // Super Admin editing themselves keeps super_admin (role is not assignable via form).
        if ($target?->isSuperAdmin()) {
            return ['super_admin', false];
        }

        $role = $data['role'] ?? $this->defaultRole($authUser);

        if (! is_string($role) || $role === '' || ! array_key_exists($role, $this->roleOptions($authUser, $target))) {
            abort(422, 'Perfil inválido.');
        }

        return [$role, false];
    }

    protected function canAssignAdvancedFields(User $authUser): bool
    {
        return $authUser->hasFullAdminAccess();
    }

    protected function canManagePagePerms(User $authUser, ?User $target = null): bool
    {
        if (! ($authUser->hasFullAdminAccess() || $authUser->isManager())) {
            return false;
        }

        // Permissões de páginas só existem para perfis CMS (gestor / colaborador).
        if ($target) {
            return $this->userUsesPagePermissions($target);
        }

        return true;
    }

    /**
     * Perfis que usam vínculo de páginas no CMS.
     */
    protected function userUsesPagePermissions(User $user): bool
    {
        return $user->hasAnyRoleName(['manager', 'collaborator']);
    }

    protected function roleUsesPagePermissions(string $role): bool
    {
        return in_array($role, ['manager', 'collaborator'], true);
    }

    /**
     * @return array<int, array{can_access: bool, can_edit: bool, can_approve: bool}>
     */
    protected function validatedPageLinks(Request $request, User $authUser): array
    {
        $allowedIds = $this->availablePages($authUser)->pluck('id')->map(fn ($id) => (int) $id)->all();

        $links = $request->input('page_links', []);
        if (! is_array($links)) {
            $links = [];
        }

        // Remove linhas vazias do formulário dinâmico.
        $links = array_values(array_filter($links, function ($link) {
            return is_array($link) && ! empty($link['cms_page_id']);
        }));

        $request->merge(['page_links' => $links]);

        try {
            $validated = $request->validate([
                'page_links' => ['nullable', 'array'],
                'page_links.*.cms_page_id' => ['required', 'integer', 'exists:cms_pages,id'],
                'page_links.*.can_access' => ['nullable', 'boolean'],
                'page_links.*.can_edit' => ['nullable', 'boolean'],
                'page_links.*.can_approve' => ['nullable', 'boolean'],
            ])['page_links'] ?? [];
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e->redirectTo(route('admin.users.index', ['novo' => 1]));
        }

        $sync = [];
        foreach ($validated as $link) {
            $pageId = (int) ($link['cms_page_id'] ?? 0);
            if ($pageId <= 0 || ! in_array($pageId, $allowedIds, true)) {
                continue;
            }

            $sync[$pageId] = [
                'can_access' => (bool) ($link['can_access'] ?? false),
                'can_edit' => (bool) ($link['can_edit'] ?? false),
                'can_approve' => (bool) ($link['can_approve'] ?? false),
            ];
        }

        return $sync;
    }

    protected function defaultRole(User $authUser): ?string
    {
        if ($authUser->isSuperAdmin()) {
            return 'admin';
        }

        if ($authUser->isAdmin()) {
            return 'manager';
        }

        if ($authUser->isManager()) {
            return 'collaborator';
        }

        if ($authUser->isFotografiaLider()) {
            return 'fotografia_colaborador';
        }

        return null;
    }

    /**
     * @return array<string, string>
     */
    protected function roleOptions(User $authUser, ?User $target = null): array
    {
        // Super Admin editing themselves: role is locked.
        if ($target?->isSuperAdmin()) {
            return ['super_admin' => 'Super Admin'];
        }

        // Super Admin / Admin: todos os perfis operacionais (Admin só o Super Admin cria).
        if ($authUser->hasFullAdminAccess()) {
            $options = [
                'manager' => 'Gestor',
                'collaborator' => 'Colaborador',
                'fotografia_lider' => 'Líder de Fotografia',
                'fotografia_colaborador' => 'Colaborador de Fotografia',
            ];

            if ($authUser->isSuperAdmin()) {
                return ['admin' => 'Admin'] + $options;
            }

            return $options;
        }

        if ($authUser->isManager()) {
            return ['collaborator' => 'Colaborador'];
        }

        if ($authUser->isFotografiaLider()) {
            return ['fotografia_colaborador' => 'Colaborador de Fotografia'];
        }

        return [];
    }

    /**
     * @return Collection<int, CmsPage>
     */
    protected function availablePages(User $authUser)
    {
        $query = CmsPage::query()->orderBy('label');

        if ($authUser->isManager()) {
            $allowed = $authUser->pages()->wherePivot('can_access', true)->pluck('cms_pages.id');
            $query->whereIn('id', $allowed);
        }

        return $query->get();
    }
}
