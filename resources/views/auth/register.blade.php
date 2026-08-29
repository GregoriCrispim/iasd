@extends('layouts.app')

@section('title', 'Criar conta — IASD Central de Brasília')
@section('meta-description', 'Cadastro de membros da IASD Central de Brasília para acesso à galeria de fotos.')
@section('page-name', 'Criar conta')

@push('styles')
<style>
    .auth-wrap {
        max-width: 620px;
        margin: 0 auto;
        padding: clamp(24px, 5vw, 64px) 16px;
    }
    .auth-card {
        background: #fff;
        border: 1px solid rgba(0, 51, 102, 0.12);
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        padding: clamp(24px, 4vw, 40px);
    }
    .auth-card h1 {
        font-family: "Bebas Neue", "Noto Sans JP", sans-serif;
        color: #003366;
        font-size: clamp(2rem, 4vw, 2.6rem);
        margin: 0 0 0.35rem;
        letter-spacing: 1px;
    }
    .auth-card p.auth-sub {
        color: #555;
        margin: 0 0 1.75rem;
        font-size: 0.95rem;
        line-height: 1.45;
    }
    .auth-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.15rem 1rem;
    }
    .auth-field {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        min-width: 0;
    }
    .auth-field.full { grid-column: 1 / -1; }
    .auth-field label {
        display: block;
        font-size: 0.85rem;
        color: #003366;
        font-weight: 600;
        line-height: 1.3;
    }
    .auth-field label .req {
        display: inline;
        color: #a12622;
        margin-left: 0.15rem;
        align-items: unset;
        justify-content: unset;
    }
    .auth-field input {
        width: 100%;
        padding: 0.75rem 0.95rem;
        border: 1px solid rgba(0, 51, 102, 0.25);
        border-radius: 10px;
        font-size: 0.95rem;
        font-family: "Roboto", sans-serif;
        background: #fff;
        color: #222;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        box-sizing: border-box;
    }
    .auth-field input::placeholder { color: #9aa3ad; }
    .auth-field input:focus {
        outline: none;
        border-color: #003366;
        box-shadow: 0 0 0 3px rgba(0, 51, 102, 0.18);
    }
    .auth-field input.is-invalid {
        border-color: #c0392b;
        box-shadow: 0 0 0 3px rgba(192, 57, 43, 0.12);
    }
    .auth-field input.is-valid {
        border-color: #2e7d4f;
    }

    .auth-select {
        position: relative;
    }
    .auth-select__trigger {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.75rem 0.95rem;
        border: 1px solid rgba(0, 51, 102, 0.25);
        border-radius: 10px;
        font-size: 0.95rem;
        font-family: "Roboto", sans-serif;
        background: #fff;
        color: #222;
        cursor: pointer;
        text-align: left;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        box-sizing: border-box;
    }
    .auth-select__trigger:focus,
    .auth-select__trigger:focus-visible,
    .auth-select.is-open .auth-select__trigger {
        outline: none;
        border-color: #003366;
        box-shadow: 0 0 0 3px rgba(0, 51, 102, 0.18);
    }
    .auth-select__trigger.is-invalid {
        border-color: #c0392b;
        box-shadow: 0 0 0 3px rgba(192, 57, 43, 0.12);
    }
    .auth-select__trigger.is-valid {
        border-color: #2e7d4f;
    }
    .auth-select__value {
        flex: 1;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .auth-select__value.is-placeholder { color: #9aa3ad; }
    .auth-select__chevron {
        flex-shrink: 0;
        color: #5a6b7d;
        font-size: 0.95rem;
        transition: transform 0.15s ease;
    }
    .auth-select.is-open .auth-select__chevron {
        transform: rotate(180deg);
    }
    .auth-select__list {
        position: absolute;
        z-index: 20;
        left: 0;
        right: 0;
        top: calc(100% + 6px);
        margin: 0;
        padding: 0.35rem;
        list-style: none;
        background: #fff;
        border: 1px solid rgba(0, 51, 102, 0.18);
        border-radius: 12px;
        box-shadow: 0 12px 32px rgba(0, 30, 60, 0.14);
        max-height: 240px;
        overflow-y: auto;
        display: none;
    }
    .auth-select.is-open .auth-select__list { display: block; }
    .auth-select__option {
        display: block;
        width: 100%;
        padding: 0.7rem 0.85rem;
        border: none;
        border-radius: 8px;
        background: transparent;
        color: #222;
        font-size: 0.95rem;
        font-family: "Roboto", sans-serif;
        text-align: left;
        cursor: pointer;
    }
    .auth-select__option:hover,
    .auth-select__option:focus-visible {
        background: rgba(0, 51, 102, 0.08);
        color: #003366;
        outline: none;
    }
    .auth-select__option[aria-selected="true"] {
        background: rgba(0, 51, 102, 0.12);
        color: #003366;
        font-weight: 600;
    }

    .auth-field .auth-error {
        color: #a12622;
        font-size: 0.78rem;
        line-height: 1.35;
        margin: 0;
        display: none;
    }
    .auth-field.has-error .auth-error { display: block; }

    .auth-password-wrap {
        position: relative;
        display: block;
    }
    .auth-password-wrap.has-value input {
        padding-right: 2.85rem;
    }
    .auth-password-toggle {
        position: absolute;
        right: 0.55rem;
        top: 50%;
        transform: translateY(-50%);
        display: none;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        padding: 0;
        border: none;
        background: transparent;
        color: #5a6b7d;
        cursor: pointer;
        border-radius: 6px;
        line-height: 1;
    }
    .auth-password-wrap.has-value .auth-password-toggle {
        display: inline-flex;
    }
    .auth-password-toggle:hover,
    .auth-password-toggle:focus-visible {
        color: #003366;
        background: rgba(0, 51, 102, 0.08);
        outline: none;
    }
    .auth-password-toggle i { font-size: 1.1rem; pointer-events: none; }

    .auth-checks {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
        margin: 1.35rem 0 0;
    }
    .auth-check {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        font-size: 0.88rem;
        color: #444;
        line-height: 1.45;
        margin: 0;
        cursor: pointer;
    }
    .auth-check input {
        margin-top: 0.2rem;
        flex-shrink: 0;
        width: 1rem;
        height: 1rem;
        accent-color: #003366;
    }
    /* Sobrescreve o span{display:flex} global do footer.css */
    .auth-check > span {
        display: block;
        flex: 1;
        min-width: 0;
        text-align: left;
        align-items: unset;
        justify-content: unset;
    }
    .auth-check .req {
        display: inline;
        color: #a12622;
    }
    .auth-check a {
        display: inline;
        color: #003366;
        font-weight: 600;
        text-decoration: underline;
        text-underline-offset: 2px;
        white-space: nowrap;
    }
    .auth-check.is-invalid-check {
        color: #a12622;
    }

    #guardianBlock {
        display: none;
        background: #f4f8fc;
        border: 1px solid rgba(0, 51, 102, 0.12);
        border-radius: 10px;
        padding: 0.9rem 1rem;
    }
    #guardianBlock.is-visible { display: block; }

    .auth-btn {
        width: 100%;
        border: none;
        border-radius: 10px;
        background: #003366;
        color: #fff;
        padding: 0.9rem 1rem;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        margin-top: 1.35rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: background 0.15s ease;
    }
    .auth-btn:hover { background: #002244; }
    .auth-btn:disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }

    .auth-foot {
        text-align: center;
        margin-top: 1.35rem;
        font-size: 0.9rem;
        color: #555;
    }
    .auth-foot a {
        color: #003366;
        font-weight: 600;
        text-decoration: none;
    }
    .auth-foot a:hover { text-decoration: underline; }

    .auth-alert {
        background: #fdecea;
        color: #a12622;
        border: 1px solid #f5c6c3;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        margin-bottom: 1.35rem;
    }
    .auth-alert ul { margin: 0; padding-left: 1.1rem; }

    @media (max-width: 560px) {
        .auth-grid { grid-template-columns: 1fr; gap: 1rem; }
    }
</style>
@endpush

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <h1>Criar conta</h1>
        <p class="auth-sub">Crie sua conta para acessar a galeria de fotos da igreja.</p>

        @if (session('error'))
            <div class="auth-alert" role="alert">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="auth-alert" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="registerForm" method="POST" action="{{ route('member.register.post') }}" novalidate>
            @csrf
            @if (request('redirect'))
                <input type="hidden" name="redirect" value="{{ request('redirect') }}">
            @endif
            <div class="auth-grid">
                <div class="auth-field full" data-field="name">
                    <label for="name">Nome completo<span class="req" aria-hidden="true">*</span></label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autocomplete="name"
                        maxlength="120"
                        minlength="3"
                        placeholder="Seu nome completo"
                        aria-describedby="name_error"
                    >
                    <p class="auth-error" id="name_error" role="alert"></p>
                </div>

                <div class="auth-field" data-field="email">
                    <label for="email">E-mail<span class="req" aria-hidden="true">*</span></label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        maxlength="190"
                        placeholder="seu@email.com"
                        aria-describedby="email_error"
                    >
                    <p class="auth-error" id="email_error" role="alert"></p>
                </div>

                <div class="auth-field" data-field="phone">
                    <label for="phone">Telefone<span class="req" aria-hidden="true">*</span></label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        required
                        autocomplete="tel"
                        maxlength="15"
                        inputmode="numeric"
                        placeholder="(61) 90000-0000"
                        aria-describedby="phone_error"
                    >
                    <p class="auth-error" id="phone_error" role="alert"></p>
                </div>

                <div class="auth-field" data-field="password">
                    <label for="password">Senha<span class="req" aria-hidden="true">*</span></label>
                    <div class="auth-password-wrap">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            minlength="8"
                            maxlength="72"
                            placeholder="Mínimo 8 caracteres"
                            aria-describedby="password_error"
                        >
                        <button type="button" class="auth-password-toggle" data-target="password" aria-label="Mostrar senha" title="Mostrar senha">
                            <i class="bi bi-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    <p class="auth-error" id="password_error" role="alert"></p>
                </div>

                <div class="auth-field" data-field="password_confirmation">
                    <label for="password_confirmation">Confirmar senha<span class="req" aria-hidden="true">*</span></label>
                    <div class="auth-password-wrap">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            minlength="8"
                            maxlength="72"
                            placeholder="Repita a senha"
                            aria-describedby="password_confirmation_error"
                        >
                        <button type="button" class="auth-password-toggle" data-target="password_confirmation" aria-label="Mostrar senha" title="Mostrar senha">
                            <i class="bi bi-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    <p class="auth-error" id="password_confirmation_error" role="alert"></p>
                </div>

                <div class="auth-field" data-field="birth_date">
                    <label for="birth_date">Data de nascimento<span class="req" aria-hidden="true">*</span></label>
                    <input
                        type="date"
                        id="birth_date"
                        name="birth_date"
                        value="{{ old('birth_date') }}"
                        required
                        aria-describedby="birth_date_error"
                    >
                    <p class="auth-error" id="birth_date_error" role="alert"></p>
                </div>

                <div class="auth-field" data-field="congregation">
                    <label id="congregation_label">Congregação / vínculo<span class="req" aria-hidden="true">*</span></label>
                    @php
                        $membershipLinks = \App\Models\User::MEMBERSHIP_LINKS;
                        $selectedLink = old('congregation');
                        $selectedLabel = is_string($selectedLink) ? ($membershipLinks[$selectedLink] ?? null) : null;
                    @endphp
                    <div class="auth-select" data-auth-select>
                        <input
                            type="hidden"
                            id="congregation"
                            name="congregation"
                            value="{{ $selectedLink }}"
                            required
                            aria-required="true"
                        >
                        <button
                            type="button"
                            class="auth-select__trigger"
                            id="congregation_trigger"
                            aria-haspopup="listbox"
                            aria-expanded="false"
                            aria-controls="congregation_list"
                            aria-labelledby="congregation_label congregation_value"
                            aria-describedby="congregation_error"
                        >
                            <span
                                id="congregation_value"
                                class="auth-select__value{{ $selectedLabel ? '' : ' is-placeholder' }}"
                            >{{ $selectedLabel ?: 'Selecione seu vínculo' }}</span>
                            <i class="bi bi-chevron-down auth-select__chevron" aria-hidden="true"></i>
                        </button>
                        <ul class="auth-select__list" id="congregation_list" role="listbox" aria-labelledby="congregation_label" hidden>
                            @foreach ($membershipLinks as $value => $label)
                                <li role="presentation">
                                    <button
                                        type="button"
                                        class="auth-select__option"
                                        role="option"
                                        data-value="{{ $value }}"
                                        aria-selected="{{ $selectedLink === $value ? 'true' : 'false' }}"
                                    >{{ $label }}</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <p class="auth-error" id="congregation_error" role="alert"></p>
                </div>
            </div>

            <div class="auth-checks">
                <div id="guardianBlock">
                    <label class="auth-check" id="guardianCheckLabel">
                        <input type="checkbox" name="guardian_consent" id="guardian_consent" value="1" {{ old('guardian_consent') ? 'checked' : '' }}>
                        <span>Declaro que sou o responsável legal por este cadastro de menor de 18 anos e autorizo o tratamento dos dados, incluindo o uso da busca facial em nome do menor.</span>
                    </label>
                    <p class="auth-error" id="guardian_consent_error" role="alert" style="margin-top:0.5rem;"></p>
                </div>

                <label class="auth-check" id="acceptTermsLabel" data-field="accept_terms">
                    <input type="checkbox" name="accept_terms" id="accept_terms" value="1" required>
                    <span>Li e aceito a <a href="{{ route('privacidade') }}" target="_blank" rel="noopener">Política de Privacidade</a> e autorizo o tratamento dos meus dados pessoais.<span class="req" aria-hidden="true">*</span></span>
                </label>
                <p class="auth-error" id="accept_terms_error" role="alert"></p>
            </div>

            <button type="submit" class="auth-btn"><i class="bi bi-person-plus"></i> Criar conta</button>
        </form>

        <div class="auth-foot">
            Já tem conta? <a href="{{ route('member.login', request()->only('redirect')) }}">Entrar</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var form = document.getElementById('registerForm');
    if (!form) return;

    var nameEl = document.getElementById('name');
    var email = document.getElementById('email');
    var phone = document.getElementById('phone');
    var password = document.getElementById('password');
    var passwordConfirm = document.getElementById('password_confirmation');
    var birth = document.getElementById('birth_date');
    var congregation = document.getElementById('congregation');
    var guardianBlock = document.getElementById('guardianBlock');
    var guardianConsent = document.getElementById('guardian_consent');
    var acceptTerms = document.getElementById('accept_terms');
    var acceptTermsLabel = document.getElementById('acceptTermsLabel');

    var today = new Date();
    var maxBirth = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 1);
    var minBirth = new Date(today.getFullYear() - 120, today.getMonth(), today.getDate());
    birth.max = formatDateInput(maxBirth);
    birth.min = formatDateInput(minBirth);

    function formatDateInput(d) {
        var m = String(d.getMonth() + 1).padStart(2, '0');
        var day = String(d.getDate()).padStart(2, '0');
        return d.getFullYear() + '-' + m + '-' + day;
    }

    function ageFromBirth(value) {
        if (!value) return null;
        var d = new Date(value + 'T00:00:00');
        if (isNaN(d.getTime())) return null;
        var now = new Date();
        var age = now.getFullYear() - d.getFullYear();
        var m = now.getMonth() - d.getMonth();
        if (m < 0 || (m === 0 && now.getDate() < d.getDate())) age -= 1;
        return age;
    }

    function isMinor() {
        var age = ageFromBirth(birth.value);
        return age !== null && age < 18;
    }

    function setFieldError(fieldName, message) {
        var wrap = form.querySelector('[data-field="' + fieldName + '"]');
        var input = document.getElementById(fieldName);
        var err = document.getElementById(fieldName + '_error');
        var trigger = fieldName === 'congregation'
            ? document.getElementById('congregation_trigger')
            : null;
        if (wrap) wrap.classList.toggle('has-error', !!message);
        if (input) {
            input.classList.toggle('is-invalid', !!message);
            if (!message && input.value) input.classList.add('is-valid');
            else input.classList.remove('is-valid');
            input.setAttribute('aria-invalid', message ? 'true' : 'false');
        }
        if (trigger) {
            trigger.classList.toggle('is-invalid', !!message);
            if (!message && input && input.value) trigger.classList.add('is-valid');
            else trigger.classList.remove('is-valid');
            trigger.setAttribute('aria-invalid', message ? 'true' : 'false');
        }
        if (err) {
            err.textContent = message || '';
            err.style.display = message ? 'block' : 'none';
        }
    }

    function maskPhone(value) {
        var digits = String(value || '').replace(/\D/g, '').slice(0, 11);
        if (digits.length === 0) return '';
        if (digits.length <= 2) return '(' + digits;
        if (digits.length <= 6) return '(' + digits.slice(0, 2) + ') ' + digits.slice(2);
        if (digits.length <= 10) {
            return '(' + digits.slice(0, 2) + ') ' + digits.slice(2, 6) + '-' + digits.slice(6);
        }
        return '(' + digits.slice(0, 2) + ') ' + digits.slice(2, 7) + '-' + digits.slice(7);
    }

    function applyMask(input, maskFn) {
        var start = input.selectionStart;
        var before = input.value;
        var after = maskFn(before);
        input.value = after;
        if (document.activeElement === input && typeof start === 'number') {
            var diff = after.length - before.length;
            var pos = Math.max(0, Math.min(after.length, start + diff));
            input.setSelectionRange(pos, pos);
        }
    }

    phone.addEventListener('input', function () {
        applyMask(phone, maskPhone);
        if (phone.dataset.touched) validatePhone();
    });

    // Prefill masks
    if (phone.value) phone.value = maskPhone(phone.value);

    nameEl.addEventListener('input', function () {
        if (nameEl.value.length > 120) nameEl.value = nameEl.value.slice(0, 120);
        if (nameEl.dataset.touched) validateName();
    });
    email.addEventListener('input', function () {
        if (email.dataset.touched) validateEmail();
    });
    password.addEventListener('input', function () {
        if (password.dataset.touched) validatePassword();
        if (passwordConfirm.dataset.touched) validatePasswordConfirm();
    });
    passwordConfirm.addEventListener('input', function () {
        if (passwordConfirm.dataset.touched) validatePasswordConfirm();
    });
    birth.addEventListener('change', function () {
        syncGuardian();
        if (birth.dataset.touched) validateBirth();
    });
    birth.addEventListener('input', function () {
        syncGuardian();
        if (birth.dataset.touched) validateBirth();
    });
    acceptTerms.addEventListener('change', function () {
        if (acceptTerms.dataset.touched) validateAcceptTerms();
    });
    guardianConsent.addEventListener('change', function () {
        if (guardianConsent.dataset.touched) validateGuardian();
    });

    function markTouched(el) { el.dataset.touched = '1'; }

    (function initAuthSelect() {
        var root = form.querySelector('[data-auth-select]');
        if (!root || !congregation) return;

        var trigger = document.getElementById('congregation_trigger');
        var list = document.getElementById('congregation_list');
        var valueEl = document.getElementById('congregation_value');
        var options = root.querySelectorAll('.auth-select__option');

        function closeSelect() {
            root.classList.remove('is-open');
            if (list) list.hidden = true;
            if (trigger) trigger.setAttribute('aria-expanded', 'false');
        }

        function openSelect() {
            root.classList.add('is-open');
            if (list) list.hidden = false;
            if (trigger) trigger.setAttribute('aria-expanded', 'true');
        }

        function selectOption(option) {
            var value = option.getAttribute('data-value') || '';
            var label = option.textContent.trim();
            congregation.value = value;
            if (valueEl) {
                valueEl.textContent = label;
                valueEl.classList.remove('is-placeholder');
            }
            options.forEach(function (opt) {
                opt.setAttribute('aria-selected', opt === option ? 'true' : 'false');
            });
            markTouched(congregation);
            validateCongregation();
            closeSelect();
        }

        if (trigger) {
            trigger.addEventListener('click', function () {
                if (root.classList.contains('is-open')) closeSelect();
                else openSelect();
            });
        }

        options.forEach(function (option) {
            option.addEventListener('click', function () {
                selectOption(option);
            });
        });

        document.addEventListener('click', function (e) {
            if (!root.contains(e.target)) closeSelect();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeSelect();
        });

        if (congregation.value) {
            validateCongregation();
        }
    })();

    [nameEl, email, phone, password, passwordConfirm, birth].forEach(function (el) {
        el.addEventListener('blur', function () {
            markTouched(el);
            validateField(el.id);
        });
    });

    function validateCongregation() {
        var v = congregation.value.trim();
        if (!v) return setFieldError('congregation', 'Selecione seu vínculo com a igreja.'), false;
        return setFieldError('congregation', ''), true;
    }

    function validateName() {
        var v = nameEl.value.trim();
        if (!v) return setFieldError('name', 'Informe seu nome completo.'), false;
        if (v.length < 3) return setFieldError('name', 'Nome deve ter pelo menos 3 caracteres.'), false;
        if (v.length > 120) return setFieldError('name', 'Nome pode ter no máximo 120 caracteres.'), false;
        if (!/^[A-Za-zÀ-ÿ'’.\-\s]+$/.test(v)) {
            return setFieldError('name', 'Use apenas letras, espaços e acentos.'), false;
        }
        return setFieldError('name', ''), true;
    }

    function validateEmail() {
        var v = email.value.trim();
        if (!v) return setFieldError('email', 'Informe seu e-mail.'), false;
        if (v.length > 190) return setFieldError('email', 'E-mail pode ter no máximo 190 caracteres.'), false;
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v)) {
            return setFieldError('email', 'Informe um e-mail válido.'), false;
        }
        return setFieldError('email', ''), true;
    }

    function validatePhone() {
        var digits = phone.value.replace(/\D/g, '');
        if (!digits) return setFieldError('phone', 'Informe seu telefone.'), false;
        if (digits.length < 10 || digits.length > 11) {
            return setFieldError('phone', 'Telefone incompleto. Use DDD + número.'), false;
        }
        return setFieldError('phone', ''), true;
    }

    function validatePassword() {
        var v = password.value;
        if (!v) return setFieldError('password', 'Informe uma senha.'), false;
        if (v.length < 8) return setFieldError('password', 'A senha deve ter no mínimo 8 caracteres.'), false;
        if (v.length > 72) return setFieldError('password', 'A senha pode ter no máximo 72 caracteres.'), false;
        if (!/[A-Za-zÀ-ÿ]/.test(v) || !/[0-9]/.test(v)) {
            return setFieldError('password', 'A senha deve conter letras e números.'), false;
        }
        return setFieldError('password', ''), true;
    }

    function validatePasswordConfirm() {
        var v = passwordConfirm.value;
        if (!v) return setFieldError('password_confirmation', 'Confirme sua senha.'), false;
        if (v !== password.value) {
            return setFieldError('password_confirmation', 'As senhas não coincidem.'), false;
        }
        return setFieldError('password_confirmation', ''), true;
    }

    function validateBirth() {
        var v = birth.value;
        if (!v) return setFieldError('birth_date', 'Informe a data de nascimento.'), false;
        var d = new Date(v + 'T00:00:00');
        if (isNaN(d.getTime())) return setFieldError('birth_date', 'Data inválida.'), false;
        if (d >= new Date(formatDateInput(today) + 'T00:00:00')) {
            return setFieldError('birth_date', 'A data deve ser anterior a hoje.'), false;
        }
        if (d < minBirth) return setFieldError('birth_date', 'Data de nascimento inválida.'), false;
        return setFieldError('birth_date', ''), true;
    }

    function validateGuardian() {
        var err = document.getElementById('guardian_consent_error');
        var label = document.getElementById('guardianCheckLabel');
        if (!isMinor()) {
            if (err) { err.textContent = ''; err.style.display = 'none'; }
            if (label) label.classList.remove('is-invalid-check');
            return true;
        }
        if (!guardianConsent.checked) {
            if (err) {
                err.textContent = 'Para menores de 18 anos, o consentimento do responsável é obrigatório.';
                err.style.display = 'block';
            }
            if (label) label.classList.add('is-invalid-check');
            return false;
        }
        if (err) { err.textContent = ''; err.style.display = 'none'; }
        if (label) label.classList.remove('is-invalid-check');
        return true;
    }

    function validateAcceptTerms() {
        var err = document.getElementById('accept_terms_error');
        if (!acceptTerms.checked) {
            if (err) {
                err.textContent = 'É necessário aceitar a Política de Privacidade.';
                err.style.display = 'block';
            }
            if (acceptTermsLabel) acceptTermsLabel.classList.add('is-invalid-check');
            return false;
        }
        if (err) { err.textContent = ''; err.style.display = 'none'; }
        if (acceptTermsLabel) acceptTermsLabel.classList.remove('is-invalid-check');
        return true;
    }

    function validateField(id) {
        switch (id) {
            case 'name': return validateName();
            case 'email': return validateEmail();
            case 'phone': return validatePhone();
            case 'password': return validatePassword();
            case 'password_confirmation': return validatePasswordConfirm();
            case 'birth_date': return validateBirth();
            case 'congregation': return validateCongregation();
            default: return true;
        }
    }

    function syncGuardian() {
        if (!guardianBlock) return;
        var show = isMinor();
        guardianBlock.classList.toggle('is-visible', show);
        if (!show) {
            guardianConsent.checked = false;
            validateGuardian();
        }
    }

    // Password visibility toggles — só aparecem após digitar; começam fechados (type=password)
    form.querySelectorAll('.auth-password-wrap').forEach(function (wrap) {
        var input = wrap.querySelector('input');
        var btn = wrap.querySelector('.auth-password-toggle');
        if (!input || !btn) return;

        function setHidden() {
            input.type = 'password';
            var icon = btn.querySelector('i');
            if (icon) {
                icon.classList.add('bi-eye');
                icon.classList.remove('bi-eye-slash');
            }
            btn.setAttribute('aria-label', 'Mostrar senha');
            btn.setAttribute('title', 'Mostrar senha');
        }

        function syncToggleVisibility() {
            var hasValue = input.value.length > 0;
            wrap.classList.toggle('has-value', hasValue);
            if (!hasValue) setHidden();
        }

        input.addEventListener('input', syncToggleVisibility);
        syncToggleVisibility();

        btn.addEventListener('click', function () {
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            var icon = btn.querySelector('i');
            if (icon) {
                icon.classList.toggle('bi-eye', !show);
                icon.classList.toggle('bi-eye-slash', show);
            }
            btn.setAttribute('aria-label', show ? 'Ocultar senha' : 'Mostrar senha');
            btn.setAttribute('title', show ? 'Ocultar senha' : 'Mostrar senha');
        });
    });

    form.addEventListener('submit', function (e) {
        markTouched(nameEl);
        markTouched(email);
        markTouched(phone);
        markTouched(password);
        markTouched(passwordConfirm);
        markTouched(birth);
        markTouched(congregation);
        acceptTerms.dataset.touched = '1';
        guardianConsent.dataset.touched = '1';

        var ok = true;
        ok = validateName() && ok;
        ok = validateEmail() && ok;
        ok = validatePhone() && ok;
        ok = validatePassword() && ok;
        ok = validatePasswordConfirm() && ok;
        ok = validateBirth() && ok;
        ok = validateCongregation() && ok;
        ok = validateGuardian() && ok;
        ok = validateAcceptTerms() && ok;

        if (!ok) {
            e.preventDefault();
            var firstInvalid = form.querySelector('.is-invalid, .is-invalid-check');
            if (firstInvalid) {
                var focusEl = firstInvalid.matches('input') ? firstInvalid : firstInvalid.querySelector('input') || firstInvalid;
                if (focusEl && focusEl.focus) focusEl.focus();
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    syncGuardian();
})();
</script>
@endpush
