<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $authUser */
        $authUser = $request->user();

        $users = $this->scopedQuery($authUser)
            ->with(['roles', 'manager'])
            ->when($request->filled('q'), function (Builder $query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(fn (Builder $q) => $q->where('name', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->orderBy('name')
            ->paginate(30)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'authUser' => $authUser,
            'roleOptions' => $this->roleOptions($authUser),
            'defaultRole' => $this->defaultRole($authUser),
            'canAssignAdvanced' => $this->canAssignAdvancedFields($authUser),
        ]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.users.index', ['novo' => 1]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var User $authUser */
        $authUser = $request->user();

        $data = $this->validateData($request, null, $authUser);
        [$role, $managerId] = $this->resolveAssignment($authUser, $data);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'created_by' => $authUser->id,
            'manager_id' => $managerId === false ? null : $managerId,
        ]);

        $user->syncRoles([$role]);

        return redirect()->route('admin.users.index')->with('success', 'Usuário criado.');
    }

    public function edit(Request $request, User $user): View
    {
        /** @var User $authUser */
        $authUser = $request->user();
        $this->authorizeManage($authUser, $user);

        $canManagePagePerms = $this->canManagePagePerms($authUser);
        $attached = collect();
        $available = collect();

        if ($canManagePagePerms) {
            $attached = $user->pages()->orderBy('label')->get();
            $available = $this->availablePages($authUser)
                ->whereNotIn('id', $attached->pluck('id'));
        }

        return view('admin.users.form', [
            'user' => $user,
            'roleOptions' => $this->roleOptions($authUser),
            'currentRole' => $user->roles->pluck('name')->first(),
            'attached' => $attached,
            'available' => $available,
            'canManagePagePerms' => $canManagePagePerms,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        /** @var User $authUser */
        $authUser = $request->user();
        $this->authorizeManage($authUser, $user);

        $data = $this->validateData($request, $user, $authUser);
        [$role, $managerId] = $this->resolveAssignment($authUser, $data);

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
        /** @var User $authUser */
        $authUser = $request->user();

        if (! $authUser->isSuperAdmin()) {
            abort(403);
        }

        if ($user->id === $authUser->id) {
            return back()->with('error', 'Você não pode excluir o próprio usuário.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuário removido.');
    }

    /* ---------------- Page permissions ---------------- */

    public function pages(Request $request, User $user): RedirectResponse
    {
        /** @var User $authUser */
        $authUser = $request->user();
        $this->authorizeManage($authUser, $user);

        if (! $this->canManagePagePerms($authUser)) {
            abort(403);
        }

        return redirect()->route('admin.users.edit', $user)->withFragment('permissoes-paginas');
    }

    public function attachPage(Request $request, User $user): RedirectResponse
    {
        /** @var User $authUser */
        $authUser = $request->user();
        $this->authorizeManage($authUser, $user);

        if (! $this->canManagePagePerms($authUser)) {
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

        return back()->with('success', 'Página vinculada.');
    }

    public function updatePage(Request $request, User $user, CmsPage $page): RedirectResponse
    {
        /** @var User $authUser */
        $authUser = $request->user();
        $this->authorizeManage($authUser, $user);

        if (! $this->canManagePagePerms($authUser)) {
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

        return back()->with('success', 'Permissões atualizadas.');
    }

    public function detachPage(Request $request, User $user, CmsPage $page): RedirectResponse
    {
        /** @var User $authUser */
        $authUser = $request->user();
        $this->authorizeManage($authUser, $user);

        if (! $this->canManagePagePerms($authUser)) {
            abort(403);
        }

        $user->pages()->detach($page->id);

        return back()->with('success', 'Página removida do usuário.');
    }

    /* ---------------- Helpers ---------------- */

    protected function scopedQuery(User $authUser): Builder
    {
        $query = User::query();

        if ($authUser->isSuperAdmin()) {
            return $query;
        }

        if ($authUser->isManager() || $authUser->isFotografiaLider()) {
            return $query->where(fn (Builder $b) => $b->where('manager_id', $authUser->id)->orWhere('id', $authUser->id));
        }

        return $query->where('id', $authUser->id);
    }

    protected function authorizeManage(User $authUser, User $target): void
    {
        if ($authUser->isSuperAdmin()) {
            return;
        }

        if (($authUser->isManager() || $authUser->isFotografiaLider())
            && ($target->manager_id === $authUser->id || $target->id === $authUser->id)) {
            return;
        }

        abort(403);
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
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:6'],
        ];

        if ($this->canAssignAdvancedFields($authUser)) {
            $rules['role'] = ['required', Rule::in(array_keys($this->roleOptions($authUser)))];
        }

        return $request->validate($rules);
    }

    /**
     * Hierarquia automática: gestor/líder vinculam subordinados a si.
     * Super admin não define responsável pelo formulário (false = não alterar no update).
     *
     * @param  array<string, mixed>  $data
     * @return array{0: string, 1: int|null|false}
     */
    protected function resolveAssignment(User $authUser, array $data): array
    {
        if ($authUser->isManager()) {
            return ['collaborator', $authUser->id];
        }

        if ($authUser->isFotografiaLider()) {
            return ['fotografia_colaborador', $authUser->id];
        }

        return [$data['role'], false];
    }

    protected function canAssignAdvancedFields(User $authUser): bool
    {
        return $authUser->isSuperAdmin();
    }

    protected function canManagePagePerms(User $authUser): bool
    {
        return $authUser->isSuperAdmin() || $authUser->isManager();
    }

    protected function defaultRole(User $authUser): ?string
    {
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
    protected function roleOptions(User $authUser): array
    {
        if ($authUser->isManager()) {
            return ['collaborator' => 'Colaborador'];
        }

        if ($authUser->isFotografiaLider()) {
            return ['fotografia_colaborador' => 'Colaborador de Fotografia'];
        }

        return [
            'manager' => 'Gestor',
            'collaborator' => 'Colaborador',
            'fotografia_lider' => 'Líder de Fotografia',
            'fotografia_colaborador' => 'Colaborador de Fotografia',
        ];
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
