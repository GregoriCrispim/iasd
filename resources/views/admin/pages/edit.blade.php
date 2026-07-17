@extends('admin.layout')

@php $activeNav = 'pages'; @endphp
@section('title', 'Editar página')
@section('heading', 'Editar página')

@section('actions')
    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-head"><h2>{{ $page->label }}</h2></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.pages.update', $page) }}" class="form-grid">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="field">
                        <label>Rota <span class="req">*</span></label>
                        <input type="text" name="route_name" class="input" value="{{ old('route_name', $page->route_name) }}" required>
                    </div>
                    <div class="field">
                        <label>Rótulo <span class="req">*</span></label>
                        <input type="text" name="label" class="input" value="{{ old('label', $page->label) }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="field">
                        <label>View (view_path)</label>
                        <input type="text" name="view_path" class="input" value="{{ old('view_path', $page->view_path) }}">
                    </div>
                    <div class="field">
                        <label>Slug da seção</label>
                        <input type="text" name="section_slug" class="input" value="{{ old('section_slug', $page->section_slug) }}">
                    </div>
                </div>

                <div class="switch-field">
                    <label class="switch">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                    <span>Página ativa</span>
                </div>

                <div class="switch-field">
                    <label class="switch">
                        <input type="checkbox" name="cms_enabled" value="1" {{ old('cms_enabled', $page->cms_enabled) ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                    <span>CMS habilitado (permite editar blocos desta página)</span>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn"><i class="bi bi-check-lg"></i> Salvar</button>
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
