@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-plantoes'; @endphp
@section('title', 'Plantão — '.$plantao->hospital)
@section('heading', 'Plantão — '.$plantao->hospital)

@section('actions')
    <a href="{{ route('doutores.plantoes.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
    <a href="{{ route('doutores.plantoes.equipes', $plantao) }}" class="btn btn-secondary"><i class="bi bi-diagram-3"></i> Equipes</a>
    <a href="{{ route('doutores.plantoes.edit', $plantao) }}" class="btn"><i class="bi bi-pencil"></i> Editar</a>
@endsection

@section('content')
    <div class="form-row" style="align-items:start;">
        <div class="card">
            <div class="card-head"><h2>Informações</h2><div class="adm-actions">@include('doutores.partials.badge', ['status' => $plantao->status])</div></div>
            <div class="card-body">
                <dl style="display:grid;grid-template-columns:140px 1fr;gap:8px 12px;margin:0;">
                    <dt class="text-muted">Data</dt><dd style="margin:0;">{{ $plantao->data?->format('d/m/Y') }} {{ $plantao->hora_inicio }}{{ $plantao->hora_fim ? ' – '.$plantao->hora_fim : '' }}</dd>
                    <dt class="text-muted">Tipo</dt><dd style="margin:0;">{{ \App\Models\Doutores\Plantao::TIPOS[$plantao->tipo] ?? $plantao->tipo }}</dd>
                    <dt class="text-muted">Endereço</dt><dd style="margin:0;">{{ $plantao->endereco ?? '—' }}</dd>
                    <dt class="text-muted">Inscrições</dt><dd style="margin:0;">{{ $plantao->inscricao_inicio?->format('d/m/Y H:i') ?? 'imediato' }} → {{ $plantao->inscricao_fim?->format('d/m/Y H:i') ?? 'sem limite' }}</dd>
                    <dt class="text-muted">Observações</dt><dd style="margin:0;white-space:pre-line;">{{ $plantao->observacoes ?? '—' }}</dd>
                </dl>
            </div>
        </div>

        <div class="card">
            <div class="card-head"><h2>Resultados</h2>@if ($plantao->resultados_registrados_em)<div class="adm-actions"><span class="muted-sm">Registrados em {{ $plantao->resultados_registrados_em->format('d/m/Y H:i') }}</span></div>@endif</div>
            <div class="card-body">
                <form method="POST" action="{{ route('doutores.plantoes.resultados', $plantao) }}" class="form-grid">
                    @csrf @method('PUT')
                    <div class="form-row">
                        <div class="field">
                            <label for="quartos_visitados">Quartos visitados</label>
                            <input type="number" min="0" id="quartos_visitados" name="quartos_visitados" class="input" value="{{ old('quartos_visitados', $plantao->quartos_visitados) }}" required>
                        </div>
                        <div class="field">
                            <label for="visitas_realizadas">Visitas realizadas</label>
                            <input type="number" min="0" id="visitas_realizadas" name="visitas_realizadas" class="input" value="{{ old('visitas_realizadas', $plantao->visitas_realizadas) }}" required>
                        </div>
                    </div>
                    <div class="field">
                        <label for="livros_distribuidos">Livros distribuídos</label>
                        <input type="number" min="0" id="livros_distribuidos" name="livros_distribuidos" class="input" value="{{ old('livros_distribuidos', $plantao->livros_distribuidos) }}" required>
                    </div>
                    <label class="checkbox-field"><input type="checkbox" name="marcar_realizado" value="1" checked> Marcar plantão como realizado</label>
                    <div class="form-actions"><button type="submit" class="btn"><i class="bi bi-check-lg"></i> Registrar resultados</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top:20px;">
        <div class="card-head"><h2>Inscrições ({{ $plantao->inscricoes->count() }})</h2></div>
        <div class="table-wrap">
            <table class="adm-table">
                <thead><tr><th>Voluntário</th><th>Função</th><th>Turno</th><th>Status</th><th>Presença</th><th class="col-actions">Ações</th></tr></thead>
                <tbody>
                    @forelse ($plantao->inscricoes as $i)
                        <tr>
                            <td><strong>{{ $i->user?->name }}</strong><br><span class="muted-sm">{{ $i->user?->email }}</span></td>
                            <td>{{ $i->papel ?? '—' }}</td>
                            <td>{{ $i->turno ?? '—' }}</td>
                            <td>@include('doutores.partials.badge', ['status' => $i->status])</td>
                            <td>{{ $i->confirmada_por_voluntario_em ? 'Confirmada' : '—' }}</td>
                            <td class="col-actions">
                                @if (in_array($i->status, ['pendente', 'confirmada', 'recusada'], true))
                                    <form method="POST" action="{{ route('doutores.inscricoes.status', $i) }}" class="row-actions">
                                        @csrf @method('PUT')
                                        @if ($i->status !== 'confirmada')
                                            <button name="status" value="confirmada" class="btn btn-success btn-sm" title="Confirmar"><i class="bi bi-check-lg"></i></button>
                                        @endif
                                        @if ($i->status !== 'recusada')
                                            <button name="status" value="recusada" class="btn btn-danger btn-sm" title="Recusar"><i class="bi bi-x-lg"></i></button>
                                        @endif
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="bi bi-people"></i>Nenhuma inscrição ainda.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-row" style="margin-top:20px;align-items:start;">
        <div class="card">
            <div class="card-head">
                <h2>Avisos</h2>
                <div class="adm-actions"><a href="{{ route('doutores.avisos.create', ['plantao_id' => $plantao->id]) }}" class="btn btn-secondary btn-sm"><i class="bi bi-plus-lg"></i> Novo aviso</a></div>
            </div>
            <div class="card-body">
                @forelse ($plantao->avisos as $a)
                    <div style="margin-bottom:12px;">
                        <strong>{{ $a->titulo }}</strong> @if ($a->equipe_numero)<span class="badge badge-purple">Equipe {{ $a->equipe_numero }}</span>@endif
                        <div class="muted-sm">{{ $a->created_at?->format('d/m/Y H:i') }}</div>
                    </div>
                @empty
                    <span class="text-muted">Nenhum aviso.</span>
                @endforelse
            </div>
        </div>
        <div class="card">
            <div class="card-head"><h2>Testemunhos</h2></div>
            <div class="card-body">
                @forelse ($plantao->testemunhos as $t)
                    <div style="margin-bottom:14px;">
                        <strong>{{ $t->user?->name }}</strong>
                        <div style="white-space:pre-line;">{{ $t->texto }}</div>
                    </div>
                @empty
                    <span class="text-muted">Nenhum testemunho registrado.</span>
                @endforelse
            </div>
        </div>
    </div>
@endsection
