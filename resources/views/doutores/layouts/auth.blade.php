<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Entrar') — Doutores da Esperança</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/doutores.css') }}?v={{ filemtime(public_path('css/doutores.css')) }}">
    @stack('styles')
</head>
<body class="dd dd-auth-body">
    <div class="dd-stripe"></div>
    <main class="dd-auth-main">
        <div class="dd-auth-card">
            <div class="dd-auth-brand">
                <img src="{{ asset('img/doutores/logo-Doutores.png') }}" alt="Doutores da Esperança">
                <h1>@yield('brand-title', 'Doutores de Esperança')</h1>
                <p>@yield('brand-subtitle', 'Entre para acessar o painel de voluntários')</p>
            </div>

            @if (session('success'))
                <div class="dd-alert ok" role="status">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="dd-alert err" role="alert">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="dd-alert err" role="alert">
                    <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
    <div class="dd-stripe"></div>
    <script src="{{ asset('js/doutores-select.js') }}?v={{ filemtime(public_path('js/doutores-select.js')) }}" defer></script>
    @stack('scripts')
</body>
</html>
