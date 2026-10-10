@extends('layouts.app')

@section('title', 'IASD Central de Brasília - Teste de Dons Espirituais')

@section('meta-description', 'Descubra seu dom espiritual com o teste de dons da IASD Central de Brasília. 76 afirmações, respostas rápidas e resultado na hora. Refletir sobre seu chamado ministerial.')
@section('og-title', 'Teste de Dons Espirituais - IASD Central de Brasília')
@section('og-description', 'Descubra seu dom espiritual: 76 afirmações em espírito de oração, com resultado imediato.')
@section('page-name', 'Teste de Dons Espirituais')

@push('styles')
<style>
    /* Banner em mosaico: dons em ação */
    .td-banner__grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
    }

    .td-banner__item {
        position: relative;
        margin: 0;
        overflow: hidden;
        aspect-ratio: 9 / 7;
        background: #e9ecef;
    }

    .td-banner__item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }

    .td-banner__item:hover img,
    .td-banner__item:focus-within img {
        transform: scale(1.05);
    }

    /* Degradê na base para legibilidade da legenda */
    .td-banner__item::after {
        content: '';
        position: absolute;
        inset: auto 0 0 0;
        height: 45%;
        background: linear-gradient(to top, rgba(0, 21, 49, 0.75) 0%, transparent 100%);
        pointer-events: none;
    }

    .td-banner__item figcaption {
        position: absolute;
        left: 14px;
        bottom: 12px;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #fff;
        text-shadow: 0 1px 4px rgba(0, 0, 0, 0.4);
    }

    .td-banner__item figcaption i {
        font-size: 1.05rem;
        color: #f5a623;
    }

    /* Container padrão das páginas do site */
    .td-container {
        width: 80%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 0 60px;
    }

    /* Hero de introdução (padrão .page-hero + .acb-fullbleed) */
    .td-intro {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 20px;
        padding: 45px 40px 30px;
        margin-bottom: 15px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .td-intro::before {
        content: '';
        position: absolute;
        top: -120px;
        right: -120px;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle, rgba(0, 51, 102, 0.07) 0%, transparent 70%);
        pointer-events: none;
    }

    /* Badge de chamada acima do título */
    .td-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        border-radius: 999px;
        background: rgba(211, 84, 0, 0.1);
        color: #d35400;
        font-family: 'Roboto', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 18px;
    }

    .td-kicker i {
        font-size: 1rem;
    }

    .td-intro h1 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 3em;
        color: #003366;
        margin-bottom: 25px;
        font-weight: 500;
        position: relative;
    }

    /* Pergunta de abertura vira banner em destaque */
    .td-instrucoes blockquote.cite-intro {
        background: linear-gradient(135deg, #003366 0%, #001531 100%);
        color: #fff;
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 51, 102, 0.25);
        padding: 24px 28px;
        margin: 0 auto 35px;
        max-width: 760px;
        text-align: center;
        font-family: 'Playfair Display', serif;
        font-size: 1.3rem;
        line-height: 1.6;
    }

    .td-instrucoes blockquote.cite-intro em {
        font-style: italic;
    }

    .td-instrucoes blockquote.cite-intro strong {
        color: #9dc3e6;
    }

    .td-instrucoes {
        text-align: left;
        font-family: 'Roboto', sans-serif;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
        max-width: 900px;
        margin: 0 auto 20px;
    }

    .td-instrucoes h2 {
        font-family: 'Bebas neue', sans-serif;
        font-size: 2.2em;
        color: #003366;
        margin: 35px 0 18px;
        font-weight: 500;
        text-align: left;
    }

    .td-instrucoes h2::after {
        content: '';
        display: block;
        width: 64px;
        height: 4px;
        border-radius: 999px;
        background: linear-gradient(135deg, #d35400 0%, #ba4a00 100%);
        margin-top: 8px;
    }

    /* Lista com bullet points personalizados */
    .td-instrucoes ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .td-instrucoes li {
        position: relative;
        padding: 6px 0 6px 32px;
        margin: 0;
    }

    .td-instrucoes li::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 17px;
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: linear-gradient(135deg, #d35400 0%, #ba4a00 100%);
    }

    .td-instrucoes li strong {
        color: #003366;
    }

    /* Citação (Ellen White) em destaque dentro do item */
    .td-instrucoes li q {
        display: block;
        border-left: 3px solid #d35400;
        padding: 8px 0 8px 16px;
        margin: 12px 0 4px;
        font-family: 'Playfair Display', serif;
        font-style: italic;
        color: #003366;
        quotes: '“' '”';
    }

    .td-instrucoes li cite {
        font-style: normal;
        font-size: 0.9rem;
        color: #6b7280;
        margin-left: 4px;
    }

    /* Botões (laranja de destaque, na linha do accent do Estudo Bíblico) */
    .td-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 15px 36px;
        border: none;
        border-radius: 10px;
        font-family: 'Roboto', sans-serif;
        font-size: 1.15em;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
        transition: transform 0.3s, box-shadow 0.3s, filter 0.2s;
    }

    .td-btn--primary {
        background: linear-gradient(135deg, #d35400 0%, #ba4a00 100%);
        color: #fff;
    }

    .td-btn--primary:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(211, 84, 0, 0.35);
    }

    .td-btn--secondary {
        background: #fff;
        color: #003366;
        border: 2px solid #003366;
        font-size: 1em;
        padding: 13px 30px;
    }

    .td-btn--secondary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }

    .td-btn:focus-visible {
        outline: 3px solid #f5a623;
        outline-offset: 2px;
    }

    .td-btn:disabled {
        opacity: 0.45;
        cursor: not-allowed;
    }

    /* Garante que [hidden] vença qualquer display definido por classe */
    .td-container [hidden] {
        display: none !important;
    }

    .td-actions {
        margin: 0 0 45px;
        text-align: center;
        /* abaixo do header fixo ao rolar até o botão */
        scroll-margin-top: calc(var(--header-height, 80px) + 12px);
    }

    .td-error {
        margin: 0 0 30px;
        padding: 16px 20px;
        border-radius: 12px;
        background: #fdecea;
        border: 1px solid #f5c6cb;
        color: #8a1c26;
        font-family: 'Roboto', sans-serif;
    }

    /* ---------- Quiz ---------- */
    .td-quiz {
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        padding: 32px 24px 40px;
    }

    .td-progress-info {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        gap: 10px;
        font-family: 'Roboto', sans-serif;
        color: #003366;
        margin-bottom: 10px;
    }

    .td-progress-info strong {
        font-size: 1.05rem;
    }

    .td-progress-track {
        height: 10px;
        border-radius: 999px;
        background: #e9ecef;
        overflow: hidden;
        margin-bottom: 30px;
    }

    .td-progress-fill {
        height: 100%;
        width: 0;
        background: linear-gradient(135deg, #d35400 0%, #ba4a00 100%);
        border-radius: 999px;
        transition: width 0.3s ease;
    }

    .td-question {
        border: none;
        margin: 0;
        padding: 0;
        min-width: 0;
    }

    .td-question legend {
        font-family: 'Roboto', sans-serif;
        font-size: 1.25rem;
        line-height: 1.6;
        font-weight: 600;
        color: #333;
        margin-bottom: 25px;
        padding: 0;
    }

    .td-options {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin: 0;
        padding: 0;
        border: none;
    }

    .td-option {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 14px;
        background: #f8f9fa;
        cursor: pointer;
        transition: border-color 0.2s, background-color 0.2s;
    }

    .td-option:hover {
        border-color: #d35400;
    }

    .td-option:has(input:checked) {
        border-color: #d35400;
        background: #fdf0e7;
    }

    .td-option:has(input:focus-visible) {
        outline: 3px solid #f5a623;
        outline-offset: 2px;
    }

    .td-option input {
        width: 20px;
        height: 20px;
        flex: 0 0 auto;
        accent-color: #d35400;
        cursor: pointer;
    }

    .td-option span {
        font-family: 'Roboto', sans-serif;
        font-size: 1rem;
        line-height: 1.4;
        color: #333;
    }

    .td-msg {
        min-height: 24px;
        margin-top: 16px;
        font-family: 'Roboto', sans-serif;
        font-size: 0.95rem;
        color: #8a1c26;
    }

    .td-nav {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-top: 20px;
    }

    .td-nav .td-btn {
        padding: 13px 26px;
        font-size: 1em;
    }

    @media (max-width: 768px) {
        .td-container {
            width: 90%;
        }

        .td-banner__grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .td-intro {
            padding: 35px 20px;
        }

        .td-instrucoes blockquote.cite-intro {
            padding: 20px;
            font-size: 1.15rem;
        }
    }

    @media (max-width: 480px) {
        .td-question legend {
            font-size: 1.1rem;
        }

        .td-nav {
            flex-direction: column-reverse;
        }

        .td-nav .td-btn {
            width: 100%;
        }

        .td-actions .td-btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
{{-- Banner em mosaico: pessoas exercendo dons de formas diferentes --}}
<section class="td-banner" aria-label="Pessoas exercendo seus dons na igreja">
    <div class="td-banner__grid">
        <figure class="td-banner__item">
            <img src="{{ asset('img/cards/teste_dons/banner/recepcao.webp') }}" alt="Membro acolhe visitante com um aperto de mãos na entrada da igreja" fetchpriority="high" decoding="async">
            <figcaption><i class="bi bi-door-open" aria-hidden="true"></i> Acolher</figcaption>
        </figure>
        <figure class="td-banner__item">
            <img src="{{ asset('img/cards/teste_dons/banner/cantando.webp') }}" alt="Grupo de louvor cantando com microfones no palco" decoding="async">
            <figcaption><i class="bi bi-music-note-beamed" aria-hidden="true"></i> Cantar</figcaption>
        </figure>
        <figure class="td-banner__item">
            <img src="{{ asset('img/cards/teste_dons/banner/midia.webp') }}" alt="Voluntário operando a mesa de som durante o culto" decoding="async">
            <figcaption><i class="bi bi-sliders2" aria-hidden="true"></i> Comunicar</figcaption>
        </figure>
        <figure class="td-banner__item">
            <img src="{{ asset('img/cards/teste_dons/banner/orando.webp') }}" alt="Pais oram e impõem as mãos sobre o filho à beira do batistério" decoding="async">
            <figcaption><i class="bi bi-heart-fill" aria-hidden="true"></i> Orar</figcaption>
        </figure>
    </div>
</section>

<div class="td-container">
    @if (session('error'))
        <div class="td-error" role="alert">{{ session('error') }}</div>
    @endif

    {{-- Tela inicial: hero de instruções + nota (padrão das páginas do site) --}}
    <section class="td-intro" id="td-intro">
        <p class="td-kicker"><i class="bi bi-stars" aria-hidden="true"></i> Descubra seu chamado</p>
        <h1>Teste de Dons Espirituais</h1>

        <div class="td-instrucoes">
            {!! $texts['instructions_html'] !!}
        </div>
    </section>

    <div class="td-actions">
        <button type="button" class="td-btn td-btn--primary" id="td-start">{{ $texts['labels']['start'] }}</button>
        <button type="button" class="td-btn td-btn--primary" id="td-home" hidden>Volta para a página inicial</button>
    </div>

    {{-- Tela do quiz: uma pergunta por vez --}}
    <section class="td-quiz" id="td-quiz" hidden aria-label="Questionário do teste de dons">
        <div class="td-progress-info">
            <strong id="td-progress-label" aria-live="polite"></strong>
            <span id="td-progress-percent"></span>
        </div>
        <div class="td-progress-track" role="progressbar" id="td-progress"
             aria-valuemin="0" aria-valuemax="76" aria-valuenow="0" aria-label="Progresso do teste">
            <div class="td-progress-fill" id="td-progress-fill"></div>
        </div>

        <form id="td-form" method="POST" action="{{ route('teste-de-dons.resultado') }}">
            @csrf
            <input type="hidden" name="answers_json" id="td-answers-input" value="">

            <fieldset class="td-question">
                <legend id="td-question-text"></legend>
                <div class="td-options" role="radiogroup" aria-labelledby="td-question-text" id="td-options"></div>
            </fieldset>

            <p class="td-msg" id="td-msg" role="alert" aria-live="assertive"></p>

            <div class="td-nav">
                <button type="button" class="td-btn td-btn--secondary" id="td-prev">{{ $texts['labels']['previous'] }}</button>
                <button type="button" class="td-btn td-btn--primary" id="td-next" disabled>{{ $texts['labels']['next'] }}</button>
            </div>
        </form>
    </section>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    var questions = @json($questions['questions']);
    var total = questions.length; // 76
    var answerLabels = @json($texts['answer_labels_html']);
    var messages = @json($texts['messages']);
    var labels = {
        finish: @js($texts['labels']['finish'])
    };

    var STORAGE_KEY = 'teste-dons:answers';

    var intro = document.getElementById('td-intro');
    var quiz = document.getElementById('td-quiz');
    var startBtn = document.getElementById('td-start');
    var homeLink = document.getElementById('td-home');
    var progressLabel = document.getElementById('td-progress-label');
    var progressPercent = document.getElementById('td-progress-percent');
    var progressTrack = document.getElementById('td-progress');
    var progressFill = document.getElementById('td-progress-fill');
    var questionText = document.getElementById('td-question-text');
    var optionsWrap = document.getElementById('td-options');
    var msg = document.getElementById('td-msg');
    var prevBtn = document.getElementById('td-prev');
    var nextBtn = document.getElementById('td-next');
    var form = document.getElementById('td-form');
    var answersInput = document.getElementById('td-answers-input');

    var answers = loadAnswers();
    var current = 0;

    function loadAnswers() {
        try {
            var raw = window.localStorage.getItem(STORAGE_KEY);
            if (!raw) return [];
            var parsed = JSON.parse(raw);
            if (!Array.isArray(parsed)) return [];
            return parsed.slice(0, total);
        } catch (e) {
            return [];
        }
    }

    function saveAnswers() {
        try {
            window.localStorage.setItem(STORAGE_KEY, JSON.stringify(answers));
        } catch (e) { /* sem armazenamento disponível: segue em memória */ }
    }

    function answeredCount() {
        return answers.filter(function (a) { return a !== null && a !== undefined; }).length;
    }

    function showIntro() {
        quiz.hidden = true;
        intro.hidden = false;
        startBtn.hidden = false;
        homeLink.hidden = true;
    }

    function showQuiz(atIndex) {
        intro.hidden = true;
        startBtn.hidden = true;
        homeLink.hidden = false;
        quiz.hidden = false;
        renderQuestion(atIndex);
        // Rola até o botão "Volta para a página inicial" ficar no topo.
        document.querySelector('.td-actions')
            .scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function renderQuestion(index) {
        current = index;
        var question = questions[index];

        progressLabel.textContent = 'Pergunta ' + (index + 1) + ' de ' + total;
        var answered = answeredCount();
        progressPercent.textContent = answered + ' respondidas';
        progressTrack.setAttribute('aria-valuenow', String(index + 1));
        progressFill.style.width = ((index + 1) / total * 100).toFixed(1) + '%';

        // O texto da afirmação não revela o dom avaliado.
        questionText.textContent = question.text;

        optionsWrap.innerHTML = '';
        var selected = answers[index];

        answerLabels.forEach(function (labelHtml, value) {
            var option = document.createElement('label');
            option.className = 'td-option';

            var input = document.createElement('input');
            input.type = 'radio';
            input.name = 'q' + index;
            input.value = String(value);
            input.checked = selected === value;

            input.addEventListener('change', function () {
                answers[index] = value;
                saveAnswers();
                msg.textContent = '';
                nextBtn.disabled = false;
                // foco sem rolar a tela: a página permanece parada
                nextBtn.focus({ preventScroll: true });
            });

            var span = document.createElement('span');
            // Os rótulos vêm com <br> (3 linhas); em uma única linha,
            // separamos as variações com " / ".
            span.textContent = labelHtml.replace(/<br\s*\/?>/gi, ' / ');

            option.appendChild(input);
            option.appendChild(span);
            optionsWrap.appendChild(option);
        });

        msg.textContent = '';
        prevBtn.disabled = index === 0;
        nextBtn.disabled = selected === null || selected === undefined;
        nextBtn.textContent = index === total - 1 ? labels.finish : @js($texts['labels']['next']);

        questionText.setAttribute('tabindex', '-1');
        questionText.focus({ preventScroll: true });
    }

    startBtn.addEventListener('click', function () {
        // Começa sempre do zero: sem seleções prévias na pergunta 1.
        // Durante o quiz as respostas continuam salvas no navegador.
        answers = [];
        saveAnswers();
        showQuiz(0);
    });

    homeLink.addEventListener('click', function () {
        showIntro();
    });

    prevBtn.addEventListener('click', function () {
        if (current > 0) renderQuestion(current - 1);
    });

    nextBtn.addEventListener('click', function () {
        var selected = answers[current];
        if (selected === null || selected === undefined) {
            msg.textContent = messages.no_answer;
            optionsWrap.querySelector('input') && optionsWrap.querySelector('input').focus();
            return;
        }
        if (current < total - 1) {
            renderQuestion(current + 1);
            return;
        }
        // Última pergunta: envia tudo para o servidor validar e pontuar.
        answersInput.value = JSON.stringify(answers);
        form.submit();
    });

    showIntro();
})();
</script>
@endpush
