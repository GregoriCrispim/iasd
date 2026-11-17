@extends('doutores.layouts.auth')

@section('title', 'Entrar')

@section('content')
    <form method="POST" action="{{ route('doutores.login.post') }}" class="dd-auth-form">
        @csrf
        <div class="field">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" class="input" value="{{ old('email') }}" required autofocus autocomplete="username">
        </div>
        <div class="field">
            <label for="password">Senha</label>
            <input type="password" id="password" name="password" class="input" required autocomplete="current-password">
        </div>
        <label class="checkbox-field">
            <input type="checkbox" name="remember" value="1"> Manter conectado
        </label>
        <button type="submit" class="dd-btn-brand dd-btn-block"><i class="bi bi-box-arrow-in-right"></i> Entrar</button>
    </form>

    <div class="dd-auth-links">
        Ainda não é voluntário? <a href="{{ route('doutores.cadastrar') }}">Cadastre-se</a><br>
        <a href="{{ route('doutores-da-esperanca') }}">Conheça o projeto</a>
    </div>
@endsection
