<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages_padrao.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Noto+Sans+JP:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Noto+Sans+JP:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet"></noscript>
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/profecias_inspired.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form_overlay.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet' media="print" onload="this.media='all'">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" media="print" onload="this.media='all'">
    <noscript>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    </noscript>

    <title>@yield('title', 'IASD Central de Brasília')</title>
</head>
<body>
    <main>
        @include('partials.header')

        @yield('content')

        @include('partials.footer')
    </main>
    <aside style="position: fixed; right: 0;"><img src="{{ asset('img/logo-7-coluna.svg') }}" alt="" style="width: 50%;" loading="lazy"></aside>

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

