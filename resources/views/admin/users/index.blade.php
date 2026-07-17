@extends('admin.layout')

@php
    $activeNav = 'users';
    $roleLabels = ['super_admin' => 'Super Admin', 'manager' => 'Gestor', 'collaborator' => 'Colaborador'];
    $roleBadges = ['super_admin' => 'badge-purple', 'manager' => 'badge-blue', 'collaborator' => 'badge-gray'];
@endphp
@section('title', 'Usuários')
@section('heading', 'Usuários')

@section('actions')
    <a href="{{ route('admin.users.create') }}" class="btn"><i class="bi bi-plus-lg"></i> Novo usuário</a>
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
                        <th class="text-right">Ações</th>
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
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('admin.users.pages', $u) }}" class="btn btn-ghost btn-sm" title="Permissões de páginas"><i class="bi bi-file-earmark-lock"></i></a>
                                    <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-secondary btn-sm"><i class="bi bi-pencil"></i></a>
                                    @if ($authUser->isSuperAdmin() && $u->id !== $authUser->id)
                                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Remover este usuário?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
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
@endsection
