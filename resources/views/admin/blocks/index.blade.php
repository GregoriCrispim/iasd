@extends('admin.layout')

@php $activeNav = 'blocks'; @endphp
@section('title', 'Blocos')
@section('heading', 'Blocos')

@section('actions')
    <a href="{{ route('admin.blocks.create') }}" class="btn"><i class="bi bi-plus-lg"></i> Novo bloco</a>
@endsection

@section('content')
    <div class="card">
        <form method="GET" class="filters">
            <div class="field">
                <label>Buscar</label>
                <input type="text" name="q" value="{{ request('q') }}" class="input" placeholder="Página, chave ou nome...">
            </div>
            <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filtrar</button>
        </form>

        <div class="table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Página</th>
                        <th>Chave</th>
                        <th>Nome</th>
                        <th>Revisão publicada</th>
                        <th>Atualizado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($blocks as $block)
                        <tr>
                            <td><strong>{{ $block->page?->label ?? '—' }}</strong></td>
                            <td><code>{{ $block->block_key }}</code></td>
                            <td>{{ $block->label }}</td>
                            <td class="text-muted">{{ $block->published_revision_id ? '#' . $block->published_revision_id : '—' }}</td>
                            <td class="text-muted nowrap">{{ $block->updated_at?->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('admin.blocks.edit', $block) }}" class="btn btn-secondary btn-sm"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('admin.blocks.destroy', $block) }}" id="del-block-{{ $block->id }}" onsubmit="return confirm('Remover este bloco? As revisões associadas também serão removidas.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="bi bi-grid-1x2"></i>Nenhum bloco cadastrado.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="adm-pagination">{{ $blocks->links() }}</div>
    </div>
@endsection
