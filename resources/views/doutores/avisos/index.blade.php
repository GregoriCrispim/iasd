@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-avisos'; @endphp
@section('title', 'Avisos')
@section('heading', 'Avisos')

@section('actions')
    <a href="{{ route('doutores.avisos.create') }}" class="btn"><i class="bi bi-plus-lg"></i> Novo aviso</a>
@endsection

@section('content')
    <div class="card">
        <div class="table-wrap">
            <table class="adm-table">
                <thead><tr><th>Título</th><th>Plantão</th><th>Destino</th><th>Autor</th><th>Leituras</th><th>Publicado</th><th class="col-actions">Ações</th></tr></thead>
                <tbody>
                    @forelse ($avisos as $a)
                        <tr>
                            <td><strong>{{ $a->titulo }}</strong><br><span class="muted-sm">{{ \Illuminate\Support\Str::limit($a->conteudo, 90) }}</span></td>
                            <td>
                                @if ($a->plantao)
                                    <a href="{{ route('doutores.plantoes.show', $a->plantao) }}">{{ $a->plantao->hospital }}</a><br>
                                    <span class="muted-sm">{{ $a->plantao->data?->format('d/m/Y') }}</span>
                                @endif
                            </td>
                            <td>{{ $a->equipe_numero ? 'Equipe '.$a->equipe_numero : 'Todos os inscritos' }}</td>
                            <td>{{ $a->autor?->name ?? '—' }}</td>
                            <td>{{ $a->leituras_count }}</td>
                            <td>{{ $a->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="col-actions">
                                <form method="POST" action="{{ route('doutores.avisos.destroy', $a) }}"
                                      onsubmit="return admConfirm('Remover este aviso?', this, { title: 'Remover aviso' });">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Remover"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="empty-state"><i class="bi bi-megaphone"></i>Nenhum aviso publicado.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="adm-pagination">{{ $avisos->links() }}</div>
    </div>
@endsection
