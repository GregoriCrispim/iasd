@extends('doutores.layouts.secured')

@php
    $activeNav = 'doutores-polos';
    $editing = $polo->exists;
@endphp
@section('title', $editing ? 'Editar polo' : 'Novo polo')
@section('heading', $editing ? 'Editar polo' : 'Novo polo')

@section('actions')
    <a href="{{ route('doutores.polos.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
@endsection

@section('content')
    @php $selecionados = collect(old('coordenadores', $coordenadoresSelecionados))->map(fn ($v) => (int) $v)->all(); @endphp
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ $editing ? route('doutores.polos.update', $polo) : route('doutores.polos.store') }}" class="form-grid">
                @csrf
                @if ($editing) @method('PUT') @endif

                <div class="form-row">
                    <div class="field">
                        <label for="nome">Nome <span class="req">*</span></label>
                        <input type="text" id="nome" name="nome" class="input" value="{{ old('nome', $polo->nome) }}" required maxlength="255">
                    </div>
                    <div class="field">
                        <label for="status">Status <span class="req">*</span></label>
                        <select id="status" name="status" class="select" required>
                            <option value="ativo" {{ old('status', $polo->status) === 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="inativo" {{ old('status', $polo->status) === 'inativo' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="cep">CEP</label>
                        <input type="text" id="cep" name="cep" class="input" value="{{ old('cep', $polo->cep) }}" maxlength="9">
                    </div>
                    <div class="field">
                        <label for="endereco">Endereço</label>
                        <input type="text" id="endereco" name="endereco" class="input" value="{{ old('endereco', $polo->endereco) }}" maxlength="255">
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="cidade">Cidade</label>
                        <input type="text" id="cidade" name="cidade" class="input" value="{{ old('cidade', $polo->cidade) }}" maxlength="255">
                    </div>
                    <div class="field">
                        <label for="estado">UF <span class="req">*</span></label>
                        <input type="text" id="estado" name="estado" class="input" value="{{ old('estado', $polo->estado) }}" required maxlength="2" style="text-transform:uppercase;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="telefone">Telefone</label>
                        <input type="text" id="telefone" name="telefone" class="input" value="{{ old('telefone', $polo->telefone) }}" maxlength="255">
                    </div>
                    <div class="field">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" class="input" value="{{ old('email', $polo->email) }}" maxlength="255">
                    </div>
                </div>
                <div class="field">
                    <label for="coordenador">Coordenador (nome de exibição)</label>
                    <input type="text" id="coordenador" name="coordenador" class="input" value="{{ old('coordenador', $polo->coordenador) }}" maxlength="255">
                </div>

                <div class="field">
                    <label>Usuários coordenadores deste polo</label>
                    <span class="hint">Coordenadores só enxergam hospitais, plantões e voluntários dos polos em que estão vinculados.</span>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:8px;margin-top:6px;">
                        @forelse ($coordenadoresDisponiveis as $c)
                            <label class="checkbox-field">
                                <input type="checkbox" name="coordenadores[]" value="{{ $c->id }}" {{ in_array($c->id, $selecionados, true) ? 'checked' : '' }}>
                                <span>{{ $c->name }} <span class="muted-sm">{{ $c->email }}</span></span>
                            </label>
                        @empty
                            <span class="text-muted">Nenhum usuário com o papel “Doutores — Coordenador”. Atribua o papel em Usuários.</span>
                        @endforelse
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn"><i class="bi bi-check-lg"></i> Salvar</button>
                    <a href="{{ route('doutores.polos.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
