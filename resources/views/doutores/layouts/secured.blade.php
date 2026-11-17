@php
    use App\Support\DoutoresAuth;

    /** @var \App\Models\User|null $dUser */
    $dUser = auth('doutores')->user();
    $dPerfis = $dUser ? DoutoresAuth::availablePerfis($dUser) : [];
    $dPerfil = $dUser ? (DoutoresAuth::getActivePerfil(request()) ?? DoutoresAuth::defaultPerfil($dUser)) : null;
    $dIsAdmin = $dPerfil === DoutoresAuth::PERFIL_ADMIN;
    $dIsManager = in_array($dPerfil, [DoutoresAuth::PERFIL_ADMIN, DoutoresAuth::PERFIL_COORD], true);
    $dIsVoluntario = $dPerfil === DoutoresAuth::PERFIL_VOL;
    $dPerfilLabels = [
        'administrador' => 'Administrador',
        'coordenador' => 'Coordenador',
        'voluntario' => 'Voluntário',
    ];
    $active = $activeNav ?? '';
    $portalCfg = \App\Models\Doutores\PortalConfig::query()->find('portal');
@endphp
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Doutores da Esperança') — Doutores da Esperança</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/doutores.css') }}?v={{ filemtime(public_path('css/doutores.css')) }}">
    @stack('styles')
</head>
<body class="dd">
<header class="dd-topbar">
    <div class="dd-topbar-inner">
        <button type="button" class="dd-burger" id="ddBurger" aria-label="Menu"><i class="bi bi-list"></i></button>
        <a href="{{ $dIsVoluntario ? route('doutores.area.inicio') : route('doutores.home') }}" class="dd-brand">
            <img src="{{ asset('img/doutores/logo-Doutores.png') }}" alt="Doutores da Esperança">
            <span class="dd-brand-text">Doutores Esperança<small>Voluntariado hospitalar</small></span>
        </a>

        <div class="dd-topbar-actions">
            @if ($dUser && count($dPerfis) > 1)
                <form method="POST" action="{{ route('doutores.perfil.trocar') }}" class="dd-perfil-switch">
                    @csrf
                    <label for="ddPerfilSwitch">Perfil</label>
                    <select name="perfil" id="ddPerfilSwitch" class="select" onchange="this.form.submit()">
                        @foreach ($dPerfis as $perfilOption)
                            <option value="{{ $perfilOption }}" {{ $dPerfil === $perfilOption ? 'selected' : '' }}>{{ $dPerfilLabels[$perfilOption] ?? $perfilOption }}</option>
                        @endforeach
                    </select>
                    <noscript><button type="submit" class="btn btn-secondary btn-sm">Trocar</button></noscript>
                </form>
            @endif
            @if ($dUser)
                <div class="dd-user-chip">
                    {{ $dUser->name }}
                    <small>{{ $dPerfilLabels[$dPerfil] ?? '' }}</small>
                </div>
                <form method="POST" action="{{ route('doutores.logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm"><i class="bi bi-box-arrow-right"></i> Sair</button>
                </form>
            @endif
        </div>
    </div>
    <div class="dd-stripe"></div>
</header>

