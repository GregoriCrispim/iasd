@extends('admin.layout')

@php
    $activeNav = 'users';
    $roleLabels = [
        'super_admin' => 'Super Admin',
        'manager' => 'Gestor',
        'collaborator' => 'Colaborador',
        'fotografia_lider' => 'Líder de Fotografia',
        'fotografia_colaborador' => 'Colaborador de Fotografia',
    ];
    $roleBadges = [
        'super_admin' => 'badge-purple',
        'manager' => 'badge-blue',
        'collaborator' => 'badge-gray',
        'fotografia_lider' => 'badge-amber',
        'fotografia_colaborador' => 'badge-gray',
    ];

    $openCreateUser = request()->boolean('novo') || ($errors->any() && old('_form') === 'create');
    if ($openCreateUser && $errors->any()) {
        view()->share('hideGlobalErrors', true);
    }

    $createOld = fn (string $field, $default = null) => old('_form') === 'create' ? old($field, $default) : $default;
    $createError = fn (string $field) => old('_form') === 'create' && $errors->has($field);
    $roleOptions = $roleOptions ?? [];
    $canAssignAdvanced = $canAssignAdvanced ?? false;
    $defaultRole = $defaultRole ?? null;
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
                                    <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-secondary btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>
                                    @if ($authUser->isSuperAdmin() && $u->id !== $authUser->id)
                                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Remover este usuário?');">
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

    <dialog id="user-create-modal" class="adm-dialog adm-dialog--user" aria-labelledby="user-create-title">
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

            <div class="adm-album-modal-body adm-user-modal-body">
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
                                    minlength="6"
                                    autocomplete="new-password"
                                    placeholder="Mínimo de 6 caracteres"
                                >
                                <button
                                    type="button"
                                    class="adm-password-toggle"
                                    id="user-password-toggle"
                                    title="Mostrar senha"
                                    aria-label="Mostrar senha"
                                ><i class="bi bi-eye"></i></button>
                            </div>
                            <span class="hint">Use no mínimo 6 caracteres.</span>
                        </div>

                        @if ($canAssignAdvanced)
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
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="adm-album-modal-foot">
                <button type="button" class="btn btn-secondary" title="Cancelar" onclick="admCloseDialog('user-create-modal')">Cancelar</button>
                <button type="submit" class="btn" title="Criar usuário"><i class="bi bi-check-lg"></i> Criar usuário</button>
            </div>
        </form>
    </dialog>
@endsection

@push('scripts')
<script>
    function admOpenUserCreateModal(keepValues) {
        if (!keepValues) {
            var form = document.getElementById('user-create-form');
            if (form) form.reset();

            var password = document.getElementById('user-password');
            if (password) {
                password.type = 'password';
            }
            var toggle = document.getElementById('user-password-toggle');
            if (toggle) {
                toggle.title = 'Mostrar senha';
                toggle.setAttribute('aria-label', 'Mostrar senha');
                var icon = toggle.querySelector('i');
                if (icon) icon.className = 'bi bi-eye';
            }
        }

        admOpenDialog('user-create-modal');
        requestAnimationFrame(function () {
            var name = document.getElementById('user-name');
            if (name) name.focus();
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        var toggle = document.getElementById('user-password-toggle');
        var password = document.getElementById('user-password');
        if (toggle && password) {
            toggle.addEventListener('click', function () {
                var show = password.type === 'password';
                password.type = show ? 'text' : 'password';
                toggle.title = show ? 'Ocultar senha' : 'Mostrar senha';
                toggle.setAttribute('aria-label', toggle.title);
                var icon = toggle.querySelector('i');
                if (icon) icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        }

        @if ($openCreateUser)
        admOpenUserCreateModal({{ $errors->any() ? 'true' : 'false' }});
        @endif
    });
</script>
@endpush
