@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Programações 2026')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@300;400;500;700&family=Noto+Sans+JP:wght@400;500;600;700&display=swap');

    /* Estilos isolados desta view (prefixo programacoes-) */
    .programacoes-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
        font-family: "Noto Sans JP", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        color: #0f172a;
    }

    .programacoes-intro {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 50px 40px;
        border-radius: 15px;
        margin-bottom: 50px;
        text-align: center;
    }

    .programacoes-intro h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .programacoes-intro p {
        font-family: 'Roboto', sans-serif;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #333;
        text-align: center;
        max-width: 900px;
        margin: 0 auto;
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

    .programacoes-weekday {
        font-size: 0.85rem;
        font-weight: 800;
        color: rgba(15, 23, 42, 0.7);
        letter-spacing: 0.4px;
        text-transform: uppercase;
        padding: 8px 10px;
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
        border-top: 1px solid rgba(15, 23, 42, 0.08);
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
    .programacoes-month-day-group {
        margin-bottom: 20px;
    }

    .programacoes-month-day-header {
        font-weight: 900;
        font-size: 0.95rem;
        color: #0b2a4a;
        margin-bottom: 10px;
        padding: 8px 12px;
        background: linear-gradient(180deg, #f0f9ff 0%, #e0f2fe 100%);
        border-radius: 10px;
        border: 1px solid rgba(2, 132, 199, 0.15);
    }
    .programacoes-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: #fff;
        border: 1px solid rgba(2, 132, 199, 0.25);
        border-radius: 10px;
        color: #0369a1;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        margin-bottom: 12px;
        transition: all 150ms ease;
    }

    .programacoes-back-btn:hover {
        background: rgba(2, 132, 199, 0.08);
        border-color: rgba(2, 132, 199, 0.45);
        transform: translateX(-2px);
    }

    .programacoes-back-btn svg {
        width: 18px;
        height: 18px;
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

        .programacoes-calendar-grid {
            gap: 8px;
        }

        .programacoes-day {
            min-height: 92px;
            padding: 8px;
            border-radius: 12px;
        }

        .programacoes-weekday {
            padding: 6px 8px;
            font-size: 0.78rem;
        }

        .programacoes-select {
            min-width: 160px;
        }
    }
</style>
@endpush

@section('content')
<img src="{{ asset('img/cards/programacoes/programacoes_header.webp') }}" alt="Programações 2026" style="width: 100%;">

<div class="programacoes-container">

    <!-- Seção Introdutória -->
    <div class="programacoes-intro">
        <h1>Programações 2026</h1>
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
                        <h2 id="programacoesCalendarTitle">Calendário</h2>
                        <p>Toque/clique em um dia para ver os detalhes.</p>
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
<script src="{{ asset('js/programacoes-events.js') }}"></script>
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
    const EVENTS = PROGRAMACOES_EVENTS_2026;

    const calendarRoot = document.getElementById('programacoesCalendar');
    const gridEl = document.getElementById('programacoesCalendarGrid');
    const titleEl = document.getElementById('programacoesCalendarTitle');
    const monthSelectEl = document.getElementById('programacoesMonthSelect');
    const agendaSectionEl = document.getElementById('programacoesAgendaSection');
    const agendaTitleEl = document.getElementById('programacoesAgendaTitle');
    const agendaSubtitleEl = document.getElementById('programacoesAgendaSubtitle');
    const agendaListEl = document.getElementById('programacoesAgendaList');

    if (!calendarRoot || !gridEl || !titleEl || !monthSelectEl || !agendaSectionEl || !agendaTitleEl || !agendaSubtitleEl || !agendaListEl) return;

    const toIso = (y, m1, d) => `${String(y).padStart(4,'0')}-${String(m1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
    const clampMonth = (m) => Math.max(0, Math.min(11, m));

    const monthDaysCount = (y, m0) => new Date(y, m0 + 1, 0).getDate();

    const getEventsOnDay = (isoDate) => EVENTS.filter(e => e.start <= isoDate && isoDate <= e.end);

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

    const hideAgenda = () => {
        agendaSectionEl.style.display = 'none';
        agendaSubtitleEl.textContent = '';
        agendaListEl.innerHTML = '';
    };

    const renderMonthAgenda = () => {
        selectedDateIso = null;
        agendaListEl.innerHTML = '';

        const daysInMonth = monthDaysCount(YEAR, currentMonth);
        const monthStart = toIso(YEAR, currentMonth + 1, 1);
        const monthEnd = toIso(YEAR, currentMonth + 1, daysInMonth);

        // Filter events for the current month
        const monthEvents = EVENTS.filter(e => {
            return e.start >= monthStart && e.start <= monthEnd;
        });

        if (monthEvents.length === 0) {
            agendaTitleEl.textContent = 'Agenda do Mês';
            agendaSubtitleEl.textContent = MONTHS[currentMonth] + ' de ' + YEAR;
            agendaSectionEl.style.display = 'block';
            agendaListEl.innerHTML = '<div class="programacoes-agenda-empty">Nenhum evento neste mês.</div>';
            return;
        }

        // Group events by date
        const eventsByDate = {};
        for (const e of monthEvents) {
            if (!eventsByDate[e.start]) {
                eventsByDate[e.start] = [];
            }
            eventsByDate[e.start].push(e);
        }

        // Sort dates
        const sortedDates = Object.keys(eventsByDate).sort();

        agendaTitleEl.textContent = 'Agenda do Mês';
        agendaSubtitleEl.textContent = MONTHS[currentMonth] + ' de ' + YEAR;
        agendaSectionEl.style.display = 'block';

        for (const date of sortedDates) {
            const dayEvents = eventsByDate[date];
            const dayGroup = document.createElement('div');
            dayGroup.className = 'programacoes-month-day-group';

            const dayHeader = document.createElement('div');
            dayHeader.className = 'programacoes-month-day-header';
            dayHeader.textContent = formatDateLong(date);
            dayGroup.appendChild(dayHeader);

            for (const e of dayEvents) {
                const colors = eventColor(e);
                const item = document.createElement('div');
                item.className = 'programacoes-agenda-item';
                item.style.setProperty('--event-accent', colors.accent);
                item.innerHTML = `
                    <p class="title">${e.title}</p>
                    ${e.metaHtml ? `<p class="meta">${e.metaHtml}</p>` : ''}
                `;
                dayGroup.appendChild(item);
            }

            agendaListEl.appendChild(dayGroup);
        }
    };

    const renderAgenda = (isoDate, { scrollIntoView } = { scrollIntoView: false }) => {
        selectedDateIso = isoDate;
        agendaListEl.innerHTML = '';

        if (!isoDate) {
            hideAgenda();
            return;
        }

        const events = getEventsOnDay(isoDate);
        if (events.length === 0) {
            hideAgenda();
            return;
        }

        agendaTitleEl.textContent = 'Agenda';
        agendaSubtitleEl.textContent = formatDateLong(isoDate);
        agendaSectionEl.style.display = 'block';

        // Add back button
        const backBtn = document.createElement('button');
        backBtn.className = 'programacoes-back-btn';
        backBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg> Ver agenda do mês';
        backBtn.addEventListener('click', () => {
            selectedDateIso = null;
            renderMonthAgenda();
            renderCalendar();
        });
        agendaListEl.appendChild(backBtn);

        for (const e of events) {
            const colors = eventColor(e);
            const item = document.createElement('div');
            item.className = 'programacoes-agenda-item';
            item.style.setProperty('--event-accent', colors.accent);
            const range = formatRangeShort(e.start, e.end);
            item.innerHTML = `
                <p class="title">${e.title}</p>
                ${e.metaHtml ? `<p class="meta">${e.metaHtml}<\/p>` : ''}
            `;
            agendaListEl.appendChild(item);
        }

        if (scrollIntoView) {
            // Garante que o título "Agenda" fique visível após o scroll (evita ficar sob header fixo).
            agendaTitleEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

    const renderCalendar = () => {
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

            if (!inCurrentMonth) dayEl.classList.add('is-outside');
            if (isTodayIso && iso === isTodayIso) dayEl.classList.add('is-today');
            if (selectedDateIso && iso === selectedDateIso) dayEl.classList.add('is-selected');

            const events = inCurrentMonth ? getEventsOnDay(iso) : [];

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

            if (inCurrentMonth) {
                dayEl.addEventListener('click', () => {
                    // Só exibe (e rola) quando existir programação no dia
                    const hasEvents = getEventsOnDay(iso).length > 0;
                    renderAgenda(iso, { scrollIntoView: hasEvents });
                    renderCalendar();
                });
            }

            gridEl.appendChild(dayEl);
        }

        if (!selectedDateIso) renderMonthAgenda();
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

    renderCalendar();
});
</script>
@endpush
