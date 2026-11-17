@php
    $portalCfg = \App\Models\Doutores\PortalConfig::query()->find('portal');
@endphp
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Doutores da Esperança') — Doutores da Esperança</title>
    <meta name="description" content="@yield('meta-description', 'Portal do voluntariado Doutores da Esperança — IASD Central de Brasília.')">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/doutores.css') }}?v={{ filemtime(public_path('css/doutores.css')) }}">
    @stack('styles')
</head>
<body class="dd" style="display:flex;flex-direction:column;min-height:100vh;">
<header class="dd-portal-header">
    <div class="dd-stripe"></div>
    <div class="dd-portal-header-inner">
        <a href="{{ route('doutores-da-esperanca') }}" class="dd-brand">
            <img src="{{ asset('img/doutores/logo-Doutores.png') }}" alt="Doutores da Esperança">
            <span class="dd-brand-text">Doutores Esperança<small>Voluntariado hospitalar</small></span>
        </a>
        <nav class="dd-portal-nav">
            <a href="{{ route('doutores-da-esperanca') }}">O projeto</a>
            <a href="{{ route('doutores.public.publicacoes.index') }}">Publicações</a>
            <a href="{{ route('doutores.login') }}" class="dd-btn-brand">Entrar</a>
        </nav>
    </div>
</header>

<main class="dd-portal-main">
    @yield('content')
</main>

<footer class="dd-portal-footer">
    <div class="dd-stripe"></div>
    <div class="dd-portal-footer-inner">
        <strong>Doutores da Esperança</strong>
        @if ($portalCfg?->texto_sobre)<span>{{ \Illuminate\Support\Str::limit($portalCfg->texto_sobre, 220) }}</span>@endif
        @if ($portalCfg?->contato_email)<span><i class="bi bi-envelope"></i> {{ $portalCfg->contato_email }}</span>@endif
        @if ($portalCfg?->contato_telefone)<span><i class="bi bi-telephone"></i> {{ $portalCfg->contato_telefone }}</span>@endif
        @if ($portalCfg?->contato_endereco)<span><i class="bi bi-geo-alt"></i> {{ $portalCfg->contato_endereco }}</span>@endif
        <span>© {{ date('Y') }} IASD Central de Brasília</span>
    </div>
</footer>
<script src="{{ asset('js/doutores-select.js') }}?v={{ filemtime(public_path('js/doutores-select.js')) }}" defer></script>
@stack('scripts')
</body>
</html>
