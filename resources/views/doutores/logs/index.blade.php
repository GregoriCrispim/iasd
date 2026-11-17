@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-logs'; @endphp
@section('title', 'Logs')
@section('heading', 'Registro de atividades')

@section('content')
    <div class="card">
        <form method="GET" class="filters">
            <div class="field">
                <label>Buscar</label>
                <input type="text" name="q" value="{{ request('q') }}" class="input" placeholder="Ação, descrição, usuário…">
            </div>
            <div class="field">
                <label>Categoria</label>
                <select name="categoria" class="select">
                    <option value="">Todas</option>
                    @foreach ($categorias as $c)
                        <option value="{{ $c }}" {{ request('categoria') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label>Severidade</label>
                <select name="severidade" class="select">
                    <option value="">Todas</option>
                    @foreach (['info', 'warning', 'error'] as $s)
                        <option value="{{ $s }}" {{ request('severidade') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field"><label>De</label><input type="date" name="de" value="{{ request('de') }}" class="input"></div>
            <div class="field"><label>Até</label><input type="date" name="ate" value="{{ request('ate') }}" class="input"></div>
            <button type="submit" class="btn btn-secondary"><i class="bi bi-funnel"></i> Filtrar</button>
        </form>

        <div class="table-wrap">
            <table class="adm-table">
                <thead><tr><th>Quando</th><th>Ação</th><th>Descrição</th><th>Executado por</th><th>Alvo</th><th>IP</th><th>Sev.</th></tr></thead>
                <tbody>
                    @forelse ($logs as $l)
                        <tr>
                            <td style="white-space:nowrap;">{{ $l->registrado_em?->format('d/m/Y H:i:s') }}</td>
                            <td><strong>{{ $l->acao }}</strong><br><span class="badge badge-gray">{{ $l->categoria }}</span></td>
                            <td>{{ $l->descricao }}</td>
                            <td>{{ $l->executado_por ?? '—' }}<br><span class="muted-sm">{{ $l->executado_por_perfil }}</span></td>
                            <td>{{ $l->usuario_alvo ?? '—' }}<br><span class="muted-sm">{{ $l->documento_alvo }}</span></td>
                            <td class="text-muted">{{ $l->endereco_ip }}</td>
                            <td>@include('doutores.partials.badge', ['status' => $l->severidade])</td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="empty-state"><i class="bi bi-clock-history"></i>Nenhum registro.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="adm-pagination">{{ $logs->links() }}</div>
    </div>
@endsection
