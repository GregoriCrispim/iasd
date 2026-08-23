@extends('admin.layout')

@php
    $activeNav = 'users';
    $editing = $user->exists;
    $auth = auth('admin')->user();
    $canAssignAdvanced = $auth->isSuperAdmin();
    $canManagePagePerms = $canManagePagePerms ?? ($auth->isSuperAdmin() || $auth->isManager());
@endphp
@section('title', $editing ? 'Editar usuário' : 'Novo usuário')
@section('heading', $editing ? 'Editar usuário' : 'Novo usuário')

@section('actions')
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" title="Voltar"><i class="bi bi-arrow-left"></i> Voltar</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ $editing ? route('admin.users.update', $user) : route('admin.users.store') }}" class="form-grid">
                @csrf
                @if ($editing) @method('PUT') @endif

                <div class="form-row">
                    <div class="field">
                        <label>Nome <span class="req">*</span></label>
                        <input type="text" name="name" class="input" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="field">
                        <label>E-mail <span class="req">*</span></label>
                        <input type="email" name="email" class="input" value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="field">
                        <label>Senha @if (!$editing)<span class="req">*</span>@endif</label>
                        <input type="password" name="password" class="input" autocomplete="new-password" @if (!$editing) required @endif>
                        @if ($editing)<span class="hint">Deixe em branco para manter a senha atual.</span>@endif
                    </div>

                    @if ($canAssignAdvanced)
                        <div class="field">
                            <label>Perfil <span class="req">*</span></label>
                            <select name="role" class="select" required>
                                @foreach ($roleOptions as $value => $label)
                                    <option value="{{ $value }}" {{ old('role', $currentRole) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    @elseif (count($roleOptions) === 1)
                        <div class="field">
                            <label>Perfil</label>
                            <input type="text" class="input" value="{{ reset($roleOptions) }}" disabled>
                        </div>
                    @endif
                </div>

                @if ($canAssignAdvanced)
                    <div class="form-row">
                        <div class="field">
                            <label>Responsável</label>
                            <select name="manager_id" class="select">
                                <option value="">—</option>
                                @foreach ($managerOptions as $m)
                                    <option value="{{ $m->id }}" {{ (string) old('manager_id', $user->manager_id) === (string) $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>E-mail verificado em</label>
                            <input type="datetime-local" name="email_verified_at" class="input"
                                   value="{{ old('email_verified_at', $user->email_verified_at?->format('Y-m-d\TH:i')) }}">
                        </div>
                    </div>
                @endif

                <div class="form-actions">
                    <button type="submit" class="btn" title="Salvar"><i class="bi bi-check-lg"></i> Salvar</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" title="Cancelar">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    @if ($editing && $canManagePagePerms)
        <div class="card" id="permissoes-paginas">
            <div class="card-head"><h2>Permissões de páginas</h2></div>
            <div class="table-wrap">
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
                        @forelse ($attached as $page)
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
                                        <button type="submit" form="{{ $fid }}" class="btn btn-sm" title="Salvar permissões"><i class="bi bi-check-lg"></i> Salvar</button>
                                        <button type="submit" form="detach-{{ $page->id }}" class="btn btn-danger btn-sm" title="Remover vínculo" onclick="return confirm('Remover o vínculo com esta página?');"><i class="bi bi-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6"><div class="empty-state"><i class="bi bi-file-earmark-lock"></i>Nenhuma página vinculada.</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @foreach ($attached as $page)
            <form id="perm-{{ $page->id }}" method="POST" action="{{ route('admin.users.pages.update', [$user, $page]) }}" class="hidden-form">@csrf @method('PUT')</form>
            <form id="detach-{{ $page->id }}" method="POST" action="{{ route('admin.users.pages.detach', [$user, $page]) }}" class="hidden-form">@csrf @method('DELETE')</form>
        @endforeach

        <div class="card">
            <div class="card-head"><h2>Vincular nova página</h2></div>
            <div class="card-body">
                @if ($available->isEmpty())
                    <p class="text-muted mb-0">Não há páginas disponíveis para vincular.</p>
                @else
                    <form method="POST" action="{{ route('admin.users.pages.attach', $user) }}" class="form-grid">
                        @csrf
                        <div class="field">
                            <label>Página <span class="req">*</span></label>
                            <select name="cms_page_id" class="select" required>
                                <option value="">Selecione...</option>
                                @foreach ($available as $page)
                                    <option value="{{ $page->id }}">{{ $page->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="display:flex;gap:24px;flex-wrap:wrap;">
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
                        <div class="form-actions">
                            <button type="submit" class="btn"><i class="bi bi-plus-lg"></i> Vincular</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @endif
@endsection
