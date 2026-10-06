<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Meta Tags Básicas -->
    <meta name="description" content="@yield('meta-description', 'IASD Central de Brasília - Uma comunidade de fé, amor e esperança. Encontre estudos bíblicos, programações, eventos e mais.')">
    <meta name="keywords" content="IASD, Igreja Adventista, Brasília, estudo bíblico, escola sabatina, programações, eventos, adventista, igreja, culto">
    <meta name="author" content="IASD Central de Brasília">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ request()->url() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:title" content="@yield('og-title', 'IASD Central de Brasília')">
    <meta property="og:description" content="@yield('og-description', 'Uma comunidade de fé, amor e esperança em Brasília. Junte-se a nós!')">
    <meta property="og:image" content="@yield('og-image', asset('img/logo_iasd.webp'))">
    <meta property="og:site_name" content="IASD Central de Brasília">
    <meta property="og:locale" content="pt_BR">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ request()->url() }}">
    <meta property="twitter:title" content="@yield('twitter-title', 'IASD Central de Brasília')">
    <meta property="twitter:description" content="@yield('twitter-description', 'Uma comunidade de fé, amor e esperança em Brasília. Junte-se a nós!')">
    <meta property="twitter:image" content="@yield('twitter-image', asset('img/logo_iasd.webp'))">

    @yield('meta')

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('img/favicon-32.png') }}" type="image/png" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('img/apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://unpkg.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="dns-prefetch" href="https://unpkg.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet" href="{{ asset('css/padrao.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}?v={{ filemtime(public_path('css/header.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Noto+Sans+JP:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Noto+Sans+JP:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet"></noscript>
    @stack('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    </noscript>

    <title>@yield('title', 'IASD Central de Brasília')</title>

    <style>
        /* Layout específico para notícia - largura total */
        main {
            width: 100% !important;
            max-width: 100% !important;
        }
        header {
            width: 100% !important;
            max-width: 100% !important;
        }
    </style>
</head>

<body>
    @include('partials.header')

    <main style="width: 100% !important; max-width: 100% !important;">
        @yield('content')
    </main>

    @include('partials.footer')

    @include('partials.form_overlay')

    @if (session('success'))
        <div class="app-toast app-toast--success" id="appToast" role="status" aria-live="polite">
            <div class="app-toast__icon" aria-hidden="true">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="app-toast__text">{{ session('success') }}</div>
            <button type="button" class="app-toast__close" aria-label="Fechar">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <script>
            (function () {
                var toast = document.getElementById('appToast');
                if (!toast) return;

                var closeBtn = toast.querySelector('.app-toast__close');
                var hide = function () {
                    toast.classList.add('is-hidden');
                    window.setTimeout(function () {
                        if (toast && toast.parentNode) toast.parentNode.removeChild(toast);
                    }, 350);
                };

                if (closeBtn) closeBtn.addEventListener('click', hide);
                window.setTimeout(hide, 15000);
            })();
        </script>
    @endif

    <script>window.APP_URL = '{{ url("/") }}';</script>
    <script src="{{ asset('js/por_do_sol.js') }}" defer></script>
    <script src="{{ asset('js/menu_hamburguer.js') }}" defer></script>
    <script src="{{ asset('js/form_overlay.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
