@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-voluntarios'; @endphp
@section('title', 'Voluntários')
@section('heading', 'Voluntários')

@section('actions')
    <button type="button" class="btn" onclick="document.getElementById('novo-voluntario').open = true; document.getElementById('novo-voluntario').scrollIntoView({behavior:'smooth'});">
        <i class="bi bi-person-plus"></i> Novo voluntário
    </button>
@endsection

@section('content')
    <details id="novo-voluntario" class="card" style="margin-bottom:20px;" {{ $openCreate ? 'open' : '' }}>
        <summary class="card-head" style="cursor:pointer;"><h2>Cadastrar voluntário</h2></summary>
        <div class="card-body">
            <form method="POST" action="{{ route('doutores.voluntarios.store') }}" class="form-grid" autocomplete="off">
                @csrf
                <input type="hidden" name="_form" value="create">
                <div class="form-row">
                    <div class="field">
                        <label for="v-name">Nome <span class="req">*</span></label>
                        <input type="text" id="v-name" name="name" class="input" value="{{ old('name') }}" required maxlength="255">
                    </div>
                    <div class="field">
                        <label for="v-email">E-mail <span class="req">*</span></label>
                        <input type="email" id="v-email" name="email" class="input" value="{{ old('email') }}" required maxlength="255">
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="v-password">Senha</label>
                        <input type="password" id="v-password" name="password" class="input" minlength="8" autocomplete="new-password" placeholder="Em branco = gerar senha temporária">
                    </div>
                    <div class="field">
                        <label for="v-cpf">CPF</label>
                        <input type="text" id="v-cpf" name="cpf" class="input" value="{{ old('cpf') }}" maxlength="14" placeholder="Somente números">
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="v-telefone">Telefone</label>
                        <input type="text" id="v-telefone" name="telefone" class="input" value="{{ old('telefone') }}" maxlength="20">
                    </div>
                    <div class="field">
                        <label for="v-cidade">Cidade</label>
                        <input type="text" id="v-cidade" name="cidade" class="input" value="{{ old('cidade') }}" maxlength="255">
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="v-hospital">Hospital preferido</label>
                        <select id="v-hospital" name="hospital_preferido" class="select">
                            <option value="">—</option>
                            @foreach ($hospitaisAtivos as $h)
                                <option value="{{ $h }}" {{ old('hospital_preferido') === $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="v-igreja">Igreja</label>
                        <select id="v-igreja" name="igreja" class="select">
                            <option value="">—</option>
                            @foreach ($igrejas as $h)
                                <option value="{{ $h }}" {{ old('igreja') === $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="v-cargo">Função</label>
                        <select id="v-cargo" name="cargo" class="select">
                            <option value="">—</option>
                            @foreach ($funcoes as $h)
                                <option value="{{ $h }}" {{ old('cargo') === $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="v-status">Status inicial</label>
                        <select id="v-status" name="status" class="select">
                            <option value="ativo" {{ old('status', 'ativo') === 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="pendente" {{ old('status') === 'pendente' ? 'selected' : '' }}>Pendente</option>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn"><i class="bi bi-check-lg"></i> Criar voluntário</button>
                </div>
            </form>
        </div>
    </details>

    <div class="card">
        <form method="GET" class="filters">
            <div class="field">
                <label>Buscar</label>
                <input type="text" name="q" value="{{ request('q') }}" class="input" placeholder="Nome, e-mail, CPF ou telefone…">
            </div>
            <div class="field">
                <label>Status</label>
                <select name="status" class="select">
                    <option value="">Todos</option>
                    @foreach (['pendente' => 'Pendente', 'ativo' => 'Ativo', 'inativo' => 'Inativo'] as $k => $l)
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
                <thead><tr><th>Nome</th><th>Contato</th><th>Hospital preferido</th><th>Status</th><th>Termos</th><th class="col-actions">Ações</th></tr></thead>
                <tbody>
                    @forelse ($profiles as $p)
                        <tr>
                            <td><strong>{{ $p->user?->name }}</strong><br><span class="muted-sm">{{ $p->cargo }}</span></td>
                            <td>{{ $p->user?->email }}<br><span class="muted-sm">{{ $p->telefone }}</span></td>
                            <td>{{ $p->hospital_preferido ?? '—' }}</td>
                            <td>@include('doutores.partials.badge', ['status' => $p->status])</td>
                            <td>{!! $p->termos_aceitos_em ? '<span class="badge badge-green">Aceitos</span>' : '<span class="badge badge-gray">Pendentes</span>' !!}</td>
                            <td class="col-actions">
                                <div class="row-actions">
                                    <a href="{{ route('doutores.voluntarios.show', $p) }}" class="btn btn-secondary btn-sm" title="Abrir"><i class="bi bi-eye"></i></a>
                                    @if ($p->status !== 'ativo')
                                        <form method="POST" action="{{ route('doutores.voluntarios.approve', $p) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" title="Aprovar"><i class="bi bi-check-lg"></i></button>
                                        </form>
                                    @endif
                                    @if ($p->status !== 'inativo')
                                        <form method="POST" action="{{ route('doutores.voluntarios.reject', $p) }}"
                                              onsubmit="return admConfirm({{ json_encode('Inativar o cadastro de '.($p->user?->name ?? 'voluntário').'?') }}, this, { title: 'Inativar voluntário', confirmLabel: 'Inativar', confirmIcon: 'person-x' });">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" title="Reprovar / inativar"><i class="bi bi-x-lg"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="bi bi-people"></i>Nenhum voluntário encontrado.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="adm-pagination">{{ $profiles->links() }}</div>
    </div>
@endsection
