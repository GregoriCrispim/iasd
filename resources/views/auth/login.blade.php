@extends('layouts.app')

@section('title', 'Entrar — IASD Central de Brasília')
@section('meta-description', 'Acesse sua conta de membro da IASD Central de Brasília.')
@section('page-name', 'Entrar')

@push('styles')
<style>
    .auth-wrap {
        max-width: 460px;
        margin: 0 auto;
        padding: clamp(24px, 5vw, 64px) 16px;
    }
    .auth-card {
        background: #fff;
        border: 1px solid rgba(0, 51, 102, 0.12);
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        padding: clamp(24px, 4vw, 40px);
    }
    .auth-card h1 {
        font-family: "Bebas Neue", "Noto Sans JP", sans-serif;
        color: #003366;
        font-size: clamp(2rem, 4vw, 2.6rem);
        margin: 0 0 0.25rem;
        letter-spacing: 1px;
    }
    .auth-card p.auth-sub { color: #555; margin: 0 0 1.5rem; font-size: 0.95rem; }
    .auth-field { margin-bottom: 1rem; }
    .auth-field label { display: block; font-size: 0.85rem; color: #003366; font-weight: 600; margin-bottom: 0.35rem; }
    .auth-field input {
        width: 100%;
        padding: 0.7rem 0.9rem;
        border: 1px solid rgba(0, 51, 102, 0.25);
        border-radius: 10px;
        font-size: 0.95rem;
        font-family: "Roboto", sans-serif;
        background: #fff;
    }
    .auth-field input:focus { outline: 2px solid rgba(0, 51, 102, 0.35); border-color: #003366; }
    .auth-check { display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; color: #444; margin-bottom: 1.25rem; }
    .auth-btn {
        width: 100%;
        border: none;
        border-radius: 10px;
        background: #003366;
        color: #fff;
        padding: 0.8rem 1rem;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .auth-btn:hover { background: #002244; }
    .auth-foot { text-align: center; margin-top: 1.25rem; font-size: 0.9rem; color: #555; }
    .auth-foot a { color: #003366; font-weight: 600; text-decoration: none; }
    .auth-alert {
        background: #fdecea;
        color: #a12622;
        border: 1px solid #f5c6c3;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        margin-bottom: 1.25rem;
    }
    .auth-alert ul { margin: 0; padding-left: 1.1rem; }
</style>
@endpush

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <h1>Entrar</h1>
        <p class="auth-sub">Acesse sua conta de membro para usar a busca facial na galeria.</p>

        @if (session('error'))
            <div class="auth-alert" role="alert">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="auth-alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('member.login.post') }}">
            @csrf
            @if (request('redirect'))
                <input type="hidden" name="redirect" value="{{ request('redirect') }}">
            @endif
            <div class="auth-field">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            </div>
            <div class="auth-field">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <label class="auth-check">
                <input type="checkbox" name="remember" value="1"> Manter conectado
            </label>
            <button type="submit" class="auth-btn"><i class="bi bi-box-arrow-in-right"></i> Entrar</button>
        </form>

        <div class="auth-foot">
            Ainda não tem conta? <a href="{{ route('member.register', request()->only('redirect')) }}">Criar conta</a>
        </div>
    </div>
</div>
@endsection
