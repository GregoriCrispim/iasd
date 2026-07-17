<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Entrar — IASD Central</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="adm">
<div class="adm-login-wrap">
    <div class="adm-login-card">
        <div class="login-brand">
            <img src="{{ asset('img/logo_iasd.png') }}" alt="IASD Central" onerror="this.style.display='none'">
            <h1>Painel IASD Central</h1>
            <p>Entre para gerenciar o conteúdo</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}" class="form-grid">
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
            <button type="submit" class="btn" style="justify-content:center;">
                <i class="bi bi-box-arrow-in-right"></i> Entrar
            </button>
        </form>
    </div>
</div>
</body>
</html>
