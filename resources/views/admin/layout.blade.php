@php
    /** @var \App\Models\User|null $authUser */
    $authUser = auth()->user();
    $isSuper = $authUser && $authUser->isSuperAdmin();
    $isManager = $authUser && $authUser->isManager();
    $active = $activeNav ?? '';
@endphp
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Painel') — IASD Central</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body class="adm">
<div class="adm-shell">
    <aside class="adm-sidebar" id="admSidebar">
        <div class="adm-brand">
            <img src="{{ asset('img/logo_iasd.png') }}" alt="IASD Central" onerror="this.style.display='none'">
            <div>
                <strong>IASD Central</strong>
                <span>Painel de gestão</span>
            </div>
        </div>

        <nav class="adm-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ $active === 'dashboard' ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Início
            </a>

            <div class="adm-nav-group">CMS</div>
            @if ($authUser)
                <a href="{{ route('admin.revisions.index') }}" class="{{ $active === 'revisions' ? 'active' : '' }}">
                    <i class="bi bi-pencil-square"></i> Revisões
                </a>
            @endif
            @if ($isSuper || $isManager)
                <a href="{{ route('admin.approvals.index') }}" class="{{ $active === 'approvals' ? 'active' : '' }}">
                    <i class="bi bi-inbox"></i> Aprovações
                    @if (!empty($pendingApprovalsCount))
                        <span class="badge-count">{{ $pendingApprovalsCount }}</span>
                    @endif
                </a>
            @endif
            @if ($isSuper)
                <a href="{{ route('admin.blocks.index') }}" class="{{ $active === 'blocks' ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i> Blocos
                </a>
                <a href="{{ route('admin.pages.index') }}" class="{{ $active === 'pages' ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i> Páginas
                </a>
            @endif

            @if ($isSuper || $isManager)
                <div class="adm-nav-group">Gestão</div>
                <a href="{{ route('admin.users.index') }}" class="{{ $active === 'users' ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Usuários
                </a>
            @endif
        </nav>

        <div class="adm-sidebar-foot">
            @if ($authUser)
                <div class="adm-user">
                    {{ $authUser->name }}
                    <small>
                        @if ($isSuper) Super Admin
                        @elseif ($isManager) Gestor
                        @else Colaborador @endif
                    </small>
                </div>
            @endif
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm" style="width:100%;justify-content:center;">
                    <i class="bi bi-box-arrow-right"></i> Sair
                </button>
            </form>
        </div>
    </aside>

    <div class="adm-overlay" id="admOverlay"></div>

    <div class="adm-main">
        <header class="adm-topbar">
            <button type="button" class="adm-burger" id="admBurger" aria-label="Menu"><i class="bi bi-list"></i></button>
            <h1>@yield('heading', 'Painel')</h1>
            <div class="adm-actions">@yield('actions')</div>
        </header>

        <main class="adm-content">
            @if (session('success'))
                <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i>{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i>{{ session('error') }}</div>
            @endif
            @if ($errors->any() && !isset($hideGlobalErrors))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
    (function () {
        var burger = document.getElementById('admBurger');
        var sidebar = document.getElementById('admSidebar');
        var overlay = document.getElementById('admOverlay');
        function toggle() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        }
        if (burger) burger.addEventListener('click', toggle);
        if (overlay) overlay.addEventListener('click', toggle);
    })();

    function admConfirm(message, formId) {
        if (window.confirm(message || 'Tem certeza?')) {
            document.getElementById(formId).submit();
        }
        return false;
    }

    function admOpenDialog(id) {
        var d = document.getElementById(id);
        if (d && typeof d.showModal === 'function') { d.showModal(); }
        else if (d) { d.setAttribute('open', 'open'); }
    }
    function admCloseDialog(id) {
        var d = document.getElementById(id);
        if (d && typeof d.close === 'function') { d.close(); }
        else if (d) { d.removeAttribute('open'); }
    }
</script>
@stack('scripts')
</body>
</html>
