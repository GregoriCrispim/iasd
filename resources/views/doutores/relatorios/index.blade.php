@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-relatorios'; @endphp
@section('title', 'Relatórios')
@section('heading', 'Relatórios')

@section('actions')
    <a href="{{ route('doutores.relatorios.export', array_merge(request()->only(['hospital', 'de', 'ate']), ['tipo' => 'plantoes'])) }}" class="btn btn-secondary"><i class="bi bi-download"></i> CSV plantões</a>
    <a href="{{ route('doutores.relatorios.export', array_merge(request()->only(['hospital']), ['tipo' => 'voluntarios'])) }}" class="btn btn-secondary"><i class="bi bi-download"></i> CSV voluntários</a>
@endsection

@section('content')
    <div class="stat-grid">
        <div class="stat"><i class="bi bi-calendar-check stat-icon"></i><div class="stat-label">Plantões no período</div><div class="stat-value">{{ $totais['plantoes'] }}</div></div>
        <div class="stat"><i class="bi bi-check2-circle stat-icon"></i><div class="stat-label">Realizados</div><div class="stat-value">{{ $totais['realizados'] }}</div></div>
        <div class="stat"><i class="bi bi-people stat-icon"></i><div class="stat-label">Voluntários confirmados (soma)</div><div class="stat-value">{{ $totais['voluntarios_escalados'] }}</div></div>
        <div class="stat"><i class="bi bi-door-open stat-icon"></i><div class="stat-label">Quartos visitados</div><div class="stat-value">{{ $totais['quartos'] }}</div></div>
        <div class="stat"><i class="bi bi-heart stat-icon"></i><div class="stat-label">Visitas</div><div class="stat-value">{{ $totais['visitas'] }}</div></div>
        <div class="stat"><i class="bi bi-book stat-icon"></i><div class="stat-label">Livros distribuídos</div><div class="stat-value">{{ $totais['livros'] }}</div></div>
        <div class="stat"><i class="bi bi-person-badge stat-icon"></i><div class="stat-label">Voluntários cadastrados</div><div class="stat-value">{{ $voluntariosTotal }}</div></div>
    </div>

    <div class="card">
        <form method="GET" class="filters">
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
                <thead><tr><th>Data</th><th>Hospital</th><th>Status</th><th>Confirmados</th><th>Quartos</th><th>Visitas</th><th>Livros</th></tr></thead>
                <tbody>
                    @forelse ($plantoes as $p)
                        <tr>
                            <td><a href="{{ route('doutores.plantoes.show', $p) }}">{{ $p->data?->format('d/m/Y') }}</a></td>
                            <td>{{ $p->hospital }}</td>
                            <td>@include('doutores.partials.badge', ['status' => $p->status])</td>
                            <td>{{ $p->presentes_count }}</td>
                            <td>{{ $p->quartos_visitados }}</td>
                            <td>{{ $p->visitas_realizadas }}</td>
                            <td>{{ $p->livros_distribuidos }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="empty-state"><i class="bi bi-file-earmark-bar-graph"></i>Nenhum plantão no período.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
