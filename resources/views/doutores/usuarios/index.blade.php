@extends('doutores.layouts.secured')

@php
    $activeNav = 'doutores-usuarios';
    $editing = (bool) $edit;
    $selRoles = old('doutores_roles', $editing ? $edit->doutoresRoleNames() : []);
    $selPolos = array_map('intval', (array) old('polo_ids', $editing ? $edit->doutoresPolos->pluck('id')->all() : []));
    $statusAtual = old('status', $editing ? ($edit->doutoresProfile?->status ?? 'ativo') : 'ativo');
@endphp
@section('title', 'Usuários')
@section('heading', 'Usuários do portal')

@section('content')
    <div class="card">
        <div class="card-head">
            <h2>{{ $editing ? 'Editar usuário' : 'Novo usuário' }}</h2>
            @if ($editing)
                <div class="adm-actions"><a href="{{ route('doutores.usuarios.index') }}" class="btn btn-secondary btn-sm">Cancelar edição</a></div>
            @endif
        </div>
        <div class="card-body">
            <p class="muted-sm" style="margin-top:0;">Aqui você gerencia só os papéis do Doutores. O mesmo e-mail pode existir no painel do site; os acessos ficam separados por sessão.</p>
            <form method="POST" action="{{ $editing ? route('doutores.usuarios.update', $edit) : route('doutores.usuarios.store') }}" class="form-grid">
                @csrf
                @if ($editing) @method('PUT') @endif
                <div class="form-row">
                    <div class="field">
                        <label for="u-name">Nome <span class="req">*</span></label>
                        <input type="text" id="u-name" name="name" class="input" required maxlength="255" value="{{ old('name', $editing ? $edit->name : '') }}">
                    </div>
                    <div class="field">
                        <label for="u-email">E-mail <span class="req">*</span></label>
                        <input type="email" id="u-email" name="email" class="input" required maxlength="255" value="{{ old('email', $editing ? $edit->email : '') }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="u-password">{{ $editing ? 'Nova senha' : 'Senha' }}</label>
                        <input type="password" id="u-password" name="password" class="input" minlength="8" autocomplete="new-password">
                        <span class="hint">{{ $editing ? 'Deixe em branco para manter a atual.' : 'Em branco: uma senha temporária será gerada.' }}</span>
                    </div>
                    @if ($editing)
                        <div class="field">
                            <label for="u-status">Situação do perfil</label>
                            <select id="u-status" name="status" class="select">
                                @foreach (['ativo' => 'Ativo', 'pendente' => 'Pendente', 'inativo' => 'Inativo'] as $k => $l)
                                    <option value="{{ $k }}" {{ $statusAtual === $k ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="field">
                    <label>Papéis <span class="req">*</span></label>
                    <div style="display:flex;gap:18px;flex-wrap:wrap;">
                        @foreach ($roleLabels as $roleName => $label)
                            <label class="checkbox-field">
                                <input type="checkbox" name="doutores_roles[]" value="{{ $roleName }}" class="u-role" {{ in_array($roleName, $selRoles, true) ? 'checked' : '' }}>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="field" id="u-polos" {{ in_array('doutores_coordenador', $selRoles, true) ? '' : 'hidden' }}>
                    <label>Polos do coordenador</label>
                    <div style="display:flex;gap:16px;flex-wrap:wrap;">
                        @forelse ($polos as $polo)
                            <label class="checkbox-field">
                                <input type="checkbox" name="polo_ids[]" value="{{ $polo->id }}" {{ in_array($polo->id, $selPolos, true) ? 'checked' : '' }}>
                                {{ $polo->nome }}
                            </label>
                        @empty
                            <span class="muted-sm">Nenhum polo cadastrado.</span>
                        @endforelse
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn"><i class="bi bi-check-lg"></i> {{ $editing ? 'Salvar alterações' : 'Criar usuário' }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <form method="GET" class="filters">
            <div class="field">
                <label for="q">Buscar</label>
                <input type="text" id="q" name="q" class="input" value="{{ $q }}" placeholder="Nome ou e-mail">
            </div>
            <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filtrar</button>
        </form>
        <div class="table-wrap">
            <table class="adm-table">
                <thead><tr><th>Nome</th><th>E-mail</th><th>Papéis</th><th>Polos</th><th>Situação</th><th class="col-actions">Ações</th></tr></thead>
                <tbody>
                    @forelse ($usuarios as $u)
                        @php
                            $somenteDoutores = $u->roles->every(fn ($r) => str_starts_with($r->name, 'doutores_'));
                        @endphp
                        <tr>
                            <td><strong>{{ $u->name }}</strong></td>
                            <td class="text-muted">{{ $u->email }}</td>
                            <td>
                                @foreach ($u->doutoresRoleNames() as $rn)
                                    <span class="badge badge-green">{{ $roleLabels[$rn] ?? $rn }}</span>
                                @endforeach
                            </td>
                            <td class="muted-sm">{{ $u->doutoresPolos->pluck('nome')->implode(', ') ?: '—' }}</td>
                            <td>@include('doutores.partials.badge', ['status' => $u->doutoresProfile?->status ?? 'pendente'])</td>
                            <td class="col-actions">
                                @if ($somenteDoutores)
                                    <div class="row-actions">
                                        <a href="{{ route('doutores.usuarios.index', ['editar' => $u->id]) }}" class="btn btn-secondary btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>
                                        @if ($u->id !== $autoId)
                                            <form method="POST" action="{{ route('doutores.usuarios.destroy', $u) }}"
                                                  onsubmit="return admConfirm({{ json_encode('Remover o usuário '.$u->name.'?') }}, this, { title: 'Remover usuário' });">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Remover"><i class="bi bi-trash"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                @else
                                    <span class="muted-sm">Conta compartilhada</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="bi bi-people"></i>Nenhum usuário encontrado.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="adm-pagination">{{ $usuarios->links() }}</div>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        var box = document.getElementById('u-polos');
        var coord = document.querySelector('.u-role[value="doutores_coordenador"]');
        if (!box || !coord) return;
        coord.addEventListener('change', function () { box.hidden = !coord.checked; });
    })();
</script>
@endpush
