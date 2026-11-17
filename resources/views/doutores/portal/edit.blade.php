@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-portal'; @endphp
@section('title', 'Portal')
@section('heading', 'Configurações do portal')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('doutores.portal.update') }}" class="form-grid">
                @csrf @method('PUT')
                <div class="field">
                    <label for="titulo_hero">Título principal</label>
                    <input type="text" id="titulo_hero" name="titulo_hero" class="input" value="{{ old('titulo_hero', $config->titulo_hero) }}" maxlength="255">
                </div>
                <div class="field">
                    <label for="subtitulo_hero">Subtítulo</label>
                    <textarea id="subtitulo_hero" name="subtitulo_hero" rows="3" class="input" maxlength="2000">{{ old('subtitulo_hero', $config->subtitulo_hero) }}</textarea>
                </div>
                <div class="field">
                    <label for="texto_sobre">Texto “Sobre”</label>
                    <textarea id="texto_sobre" name="texto_sobre" rows="8" class="input" maxlength="10000">{{ old('texto_sobre', $config->texto_sobre) }}</textarea>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="contato_email">E-mail de contato</label>
                        <input type="email" id="contato_email" name="contato_email" class="input" value="{{ old('contato_email', $config->contato_email) }}" maxlength="255">
                    </div>
                    <div class="field">
                        <label for="contato_telefone">Telefone</label>
                        <input type="text" id="contato_telefone" name="contato_telefone" class="input" value="{{ old('contato_telefone', $config->contato_telefone) }}" maxlength="255">
                    </div>
                </div>
                <div class="field">
                    <label for="contato_endereco">Endereço</label>
                    <input type="text" id="contato_endereco" name="contato_endereco" class="input" value="{{ old('contato_endereco', $config->contato_endereco) }}" maxlength="255">
                </div>
                <div class="form-actions"><button type="submit" class="btn"><i class="bi bi-check-lg"></i> Salvar</button></div>
            </form>
        </div>
    </div>
@endsection
