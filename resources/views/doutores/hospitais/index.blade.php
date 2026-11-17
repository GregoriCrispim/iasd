@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-hospitais'; @endphp
@section('title', 'Hospitais')
@section('heading', 'Hospitais')

@section('actions')
    <a href="{{ route('doutores.hospitais.create') }}" class="btn"><i class="bi bi-plus-lg"></i> Novo hospital</a>
@endsection

@section('content')
    <div class="card">
        <div class="table-wrap">
            <table class="adm-table">
                <thead><tr><th>Hospital</th><th>Polo</th><th>Endereço</th><th>Status</th><th class="col-actions">Ações</th></tr></thead>
                <tbody>
                    @forelse ($hospitais as $h)
                        <tr>
                            <td><strong>{{ $h->nome }}</strong></td>
                            <td>{{ $h->polo?->nome ?? '—' }}</td>
                            <td>{{ collect([$h->endereco, $h->cidade ? $h->cidade.'/'.$h->uf : null])->filter()->implode(' — ') ?: '—' }}</td>
                            <td>@include('doutores.partials.badge', ['status' => $h->status])</td>
                            <td class="col-actions">
                                <div class="row-actions">
                                    <a href="{{ route('doutores.hospitais.edit', $h) }}" class="btn btn-secondary btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('doutores.hospitais.destroy', $h) }}"
                                          onsubmit="return admConfirm({{ json_encode('Remover o hospital '.$h->nome.'?') }}, this, { title: 'Remover hospital' });">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Remover"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="empty-state"><i class="bi bi-hospital"></i>Nenhum hospital cadastrado.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
