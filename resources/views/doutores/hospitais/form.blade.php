@extends('doutores.layouts.secured')

@php
    $activeNav = 'doutores-hospitais';
    $editing = $hospital->exists;
@endphp
@section('title', $editing ? 'Editar hospital' : 'Novo hospital')
@section('heading', $editing ? 'Editar hospital' : 'Novo hospital')

@section('actions')
    <a href="{{ route('doutores.hospitais.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ $editing ? route('doutores.hospitais.update', $hospital) : route('doutores.hospitais.store') }}" class="form-grid">
                @csrf
                @if ($editing) @method('PUT') @endif

                <div class="form-row">
                    <div class="field">
                        <label for="nome">Nome <span class="req">*</span></label>
                        <input type="text" id="nome" name="nome" class="input" value="{{ old('nome', $hospital->nome) }}" required maxlength="255">
                    </div>
                    <div class="field">
                        <label for="polo_id">Polo</label>
                        <select id="polo_id" name="polo_id" class="select">
                            <option value="">—</option>
                            @foreach ($polos as $p)
                                <option value="{{ $p->id }}" {{ (string) old('polo_id', $hospital->polo_id) === (string) $p->id ? 'selected' : '' }}>{{ $p->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="cep">CEP</label>
                        <input type="text" id="cep" name="cep" class="input" value="{{ old('cep', $hospital->cep) }}" maxlength="9">
                    </div>
                    <div class="field">
                        <label for="endereco">Endereço</label>
                        <input type="text" id="endereco" name="endereco" class="input" value="{{ old('endereco', $hospital->endereco) }}" maxlength="255">
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="cidade">Cidade</label>
                        <input type="text" id="cidade" name="cidade" class="input" value="{{ old('cidade', $hospital->cidade) }}" maxlength="255">
                    </div>
                    <div class="form-row" style="gap:12px;">
                        <div class="field">
                            <label for="uf">UF <span class="req">*</span></label>
                            <input type="text" id="uf" name="uf" class="input" value="{{ old('uf', $hospital->uf) }}" required maxlength="2" style="text-transform:uppercase;">
                        </div>
                        <div class="field">
                            <label for="status">Status <span class="req">*</span></label>
                            <select id="status" name="status" class="select" required>
                                <option value="ativo" {{ old('status', $hospital->status) === 'ativo' ? 'selected' : '' }}>Ativo</option>
                                <option value="inativo" {{ old('status', $hospital->status) === 'inativo' ? 'selected' : '' }}>Inativo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn"><i class="bi bi-check-lg"></i> Salvar</button>
                    <a href="{{ route('doutores.hospitais.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
