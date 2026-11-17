@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-voluntarios'; @endphp
@section('title', $profile->user?->name ?? 'Voluntário')
@section('heading', $profile->user?->name ?? 'Voluntário')

@section('actions')
    <a href="{{ route('doutores.voluntarios.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
    @if ($profile->status !== 'ativo')
        <form method="POST" action="{{ route('doutores.voluntarios.approve', $profile) }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> Aprovar</button>
        </form>
    @endif
@endsection

@section('content')
    <div class="form-row" style="align-items:start;">
        <div class="card">
            <div class="card-head"><h2>Dados do voluntário</h2><div class="adm-actions">@include('doutores.partials.badge', ['status' => $profile->status])</div></div>
            <div class="card-body">
                <dl style="display:grid;grid-template-columns:150px 1fr;gap:8px 12px;margin:0;">
                    <dt class="text-muted">E-mail</dt><dd style="margin:0;">{{ $profile->user?->email }}</dd>
                    <dt class="text-muted">CPF</dt><dd style="margin:0;">{{ $profile->cpf ?? '—' }}</dd>
                    <dt class="text-muted">Telefone</dt><dd style="margin:0;">{{ $profile->telefone ?? '—' }}</dd>
                    <dt class="text-muted">Gênero</dt><dd style="margin:0;">{{ $profile->genero ? ucfirst($profile->genero) : '—' }}</dd>
                    <dt class="text-muted">Endereço</dt><dd style="margin:0;">{{ collect([$profile->endereco, $profile->bairro, $profile->cidade, $profile->cep])->filter()->implode(', ') ?: '—' }}</dd>
                    <dt class="text-muted">Igreja</dt><dd style="margin:0;">{{ $profile->igreja ?? '—' }}</dd>
                    <dt class="text-muted">Função</dt><dd style="margin:0;">{{ $profile->cargo ?? '—' }}</dd>
                    <dt class="text-muted">Preferência</dt><dd style="margin:0;">{{ collect([$profile->dia_preferido, $profile->horario_preferido])->filter()->implode(' · ') ?: '—' }}</dd>
                    <dt class="text-muted">Termos</dt><dd style="margin:0;">{{ $profile->termos_aceitos_em?->format('d/m/Y H:i') ?? 'Não aceitos' }}</dd>
                    <dt class="text-muted">Cadastro</dt><dd style="margin:0;">{{ $profile->created_at?->format('d/m/Y H:i') }}</dd>
                </dl>
            </div>
        </div>

        <div class="card">
            <div class="card-head"><h2>Gestão</h2></div>
            <div class="card-body">
                <form method="POST" action="{{ route('doutores.voluntarios.update', $profile) }}" class="form-grid">
                    @csrf @method('PUT')
                    <div class="field">
                        <label for="hospital_preferido">Hospital preferido</label>
                        <input type="text" id="hospital_preferido" name="hospital_preferido" class="input" value="{{ old('hospital_preferido', $profile->hospital_preferido) }}" maxlength="255">
                    </div>
                    <div class="field">
                        <label for="coordenador_responsavel_id">Coordenador responsável</label>
                        <select id="coordenador_responsavel_id" name="coordenador_responsavel_id" class="select">
                            <option value="">—</option>
                            @foreach ($coordenadores as $c)
                                <option value="{{ $c->id }}" {{ (string) old('coordenador_responsavel_id', $profile->coordenador_responsavel_id) === (string) $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="observacoes">Observações internas</label>
                        <textarea id="observacoes" name="observacoes" rows="4" class="input" maxlength="5000">{{ old('observacoes', $profile->observacoes) }}</textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn"><i class="bi bi-check-lg"></i> Salvar</button>
                    </div>
                </form>

                @if ($profile->status !== 'inativo')
                    <form method="POST" action="{{ route('doutores.voluntarios.reject', $profile) }}" class="form-grid" style="margin-top:18px;padding-top:18px;border-top:1px solid var(--adm-border);"
                          onsubmit="return admConfirm('Inativar este voluntário? Ele não conseguirá mais entrar no painel.', this, { title: 'Inativar voluntário', confirmLabel: 'Inativar', confirmIcon: 'person-x' });">
                        @csrf
                        <div class="field">
                            <label for="motivo">Motivo (opcional)</label>
                            <input type="text" id="motivo" name="motivo" class="input" maxlength="1000">
                        </div>
                        <div><button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-person-x"></i> Reprovar / inativar</button></div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="card" style="margin-top:20px;">
        <div class="card-head"><h2>Documentos enviados</h2></div>
        <div class="table-wrap">
            <table class="adm-table">
                <thead><tr><th>Tipo</th><th>Arquivo</th><th>Enviado em</th><th>Status</th><th class="col-actions">Ações</th></tr></thead>
                <tbody>
                    @forelse ($anexos as $a)
                        <tr>
                            <td>{{ \App\Models\Doutores\Anexo::TIPOS[$a->tipo] ?? $a->tipo }}</td>
                            <td><a href="{{ route('doutores.anexos.download', $a) }}">{{ $a->nome_arquivo }}</a></td>
                            <td>{{ $a->created_at?->format('d/m/Y H:i') }}</td>
                            <td>@include('doutores.partials.badge', ['status' => $a->status])</td>
                            <td class="col-actions">
                                <form method="POST" action="{{ route('doutores.anexos.revisar', $a) }}" class="row-actions">
                                    @csrf @method('PUT')
                                    <button name="status" value="aprovado" class="btn btn-success btn-sm" title="Aprovar"><i class="bi bi-check-lg"></i></button>
                                    <button name="status" value="rejeitado" class="btn btn-danger btn-sm" title="Rejeitar"><i class="bi bi-x-lg"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="empty-state"><i class="bi bi-paperclip"></i>Nenhum documento.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-row" style="margin-top:20px;align-items:start;">
        <div class="card">
            <div class="card-head"><h2>Últimas inscrições</h2></div>
            <div class="table-wrap">
                <table class="adm-table">
                    <thead><tr><th>Data</th><th>Hospital</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($inscricoes as $i)
                            <tr>
                                <td>{{ $i->data?->format('d/m/Y') ?? '—' }}</td>
                                <td>
                                    @if ($i->plantao)<a href="{{ route('doutores.plantoes.show', $i->plantao) }}">{{ $i->hospital }}</a>@else {{ $i->hospital }} @endif
                                </td>
                                <td>@include('doutores.partials.badge', ['status' => $i->status])</td>
                            </tr>
                        @empty
                            <tr><td colspan="3"><div class="empty-state">Sem inscrições.</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-head"><h2>Testemunhos</h2></div>
            <div class="card-body">
                @forelse ($testemunhos as $t)
                    <div style="margin-bottom:14px;">
                        <div class="muted-sm">{{ $t->plantao?->hospital }} · {{ $t->plantao?->data?->format('d/m/Y') }}</div>
                        <div style="white-space:pre-line;">{{ $t->texto }}</div>
                    </div>
                @empty
                    <span class="text-muted">Sem testemunhos.</span>
                @endforelse
            </div>
        </div>
    </div>
@endsection
