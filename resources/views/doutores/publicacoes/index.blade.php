@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-publicacoes'; @endphp
@section('title', 'Publicações')
@section('heading', 'Publicações')

@section('actions')
    <a href="{{ route('doutores.publicacoes.create') }}" class="btn"><i class="bi bi-plus-lg"></i> Nova publicação</a>
@endsection

@section('content')
    <div class="card">
        <div class="table-wrap">
            <table class="adm-table">
                <thead><tr><th>Título</th><th>Autor</th><th>Status</th><th>Comentários</th><th>Criada em</th><th class="col-actions">Ações</th></tr></thead>
                <tbody>
                    @forelse ($publicacoes as $p)
                        <tr>
                            <td><strong>{{ $p->titulo }}</strong></td>
                            <td>{{ $p->autor?->name ?? '—' }}</td>
                            <td>@include('doutores.partials.badge', ['status' => $p->status])</td>
                            <td>
                                {{ $p->comentarios_count }}
                                @if ($p->comentarios_pendentes_count)<span class="badge badge-amber">{{ $p->comentarios_pendentes_count }} p/ moderar</span>@endif
                            </td>
                            <td>{{ $p->created_at?->format('d/m/Y') }}</td>
                            <td class="col-actions">
                                <div class="row-actions">
                                    <a href="{{ route('doutores.publicacoes.edit', $p) }}" class="btn btn-secondary btn-sm" title="Editar / moderar"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('doutores.publicacoes.destroy', $p) }}"
                                          onsubmit="return admConfirm({{ json_encode('Remover a publicação “'.$p->titulo.'”?') }}, this, { title: 'Remover publicação' });">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Remover"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="bi bi-newspaper"></i>Nenhuma publicação.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="adm-pagination">{{ $publicacoes->links() }}</div>
    </div>
@endsection
