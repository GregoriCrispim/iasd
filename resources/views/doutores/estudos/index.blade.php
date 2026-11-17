@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-estudos'; @endphp
@section('title', 'Estudos bíblicos')
@section('heading', 'Solicitações de estudo bíblico')

@section('actions')
    <button type="button" class="btn" onclick="var d=document.getElementById('novo-estudo'); d.open = true; d.scrollIntoView({behavior:'smooth'});"><i class="bi bi-plus-lg"></i> Nova solicitação</button>
@endsection

@section('content')
    <details id="novo-estudo" class="card" style="margin-bottom:20px;" {{ $errors->any() && old('nome_paciente') !== null ? 'open' : '' }}>
        <summary class="card-head" style="cursor:pointer;"><h2>Registrar solicitação</h2></summary>
        <div class="card-body">
            <form method="POST" action="{{ route('doutores.estudos.store') }}" class="form-grid">
                @csrf
                <div class="form-row">
                    <div class="field">
                        <label for="nome_paciente">Nome <span class="req">*</span></label>
                        <input type="text" id="nome_paciente" name="nome_paciente" class="input" value="{{ old('nome_paciente') }}" required maxlength="255">
                    </div>
                    <div class="field">
                        <label for="telefone">Telefone</label>
                        <input type="text" id="telefone" name="telefone" class="input" value="{{ old('telefone') }}" maxlength="30">
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" class="input" value="{{ old('email') }}" maxlength="255">
                    </div>
                    <div class="field">
                        <label for="hospital">Hospital</label>
                        <select id="hospital" name="hospital" class="select">
                            <option value="">—</option>
                            @foreach ($hospitais as $h)
                                <option value="{{ $h }}" {{ old('hospital') === $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="endereco">Endereço</label>
                        <input type="text" id="endereco" name="endereco" class="input" value="{{ old('endereco') }}" maxlength="255">
                    </div>
                    <div class="field">
                        <label for="cidade">Cidade</label>
                        <input type="text" id="cidade" name="cidade" class="input" value="{{ old('cidade') }}" maxlength="255">
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="dia_preferido">Dia preferido</label>
                        <input type="text" id="dia_preferido" name="dia_preferido" class="input" value="{{ old('dia_preferido') }}" maxlength="100">
                    </div>
                    <div class="field">
                        <label for="horario_preferido">Horário preferido</label>
                        <input type="text" id="horario_preferido" name="horario_preferido" class="input" value="{{ old('horario_preferido') }}" maxlength="100">
                    </div>
                </div>
                <div class="field">
                    <label for="observacoes">Observações</label>
                    <textarea id="observacoes" name="observacoes" rows="3" class="input" maxlength="5000">{{ old('observacoes') }}</textarea>
                </div>
                <div class="form-actions"><button type="submit" class="btn"><i class="bi bi-check-lg"></i> Registrar</button></div>
            </form>
        </div>
    </details>

    <div class="card">
        <form method="GET" class="filters">
            <div class="field">
                <label>Buscar</label>
                <input type="text" name="q" value="{{ request('q') }}" class="input" placeholder="Nome, telefone ou e-mail…">
            </div>
            <div class="field">
                <label>Status</label>
                <select name="status" class="select">
                    <option value="">Todos</option>
                    @foreach (\App\Models\Doutores\SolicitacaoEstudo::STATUSES as $k => $l)
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
            <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filtrar</button>
        </form>

        <div class="table-wrap">
            <table class="adm-table">
                <thead><tr><th>Solicitante</th><th>Hospital</th><th>Solicitado em</th><th>Atribuído a</th><th>Status</th><th class="col-actions">Ações</th></tr></thead>
                <tbody>
                    @forelse ($estudos as $e)
                        <tr>
                            <td><strong>{{ $e->nome_paciente }}</strong><br><span class="muted-sm">{{ $e->telefone }}</span></td>
                            <td>{{ $e->hospital ?? '—' }}</td>
                            <td>{{ ($e->data_solicitacao ?? $e->created_at)?->format('d/m/Y') }}</td>
                            <td>{{ $e->atribuido_a ?? '—' }}</td>
                            <td>@include('doutores.partials.badge', ['status' => $e->status])</td>
                            <td class="col-actions"><a href="{{ route('doutores.estudos.show', $e) }}" class="btn btn-secondary btn-sm" title="Abrir"><i class="bi bi-eye"></i></a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="bi bi-book"></i>Nenhuma solicitação encontrada.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="adm-pagination">{{ $estudos->links() }}</div>
    </div>
@endsection
