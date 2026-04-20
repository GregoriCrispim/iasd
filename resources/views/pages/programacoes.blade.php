@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Programações 2026')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
<style>
    /* Estilos isolados desta view (prefixo programacoes-) */
    .programacoes-container {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: clamp(24px, 4vw, 56px) 16px;
        font-family: "Noto Sans JP", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        color: #0f172a;
    }

    .programacoes-hero {
        background: radial-gradient(1200px 500px at 20% -20%, rgba(2, 132, 199, 0.18), transparent 60%),
        radial-gradient(900px 500px at 100% 0%, rgba(30, 64, 175, 0.18), transparent 55%),
        linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 18px;
        padding: clamp(20px, 3vw, 40px);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        margin-bottom: 28px;
        overflow: hidden;
    }

    .programacoes-hero h1 {
        font-family: "Bebas Neue", "Noto Sans JP", sans-serif;
        letter-spacing: 0.5px;
        font-size: clamp(2.2rem, 4vw, 3.2rem);
        line-height: 1;
        margin: 0 0 12px 0;
        color: #0b2a4a;
    }

    .programacoes-hero p {
        font-family: "Roboto", "Noto Sans JP", sans-serif;
        margin: 0;
        max-width: 78ch;
        font-size: 1.05rem;
        line-height: 1.7;
        color: rgba(15, 23, 42, 0.85);
    }

    .programacoes-surface {
        background: #ffffff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .programacoes-calendar {
        display: block;
    }

    .programacoes-calendar-main {
        padding: 20px;
    }

    .programacoes-calendar-toolbar {
        display: flex;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .programacoes-calendar-title {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 0;
    }

    .programacoes-calendar-title-row {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .programacoes-calendar-title h2 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 800;
        color: #0b2a4a;
        letter-spacing: 0.2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .programacoes-calendar-title p {
        margin: 0;
        font-size: 0.95rem;
        color: rgba(15, 23, 42, 0.7);
    }

    .programacoes-calendar-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .programacoes-btn {
        appearance: none;
        background: #0b2a4a;
        color: #fff;
        border: 1px solid rgba(15, 23, 42, 0.12);
        border-radius: 12px;
        padding: 10px 12px;
        font-weight: 700;
        font-size: 0.95rem;
        line-height: 1;
        cursor: pointer;
        transition: transform 120ms ease, box-shadow 120ms ease, background 120ms ease;
        box-shadow: 0 10px 18px rgba(2, 6, 23, 0.15);
    }

    .programacoes-btn:hover {
        background: #083055;
        transform: translateY(-1px);
        box-shadow: 0 14px 24px rgba(2, 6, 23, 0.18);
    }

    .programacoes-btn:active {
        transform: translateY(0);
        box-shadow: 0 8px 14px rgba(2, 6, 23, 0.14);
    }

    .programacoes-btn.secondary {
        background: #fff;
        color: #0b2a4a;
        box-shadow: none;
    }

    .programacoes-btn.secondary:hover {
        background: #f8fafc;
    }

    .programacoes-btn.secondary.is-active {
        background: #0b2a4a;
        color: #fff;
        box-shadow: 0 10px 18px rgba(2, 6, 23, 0.15);
    }

    .programacoes-btn.secondary.is-active:hover {
        background: #083055;
    }

    .programacoes-select {
        border: 1px solid rgba(15, 23, 42, 0.16);
        border-radius: 12px;
        padding: 10px 12px;
        font-weight: 700;
        color: #0b2a4a;
        background: #fff;
        min-width: 175px;
    }

    .programacoes-calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 10px;
        margin-top: 12px;
    }

    /* Permite que itens do grid encolham no mobile (evita overflow “invisível”) */
    .programacoes-calendar-grid > * {
        min-width: 0;
    }

    .programacoes-weekday {
        font-size: 0.85rem;
        font-weight: 800;
        color: rgba(15, 23, 42, 0.7);
        letter-spacing: 0.4px;
        text-transform: uppercase;
        padding: 8px 10px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .programacoes-day {
        border: 1px solid rgba(15, 23, 42, 0.10);
        border-radius: 14px;
        padding: 10px;
        min-height: 110px;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        position: relative;
        cursor: pointer;
        transition: transform 120ms ease, box-shadow 120ms ease, border-color 120ms ease;
        overflow: hidden;
        min-width: 0;
    }

    .programacoes-day:hover {
        transform: translateY(-1px);
        border-color: rgba(2, 132, 199, 0.35);
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
    }

    .programacoes-day.is-outside {
        opacity: 0.72;
        background: #f1f5f9;
        cursor: default;
    }

    .programacoes-day.is-outside:hover {
        transform: none;
        box-shadow: none;
        border-color: rgba(15, 23, 42, 0.10);
    }

    .programacoes-day.is-today {
        border-color: rgba(2, 132, 199, 0.55);
        box-shadow: inset 0 0 0 1px rgba(2, 132, 199, 0.25);
    }

    .programacoes-day.is-selected {
        border-color: rgba(30, 64, 175, 0.60);
        box-shadow: inset 0 0 0 2px rgba(30, 64, 175, 0.20);
    }

    .programacoes-day.is-disabled {
        cursor: default;
        pointer-events: none;
        opacity: 0.82;
    }

    .programacoes-day.is-disabled:hover {
        transform: none;
        box-shadow: none;
        border-color: rgba(15, 23, 42, 0.10);
    }

    .programacoes-day-number {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 900;
        color: #0b2a4a;
        margin-bottom: 8px;
    }

    .programacoes-day-number small {
        font-weight: 800;
        color: rgba(15, 23, 42, 0.6);
    }

    .programacoes-chip {
        display: block;
        width: 100%;
        text-align: left;
        border: 0;
        background: var(--chip-bg, rgba(2, 132, 199, 0.10));
        color: var(--chip-fg, #0b2a4a);
        border-radius: 999px;
        padding: 6px 10px;
        font-size: 0.85rem;
        font-weight: 800;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
        margin-bottom: 6px;
        transition: background 120ms ease, transform 120ms ease, box-shadow 120ms ease;
        box-shadow: inset 0 0 0 1px var(--chip-border, rgba(2, 132, 199, 0.18));
    }

    .programacoes-chip:hover {
        background: var(--chip-bg-hover, rgba(2, 132, 199, 0.16));
        transform: translateY(-1px);
    }

    .programacoes-chip.more {
        background: rgba(30, 64, 175, 0.10);
        color: #0b2a4a;
        box-shadow: inset 0 0 0 1px rgba(30, 64, 175, 0.18);
    }

    .programacoes-agenda {
        padding: 18px 20px 22px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border-top: 0;
        scroll-margin-top: 96px;
    }

    .programacoes-agenda h3 {
        margin: 0 0 6px 0;
        font-size: 1.05rem;
        font-weight: 900;
        color: #0b2a4a;
    }

    .programacoes-agenda .subtitle {
        margin: 0 0 16px 0;
        font-size: 0.95rem;
        color: rgba(15, 23, 42, 0.7);
    }

    .programacoes-agenda-list {
        display: grid;
        gap: 10px;
    }

    .programacoes-agenda-item {
        border: 1px solid rgba(15, 23, 42, 0.10);
        border-radius: 14px;
        padding: 12px 12px;
        background: #fff;
        box-shadow: inset 4px 0 0 0 var(--event-accent, rgba(2, 132, 199, 0.45));
    }

    .programacoes-agenda-item .title {
        font-weight: 900;
        color: #0b2a4a;
        margin: 0 0 4px 0;
        line-height: 1.3;
    }

    .programacoes-agenda-item .meta {
        font-size: 0.9rem;
        color: rgba(15, 23, 42, 0.75);
        margin: 0;
        line-height: 1.5;
    }

    .programacoes-agenda-empty {
        border: 1px dashed rgba(15, 23, 42, 0.18);
        border-radius: 14px;
        padding: 14px;
        background: rgba(2, 132, 199, 0.05);
        color: rgba(15, 23, 42, 0.75);
        font-weight: 700;
    }

    .programacoes-noscript {
        margin-top: 14px;
        background: #fff7ed;
        border: 1px solid rgba(234, 88, 12, 0.25);
        color: rgba(124, 45, 18, 0.95);
        border-radius: 14px;
        padding: 12px 14px;
        font-weight: 700;
    }

    @media (max-width: 720px) {
        .programacoes-calendar-main,
        .programacoes-agenda {
            padding: 14px;
        }

        .programacoes-calendar-toolbar {
            flex-wrap: wrap;
        }

        .programacoes-calendar-actions {
            width: 100%;
            justify-content: center;
        }

        .programacoes-calendar-grid {
            gap: 6px;
        }

        .programacoes-day {
            min-height: 0;
            padding: 0;
            border-radius: 999px;
            aspect-ratio: 1 / 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .programacoes-weekday {
            padding: 6px 8px;
            font-size: 0.78rem;
            text-align: center;
        }

        .programacoes-select {
            min-width: 160px;
        }

        /* Mobile: dias como círculos com número centralizado */
        .programacoes-day-number {
            width: 100%;
            margin: 0;
            justify-content: center;
        }

        .programacoes-day-number small {
            display: none;
        }

        .programacoes-day-number span {
            font-size: 0.95rem;
            font-weight: 900;
        }

        .programacoes-chip {
            display: none;
        }

        .programacoes-day.has-events {
            background: var(--day-bg, rgba(2, 132, 199, 0.10));
            border-color: var(--day-border, rgba(2, 132, 199, 0.35));
            box-shadow: inset 0 0 0 1px rgba(2, 6, 23, 0.06);
        }

        .programacoes-day.has-events .programacoes-day-number span {
            color: var(--day-fg, #0b2a4a);
        }
    }
</style>
@endpush

@section('content')
<img class="page-header-img" src="{{ asset('img/cards/programacoes/programacoes_header.webp') }}" alt="Programações 2026" fetchpriority="high" decoding="async">

<div class="programacoes-container">

    <!-- Seção Introdutória -->
    <div class="programacoes-hero">
        <h1 class="acb-title-serif">Programações 2026</h1>
        <p>
            Confira todas as programações e eventos da IASD Central de Brasília para o ano de 2026.
            Reuniões espirituais, convenções, congressos e muito mais. Marque sua presença e cresça conosco na fé!
        </p>
    </div>

    <div class="programacoes-surface">
        <div class="programacoes-calendar" id="programacoesCalendar">
            <div class="programacoes-calendar-main">
                <div class="programacoes-calendar-toolbar">
                    <div class="programacoes-calendar-title">
                        <div class="programacoes-calendar-title-row">
                            <h2 class="acb-title-serif" id="programacoesCalendarTitle">Calendário</h2>
                            <button type="button" class="programacoes-btn secondary is-active" id="programacoesMonthAgendaToggle" aria-pressed="true">
                                Eventos do mês
                            </button>
                        </div>
                        <p id="programacoesCalendarHint">Toque/clique em um dia para ver os detalhes.</p>
                    </div>

                    <div class="programacoes-calendar-actions">
                        <button type="button" class="programacoes-btn secondary" data-action="prev" aria-label="Mês anterior">‹</button>
                        <select class="programacoes-select" id="programacoesMonthSelect" aria-label="Selecionar mês">
                            <option value="0">Janeiro</option>
                            <option value="1">Fevereiro</option>
                            <option value="2">Março</option>
                            <option value="3">Abril</option>
                            <option value="4">Maio</option>
                            <option value="5">Junho</option>
                            <option value="6">Julho</option>
                            <option value="7">Agosto</option>
                            <option value="8">Setembro</option>
                            <option value="9">Outubro</option>
                            <option value="10">Novembro</option>
                            <option value="11">Dezembro</option>
                        </select>
                        <button type="button" class="programacoes-btn secondary" data-action="next" aria-label="Próximo mês">›</button>
                        <button type="button" class="programacoes-btn" data-action="today" aria-label="Ir para o mês atual">Hoje</button>
                    </div>
                </div>

                <div class="programacoes-calendar-grid" id="programacoesCalendarGrid" role="grid" aria-labelledby="programacoesCalendarTitle"></div>

                <section class="programacoes-agenda" id="programacoesAgendaSection" aria-live="polite" style="display: none;">
                    <h3 id="programacoesAgendaTitle">Agenda</h3>
                    <p class="subtitle" id="programacoesAgendaSubtitle"></p>
                    <div class="programacoes-agenda-list" id="programacoesAgendaList"></div>
                </section>

                <noscript>
                    <div class="programacoes-noscript">
                        Para visualizar o calendário e alternar entre os meses, habilite o JavaScript no navegador.
                    </div>
                </noscript>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset('js/programacoes-events.js') }}" defer></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const YEAR = 2026;

    const MONTHS = [
        'Janeiro','Fevereiro','Março','Abril','Maio','Junho',
        'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'
    ];

    // Semana começando no domingo (Dom → Sáb)
    const WEEKDAYS = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];

    /**
     * Eventos (2026)
     * start/end no formato YYYY-MM-DD (intervalo inclusivo)
     */
    const EVENTS = (typeof PROGRAMACOES_EVENTS_2026 !== 'undefined' && Array.isArray(PROGRAMACOES_EVENTS_2026))
        ? PROGRAMACOES_EVENTS_2026
        : [];

    const calendarRoot = document.getElementById('programacoesCalendar');
    const gridEl = document.getElementById('programacoesCalendarGrid');
    const titleEl = document.getElementById('programacoesCalendarTitle');
    const monthSelectEl = document.getElementById('programacoesMonthSelect');
    const monthAgendaToggleEl = document.getElementById('programacoesMonthAgendaToggle');
    const calendarHintEl = document.getElementById('programacoesCalendarHint');
    const agendaSectionEl = document.getElementById('programacoesAgendaSection');
    const agendaTitleEl = document.getElementById('programacoesAgendaTitle');
    const agendaSubtitleEl = document.getElementById('programacoesAgendaSubtitle');
    const agendaListEl = document.getElementById('programacoesAgendaList');

    if (!calendarRoot || !gridEl || !titleEl || !monthSelectEl || !monthAgendaToggleEl || !calendarHintEl || !agendaSectionEl || !agendaTitleEl || !agendaSubtitleEl || !agendaListEl) return;

    let pendingAgendaScroll = false;

    const scrollToAgenda = () => {
        if (!agendaSectionEl || agendaSectionEl.style.display === 'none') return;
        const headerEl = document.querySelector('header');
        const headerH = headerEl ? headerEl.offsetHeight : 0;
        const extraGap = 14; // respiro extra abaixo do header fixo
        const top = agendaSectionEl.getBoundingClientRect().top + window.scrollY - headerH - extraGap;
        window.scrollTo({ top: Math.max(0, top), behavior: 'smooth' });
    };

    const toIso = (y, m1, d) => `${String(y).padStart(4,'0')}-${String(m1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
    const clampMonth = (m) => Math.max(0, Math.min(11, m));

    const monthDaysCount = (y, m0) => new Date(y, m0 + 1, 0).getDate();

    const getEventsOnDay = (isoDate) => EVENTS.filter(e => e.start <= isoDate && isoDate <= e.end);

    const getEventsInMonth = (m0) => {
        const monthStart = toIso(YEAR, m0 + 1, 1);
        const monthEnd = toIso(YEAR, m0 + 1, monthDaysCount(YEAR, m0));

        return EVENTS
            .filter(e => e.start <= monthEnd && e.end >= monthStart)
            .slice()
            .sort((a, b) => {
                if (a.start !== b.start) return a.start.localeCompare(b.start);
                if (a.end !== b.end) return a.end.localeCompare(b.end);
                return a.title.localeCompare(b.title, 'pt-BR');
            });
    };

    const formatDateLong = (isoDate) => {
        const [y, m, d] = isoDate.split('-').map(Number);
        const date = new Date(y, m - 1, d, 12, 0, 0);
        return new Intl.DateTimeFormat('pt-BR', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' }).format(date);
    };

    const formatRangeShort = (startIso, endIso) => {
        if (startIso === endIso) {
            const [y, m, d] = startIso.split('-').map(Number);
            return `${String(d).padStart(2,'0')}/${String(m).padStart(2,'0')}/${y}`;
        }
        const [ys, ms, ds] = startIso.split('-').map(Number);
        const [ye, me, de] = endIso.split('-').map(Number);
        if (ms === me && ys === ye) return `${String(ds).padStart(2,'0')}–${String(de).padStart(2,'0')}/${String(ms).padStart(2,'0')}/${ys}`;
        return `${String(ds).padStart(2,'0')}/${String(ms).padStart(2,'0')}–${String(de).padStart(2,'0')}/${String(me).padStart(2,'0')}/${ye}`;
    };

    // Cores determinísticas por evento (mesma cor em todos os dias do mesmo evento)
    const hashString = (str) => {
        // djb2
        let h = 5381;
        for (let i = 0; i < str.length; i++) h = ((h << 5) + h) ^ str.charCodeAt(i);
        return h >>> 0;
    };

    const eventKey = (e) => `${e.title}|${e.start}|${e.end}`;

    const eventColor = (e) => {
        const h = hashString(eventKey(e)) % 360;
        const bg = `hsl(${h} 85% 92%)`;
        const bgHover = `hsl(${h} 85% 88%)`;
        const border = `hsl(${h} 70% 50% / 0.40)`;
        const accent = `hsl(${h} 70% 45% / 0.70)`;
        const fg = `hsl(${h} 35% 20%)`;
        return { bg, bgHover, border, fg, accent };
    };

    const getInitialMonthFromUrl = () => {
        const url = new URL(window.location.href);
        const mes = url.searchParams.get('mes'); // YYYY-MM
        if (!mes || !/^\d{4}-\d{2}$/.test(mes)) return null;
        const [y, m] = mes.split('-').map(Number);
        if (y !== YEAR) return null;
        const m0 = m - 1;
        if (m0 < 0 || m0 > 11) return null;
        return m0;
    };

    const setMonthInUrl = (m0) => {
        const url = new URL(window.location.href);
        url.searchParams.set('mes', `${YEAR}-${String(m0 + 1).padStart(2,'0')}`);
        window.history.replaceState({}, '', url.toString());
    };

    const today = new Date();
    // Padrão: sempre iniciar no mês atual (independente do ano).
    // Se houver ?mes=YYYY-MM (e YEAR bater), ele tem prioridade.
    const defaultMonth = today.getMonth();

    let currentMonth = clampMonth(getInitialMonthFromUrl() ?? defaultMonth);
    let selectedDateIso = null;
    let isMonthAgendaActive = true;

    const hideAgenda = () => {
        agendaSectionEl.style.display = 'none';
        agendaSubtitleEl.textContent = '';
        agendaListEl.innerHTML = '';
    };

    const updateCalendarModeUi = () => {
        monthAgendaToggleEl.classList.toggle('is-active', isMonthAgendaActive);
        monthAgendaToggleEl.setAttribute('aria-pressed', String(isMonthAgendaActive));
        calendarHintEl.textContent = isMonthAgendaActive
            ? 'Visualizando todos os eventos do mês. Para escolher um dia, desative essa visualização.'
            : 'Toque/clique em um dia para ver os detalhes.';
    };

    const renderAgendaItems = (events) => {
        agendaListEl.innerHTML = '';

        for (const e of events) {
            const colors = eventColor(e);
            const item = document.createElement('div');
            item.className = 'programacoes-agenda-item';
            item.style.setProperty('--event-accent', colors.accent);
            const range = formatRangeShort(e.start, e.end);
            item.innerHTML = `
                <p class="title">${e.title}</p>
                <p class="meta"><strong>Data:</strong> ${range}${e.metaHtml ? `<br>${e.metaHtml}` : ''}</p>
            `;
            agendaListEl.appendChild(item);
        }
    };

    const renderAgenda = (isoDate, { scrollIntoView } = { scrollIntoView: false }) => {
        isMonthAgendaActive = false;
        selectedDateIso = isoDate;
        pendingAgendaScroll = Boolean(scrollIntoView);

        if (!isoDate) {
            hideAgenda();
            updateCalendarModeUi();
            return;
        }

        const events = getEventsOnDay(isoDate);
        if (events.length === 0) {
            hideAgenda();
            updateCalendarModeUi();
            return;
        }

        agendaTitleEl.textContent = 'Agenda';
        agendaSubtitleEl.textContent = formatDateLong(isoDate);
        agendaSectionEl.style.display = 'block';
        renderAgendaItems(events);
        updateCalendarModeUi();
    };

    const renderMonthAgenda = ({ scrollIntoView } = {}) => {
        isMonthAgendaActive = true;
        selectedDateIso = null;
        if (typeof scrollIntoView === 'boolean') pendingAgendaScroll = scrollIntoView;

        const events = getEventsInMonth(currentMonth);
        const totalLabel = `${events.length} ${events.length === 1 ? 'evento' : 'eventos'}`;

        agendaTitleEl.textContent = 'Eventos do mês';
        agendaSubtitleEl.textContent = `${MONTHS[currentMonth]} de ${YEAR} • ${totalLabel}`;
        agendaSectionEl.style.display = 'block';

        if (events.length === 0) {
            agendaListEl.innerHTML = '<div class="programacoes-agenda-empty">Nenhum evento cadastrado para este mês.</div>';
            updateCalendarModeUi();
            return;
        }

        renderAgendaItems(events);
        updateCalendarModeUi();
    };

    const renderCalendar = () => {
        updateCalendarModeUi();
        titleEl.textContent = `${MONTHS[currentMonth]} de ${YEAR}`;
        monthSelectEl.value = String(currentMonth);
        setMonthInUrl(currentMonth);

        gridEl.innerHTML = '';

        // Cabeçalhos dos dias da semana
        for (const wd of WEEKDAYS) {
            const el = document.createElement('div');
            el.className = 'programacoes-weekday';
            el.textContent = wd;
            gridEl.appendChild(el);
        }

        const daysInMonth = monthDaysCount(YEAR, currentMonth);
        const firstDay = new Date(YEAR, currentMonth, 1, 12, 0, 0);
        const leadingBlanks = firstDay.getDay(); // 0=Dom, 6=Sáb
        const trailingBlanks = (7 - ((leadingBlanks + daysInMonth) % 7)) % 7;

        // Renderiza apenas as linhas necessárias (4, 5 ou 6 semanas), sem “linha extra”
        const totalCells = leadingBlanks + daysInMonth + trailingBlanks;

        const isTodayIso = (() => {
            if (today.getFullYear() !== YEAR) return null;
            return toIso(YEAR, today.getMonth() + 1, today.getDate());
        })();

        const prevMonth = currentMonth === 0 ? 11 : currentMonth - 1;
        const prevMonthYear = currentMonth === 0 ? YEAR - 1 : YEAR;
        const nextMonth = currentMonth === 11 ? 0 : currentMonth + 1;
        const nextMonthYear = currentMonth === 11 ? YEAR + 1 : YEAR;
        const prevDays = monthDaysCount(prevMonthYear, prevMonth);

        for (let cell = 0; cell < totalCells; cell++) {
            const dayIndex = cell - leadingBlanks; // 0..daysInMonth-1 quando dentro do mês

            const inCurrentMonth = dayIndex >= 0 && dayIndex < daysInMonth;
            let y = YEAR;
            let m0 = currentMonth;
            let d = dayIndex + 1;

            if (!inCurrentMonth) {
                if (dayIndex < 0) {
                    y = prevMonthYear;
                    m0 = prevMonth;
                    d = prevDays + dayIndex + 1;
                } else {
                    y = nextMonthYear;
                    m0 = nextMonth;
                    d = (dayIndex - daysInMonth) + 1;
                }
            }

            const iso = toIso(y, m0 + 1, d);

            const dayEl = document.createElement('div');
            dayEl.className = 'programacoes-day';
            dayEl.setAttribute('role', 'gridcell');
            dayEl.setAttribute('data-date', iso);
            dayEl.setAttribute('aria-disabled', isMonthAgendaActive ? 'true' : 'false');

            if (!inCurrentMonth) dayEl.classList.add('is-outside');
            if (isTodayIso && iso === isTodayIso) dayEl.classList.add('is-today');
            if (selectedDateIso && iso === selectedDateIso) dayEl.classList.add('is-selected');
            if (inCurrentMonth && isMonthAgendaActive) dayEl.classList.add('is-disabled');

            const events = inCurrentMonth ? getEventsOnDay(iso) : [];

            // Mobile: se houver programação, colore o "círculo" do dia com a cor do evento
            if (inCurrentMonth && events.length > 0) {
                const h = hashString(eventKey(events[0])) % 360;
                dayEl.classList.add('has-events');
                dayEl.style.setProperty('--day-bg', `hsl(${h} 85% 92%)`);
                dayEl.style.setProperty('--day-border', `hsl(${h} 70% 45% / 0.45)`);
                dayEl.style.setProperty('--day-fg', `hsl(${h} 35% 20%)`);
            }

            const weekdaySmall = new Intl.DateTimeFormat('pt-BR', { weekday: 'short' })
                .format(new Date(y, m0, d, 12, 0, 0))
                .replace('.', '');

            dayEl.innerHTML = `
                <div class="programacoes-day-number">
                    <span>${d}</span>
                    <small>${weekdaySmall}</small>
                </div>
            `;

            // Chips de eventos (até 2 por dia)
            const maxChips = 2;
            const chips = events.slice(0, maxChips);
            for (const e of chips) {
                const colors = eventColor(e);
                const chip = document.createElement('button');
                chip.type = 'button';
                chip.className = 'programacoes-chip';
                chip.textContent = e.title;
                chip.style.setProperty('--chip-bg', colors.bg);
                chip.style.setProperty('--chip-bg-hover', colors.bgHover);
                chip.style.setProperty('--chip-border', colors.border);
                chip.style.setProperty('--chip-fg', colors.fg);
                chip.addEventListener('click', (ev) => {
                    ev.stopPropagation();
                    renderAgenda(iso, { scrollIntoView: true });
                    renderCalendar();
                });
                dayEl.appendChild(chip);
            }

            if (events.length > maxChips) {
                const more = document.createElement('button');
                more.type = 'button';
                more.className = 'programacoes-chip more';
                more.textContent = `+${events.length - maxChips} mais`;
                more.addEventListener('click', (ev) => {
                    ev.stopPropagation();
                    renderAgenda(iso, { scrollIntoView: true });
                    renderCalendar();
                });
                dayEl.appendChild(more);
            }

            if (inCurrentMonth && !isMonthAgendaActive) {
                dayEl.addEventListener('click', () => {
                    // Só exibe (e rola) quando existir programação no dia
                    const hasEvents = getEventsOnDay(iso).length > 0;
                    renderAgenda(iso, { scrollIntoView: hasEvents });
                    renderCalendar();
                });
            }

            gridEl.appendChild(dayEl);
        }

        if (isMonthAgendaActive) {
            renderMonthAgenda();
        } else if (!selectedDateIso) {
            renderAgenda(null);
        }

        if (pendingAgendaScroll) {
            pendingAgendaScroll = false;
            requestAnimationFrame(scrollToAgenda);
        }
    };

    // Eventos do toolbar
    calendarRoot.querySelectorAll('[data-action]').forEach(btn => {
        btn.addEventListener('click', () => {
            const action = btn.getAttribute('data-action');
            if (action === 'prev') currentMonth = (currentMonth === 0 ? 11 : currentMonth - 1);
            if (action === 'next') currentMonth = (currentMonth === 11 ? 0 : currentMonth + 1);
            if (action === 'today') currentMonth = clampMonth(defaultMonth);
            selectedDateIso = null;
            renderCalendar();
        });
    });

    monthSelectEl.addEventListener('change', () => {
        currentMonth = clampMonth(Number(monthSelectEl.value));
        selectedDateIso = null;
        renderCalendar();
    });

    monthAgendaToggleEl.addEventListener('click', () => {
        if (isMonthAgendaActive) {
            isMonthAgendaActive = false;
            hideAgenda();
            renderCalendar();
            return;
        }

        renderMonthAgenda({ scrollIntoView: true });
        renderCalendar();
    });

    renderCalendar();
});
</script>
@endpush
