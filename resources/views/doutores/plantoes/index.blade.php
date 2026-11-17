@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-plantoes'; @endphp
@section('title', 'Plantões')
@section('heading', 'Plantões')

@section('actions')
    <a href="{{ route('doutores.plantoes.create') }}" class="btn"><i class="bi bi-plus-lg"></i> Novo plantão</a>
@endsection

@section('content')
    <div class="card">
        <form method="GET" class="filters">
            <div class="field">
                <label>Status</label>
                <select name="status" class="select">
                    <option value="">Todos</option>
                    @foreach (\App\Models\Doutores\Plantao::STATUSES as $k => $l)
                        <option value="{{ $k }}" {{ request('status') === $k ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label>Hospital</label>
                <select name="hospital" class="select">
                    <option value="">Todos</option>
                    @foreach ($hospitais as $h)
                        <option value="{{ $h }}" {{ request('hospital') === $h ? 'selected' : '' }}>{{ $h }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field"><label>De</label><input type="date" name="de" value="{{ request('de') }}" class="input"></div>
            <div class="field"><label>Até</label><input type="date" name="ate" value="{{ request('ate') }}" class="input"></div>
            <button type="submit" class="btn btn-secondary"><i class="bi bi-funnel"></i> Filtrar</button>
        </form>

        <div class="table-wrap">
            <table class="adm-table">
                <thead><tr><th>Data</th><th>Hospital</th><th>Tipo</th><th>Status</th><th>Inscritos</th><th>Resultados</th><th class="col-actions">Ações</th></tr></thead>
                <tbody>
                    @forelse ($plantoes as $p)
                        <tr>
                            <td>{{ $p->data?->format('d/m/Y') }}<br><span class="muted-sm">{{ $p->hora_inicio }}{{ $p->hora_fim ? ' – '.$p->hora_fim : '' }}</span></td>
                            <td><strong>{{ $p->hospital }}</strong></td>
                            <td>{{ \App\Models\Doutores\Plantao::TIPOS[$p->tipo] ?? $p->tipo }}</td>
                            <td>@include('doutores.partials.badge', ['status' => $p->status])</td>
                            <td>
                                {{ $p->inscricoes_ativas_count }}
                                @if ($p->inscricoes_pendentes_count)<span class="badge badge-amber">{{ $p->inscricoes_pendentes_count }} pend.</span>@endif
                            </td>
                            <td>
                                @if ($p->resultados_registrados_em)
                                    <span class="muted-sm">{{ $p->visitas_realizadas }} visitas · {{ $p->livros_distribuidos }} livros</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="col-actions">
                                <div class="row-actions">
                                    <a href="{{ route('doutores.plantoes.show', $p) }}" class="btn btn-secondary btn-sm" title="Abrir"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('doutores.plantoes.equipes', $p) }}" class="btn btn-secondary btn-sm" title="Equipes"><i class="bi bi-diagram-3"></i></a>
                                    <a href="{{ route('doutores.plantoes.edit', $p) }}" class="btn btn-secondary btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('doutores.plantoes.destroy', $p) }}"
                                          onsubmit="return admConfirm({{ json_encode('Remover o plantão de '.$p->hospital.' em '.$p->data?->format('d/m/Y').'?') }}, this, { title: 'Remover plantão' });">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Remover"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="empty-state"><i class="bi bi-calendar-x"></i>Nenhum plantão encontrado.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="adm-pagination">{{ $plantoes->links() }}</div>
    </div>
@endsection
