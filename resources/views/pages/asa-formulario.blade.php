@extends('layouts.app')

@section('title', 'Cursos da ASA - IASD Central de Brasília')
@section('meta-description', 'Inscreva-se nos cursos presenciais da ASA em Brasília. Aprenda, desenvolva seus talentos e participe!')
@section('og-title', 'Cursos da ASA')
@section('page-name', 'Cursos da ASA')

@push('styles')
<style>
    .asa-form-page { max-width: 1000px; margin: 0 auto; padding: 40px 20px; color: #333; font-family: 'Roboto', sans-serif; }
    .asa-form-intro { padding: 40px; background: linear-gradient(135deg, #f0f4f8, #fff); border: 1px solid #dce5ee; border-top: 4px solid #003366; border-radius: 15px; margin-bottom: 30px; text-align: center; }
    .asa-form-intro h1 { color: #003366; font-size: clamp(2rem, 5vw, 3rem); margin-bottom: 20px; }
    .asa-form-intro h2 { color: #003366; font: 700 clamp(1.15rem, 3vw, 1.4rem)/1.5 'Roboto', sans-serif; margin: 0 0 24px; }
    .asa-form-intro p { line-height: 1.8; margin-bottom: 16px; }
    .asa-intro-copy { max-width: 740px; margin: 0 auto; text-align: left; }
    .asa-form-intro .asa-intro-presencial { margin: 24px 0; padding: 16px 20px; background: #fff4df; border-left: 4px solid #bd4b00; border-radius: 8px; color: #743500; }
    .asa-form-intro .asa-intro-address { margin-bottom: 0; padding-top: 20px; border-top: 1px solid #dce5ee; color: #46576a; font-size: .95rem; }
    .asa-form-panel { padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px #00000015; background: #fff; }
    .asa-course-form { max-width: 700px; margin: 0 auto; }
    .asa-course-form .asa-field { margin: 0 0 26px; padding: 0; border: 0; }
    .asa-course-form label, .asa-course-form legend { font-size: 1rem; font-weight: 600; display: block; margin-bottom: 10px; }
    .asa-course-form input[type="text"], .asa-course-form input[type="tel"], .asa-course-form textarea { box-sizing: border-box; width: 100%; padding: 14px; border: 2px solid #d7dce1; border-radius: 10px; background: #f8f9fa; font: inherit; color: #333; }
    .asa-course-form textarea { min-height: 140px; resize: vertical; }
    .asa-course-form input:focus-visible, .asa-course-form textarea:focus-visible, .asa-course-form button:focus-visible { outline: 3px solid #003366; outline-offset: 3px; }
    .asa-course-form .asa-option { display: flex; align-items: center; gap: 14px; padding: 14px; background: #f8f9fa; border: 2px solid #d7dce1; border-radius: 10px; font-weight: 400; cursor: pointer; }
    .asa-course-form .asa-option:has(input:checked) { border-color: #003366; background: #edf4fb; }
    .asa-option input { flex-shrink: 0; width: 20px; height: 20px; accent-color: #003366; }
    .asa-course-form .asa-option span { display: inline; }
    .asa-form-help { color: #555; font-size: .95rem; line-height: 1.6; margin-bottom: 14px; }
    .asa-submit { width: 100%; padding: 16px; border: 0; border-radius: 10px; background: #bd4b00; color: #fff; font: inherit; font-weight: 600; cursor: pointer; }
    .asa-submit:disabled { opacity: .7; cursor: wait; }
    .asa-form-notice { padding: 18px; border-radius: 8px; margin-bottom: 24px; line-height: 1.6; }
    .asa-form-notice--success { background: #d4edda; color: #155724; }
    .asa-form-notice--error { background: #f8d7da; color: #721c24; }
    .asa-form-back { display: inline-block; margin-top: 25px; color: #003366; }
    @media (max-width: 600px) { .asa-form-page { padding: 24px 15px; } .asa-form-intro, .asa-form-panel { padding: 24px 18px; } }
</style>
@endpush

@section('content')
<img src="{{ asset('img/cards/asa/asa_header.webp') }}" alt="Ação Solidária Adventista" style="width: 100%;" fetchpriority="high" decoding="async">
<div class="asa-form-page">
    <section class="asa-form-intro">
        <h1 class="acb-title-serif">Cursos da ASA</h1>
        <h2>✨ Descubra o prazer de aprender com os cursos da ASA! ✨</h2>
        <div class="asa-intro-copy">
            <p>Você já pensou em transformar seu tempo livre em momentos criativos e produtivos? A ASA tem o curso ideal para você! São cursos incríveis que vão despertar seus talentos e renovar sua rotina e até abrir portas para uma possível renda extra!</p>
            <p>Aproveite essa oportunidade para aprender, crescer e se reinventar. Seu talento pode ir muito além do que você imagina!</p>
            <p class="asa-intro-presencial"><strong>Atenção! Os cursos são presenciais e realizados em Brasília.</strong></p>
            <p class="asa-intro-address">Igreja Adventista do Sétimo Dia Central de Brasília - SGAS 611 Avenida L2 Sul módulo 75, Brasília - DF</p>
        </div>
    </section>
    <section class="asa-form-panel" aria-label="Inscrição nos cursos da ASA">
        @if (session('success'))
            <div class="asa-form-notice asa-form-notice--success" role="status">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="asa-form-notice asa-form-notice--error" role="alert">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="asa-form-notice asa-form-notice--error" role="alert">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route('asa.enviar') }}" class="asa-course-form" id="asa-course-form">
            @csrf
            <p class="asa-form-help">Todos os campos são obrigatórios.</p>
            <div class="asa-field">
                <label for="nome">Nome completo</label>
                <input type="text" id="nome" name="nome" autocomplete="name" maxlength="255" value="{{ old('nome') }}" required>
            </div>
            <div class="asa-field">
                <label for="telefone">Telefone com DDD</label>
                <input type="tel" id="telefone" name="telefone" autocomplete="tel-national" inputmode="tel" maxlength="20" placeholder="(61) 99999-9999" value="{{ old('telefone') }}" required aria-describedby="asa-phone-help">
                <p id="asa-phone-help" class="asa-form-help">Informe o DDD e o número de telefone.</p>
            </div>
            <fieldset class="asa-field">
                <legend>Qual curso deseja fazer?</legend>
                @foreach ($courses as $course)
                    <label class="asa-option">
                        <input type="radio" name="curso" value="{{ $course }}" @checked(old('curso') === $course) required>
                        <span>{{ $course }}</span>
                    </label>
                @endforeach
            </fieldset>
            <div class="asa-field">
                <label for="mensagem">Ficou com alguma dúvida ou gostaria de deixar uma sugestão? Use o espaço abaixo.</label>
                <p class="asa-form-help" id="asa-message-help">Se escolheu outros cursos, informe quais aqui. Se não tiver dúvidas ou sugestões, escreva “Sem dúvidas”.</p>
                <textarea id="mensagem" name="mensagem" maxlength="3000" required aria-describedby="asa-message-help">{{ old('mensagem') }}</textarea>
            </div>
            <button type="submit" class="asa-submit">Enviar inscrição</button>
        </form>
        <a href="{{ route('asa') }}" class="asa-form-back">Voltar para a página da ASA</a>
    </section>
</div>
@endsection

@push('scripts')
<script>
    const asaPhoneInput = document.getElementById('telefone');

    function formatAsaPhone(value) {
        const digits = value.replace(/\D/g, '').slice(0, 11);
        if (!digits) return '';
        if (digits.length <= 2) return '(' + digits;

        const localNumber = digits.slice(2);
        const prefixLength = digits.length === 11 ? 5 : 4;
        const prefix = localNumber.slice(0, prefixLength);
        const suffix = localNumber.slice(prefixLength);
        return '(' + digits.slice(0, 2) + ') ' + prefix + (suffix ? '-' + suffix : '');
    }

    function applyAsaPhoneMask() {
        const cursor = asaPhoneInput.selectionStart;
        const digitsBeforeCursor = asaPhoneInput.value.slice(0, cursor).replace(/\D/g, '').length;
        const cursorAtEnd = cursor === asaPhoneInput.value.length;
        const formatted = formatAsaPhone(asaPhoneInput.value);
        if (asaPhoneInput.value === formatted) return;

        asaPhoneInput.value = formatted;
        if (document.activeElement === asaPhoneInput) {
            let nextCursor = 0;
            let digitCount = 0;
            while (nextCursor < formatted.length && digitCount < digitsBeforeCursor) {
                if (/\d/.test(formatted[nextCursor])) digitCount++;
                nextCursor++;
            }
            if (cursorAtEnd) nextCursor = formatted.length;
            asaPhoneInput.setSelectionRange(nextCursor, nextCursor);
        }
    }

    asaPhoneInput.addEventListener('input', applyAsaPhoneMask);
    asaPhoneInput.addEventListener('blur', applyAsaPhoneMask);
    applyAsaPhoneMask();

    document.getElementById('asa-course-form').addEventListener('submit', function () {
        applyAsaPhoneMask();
        const button = this.querySelector('button[type="submit"]');
        button.disabled = true;
        button.textContent = 'Enviando...';
    });
    window.addEventListener('pageshow', function () {
        applyAsaPhoneMask();
        const button = document.querySelector('#asa-course-form button[type="submit"]');
        button.disabled = false;
        button.textContent = 'Enviar inscrição';
    });
</script>
@endpush
