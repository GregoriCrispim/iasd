@extends('layouts.app')

@section('title', 'Santa Ceia em Casa - IASD Central de Brasília')
@section('meta-description', 'Solicite a Santa Ceia em sua casa. Informe seus dados e o melhor dia e horário para receber a visita pastoral.')
@section('og-title', 'A Ceia do Senhor em minha casa')
@section('page-name', 'Santa Ceia em Casa')

@push('styles')

<style>
    .ceia-form-page { max-width: 1000px; margin: 0 auto; padding: 40px 20px; color: #333; font-family: 'Roboto', sans-serif; }
    .ceia-form-intro { padding: 40px; background: linear-gradient(135deg, #f0f4f8, #fff); border: 1px solid #dce5ee; border-top: 4px solid #003366; border-radius: 15px; margin-bottom: 30px; text-align: center; }
    .ceia-form-intro h1 { color: #003366; font-size: clamp(2rem, 5vw, 3rem); margin-bottom: 20px; }
    .ceia-form-intro p { line-height: 1.8; margin-bottom: 16px; }
    .ceia-intro-copy { max-width: 740px; margin: 0 auto; text-align: left; }
    .ceia-form-panel { padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px #00000015; background: #fff; }
    .ceia-request-form { max-width: 700px; margin: 0 auto; }
    .ceia-request-form .ceia-field { margin: 0 0 26px; padding: 0; border: 0; }
    .ceia-request-form label, .ceia-request-form legend { font-size: 1rem; font-weight: 600; display: block; margin-bottom: 10px; }
    .ceia-request-form input[type="text"], .ceia-request-form input[type="tel"], .ceia-request-form textarea { box-sizing: border-box; width: 100%; padding: 14px; border: 2px solid #d7dce1; border-radius: 10px; background: #f8f9fa; font: inherit; color: #333; }
    .ceia-request-form textarea { min-height: 140px; resize: vertical; }
    .ceia-request-form input:focus-visible, .ceia-request-form textarea:focus-visible, .ceia-request-form button:focus-visible { outline: 3px solid #003366; outline-offset: 3px; }
    .ceia-request-form .ceia-option { display: flex; align-items: center; gap: 14px; padding: 14px; background: #f8f9fa; border: 2px solid #d7dce1; border-radius: 10px; font-weight: 400; cursor: pointer; }
    .ceia-request-form .ceia-option:has(input:checked) { border-color: #003366; background: #edf4fb; }
    .ceia-option input { flex-shrink: 0; width: 20px; height: 20px; accent-color: #003366; }
    .ceia-request-form .ceia-option span { display: inline; }
    .ceia-form-help { color: #555; font-size: .95rem; line-height: 1.6; margin-bottom: 14px; }
    .ceia-submit { width: 100%; padding: 16px; border: 0; border-radius: 10px; background: #bd4b00; color: #fff; font: inherit; font-weight: 600; cursor: pointer; }
    .ceia-submit:disabled { opacity: .7; cursor: wait; }
    .ceia-form-notice { padding: 18px; border-radius: 8px; margin-bottom: 24px; line-height: 1.6; }
    .ceia-form-notice--success { background: #d4edda; color: #155724; }
    .ceia-form-notice--error { background: #f8d7da; color: #721c24; }
    .ceia-form-back { display: inline-block; margin-top: 25px; color: #003366; }
    @media (max-width: 600px) { .ceia-form-page { padding: 24px 15px; } .ceia-form-intro, .ceia-form-panel { padding: 24px 18px; } }
</style>
@endpush

@section('content')
<img src="{{ asset('img/cards/secretaria/secretaria_header.webp') }}" alt="Secretaria da Igreja" style="width: 100%;" fetchpriority="high" decoding="async">
<div class="ceia-form-page">
    <section class="ceia-form-intro">
        <h1 class="acb-title-serif">A Ceia do Senhor em minha casa</h1>
        <div class="ceia-intro-copy">
<blockquote class="acb-quote"><p>“Porque todas as vezes que comerdes este pão e beberdes este cálice, anunciais a morte do Senhor, até que Ele venha.”</p><span class="acb-quote__ref">1 Coríntios 11:26</span></blockquote>
<p>Se você está passando por um período delicado de saúde e não pode estar conosco presencialmente, saiba que ainda assim poderá participar desse momento tão especial. Basta preencher a solicitação de Ceia em Casa, e um dos nossos pastores levará a Ceia até você com todo carinho e cuidado.</p>
<p>Prepare-se com oração e exame de consciência ao longo da semana, e participe com alegria do banquete da graça de Deus!</p>
        </div>
    </section>
    <section class="ceia-form-panel" aria-label="Solicitação de Santa Ceia em Casa">
        @if (session('success'))
            <div class="ceia-form-notice ceia-form-notice--success" role="status">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="ceia-form-notice ceia-form-notice--error" role="alert">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="ceia-form-notice ceia-form-notice--error" role="alert">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route('santa-ceia.enviar') }}" class="ceia-request-form" id="ceia-request-form">
            @csrf
            <p class="ceia-form-help">Todos os campos são obrigatórios.</p>
            <div class="ceia-field">
                <label for="nome">Nome completo</label>
                <input type="text" id="nome" name="nome" autocomplete="name" maxlength="255" value="{{ old('nome') }}" required>
            </div>
            <div class="ceia-field">
                <label for="telefone">Telefone</label>
                <input type="tel" id="telefone" name="telefone" autocomplete="tel-national" inputmode="tel" maxlength="20" placeholder="(61) 99999-9999" value="{{ old('telefone') }}" required aria-describedby="ceia-phone-help">
                <p id="ceia-phone-help" class="ceia-form-help">Informe o DDD e o número de telefone.</p>
            </div>
            <div class="ceia-field">
                <label for="endereco">Endereço</label>
                <textarea id="endereco" name="endereco" autocomplete="street-address" maxlength="1000" required>{{ old('endereco') }}</textarea>
            </div>
            <fieldset class="ceia-field">
                <legend>Você é Adventista do Sétimo Dia?</legend>
                @foreach (['Sim', 'Não'] as $resposta)
                    <label class="ceia-option">
                        <input type="radio" name="adventista" value="{{ $resposta }}" @checked(old('adventista') === $resposta) required>
                        <span>{{ $resposta }}</span>
                    </label>
                @endforeach
            </fieldset>
            <div class="ceia-field">
                <label for="disponibilidade">Melhor dia e horário para receber a Ceia?</label>
                <input type="text" id="disponibilidade" name="disponibilidade" maxlength="500" value="{{ old('disponibilidade') }}" required>
            </div>
            <button type="submit" class="ceia-submit">Enviar solicitação</button>
        </form>
        <a href="{{ route('secretaria') }}" class="ceia-form-back">Voltar para a Secretaria</a>
    </section>
</div>
@endsection

@push('scripts')

<script>
    const ceiaPhoneInput = document.getElementById('telefone');

    function formatCeiaPhone(value) {
        const digits = value.replace(/\D/g, '').slice(0, 11);
        if (!digits) return '';
        if (digits.length <= 2) return '(' + digits;

        const localNumber = digits.slice(2);
        const prefixLength = digits.length === 11 ? 5 : 4;
        const prefix = localNumber.slice(0, prefixLength);
        const suffix = localNumber.slice(prefixLength);
        return '(' + digits.slice(0, 2) + ') ' + prefix + (suffix ? '-' + suffix : '');
    }

    function applyCeiaPhoneMask() {
        const cursor = ceiaPhoneInput.selectionStart;
        const digitsBeforeCursor = ceiaPhoneInput.value.slice(0, cursor).replace(/\D/g, '').length;
        const cursorAtEnd = cursor === ceiaPhoneInput.value.length;
        const formatted = formatCeiaPhone(ceiaPhoneInput.value);
        if (ceiaPhoneInput.value === formatted) return;

        ceiaPhoneInput.value = formatted;
        if (document.activeElement === ceiaPhoneInput) {
            let nextCursor = 0;
            let digitCount = 0;
            while (nextCursor < formatted.length && digitCount < digitsBeforeCursor) {
                if (/\d/.test(formatted[nextCursor])) digitCount++;
                nextCursor++;
            }
            if (cursorAtEnd) nextCursor = formatted.length;
            ceiaPhoneInput.setSelectionRange(nextCursor, nextCursor);
        }
    }

    ceiaPhoneInput.addEventListener('input', applyCeiaPhoneMask);
    ceiaPhoneInput.addEventListener('blur', applyCeiaPhoneMask);
    applyCeiaPhoneMask();

    document.getElementById('ceia-request-form').addEventListener('submit', function () {
        applyCeiaPhoneMask();
        const button = this.querySelector('button[type="submit"]');
        button.disabled = true;
        button.textContent = 'Enviando...';
    });
    window.addEventListener('pageshow', function () {
        applyCeiaPhoneMask();
        const button = document.querySelector('#ceia-request-form button[type="submit"]');
        button.disabled = false;
        button.textContent = 'Enviar solicitação';
    });
</script>

@endpush
