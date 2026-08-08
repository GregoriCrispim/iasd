@extends('admin.layout')

@php $activeNav = 'pages'; @endphp
@section('title', 'Páginas')
@section('heading', 'Páginas')

@section('actions')
    <form method="POST" action="{{ route('admin.pages.sync') }}" onsubmit="return confirm('Sincronizar páginas a partir das rotas nomeadas?');">
        @csrf
        <button type="submit" class="btn btn-secondary"><i class="bi bi-arrow-repeat"></i> Sincronizar rotas</button>
    </form>
@endsection

@section('content')
    <div class="card">
        <form method="GET" class="filters">
            <div class="field">
                <label>Buscar</label>
                <input type="text" name="q" value="{{ request('q') }}" class="input" placeholder="Rota, rótulo ou view...">
            </div>
            <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filtrar</button>
        </form>

        <div class="table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Rótulo</th>
                        <th>Rota</th>
                        <th>View</th>
                        <th>Ativa</th>
                        <th>CMS</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pages as $page)
                        <tr>
                            <td><strong>{{ $page->label }}</strong></td>
                            <td class="text-muted">{{ $page->route_name }}</td>
                            <td class="text-muted">{{ $page->view_path }}</td>
                            <td>@include('admin.partials.bool', ['value' => $page->is_active])</td>
                            <td>@include('admin.partials.bool', ['value' => $page->cms_enabled])</td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-secondary btn-sm"><i class="bi bi-pencil"></i> Editar</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="bi bi-file-earmark-text"></i>Nenhuma página encontrada.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="adm-pagination">{{ $pages->links() }}</div>
    </div>
@endsection
