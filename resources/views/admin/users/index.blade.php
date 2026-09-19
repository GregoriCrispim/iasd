@extends('admin.layout')

@php
    $activeNav = 'users';
    $roleLabels = [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'manager' => 'Gestor',
        'collaborator' => 'Colaborador',
        'fotografia_lider' => 'Líder de Fotografia',
        'fotografia_colaborador' => 'Colaborador de Fotografia',
    ];
    $roleBadges = [
        'super_admin' => 'badge-purple',
        'admin' => 'badge-blue',
        'manager' => 'badge-blue',
        'collaborator' => 'badge-gray',
        'fotografia_lider' => 'badge-amber',
        'fotografia_colaborador' => 'badge-gray',
    ];

    $openCreateUser = request()->boolean('novo') || ($errors->any() && old('_form') === 'create');
    $openEditUser = (bool) ($editUser ?? null) || ($errors->any() && old('_form') === 'edit');

    if (($openCreateUser || $openEditUser) && $errors->any()) {
        view()->share('hideGlobalErrors', true);
    }

    $createOld = fn (string $field, $default = null) => old('_form') === 'create' ? old($field, $default) : $default;
    $createError = fn (string $field) => old('_form') === 'create' && $errors->has($field);

    $editOld = fn (string $field, $default = null) => old('_form') === 'edit' ? old($field, $default) : $default;
    $editError = fn (string $field) => old('_form') === 'edit' && $errors->has($field);

    $roleOptions = $roleOptions ?? [];
    $canAssignAdvanced = $canAssignAdvanced ?? false;
    $defaultRole = $defaultRole ?? null;
    $editUser = $editUser ?? null;
    $editRoleOptions = $editRoleOptions ?? [];
    $editCurrentRole = $editCurrentRole ?? null;
    $editAttached = $editAttached ?? collect();
    $editAvailable = $editAvailable ?? collect();
    $canManagePagePerms = $canManagePagePerms ?? false;
    $createAvailablePages = $createAvailablePages ?? collect();
    $canAssignCreatePagePerms = $canAssignCreatePagePerms ?? false;
    $oldCreatePageLinks = old('_form') === 'create' ? old('page_links', []) : [];
    if (! is_array($oldCreatePageLinks)) {
        $oldCreatePageLinks = [];
    }
@endphp
@section('title', 'Usuários')
@section('heading', 'Usuários')

@section('actions')
    <button type="button" class="btn" title="Novo usuário" onclick="admOpenUserCreateModal()">
        <i class="bi bi-plus-lg"></i> Novo usuário
    </button>
@endsection

