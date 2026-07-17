@extends('admin.layout')

@php $activeNav = 'users'; @endphp
@section('title', 'Permissões de páginas')
@section('heading', 'Permissões — ' . $user->name)

@section('actions')
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-head"><h2>Páginas vinculadas</h2></div>
        <div class="table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Página</th>
                        <th>Rota</th>
                        <th>Acessar</th>
                        <th>Editar</th>
                        <th>Aprovar</th>
                        <th class="text-right">Ações</th>
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
                            <td>
                                <div class="row-actions">
                                    <button type="submit" form="{{ $fid }}" class="btn btn-sm"><i class="bi bi-check-lg"></i> Salvar</button>
                                    <button type="submit" form="detach-{{ $page->id }}" class="btn btn-danger btn-sm" onclick="return confirm('Remover o vínculo com esta página?');"><i class="bi bi-trash"></i></button>
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
@endsection
