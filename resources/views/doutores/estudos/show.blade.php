@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-estudos'; @endphp
@section('title', 'Estudo — '.$estudo->nome_paciente)
@section('heading', 'Solicitação — '.$estudo->nome_paciente)

@section('actions')
    <a href="{{ route('doutores.estudos.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
    @if (\App\Support\DoutoresAuth::getActivePerfil(request()) === \App\Support\DoutoresAuth::PERFIL_ADMIN)
        <form method="POST" action="{{ route('doutores.estudos.destroy', $estudo) }}" style="display:inline;"
              onsubmit="return admConfirm('Remover esta solicitação?', this, { title: 'Remover solicitação' });">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Remover</button>
        </form>
    @endif
@endsection

@section('content')
    <div class="form-row" style="align-items:start;">
        <div class="card">
            <div class="card-head"><h2>Dados</h2><div class="adm-actions">@include('doutores.partials.badge', ['status' => $estudo->status])</div></div>
            <div class="card-body">
                <dl style="display:grid;grid-template-columns:150px 1fr;gap:8px 12px;margin:0;">
                    <dt class="text-muted">Telefone</dt><dd style="margin:0;">{{ $estudo->telefone ?? '—' }}</dd>
                    <dt class="text-muted">E-mail</dt><dd style="margin:0;">{{ $estudo->email ?? '—' }}</dd>
                    <dt class="text-muted">Endereço</dt><dd style="margin:0;">{{ collect([$estudo->endereco, $estudo->cidade])->filter()->implode(', ') ?: '—' }}</dd>
                    <dt class="text-muted">Hospital</dt><dd style="margin:0;">{{ $estudo->hospital ?? '—' }}</dd>
                    <dt class="text-muted">Preferência</dt><dd style="margin:0;">{{ collect([$estudo->dia_preferido, $estudo->horario_preferido])->filter()->implode(' · ') ?: '—' }}</dd>
                    <dt class="text-muted">Solicitado por</dt><dd style="margin:0;">{{ $estudo->solicitado_por_nome ?? '—' }}</dd>
                    <dt class="text-muted">Observações</dt><dd style="margin:0;white-space:pre-line;">{{ $estudo->observacoes ?? '—' }}</dd>
                    <dt class="text-muted">Plantão</dt>
                    <dd style="margin:0;">
                        @if ($estudo->plantao)
                            <a href="{{ route('doutores.plantoes.show', $estudo->plantao) }}">{{ $estudo->plantao->hospital }} — {{ $estudo->plantao->data?->format('d/m/Y') }}</a>
                        @else — @endif
                    </dd>
                </dl>
            </div>
        </div>

        <div class="card">
            <div class="card-head"><h2>Atualizar andamento</h2></div>
            <div class="card-body">
                <form method="POST" action="{{ route('doutores.estudos.update', $estudo) }}" class="form-grid">
                    @csrf @method('PUT')
                    <div class="field">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="select" required>
                            @foreach (\App\Models\Doutores\SolicitacaoEstudo::STATUSES as $k => $l)
                                <option value="{{ $k }}" {{ old('status', $estudo->status) === $k ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="atribuido_a">Atribuído a</label>
                        <input type="text" id="atribuido_a" name="atribuido_a" class="input" value="{{ old('atribuido_a', $estudo->atribuido_a) }}" maxlength="255" placeholder="Nome do voluntário / instrutor">
                    </div>
                    <div class="field">
                        <label for="plantao_id">Vincular a plantão</label>
                        <select id="plantao_id" name="plantao_id" class="select">
                            <option value="">—</option>
                            @foreach ($plantoes as $p)
                                <option value="{{ $p->id }}" {{ (string) old('plantao_id', $estudo->plantao_id) === (string) $p->id ? 'selected' : '' }}>{{ $p->data?->format('d/m/Y') }} — {{ $p->hospital }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="observacoes_log">Observação do andamento</label>
                        <textarea id="observacoes_log" name="observacoes_log" rows="3" class="input" maxlength="2000"></textarea>
                    </div>
                    <div class="form-actions"><button type="submit" class="btn"><i class="bi bi-check-lg"></i> Salvar</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top:20px;">
        <div class="card-head"><h2>Histórico</h2></div>
        <div class="table-wrap">
            <table class="adm-table">
                <thead><tr><th>Quando</th><th>Status</th><th>Por</th><th>Observações</th></tr></thead>
                <tbody>
                    @forelse ($estudo->logs as $l)
                        <tr>
                            <td>{{ $l->created_at?->format('d/m/Y H:i') }}</td>
                            <td>@include('doutores.partials.badge', ['status' => $l->status])</td>
                            <td>{{ $l->executado_por }} <span class="muted-sm">{{ $l->executado_por_perfil }}</span></td>
                            <td>{{ $l->observacoes ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="empty-state">Sem histórico.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
