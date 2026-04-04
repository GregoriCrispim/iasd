<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Meta Tags Básicas -->
    <meta name="description" content="@yield('meta-description', 'IASD Central de Brasília - Uma comunidade de fé, amor e esperança. Encontre estudos bíblicos, programações, eventos e mais.')">
    <meta name="keywords" content="IASD, Igreja Adventista, Brasília, estudo bíblico, escola sabatina, programações, eventos, adventista, igreja central">
    <meta name="author" content="IASD Central de Brasília">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ request()->url() }}">
    <link rel="alternate" hreflang="pt-br" href="{{ request()->url() }}">

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

    <link rel="preconnect" href="https://unpkg.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="dns-prefetch" href="https://unpkg.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet" href="{{ asset('css/padrao.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages_padrao.css') }}">
    <!-- Fonts used by shared profecias-inspired styles -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
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

    <!-- Schema.org JSON-LD -->
    @yield('schema-organization')
    @yield('schema-localbusiness')
    @yield('schema-webpage')
    @yield('schema-breadcrumb')
    @yield('schema-faq')
    @yield('schema-events')
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

    <!-- Organization Schema -->
    @push('schema-organization')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "IASD Central de Brasília",
      "url": "https://adventistascentralbrasilia.org",
      "logo": "https://adventistascentralbrasilia.org/img/logo_iasd.webp",
      "description": "Igreja Adventista do Sétimo Dia Central de Brasília - Uma comunidade de fé, amor e esperança",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "SGAS 611 módulo 75 - Asa Sul",
        "addressLocality": "Brasília",
        "addressRegion": "DF",
        "postalCode": "70200-970",
        "addressCountry": "BR"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+55-61-98157-4702",
        "contactType": "customer service",
        "email": "comunicacaocentralbsb@gmail.com",
        "areaServed": "BR",
        "availableLanguage": ["Portuguese"]
      },
      "sameAs": [
        "https://www.facebook.com/share/18C9sd7nvQ/",
        "https://www.instagram.com/comunidadecentralbsb/",
        "https://www.youtube.com/@adventistascentralbrasilia",
        "https://www.tiktok.com/@igrejaadvcentraldebsb"
      ],
      "foundingDate": "1968",
      "numberOfEmployees": "quase 1500"
    }
    </script>
    @endpush

    <!-- LocalBusiness Schema -->
    @push('schema-localbusiness')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "IASD Central de Brasília",
      "image": "https://adventistascentralbrasilia.org/img/logo_iasd.webp",
      "telephone": "+55-61-98157-4702",
      "email": "comunicacaocentralbsb@gmail.com",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "SGAS 611 módulo 75 - Asa Sul",
        "addressLocality": "Brasília",
        "addressRegion": "DF",
        "postalCode": "70200-970",
        "addressCountry": "BR"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": "-15.8289723",
        "longitude": "-47.9048304"
      },
      "openingHoursSpecification": [
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": ["Saturday"],
          "opens": "09:00",
          "closes": "12:00"
        },
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": ["Saturday"],
          "opens": "19:00",
          "closes": "21:00"
        }
      ],
      "priceRange": "FREE"
    }
    </script>
    @endpush

    <!-- Breadcrumb Schema -->
    @push('schema-breadcrumb')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Início",
          "item": "https://adventistascentralbrasilia.org"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "@yield('page-name', 'Página')",
          "item": "{{ request()->url() }}"
        }
      ]
    }
    </script>
    @endpush
</body>
</html>

