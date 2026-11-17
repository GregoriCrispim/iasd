@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-polos'; @endphp
@section('title', 'Polos')
@section('heading', 'Polos')

@section('actions')
    <a href="{{ route('doutores.polos.create') }}" class="btn"><i class="bi bi-plus-lg"></i> Novo polo</a>
@endsection

@section('content')
    <div class="card">
        <div class="table-wrap">
            <table class="adm-table">
                <thead><tr><th>Polo</th><th>Cidade</th><th>Coordenação</th><th>Hospitais</th><th>Status</th><th class="col-actions">Ações</th></tr></thead>
                <tbody>
                    @forelse ($polos as $p)
                        <tr>
                            <td><strong>{{ $p->nome }}</strong><br><span class="muted-sm">{{ $p->telefone }} {{ $p->email }}</span></td>
                            <td>{{ collect([$p->cidade, $p->estado])->filter()->implode(' / ') }}</td>
                            <td>{{ $p->coordenador ?? '—' }}@if ($p->coordenadores_count)<br><span class="muted-sm">{{ $p->coordenadores_count }} usuário(s) vinculado(s)</span>@endif</td>
                            <td>{{ $p->hospitais_count }}</td>
                            <td>@include('doutores.partials.badge', ['status' => $p->status])</td>
                            <td class="col-actions">
                                <div class="row-actions">
                                    <a href="{{ route('doutores.polos.edit', $p) }}" class="btn btn-secondary btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('doutores.polos.destroy', $p) }}"
                                          onsubmit="return admConfirm({{ json_encode('Remover o polo '.$p->nome.'?') }}, this, { title: 'Remover polo' });">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Remover"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="bi bi-geo-alt"></i>Nenhum polo cadastrado.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
