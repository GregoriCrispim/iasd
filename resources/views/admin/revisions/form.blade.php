@extends('admin.layout')

@php
    $activeNav = 'revisions';
    $editing = $revision->exists;
@endphp
@section('title', $editing ? 'Editar revisão' : 'Nova revisão')
@section('heading', $editing ? 'Editar revisão' : 'Nova revisão')

@section('actions')
    <a href="{{ route('admin.revisions.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ $editing ? route('admin.revisions.update', $revision) : route('admin.revisions.store') }}" class="form-grid">
                @csrf
                @if ($editing) @method('PUT') @endif

                @if ($editing)
                    <div class="field">
                        <label>Bloco</label>
                        <input type="text" class="input" value="{{ ($revision->block?->page?->label ? $revision->block->page->label . ' — ' : '') . $revision->block?->label . ' (' . $revision->block?->block_key . ')' }}" disabled>
                    </div>
                @else
                    <div class="field">
                        <label>Bloco <span class="req">*</span></label>
                        <select name="cms_block_id" class="select" required>
                            <option value="">Selecione...</option>
                            @foreach ($blocks as $id => $label)
                                <option value="{{ $id }}" {{ (string) old('cms_block_id') === (string) $id ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if ($blocks->isEmpty())
                            <span class="hint">Nenhum bloco editável disponível. Peça a um super-admin para liberar uma página/bloco.</span>
                        @endif
                    </div>
                @endif

                <div class="field">
                    <label>Conteúdo <span class="req">*</span></label>
                    @include('admin.partials.tinymce', [
                        'name' => 'html',
                        'value' => old('html', $revision->html),
                        'uploadImageUrl' => route('admin.uploads.image'),
                        'uploadFileUrl' => route('admin.uploads.file'),
                    ])
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn"><i class="bi bi-check-lg"></i> Salvar rascunho</button>
                    <a href="{{ route('admin.revisions.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
