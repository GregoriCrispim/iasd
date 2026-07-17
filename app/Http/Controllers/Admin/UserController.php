<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
                $term = '%' . $request->string('q') . '%';
                $query->where(fn (Builder $q) => $q->where('name', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->orderBy('name')
            ->paginate(30)
            ->withQueryString();

        return view('admin.users.index', ['users' => $users, 'authUser' => $authUser]);
    }

    public function create(Request $request): View
    {
        /** @var User $authUser */
        $authUser = $request->user();

        return view('admin.users.form', [
            'user' => new User(),
            'roleOptions' => $this->roleOptions($authUser),
            'managerOptions' => $this->managerOptions(),
            'currentRole' => $authUser->isManager() ? 'collaborator' : null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var User $authUser */
        $authUser = $request->user();

        $data = $this->validateData($request, null, $authUser);

        $role = $authUser->isManager() ? 'collaborator' : $data['role'];

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'email_verified_at' => $authUser->isManager() ? null : ($data['email_verified_at'] ?? null),
            'created_by' => $authUser->id,
            'manager_id' => $authUser->isManager() ? $authUser->id : ($data['manager_id'] ?? null),
        ]);

        $user->syncRoles([$role]);

        return redirect()->route('admin.users.index')->with('success', 'Usuário criado.');
    }

    public function edit(Request $request, User $user): View
    {
        /** @var User $authUser */
        $authUser = $request->user();
        $this->authorizeManage($authUser, $user);

        return view('admin.users.form', [
            'user' => $user,
            'roleOptions' => $this->roleOptions($authUser),
            'managerOptions' => $this->managerOptions(),
            'currentRole' => $user->roles->pluck('name')->first(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        /** @var User $authUser */
        $authUser = $request->user();
        $this->authorizeManage($authUser, $user);

        $data = $this->validateData($request, $user, $authUser);

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $payload['password'] = $data['password'];
        }

        if ($authUser->isManager()) {
            $payload['manager_id'] = $authUser->id;
        } else {
            $payload['manager_id'] = $data['manager_id'] ?? null;
            $payload['email_verified_at'] = $data['email_verified_at'] ?? null;
        }

        $user->update($payload);

        $role = $authUser->isManager() ? 'collaborator' : $data['role'];
        $user->syncRoles([$role]);

        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        /** @var User $authUser */
        $authUser = $request->user();

        if (!$authUser->isSuperAdmin()) {
            abort(403);
        }

        if ($user->id === $authUser->id) {
            return back()->with('error', 'Você não pode excluir o próprio usuário.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuário removido.');
    }

    /* ---------------- Page permissions ---------------- */

    public function pages(Request $request, User $user): View
    {
        /** @var User $authUser */
        $authUser = $request->user();
        $this->authorizeManage($authUser, $user);

        $attached = $user->pages()->orderBy('label')->get();
        $available = $this->availablePages($authUser)
            ->whereNotIn('id', $attached->pluck('id'));

        return view('admin.users.pages', [
            'user' => $user,
            'attached' => $attached,
            'available' => $available,
        ]);
    }

    public function attachPage(Request $request, User $user): RedirectResponse
    {
        /** @var User $authUser */
        $authUser = $request->user();
        $this->authorizeManage($authUser, $user);

        $data = $request->validate([
            'cms_page_id' => ['required', 'exists:cms_pages,id'],
            'can_access' => ['nullable', 'boolean'],
            'can_edit' => ['nullable', 'boolean'],
            'can_approve' => ['nullable', 'boolean'],
        ]);

        if (!$this->availablePages($authUser)->contains('id', (int) $data['cms_page_id'])) {
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

        if ($authUser->isManager()) {
            return $query->where(fn (Builder $b) => $b->where('manager_id', $authUser->id)->orWhere('id', $authUser->id));
        }

        return $query->where('id', $authUser->id);
    }

    protected function authorizeManage(User $authUser, User $target): void
    {
        if ($authUser->isSuperAdmin()) {
            return;
        }

        if ($authUser->isManager() && ($target->manager_id === $authUser->id || $target->id === $authUser->id)) {
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
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:6'],
        ];

        if (!$authUser->isManager()) {
            $rules['role'] = ['required', Rule::in(['manager', 'collaborator'])];
            $rules['manager_id'] = ['nullable', 'exists:users,id'];
            $rules['email_verified_at'] = ['nullable', 'date'];
        }

        return $request->validate($rules);
    }

    /**
     * @return array<string, string>
     */
    protected function roleOptions(User $authUser): array
    {
        if ($authUser->isManager()) {
            return ['collaborator' => 'Colaborador'];
        }

        return ['manager' => 'Gestor', 'collaborator' => 'Colaborador'];
    }

    /**
     * @return \Illuminate\Support\Collection<int, User>
     */
    protected function managerOptions()
    {
        return User::query()
            ->whereHas('roles', fn (Builder $q) => $q->where('name', 'manager'))
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * @return \Illuminate\Support\Collection<int, CmsPage>
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
