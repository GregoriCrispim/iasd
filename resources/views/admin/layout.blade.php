@php
    /** @var \App\Models\User|null $authUser */
    $authUser = auth('admin')->user();
    $isSuper = $authUser && $authUser->isSuperAdmin();
    $isManager = $authUser && $authUser->isManager();
    $isFotoLider = $authUser && $authUser->isFotografiaLider();
    $canGaleria = $authUser && $authUser->canManageGaleria();
    $canManageUsers = $isSuper || $isManager || $isFotoLider;
    $isCmsUser = $authUser && $authUser->hasAnyRoleName(['super_admin', 'manager', 'collaborator']);
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
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
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

            @if ($isCmsUser)
                <div class="adm-nav-group">CMS</div>
                <a href="{{ route('admin.revisions.index') }}" class="{{ $active === 'revisions' ? 'active' : '' }}">
                    <i class="bi bi-pencil-square"></i> Revisões
                </a>
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
            @endif

            @if ($canGaleria)
                <div class="adm-nav-group">Mídias</div>
                <a href="{{ route('admin.galeria.index') }}" class="{{ $active === 'galeria' ? 'active' : '' }}">
                    <i class="bi bi-images"></i> Galeria
                </a>
            @endif

            @if ($canManageUsers)
                <div class="adm-nav-group">Gestão</div>
                <a href="{{ route('admin.users.index') }}" class="{{ $active === 'users' ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Usuários
                </a>
            @endif

            @if ($isSuper)
                <a href="{{ route('admin.invites.index') }}" class="{{ $active === 'invites' ? 'active' : '' }}">
                    <i class="bi bi-ticket-perforated"></i> Convites de membros
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
                        @elseif ($authUser->isFotografiaLider()) Líder de Fotografia
                        @elseif ($authUser->isFotografiaColaborador()) Colaborador de Fotografia
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
            @yield('content')
        </main>
    </div>
</div>

<div class="adm-toast-stack" id="admToastStack" aria-live="polite" aria-relevant="additions"></div>

@php
    $admToasts = [];
    if (session('success')) {
        $admToasts[] = ['type' => 'success', 'message' => session('success')];
    }
    if (session('error')) {
        $admToasts[] = ['type' => 'error', 'message' => session('error')];
    }
    if ($errors->any() && !isset($hideGlobalErrors)) {
        foreach ($errors->all() as $error) {
            $admToasts[] = ['type' => 'error', 'message' => $error];
        }
    }
@endphp
<script type="application/json" id="admFlashToasts">@json($admToasts)</script>

<dialog id="adm-confirm-modal" class="adm-dialog adm-dialog--confirm" aria-labelledby="adm-confirm-title">
    <form method="dialog" id="adm-confirm-form">
        <div class="adm-confirm-head">
            <div>
                <h3 id="adm-confirm-title">Confirmar remoção</h3>
                <p id="adm-confirm-message">Tem certeza?</p>
            </div>
            <button type="button" class="adm-album-modal-close" onclick="admCloseDialog('adm-confirm-modal')" aria-label="Fechar">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="adm-confirm-foot">
            <button type="button" class="btn btn-secondary" onclick="admCloseDialog('adm-confirm-modal')">Cancelar</button>
            <button type="submit" class="btn btn-danger" id="adm-confirm-submit"><i class="bi bi-trash"></i> Remover</button>
        </div>
    </form>
</dialog>

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

    var admConfirmPendingForm = null;
    var admConfirmPendingCallback = null;

    function admConfirm(message, target, options) {
        options = options || {};
        admConfirmPendingForm = null;
        admConfirmPendingCallback = null;

        if (typeof target === 'function') {
            admConfirmPendingCallback = target;
        } else {
            var form = typeof target === 'string' ? document.getElementById(target) : target;
            if (!form) return false;
            admConfirmPendingForm = form;
        }

        document.getElementById('adm-confirm-title').textContent = options.title || 'Confirmar remoção';
        document.getElementById('adm-confirm-message').textContent = message || 'Tem certeza?';
        document.getElementById('adm-confirm-submit').innerHTML =
            '<i class="bi bi-' + (options.confirmIcon || 'trash') + '"></i> ' + (options.confirmLabel || 'Remover');
        admOpenDialog('adm-confirm-modal');
        return false;
    }

    document.getElementById('adm-confirm-form').addEventListener('submit', function (e) {
        e.preventDefault();
        var form = admConfirmPendingForm;
        var callback = admConfirmPendingCallback;
        admConfirmPendingForm = null;
        admConfirmPendingCallback = null;
        admCloseDialog('adm-confirm-modal');
        if (callback) { callback(); }
        else if (form) { form.submit(); }
    });

    function admOpenDialog(id) {
        var d = document.getElementById(id);
        if (d && typeof d.showModal === 'function') { d.showModal(); }
        else if (d) { d.setAttribute('open', 'open'); }
    }
    function admCloseDialog(id) {
        var d = document.getElementById(id);
        if (d && typeof d.close === 'function') { d.close(); }
        else if (d) { d.removeAttribute('open'); }
        if (id === 'adm-confirm-modal') {
            admConfirmPendingForm = null;
            admConfirmPendingCallback = null;
        }
    }
    document.addEventListener('click', function (e) {
        var d = e.target;
        if (d && d.tagName === 'DIALOG' && d.classList.contains('adm-dialog') && typeof d.close === 'function') {
            d.close();
        }
    });

    (function () {
        var stack = document.getElementById('admToastStack');
        if (!stack) return;

        var ICONS = {
            success: 'bi-check-circle-fill',
            error: 'bi-exclamation-triangle-fill',
            info: 'bi-info-circle-fill',
            warning: 'bi-exclamation-circle-fill'
        };

        function escapeHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        window.admToast = function (message, type, options) {
            if (!message) return;
            options = options || {};
            type = type || 'success';
            if (!ICONS[type]) type = 'info';

            var toast = document.createElement('div');
            toast.className = 'adm-toast adm-toast--' + type;
            toast.setAttribute('role', type === 'error' ? 'alert' : 'status');
            toast.innerHTML =
                '<div class="adm-toast__icon" aria-hidden="true"><i class="bi ' + ICONS[type] + '"></i></div>' +
                '<div class="adm-toast__text">' + escapeHtml(message) + '</div>' +
                '<button type="button" class="adm-toast__close" aria-label="Fechar"><i class="bi bi-x-lg"></i></button>';

            stack.appendChild(toast);
            requestAnimationFrame(function () {
                toast.classList.add('is-visible');
            });

            var duration = options.duration != null ? options.duration : (type === 'error' ? 8000 : 5000);
            var hideTimer = null;

            function hide() {
                if (hideTimer) clearTimeout(hideTimer);
                toast.classList.remove('is-visible');
                toast.classList.add('is-hiding');
                window.setTimeout(function () {
                    if (toast.parentNode) toast.parentNode.removeChild(toast);
                }, 280);
            }

            toast.querySelector('.adm-toast__close').addEventListener('click', hide);
            if (duration > 0) {
                hideTimer = window.setTimeout(hide, duration);
            }

            return { hide: hide, el: toast };
        };

        try {
            var flashEl = document.getElementById('admFlashToasts');
            var flashes = flashEl ? JSON.parse(flashEl.textContent || '[]') : [];
            flashes.forEach(function (item, i) {
                window.setTimeout(function () {
                    window.admToast(item.message, item.type || 'success');
                }, i * 120);
            });
        } catch (e) {}
    })();
</script>
@stack('scripts')
</body>
</html>
