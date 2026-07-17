@extends('admin.layout')

@php
    $activeNav = 'blocks';
    $editing = $block->exists;
@endphp
@section('title', $editing ? 'Editar bloco' : 'Novo bloco')
@section('heading', $editing ? 'Editar bloco' : 'Novo bloco')

@section('actions')
    <a href="{{ route('admin.blocks.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ $editing ? route('admin.blocks.update', $block) : route('admin.blocks.store') }}" class="form-grid">
                @csrf
                @if ($editing) @method('PUT') @endif

                <div class="field">
                    <label>Página <span class="req">*</span></label>
                    <select name="cms_page_id" class="select" required>
                        <option value="">Selecione...</option>
                        @foreach ($pages as $p)
                            <option value="{{ $p->id }}" {{ (string) old('cms_page_id', $block->cms_page_id) === (string) $p->id ? 'selected' : '' }}>{{ $p->label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <div class="field">
                        <label>Chave do bloco <span class="req">*</span></label>
                        <input type="text" name="block_key" class="input" value="{{ old('block_key', $block->block_key) }}" required>
                        <span class="hint">Ex.: intro, conteudo, destaque. Usada no Blade com <code>@{{ '@cmsBlock(\'intro\')' }}</code>.</span>
                    </div>
                    <div class="field">
                        <label>Nome <span class="req">*</span></label>
                        <input type="text" name="label" class="input" value="{{ old('label', $block->label) }}" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn"><i class="bi bi-check-lg"></i> Salvar</button>
                    <a href="{{ route('admin.blocks.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