@section('content')
    <div class="card">
        <form method="GET" class="filters">
            <div class="field">
                <label>Buscar</label>
                <input type="text" name="q" value="{{ request('q') }}" class="input" placeholder="Nome ou e-mail...">
            </div>
            <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filtrar</button>
        </form>

        <div class="table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Perfil</th>
                        <th>Gestor</th>
                        <th class="col-actions">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $u)
                        @php $roleName = $u->roles->pluck('name')->first(); @endphp
                        <tr>
                            <td><strong>{{ $u->name }}</strong></td>
                            <td class="text-muted">{{ $u->email }}</td>
                            <td>
                                @if ($roleName)
                                    <span class="badge {{ $roleBadges[$roleName] ?? 'badge-gray' }}">{{ $roleLabels[$roleName] ?? $roleName }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $u->manager?->name ?? '—' }}</td>
                            <td class="col-actions">
                                <div class="row-actions">
                                    @if ($authUser->canManageUser($u))
                                        <a
                                            href="{{ route('admin.users.index', array_filter(['editar' => $u->id, 'q' => request('q')])) }}"
                                            class="btn btn-secondary btn-sm"
                                            title="Editar"
                                        ><i class="bi bi-pencil"></i></a>
                                    @endif
                                    @if (
                                        $authUser->hasFullAdminAccess()
                                        && ! $u->isProtectedFromDeletion()
                                        && $u->id !== $authUser->id
                                        && ! ($authUser->isAdmin() && $u->isAdmin())
                                    )
                                        <form
                                            method="POST"
                                            action="{{ route('admin.users.destroy', $u) }}"
                                            id="del-user-{{ $u->id }}"
                                            onsubmit="return admConfirm({{ json_encode('Remover o usuário '.$u->name.'? Esta ação não pode ser desfeita.') }}, this, { title: 'Remover usuário', confirmLabel: 'Remover', confirmIcon: 'trash' });"
                                        >
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Remover"><i class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="empty-state"><i class="bi bi-people"></i>Nenhum usuário encontrado.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="adm-pagination">{{ $users->links() }}</div>
    </div>

    {{-- Create modal --}}
    <dialog id="user-create-modal" class="adm-dialog adm-dialog--user {{ $canAssignCreatePagePerms ? 'adm-dialog--user-edit' : '' }}" aria-labelledby="user-create-title">
        <form method="POST" action="{{ route('admin.users.store') }}" id="user-create-form" autocomplete="off">
            @csrf
            <input type="hidden" name="_form" value="create">

            <div class="adm-album-modal-head">
                <div class="adm-user-modal-title">
                    <span class="adm-user-modal-icon" aria-hidden="true"><i class="bi bi-person-plus"></i></span>
                    <div>
                        <h3 id="user-create-title">Novo usuário</h3>
                        <p>Defina nome, e-mail, senha e o perfil de acesso ao painel.</p>
                    </div>
                </div>
                <button type="button" class="adm-album-modal-close" onclick="admCloseDialog('user-create-modal')" aria-label="Fechar" title="Fechar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="adm-album-modal-body adm-user-modal-body {{ $canAssignCreatePagePerms ? 'adm-user-edit-body' : '' }}">
                @if ($openCreateUser && $errors->any())
                    <div class="alert alert-danger" style="margin:0;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="adm-user-modal-section">
                    <h4>Identificação</h4>
                    <div class="form-row">
                        <div class="field">
                            <label for="user-name">Nome <span class="req">*</span></label>
                            <input
                                type="text"
                                id="user-name"
                                name="name"
                                class="input {{ $createError('name') ? 'has-error' : '' }}"
                                value="{{ $createOld('name') }}"
                                required
                                maxlength="255"
                                autocomplete="name"
                                placeholder="Nome completo"
                            >
                        </div>
                        <div class="field">
                            <label for="user-email">E-mail <span class="req">*</span></label>
                            <input
                                type="email"
                                id="user-email"
                                name="email"
                                class="input {{ $createError('email') ? 'has-error' : '' }}"
                                value="{{ $createOld('email') }}"
                                required
                                maxlength="255"
                                autocomplete="email"
                                placeholder="email@exemplo.com"
                            >
                        </div>
                    </div>
                </div>

                <div class="adm-user-modal-section">
                    <h4>Acesso</h4>
                    <div class="form-row">
                        <div class="field">
                            <label for="user-password">Senha <span class="req">*</span></label>
                            <div class="adm-password-wrap">
                                <input
                                    type="password"
                                    id="user-password"
                                    name="password"
                                    class="input {{ $createError('password') ? 'has-error' : '' }}"
                                    required
                                    minlength="5"
                                    autocomplete="new-password"
                                    placeholder="Mínimo de 5 caracteres"
                                >
                                <button
                                    type="button"
                                    class="adm-password-toggle"
                                    id="user-password-toggle"
                                    title="Mostrar senha"
                                    aria-label="Mostrar senha"
                                ><i class="bi bi-eye"></i></button>
                            </div>
                            <span class="hint">Use no mínimo 5 caracteres.</span>
                        </div>

                        @if ($canAssignAdvanced || count($roleOptions) > 1)
                            <div class="field">
                                <label for="user-role">Perfil <span class="req">*</span></label>
                                <select id="user-role" name="role" class="select {{ $createError('role') ? 'has-error' : '' }}" required>
                                    @foreach ($roleOptions as $value => $label)
                                        <option value="{{ $value }}" {{ $createOld('role', $defaultRole) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @elseif (count($roleOptions) === 1)
                            <div class="field">
                                <label>Perfil</label>
                                <input type="text" class="input" value="{{ array_values($roleOptions)[0] ?? '' }}" disabled>
                                <input type="hidden" id="user-role" name="role" value="{{ array_key_first($roleOptions) }}">
                            </div>
                        @endif
                    </div>
                </div>

                @if ($canAssignCreatePagePerms)
                    <div class="adm-user-modal-section" id="create-page-perms" data-page-perms-section="1" hidden>
                        <h4>Permissões de páginas</h4>
                        <p class="hint" style="margin:0 0 12px;">Vincule páginas do CMS para este gestor ou colaborador.</p>

                        <div id="create-page-links" class="adm-create-page-links"></div>

                        <div class="adm-user-attach-block" style="border-top:0;padding-top:0;margin-top:0;">
                            <button type="button" class="btn btn-secondary btn-sm" id="create-page-link-add">
                                <i class="bi bi-plus-lg"></i> Vincular nova página
                            </button>
                        </div>
                    </div>

                    <template id="create-page-link-template">
                        <div class="adm-user-attach-block create-page-link-row">
                            <div class="field">
                                <label>Página <span class="req">*</span></label>
                                <select name="page_links[__INDEX__][cms_page_id]" class="select create-page-link-select">
                                    <option value="">Selecione...</option>
                                    @foreach ($createAvailablePages as $page)
                                        <option value="{{ $page->id }}">{{ $page->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="adm-user-switch-row">
                                <div class="switch-field">
                                    <label class="switch"><input type="checkbox" name="page_links[__INDEX__][can_access]" value="1" checked><span class="slider"></span></label>
                                    <span>Acessar</span>
                                </div>
                                <div class="switch-field">
                                    <label class="switch"><input type="checkbox" name="page_links[__INDEX__][can_edit]" value="1"><span class="slider"></span></label>
                                    <span>Editar</span>
                                </div>
                                <div class="switch-field">
                                    <label class="switch"><input type="checkbox" name="page_links[__INDEX__][can_approve]" value="1"><span class="slider"></span></label>
                                    <span>Aprovar</span>
                                </div>
                                <button type="button" class="btn btn-ghost btn-sm create-page-link-remove" title="Remover">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                @endif
            </div>

            <div class="adm-album-modal-foot">
                <button type="button" class="btn btn-secondary" title="Cancelar" onclick="admCloseDialog('user-create-modal')">Cancelar</button>
                <button type="submit" class="btn" title="Criar usuário"><i class="bi bi-check-lg"></i> Criar usuário</button>
            </div>
        </form>
    </dialog>

    {{-- Edit modal --}}
    @if ($editUser)
        <dialog id="user-edit-modal" class="adm-dialog adm-dialog--user adm-dialog--user-edit" aria-labelledby="user-edit-title">
            <div class="adm-album-modal-head">
                <div class="adm-user-modal-title">
                    <span class="adm-user-modal-icon" aria-hidden="true"><i class="bi bi-pencil-square"></i></span>
                    <div>
                        <h3 id="user-edit-title">Editar usuário</h3>
                        <p>Atualize os dados de acesso{{ $canManagePagePerms ? ' e as permissões de páginas' : '' }}.</p>
                    </div>
                </div>
                <button type="button" class="adm-album-modal-close" onclick="admCloseUserEditModal()" aria-label="Fechar" title="Fechar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="adm-album-modal-body adm-user-modal-body adm-user-edit-body">
                @if ($openEditUser && $errors->any() && old('_form') === 'edit')
                    <div class="alert alert-danger" style="margin:0;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('admin.users.update', $editUser) }}"
                    id="user-edit-form"
                    autocomplete="off"
                    class="adm-user-edit-form"
                >
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_form" value="edit">
                    <input type="hidden" name="_user_id" value="{{ $editUser->id }}">

                    <div class="adm-user-modal-section">
                        <h4>Identificação</h4>
                        <div class="form-row">
                            <div class="field">
                                <label for="edit-user-name">Nome <span class="req">*</span></label>
                                <input
                                    type="text"
                                    id="edit-user-name"
                                    name="name"
                                    class="input {{ $editError('name') ? 'has-error' : '' }}"
                                    value="{{ $editOld('name', $editUser->name) }}"
                                    required
                                    maxlength="255"
                                    autocomplete="name"
                                >
                            </div>
                            <div class="field">
                                <label for="edit-user-email">E-mail <span class="req">*</span></label>
                                <input
                                    type="email"
                                    id="edit-user-email"
                                    name="email"
                                    class="input {{ $editError('email') ? 'has-error' : '' }}"
                                    value="{{ $editOld('email', $editUser->email) }}"
                                    required
                                    maxlength="255"
                                    autocomplete="email"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="adm-user-modal-section">
                        <h4>Acesso</h4>
                        <div class="form-row">
                            <div class="field">
                                <label for="edit-user-password">Senha</label>
                                <div class="adm-password-wrap">
                                    <input
                                        type="password"
                                        id="edit-user-password"
                                        name="password"
                                        class="input {{ $editError('password') ? 'has-error' : '' }}"
                                        minlength="5"
                                        autocomplete="new-password"
                                        placeholder="Deixe em branco para manter"
                                    >
                                    <button
                                        type="button"
                                        class="adm-password-toggle"
                                        id="edit-user-password-toggle"
                                        title="Mostrar senha"
                                        aria-label="Mostrar senha"
                                    ><i class="bi bi-eye"></i></button>
                                </div>
                                <span class="hint">Deixe em branco para manter a senha atual.</span>
                            </div>

                            @if ($canAssignAdvanced || count($editRoleOptions) > 1)
                                <div class="field">
                                    <label for="edit-user-role">Perfil <span class="req">*</span></label>
                                    <select id="edit-user-role" name="role" class="select {{ $editError('role') ? 'has-error' : '' }}" required>
                                        @foreach ($editRoleOptions as $value => $label)
                                            <option value="{{ $value }}" {{ $editOld('role', $editCurrentRole) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @elseif (count($editRoleOptions) === 1)
                                <div class="field">
                                    <label>Perfil</label>
                                    <input type="text" class="input" value="{{ array_values($editRoleOptions)[0] ?? '' }}" disabled>
                                    <input type="hidden" name="role" value="{{ array_key_first($editRoleOptions) }}">
                                </div>
                            @endif
                        </div>
                    </div>
                </form>

                @if ($canManagePagePerms)
                    <div class="adm-user-modal-section" id="permissoes-paginas" data-page-perms-section="1">
                        <h4>Permissões de páginas</h4>
                        <div class="table-wrap adm-user-perm-table">
                            <table class="adm-table">
                                <thead>
                                    <tr>
                                        <th>Página</th>
                                        <th>Rota</th>
                                        <th>Acessar</th>
                                        <th>Editar</th>
                                        <th>Aprovar</th>
                                        <th class="col-actions">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($editAttached as $page)
                                        @php $fid = 'perm-' . $page->id; @endphp
                                        <tr>
                                            <td><strong>{{ $page->label }}</strong></td>
                                            <td class="text-muted">{{ $page->route_name }}</td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" form="{{ $fid }}" name="can_access" value="1" {{ $page->pivot->can_access ? 'checked' : '' }}>
                                                    <span class="slider"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" form="{{ $fid }}" name="can_edit" value="1" {{ $page->pivot->can_edit ? 'checked' : '' }}>
                                                    <span class="slider"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" form="{{ $fid }}" name="can_approve" value="1" {{ $page->pivot->can_approve ? 'checked' : '' }}>
                                                    <span class="slider"></span>
                                                </label>
                                            </td>
                                            <td class="col-actions">
                                                <div class="row-actions">
                                                    <button type="submit" form="{{ $fid }}" class="btn btn-sm" title="Salvar permissões"><i class="bi bi-check-lg"></i></button>
                                                    <button
                                                        type="submit"
                                                        form="detach-{{ $page->id }}"
                                                        class="btn btn-danger btn-sm"
                                                        title="Remover vínculo"
                                                        onclick="return admConfirm({{ json_encode('Remover o vínculo com a página '.$page->label.'?') }}, document.getElementById('detach-{{ $page->id }}'), { title: 'Remover vínculo', confirmLabel: 'Remover', confirmIcon: 'trash' });"
                                                    ><i class="bi bi-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6"><div class="empty-state"><i class="bi bi-file-earmark-lock"></i>Nenhuma página vinculada.</div></td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @foreach ($editAttached as $page)
                            <form id="perm-{{ $page->id }}" method="POST" action="{{ route('admin.users.pages.update', [$editUser, $page]) }}" class="hidden-form">@csrf @method('PUT')</form>
                            <form id="detach-{{ $page->id }}" method="POST" action="{{ route('admin.users.pages.detach', [$editUser, $page]) }}" class="hidden-form">@csrf @method('DELETE')</form>
                        @endforeach

                        <div class="adm-user-attach-block">
                            <h5>Vincular nova página</h5>
                            @if ($editAvailable->isEmpty())
                                <p class="text-muted mb-0">Não há páginas disponíveis para vincular.</p>
                            @else
                                <form method="POST" action="{{ route('admin.users.pages.attach', $editUser) }}" class="form-grid">
                                    @csrf
                                    <div class="field">
                                        <label for="edit-attach-page">Página <span class="req">*</span></label>
                                        <select id="edit-attach-page" name="cms_page_id" class="select" required>
                                            <option value="">Selecione...</option>
                                            @foreach ($editAvailable as $page)
                                                <option value="{{ $page->id }}">{{ $page->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="adm-user-switch-row">
                                        <div class="switch-field">
                                            <label class="switch"><input type="checkbox" name="can_access" value="1" checked><span class="slider"></span></label>
                                            <span>Acessar</span>
                                        </div>
                                        <div class="switch-field">
                                            <label class="switch"><input type="checkbox" name="can_edit" value="1"><span class="slider"></span></label>
                                            <span>Editar</span>
                                        </div>
                                        <div class="switch-field">
                                            <label class="switch"><input type="checkbox" name="can_approve" value="1"><span class="slider"></span></label>
                                            <span>Aprovar</span>
                                        </div>
                                    </div>
                                    <div class="form-actions" style="margin:0;">
                                        <button type="submit" class="btn"><i class="bi bi-plus-lg"></i> Vincular</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="adm-album-modal-foot">
                <button type="button" class="btn btn-secondary" title="Cancelar" onclick="admCloseUserEditModal()">Cancelar</button>
                <button type="submit" form="user-edit-form" class="btn" title="Salvar"><i class="bi bi-check-lg"></i> Salvar</button>
            </div>
        </dialog>
    @endif
@endsection

@push('scripts')
<script>
    var admCreatePageLinkIndex = 0;

    function admBindPasswordToggle(toggleId, inputId) {
        var toggle = document.getElementById(toggleId);
        var password = document.getElementById(inputId);
        if (!toggle || !password) return;
        toggle.addEventListener('click', function () {
            var show = password.type === 'password';
            password.type = show ? 'text' : 'password';
            toggle.title = show ? 'Ocultar senha' : 'Mostrar senha';
            toggle.setAttribute('aria-label', toggle.title);
            var icon = toggle.querySelector('i');
            if (icon) icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    }

    function admGetCreateRole() {
        var roleEl = document.getElementById('user-role');
        return roleEl ? roleEl.value : '';
    }

    function admCreateRoleUsesPagePerms(role) {
        return role === 'manager' || role === 'collaborator';
    }

    function admGetEditRole() {
        var roleEl = document.getElementById('edit-user-role');
        if (roleEl) return roleEl.value;
        var hidden = document.querySelector('#user-edit-form input[name="role"]');
        return hidden ? hidden.value : '';
    }

    function admSyncEditPagePerms() {
        var section = document.getElementById('permissoes-paginas');
        if (!section) return;

        var show = admCreateRoleUsesPagePerms(admGetEditRole());
        section.hidden = !show;
        section.querySelectorAll('input, select, button').forEach(function (el) {
            el.disabled = !show;
        });
    }

    function admAddCreatePageLink(preset) {
        var tpl = document.getElementById('create-page-link-template');
        var list = document.getElementById('create-page-links');
        if (!tpl || !list) return;

        var index = admCreatePageLinkIndex++;
        var html = tpl.innerHTML.replaceAll('__INDEX__', String(index));
        var wrap = document.createElement('div');
        wrap.innerHTML = html.trim();
        var row = wrap.firstElementChild;
        if (!row) return;

        list.appendChild(row);

        if (preset && preset.cms_page_id) {
            var select = row.querySelector('.create-page-link-select');
            if (select) select.value = String(preset.cms_page_id);
            ['can_access', 'can_edit', 'can_approve'].forEach(function (key) {
                var input = row.querySelector('input[name="page_links[' + index + '][' + key + ']"]');
                if (input) input.checked = !!preset[key];
            });
        }

        var removeBtn = row.querySelector('.create-page-link-remove');
        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                row.remove();
            });
        }
    }

    function admClearCreatePageLinks() {
        var list = document.getElementById('create-page-links');
        if (list) list.innerHTML = '';
        admCreatePageLinkIndex = 0;
    }

    function admSyncCreatePagePerms(ensureRow) {
        var section = document.getElementById('create-page-perms');
        if (!section) return;

        var show = admCreateRoleUsesPagePerms(admGetCreateRole());
        section.hidden = !show;
        section.querySelectorAll('input, select, button').forEach(function (el) {
            el.disabled = !show;
        });

        if (show && ensureRow) {
            var list = document.getElementById('create-page-links');
            if (list && list.children.length === 0) {
                admAddCreatePageLink();
            }
        }

        if (!show) {
            admClearCreatePageLinks();
        }
    }

    function admOpenUserCreateModal(keepValues) {
        if (!keepValues) {
            var form = document.getElementById('user-create-form');
            if (form) form.reset();

            var password = document.getElementById('user-password');
            if (password) password.type = 'password';
            var toggle = document.getElementById('user-password-toggle');
            if (toggle) {
                toggle.title = 'Mostrar senha';
                toggle.setAttribute('aria-label', 'Mostrar senha');
                var icon = toggle.querySelector('i');
                if (icon) icon.className = 'bi bi-eye';
            }

            admClearCreatePageLinks();
        }

        admOpenDialog('user-create-modal');
        admSyncCreatePagePerms(!keepValues);
        requestAnimationFrame(function () {
            var name = document.getElementById('user-name');
            if (name) name.focus();
        });
    }

    function admOpenUserEditModal() {
        admOpenDialog('user-edit-modal');
        requestAnimationFrame(function () {
            var name = document.getElementById('edit-user-name');
            if (name) name.focus();
        });
    }

    function admCloseUserEditModal() {
        admCloseDialog('user-edit-modal');
        var url = new URL(window.location.href);
        if (url.searchParams.has('editar')) {
            url.searchParams.delete('editar');
            var next = url.pathname + (url.searchParams.toString() ? '?' + url.searchParams.toString() : '');
            window.history.replaceState({}, '', next);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        admBindPasswordToggle('user-password-toggle', 'user-password');
        admBindPasswordToggle('edit-user-password-toggle', 'edit-user-password');

        var roleSelect = document.getElementById('user-role');
        if (roleSelect && roleSelect.tagName === 'SELECT') {
            roleSelect.addEventListener('change', function () {
                admSyncCreatePagePerms(true);
            });
        }

        var editRoleSelect = document.getElementById('edit-user-role');
        if (editRoleSelect && editRoleSelect.tagName === 'SELECT') {
            editRoleSelect.addEventListener('change', function () {
                admSyncEditPagePerms();
            });
        }

        var addBtn = document.getElementById('create-page-link-add');
        if (addBtn) {
            addBtn.addEventListener('click', function () {
                admAddCreatePageLink();
            });
        }

        @if ($openCreateUser)
        @php
            $restoreLinks = [];
            foreach ($oldCreatePageLinks as $link) {
                if (! is_array($link) || empty($link['cms_page_id'])) {
                    continue;
                }
                $restoreLinks[] = [
                    'cms_page_id' => (int) $link['cms_page_id'],
                    'can_access' => ! empty($link['can_access']),
                    'can_edit' => ! empty($link['can_edit']),
                    'can_approve' => ! empty($link['can_approve']),
                ];
            }
        @endphp
        admOpenUserCreateModal({{ $errors->any() && old('_form') === 'create' ? 'true' : 'false' }});
        @foreach ($restoreLinks as $link)
        admAddCreatePageLink(@json($link));
        @endforeach
        admSyncCreatePagePerms({{ count($restoreLinks) > 0 ? 'false' : 'true' }});
        @endif

        @if ($openEditUser && $editUser)
        admOpenUserEditModal();
        admSyncEditPagePerms();
        @endif
    });
</script>
@endpush