<aside class="dd-sidebar" id="ddSidebar">
    <nav class="dd-nav">
        @if ($dIsVoluntario)
            <div class="dd-nav-group">Minha Área</div>
            <a href="{{ route('doutores.area.inicio') }}" class="{{ $active === 'doutores-area' ? 'active' : '' }}"><i class="bi bi-house-heart"></i> Início</a>
            <a href="{{ route('doutores.area.avisos') }}" class="{{ $active === 'doutores-area-avisos' ? 'active' : '' }}"><i class="bi bi-megaphone"></i> Avisos</a>
            <a href="{{ route('doutores.anexos.index') }}" class="{{ $active === 'doutores-anexos' ? 'active' : '' }}"><i class="bi bi-paperclip"></i> Meus Anexos</a>
            <a href="{{ route('doutores.area.termos') }}" class="{{ $active === 'doutores-area-termos' ? 'active' : '' }}"><i class="bi bi-file-earmark-check"></i> Termos</a>
            <a href="{{ route('doutores.area.plantoes') }}" class="{{ $active === 'doutores-area-plantoes' ? 'active' : '' }}"><i class="bi bi-calendar-check"></i> Inscrever-se em Plantões</a>
            <a href="{{ route('doutores.area.inscricoes') }}" class="{{ $active === 'doutores-area-inscricoes' ? 'active' : '' }}"><i class="bi bi-journal-check"></i> Minhas Inscrições</a>
            <a href="{{ route('doutores.area.testemunhos') }}" class="{{ $active === 'doutores-area-testemunhos' ? 'active' : '' }}"><i class="bi bi-chat-heart"></i> Testemunhos</a>
            <a href="{{ route('doutores.area.perfil') }}" class="{{ $active === 'doutores-area-perfil' ? 'active' : '' }}"><i class="bi bi-person-circle"></i> Meu Perfil</a>
        @elseif ($dIsManager)
            <div class="dd-nav-group">Coordenação</div>
            <a href="{{ route('doutores.home') }}" class="{{ $active === 'doutores-home' ? 'active' : '' }}"><i class="bi bi-heart-pulse"></i> Início</a>
            @if ($dIsAdmin)
                <a href="{{ route('doutores.polos.index') }}" class="{{ $active === 'doutores-polos' ? 'active' : '' }}"><i class="bi bi-geo-alt"></i> Polos</a>
            @endif
            <a href="{{ route('doutores.voluntarios.index') }}" class="{{ $active === 'doutores-voluntarios' ? 'active' : '' }}"><i class="bi bi-people"></i> Voluntários</a>
            <a href="{{ route('doutores.plantoes.index') }}" class="{{ $active === 'doutores-plantoes' ? 'active' : '' }}"><i class="bi bi-calendar-check"></i> Plantões</a>
            <a href="{{ route('doutores.avisos.index') }}" class="{{ $active === 'doutores-avisos' ? 'active' : '' }}"><i class="bi bi-megaphone"></i> Avisos</a>
            <a href="{{ route('doutores.dashboard') }}" class="{{ $active === 'doutores-dashboard' ? 'active' : '' }}"><i class="bi bi-bar-chart"></i> Dashboard</a>
            <a href="{{ route('doutores.relatorios.index') }}" class="{{ $active === 'doutores-relatorios' ? 'active' : '' }}"><i class="bi bi-file-earmark-bar-graph"></i> Relatórios</a>
            <a href="{{ route('doutores.estudos.index') }}" class="{{ $active === 'doutores-estudos' ? 'active' : '' }}"><i class="bi bi-book"></i> Estudos Bíblicos</a>
            <a href="{{ route('doutores.anexos.index') }}" class="{{ $active === 'doutores-anexos' ? 'active' : '' }}"><i class="bi bi-paperclip"></i> Documentos</a>
            @if ($dIsAdmin)
                <div class="dd-nav-group">Configuração</div>
                <a href="{{ route('doutores.dominios.index') }}" class="{{ $active === 'doutores-dominios' ? 'active' : '' }}"><i class="bi bi-list-check"></i> Domínios</a>
                <a href="{{ route('doutores.hospitais.index') }}" class="{{ $active === 'doutores-hospitais' ? 'active' : '' }}"><i class="bi bi-hospital"></i> Hospitais</a>
                <a href="{{ route('doutores.usuarios.index') }}" class="{{ $active === 'doutores-usuarios' ? 'active' : '' }}"><i class="bi bi-person-gear"></i> Usuários e perfis</a>
                <a href="{{ route('doutores.logs.index') }}" class="{{ $active === 'doutores-logs' ? 'active' : '' }}"><i class="bi bi-clock-history"></i> Logs de Segurança</a>
                <div class="dd-nav-group">Portal</div>
                <a href="{{ route('doutores.portal.edit') }}" class="{{ $active === 'doutores-portal' ? 'active' : '' }}"><i class="bi bi-window-sidebar"></i> Informações do Portal</a>
                <a href="{{ route('doutores.publicacoes.index') }}" class="{{ $active === 'doutores-publicacoes' ? 'active' : '' }}"><i class="bi bi-newspaper"></i> Publicações</a>
            @endif
        @endif

        <div class="dd-nav-group">Portal público</div>
        <a href="{{ route('doutores.public.publicacoes.index') }}" target="_blank" rel="noopener"><i class="bi bi-newspaper"></i> Publicações</a>
    </nav>
</aside>

<div class="dd-overlay" id="ddOverlay"></div>

<div class="dd-main">
    <div class="dd-page-head">
        <h1>@yield('heading', 'Doutores da Esperança')</h1>
        <div class="adm-actions">@yield('actions')</div>
    </div>

    <main class="dd-content">
        @yield('content')
    </main>

    <footer class="dd-footer">
        <span>© {{ date('Y') }} Doutores da Esperança</span>
        <span>
            @if ($portalCfg?->contato_email) {{ $portalCfg->contato_email }} @endif
            @if ($portalCfg?->contato_telefone) · {{ $portalCfg->contato_telefone }} @endif
        </span>
    </footer>
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
<script src="{{ asset('js/doutores-select.js') }}?v={{ filemtime(public_path('js/doutores-select.js')) }}" defer></script>

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
        var burger = document.getElementById('ddBurger');
        var sidebar = document.getElementById('ddSidebar');
        var overlay = document.getElementById('ddOverlay');
        function toggle() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        }
        if (burger) burger.addEventListener('click', toggle);
        if (overlay) overlay.addEventListener('click', toggle);

        // Mantém o item ativo visível após o reload (ex.: Publicações no fim da lista).
        function scrollActiveIntoView() {
            if (!sidebar) return;
            var active = sidebar.querySelector('.dd-nav a.active');
            if (!active) return;
            var sidebarRect = sidebar.getBoundingClientRect();
            var activeRect = active.getBoundingClientRect();
            var overflowBottom = activeRect.bottom - sidebarRect.bottom;
            var overflowTop = sidebarRect.top - activeRect.top;
            if (overflowBottom > 0) {
                sidebar.scrollTop += overflowBottom + 16;
            } else if (overflowTop > 0) {
                sidebar.scrollTop -= overflowTop + 16;
            }
        }
        requestAnimationFrame(scrollActiveIntoView);
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
