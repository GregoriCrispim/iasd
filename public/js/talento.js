(() => {
  const MAX = 3;
  const form = document.getElementById('volForm');
  if (!form) return;

  const searchInput = document.getElementById('volSearch');
  const ministryList = document.getElementById('volMinistryList');
  const emptyFilter = document.getElementById('volEmptyFilter');
  const choicesContainer = document.getElementById('volChoicesInputs');
  const submitBtn = document.getElementById('volSubmit');
  const submitZone = document.getElementById('volSubmitZone');
  const doneMsg = document.getElementById('volDoneMsg');
  const submitHint = document.getElementById('volSubmitHint');
  const cards = Array.from(document.querySelectorAll('.vol-ministry'));

  /** @type {Map<string, {slug: string, name: string, modality: string}>} */
  const selections = new Map();
  let previousCount = 0;

  function onlyDigits(value) {
    return String(value || '').replace(/\D+/g, '');
  }

  function formatBrPhone(value) {
    const digits = onlyDigits(value).slice(0, 11);
    if (!digits) return '';

    const ddd = digits.slice(0, 2);
    const rest = digits.slice(2);

    if (digits.length <= 10) {
      const p1 = rest.slice(0, 4);
      const p2 = rest.slice(4, 8);
      if (!rest) return `(${ddd}`;
      if (rest.length <= 4) return `(${ddd}) ${p1}`;
      return `(${ddd}) ${p1}-${p2}`;
    }

    const p1 = rest.slice(0, 5);
    const p2 = rest.slice(5, 9);
    if (!rest) return `(${ddd}`;
    if (rest.length <= 5) return `(${ddd}) ${p1}`;
    return `(${ddd}) ${p1}-${p2}`;
  }

  function applyPhoneMask() {
    const phones = form.querySelectorAll('input[data-mask="br-phone"]');
    phones.forEach((input) => {
      const handler = () => {
        const next = formatBrPhone(input.value);
        if (input.value !== next) input.value = next;
      };
      handler();
      input.addEventListener('input', handler);
      input.addEventListener('blur', handler);
      input.addEventListener('paste', () => setTimeout(handler, 0));
    });
  }

  const NAME_PARTICLES = new Set([
    'a', 'as', 'à', 'às',
    'o', 'os',
    'de', 'da', 'das', 'do', 'dos',
    'e', 'y',
    'em', 'na', 'nas', 'no', 'nos',
    'para', 'por',
    'di', 'du', 'del', 'della', 'van', 'von',
  ]);

  function capitalizeWord(word) {
    const apostrophe = word.match(/^([\p{L}]+)(['’])([\p{L}]+)$/u);
    if (apostrophe) {
      return (
        apostrophe[1].charAt(0).toLocaleUpperCase('pt-BR') +
        apostrophe[1].slice(1) +
        apostrophe[2] +
        apostrophe[3].charAt(0).toLocaleUpperCase('pt-BR') +
        apostrophe[3].slice(1)
      );
    }

    return word.charAt(0).toLocaleUpperCase('pt-BR') + word.slice(1);
  }

  function formatPersonName(value) {
    const normalized = String(value || '').replace(/\s+/g, ' ').trim();
    if (!normalized) return '';

    const lower = normalized.toLocaleLowerCase('pt-BR');
    const parts = lower.split(/(\s+|-+)/);
    let isFirstWord = true;
    let result = '';

    parts.forEach((part) => {
      if (/^\s+$/.test(part) || part === '-') {
        result += part;
        return;
      }
      if (!part) return;

      if (!isFirstWord && NAME_PARTICLES.has(part)) {
        result += part;
      } else {
        result += capitalizeWord(part);
      }
      isFirstWord = false;
    });

    return result;
  }

  function applyNameFormat() {
    const input = form.querySelector('#name');
    if (!input) return;

    const apply = () => {
      const next = formatPersonName(input.value);
      if (input.value !== next) input.value = next;
    };

    input.addEventListener('blur', apply);
    input.addEventListener('change', apply);
    // Formata ao colar e após pausa curta na digitação (camel/title case de nomes).
    let typingTimer = 0;
    input.addEventListener('input', () => {
      window.clearTimeout(typingTimer);
      typingTimer = window.setTimeout(apply, 500);
    });
    input.addEventListener('paste', () => setTimeout(apply, 0));
  }

  /* —— Field validation (format + uniqueness for email/phone) —— */
  const VALIDATE_IDLE_MS = 450;
  const UNIQUE_FIELDS = new Set(['email', 'phone']);
  const checkUrl = form.dataset.checkUrl || '';
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  const validatedFields = Array.from(form.querySelectorAll('[data-validate]'));

  /** @type {Map<HTMLElement, number>} */
  const validateTimers = new Map();
  /** @type {Map<string, AbortController>} */
  const uniqueControllers = new Map();
  /** @type {Map<string, string>} last value confirmed available */
  const uniqueAvailableCache = new Map();
  /** @type {Map<string, string>} last value confirmed taken */
  const uniqueTakenCache = new Map();

  function getErrorEl(input) {
    const key = input.dataset.validate || input.name;
    return form.querySelector(`[data-error-for="${key}"]`);
  }

  function clearFieldError(input) {
    input.classList.remove('is-invalid');
    input.removeAttribute('aria-invalid');
    const errorEl = getErrorEl(input);
    if (errorEl) {
      errorEl.textContent = '';
      errorEl.hidden = true;
    }
  }

  function showFieldError(input, message) {
    input.classList.add('is-invalid');
    input.setAttribute('aria-invalid', 'true');
    const errorEl = getErrorEl(input);
    if (errorEl) {
      errorEl.textContent = message;
      errorEl.hidden = false;
    }
  }

  function normalizeEmail(value) {
    return String(value || '').trim().toLowerCase();
  }

  function normalizePhoneValue(value) {
    return onlyDigits(value).slice(0, 11);
  }

  function uniqueKey(input) {
    return input.dataset.validate || input.name;
  }

  function canonicalUniqueValue(input) {
    const type = uniqueKey(input);
    if (type === 'email') return normalizeEmail(input.value);
    if (type === 'phone') return normalizePhoneValue(input.value);
    return String(input.value || '').trim();
  }

  function invalidateUniqueState(input) {
    const key = uniqueKey(input);
    uniqueAvailableCache.delete(key);
    uniqueTakenCache.delete(key);
    const controller = uniqueControllers.get(key);
    if (controller) {
      controller.abort();
      uniqueControllers.delete(key);
    }
  }

  function validateName(value) {
    const trimmed = value.trim();
    if (!trimmed) return 'Informe seu nome completo.';
    if (trimmed.length < 3) return 'Digite seu nome completo.';
    if (!/[\p{L}]/u.test(trimmed)) return 'Informe um nome válido.';
    return null;
  }

  function validatePhone(value) {
    const digits = onlyDigits(value);
    if (!digits) return 'Informe seu WhatsApp.';
    if (digits.length < 10 || digits.length > 11) {
      return 'Informe um WhatsApp válido com DDD.';
    }
    return null;
  }

  function validateEmail(value) {
    const trimmed = value.trim();
    if (!trimmed) return 'Informe seu e-mail.';
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(trimmed)) {
      return 'Informe um e-mail válido.';
    }
    return null;
  }

  function getFormatError(input) {
    const type = input.dataset.validate;
    const value = input.value || '';
    if (type === 'name') return validateName(value);
    if (type === 'phone') return validatePhone(value);
    if (type === 'email') return validateEmail(value);
    return null;
  }

  async function checkUniqueness(input) {
    const key = uniqueKey(input);
    if (!UNIQUE_FIELDS.has(key) || !checkUrl) return true;

    const formatError = getFormatError(input);
    if (formatError) return false;

    const value = canonicalUniqueValue(input);
    if (!value) return false;

    if (uniqueAvailableCache.get(key) === value) {
      clearFieldError(input);
      return true;
    }
    if (uniqueTakenCache.get(key) === value) {
      showFieldError(
        input,
        key === 'email'
          ? 'Este e-mail já foi usado em uma inscrição.'
          : 'Este WhatsApp já foi usado em uma inscrição.'
      );
      return false;
    }

    const previous = uniqueControllers.get(key);
    if (previous) previous.abort();

    const controller = new AbortController();
    uniqueControllers.set(key, controller);

    try {
      const body = new URLSearchParams();
      body.set('field', key);
      body.set('value', input.value || '');

      const response = await fetch(checkUrl, {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'X-Requested-With': 'XMLHttpRequest',
          'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
        },
        body: body.toString(),
        signal: controller.signal,
        credentials: 'same-origin',
      });

      if (!response.ok) {
        return true; // não bloqueia o usuário por falha transitória; o backend valida no envio
      }

      const data = await response.json();
      if (canonicalUniqueValue(input) !== value) {
        return true; // valor mudou enquanto a request rodava
      }

      if (data.valid === false) {
        showFieldError(input, data.message || 'Valor inválido.');
        return false;
      }

      if (data.available === false) {
        uniqueTakenCache.set(key, value);
        uniqueAvailableCache.delete(key);
        showFieldError(
          input,
          data.message ||
            (key === 'email'
              ? 'Este e-mail já foi usado em uma inscrição.'
              : 'Este WhatsApp já foi usado em uma inscrição.')
        );
        return false;
      }

      uniqueAvailableCache.set(key, value);
      uniqueTakenCache.delete(key);
      clearFieldError(input);
      return true;
    } catch (error) {
      if (error && error.name === 'AbortError') return true;
      return true;
    } finally {
      if (uniqueControllers.get(key) === controller) {
        uniqueControllers.delete(key);
      }
    }
  }

  let returnToSubmitPending = false;
  let returnToSubmitTimer = 0;
  let submitting = false;

  function scrollToSubmitZone() {
    if (!submitZone) return;
    submitZone.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  async function areAllPendenciesResolved() {
    for (const input of validatedFields) {
      if (getFormatError(input)) return false;

      const key = uniqueKey(input);
      if (UNIQUE_FIELDS.has(key)) {
        const value = canonicalUniqueValue(input);
        if (!value) return false;
        if (uniqueTakenCache.get(key) === value) return false;
        if (uniqueAvailableCache.get(key) !== value) {
          const uniqueOk = await checkUniqueness(input);
          if (!uniqueOk) return false;
        }
      } else if (input.classList.contains('is-invalid')) {
        return false;
      }
    }

    return selections.size >= 1 && selections.size <= MAX;
  }

  function maybeReturnToSubmit() {
    if (!returnToSubmitPending || submitting) return;

    window.clearTimeout(returnToSubmitTimer);
    returnToSubmitTimer = window.setTimeout(async () => {
      if (!returnToSubmitPending || submitting) return;
      if (!(await areAllPendenciesResolved())) return;

      returnToSubmitPending = false;
      scrollToSubmitZone();
    }, 180);
  }

  async function runFieldValidation(input, { checkUnique = true } = {}) {
    const formatError = getFormatError(input);
    if (formatError) {
      showFieldError(input, formatError);
      return false;
    }

    clearFieldError(input);

    let ok = true;
    if (checkUnique && UNIQUE_FIELDS.has(uniqueKey(input))) {
      ok = await checkUniqueness(input);
    }

    if (ok) maybeReturnToSubmit();
    return ok;
  }

  function scheduleFieldValidation(input) {
    const prev = validateTimers.get(input);
    if (prev) window.clearTimeout(prev);
    const timer = window.setTimeout(() => {
      validateTimers.delete(input);
      runFieldValidation(input);
    }, VALIDATE_IDLE_MS);
    validateTimers.set(input, timer);
  }

  function cancelScheduledValidation(input) {
    const prev = validateTimers.get(input);
    if (prev) {
      window.clearTimeout(prev);
      validateTimers.delete(input);
    }
  }

  function bindFieldValidation(input) {
    input.addEventListener('input', () => {
      invalidateUniqueState(input);
      clearFieldError(input);
      scheduleFieldValidation(input);
    });

    input.addEventListener('blur', () => {
      cancelScheduledValidation(input);
      runFieldValidation(input);
    });
  }

  async function validateAllFields() {
    const nameInput = form.querySelector('#name');
    if (nameInput) {
      nameInput.value = formatPersonName(nameInput.value);
    }

    let ok = true;
    let firstInvalid = null;

    for (const input of validatedFields) {
      cancelScheduledValidation(input);
      const valid = await runFieldValidation(input, { checkUnique: true });
      if (!valid) {
        ok = false;
        if (!firstInvalid) firstInvalid = input;
      }
    }

    return { ok, firstInvalid };
  }

  validatedFields.forEach(bindFieldValidation);

  function liderancaSlug() {
    for (const item of selections.values()) {
      if (item.modality === 'lideranca') return item.slug;
    }
    return null;
  }

  function ministryAllowsLideranca(slug) {
    const card = cards.find((item) => item.dataset.slug === slug);
    return !card || card.dataset.allowsLideranca !== '0';
  }

  function setModality(slug, name, modality) {
    const existing = selections.get(slug);
    const isSame = existing && existing.modality === modality;

    if (isSame) {
      selections.delete(slug);
    } else {
      // Ministérios sem liderança (ex.: Ancionato) só aceitam equipe.
      if (modality === 'lideranca' && !ministryAllowsLideranca(slug)) {
        return;
      }

      // No máximo 1 liderança no total (as demais devem ser equipe).
      if (modality === 'lideranca') {
        const currentLideranca = liderancaSlug();
        if (currentLideranca && currentLideranca !== slug) {
          return;
        }
      }

      if (existing) {
        selections.set(slug, { slug, name, modality });
      } else if (selections.size >= MAX) {
        return;
      } else {
        selections.set(slug, { slug, name, modality });
      }
    }

    render();
  }

  function renderCards() {
    const atLimit = selections.size >= MAX;
    const currentLideranca = liderancaSlug();

    cards.forEach((card) => {
      const slug = card.dataset.slug;
      const selected = selections.get(slug);
      const buttons = card.querySelectorAll('.vol-mod-btn');

      card.classList.toggle('is-selected', Boolean(selected));
      card.classList.toggle('is-locked', atLimit && !selected);

      buttons.forEach((btn) => {
        const active = selected && selected.modality === btn.dataset.modality;
        const liderancaBlocked =
          btn.dataset.modality === 'lideranca' &&
          Boolean(currentLideranca) &&
          currentLideranca !== slug;

        btn.classList.toggle('is-active', Boolean(active));
        btn.classList.toggle('is-disabled', liderancaBlocked);
        btn.disabled = liderancaBlocked;
        btn.setAttribute('aria-pressed', active ? 'true' : 'false');
        btn.setAttribute('aria-disabled', liderancaBlocked ? 'true' : 'false');
      });
    });
  }

  function renderSubmitZone() {
    const count = selections.size;
    const complete = count >= MAX;

    submitBtn.disabled = count < 1;
    submitZone.classList.toggle('is-complete', complete);
    doneMsg.hidden = !complete;

    if (complete) {
      submitHint.textContent = 'Tudo certo — pode enviar.';
      submitHint.classList.add('is-ready');
    } else if (count === 0) {
      submitHint.textContent = 'Escolha pelo menos 1 ministério para continuar.';
      submitHint.classList.remove('is-ready');
    } else {
      const left = MAX - count;
      submitHint.textContent =
        left === 1
          ? 'Você ainda pode escolher mais 1, se quiser.'
          : `Você ainda pode escolher mais ${left}, se quiser.`;
      submitHint.classList.remove('is-ready');
    }

    if (complete && previousCount < MAX) {
      requestAnimationFrame(() => {
        submitZone.scrollIntoView({ behavior: 'smooth', block: 'center' });
      });
    }

    previousCount = count;
  }

  function renderHiddenInputs() {
    choicesContainer.innerHTML = '';
    let i = 0;
    selections.forEach((item) => {
      const ministry = document.createElement('input');
      ministry.type = 'hidden';
      ministry.name = `choices[${i}][ministry]`;
      ministry.value = item.slug;

      const modality = document.createElement('input');
      modality.type = 'hidden';
      modality.name = `choices[${i}][modality]`;
      modality.value = item.modality;

      choicesContainer.appendChild(ministry);
      choicesContainer.appendChild(modality);
      i += 1;
    });
  }

  function render() {
    renderCards();
    renderSubmitZone();
    renderHiddenInputs();
    maybeReturnToSubmit();
  }

  function filterMinistries() {
    const term = (searchInput.value || '').trim().toLowerCase();
    let visible = 0;

    cards.forEach((card) => {
      const name = (card.dataset.name || '').toLowerCase();
      const match = !term || name.includes(term);
      card.classList.toggle('is-hidden', !match);
      if (match) visible += 1;
    });

    emptyFilter.classList.toggle('is-visible', visible === 0);
  }

  function toggleAccordion(toggle) {
    const card = toggle.closest('.vol-ministry');
    if (!card) return;

    const panel = card.querySelector('.vol-accordion');
    if (!panel) return;

    const willOpen = !panel.classList.contains('is-open');
    panel.classList.toggle('is-open', willOpen);
    panel.setAttribute('aria-hidden', willOpen ? 'false' : 'true');
    toggle.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
  }

  ministryList.addEventListener('click', (e) => {
    const toggle = e.target.closest('[data-accordion-toggle]');
    if (toggle) {
      e.preventDefault();
      e.stopPropagation();
      toggleAccordion(toggle);
      return;
    }

    const btn = e.target.closest('.vol-mod-btn');
    if (!btn || btn.disabled || btn.classList.contains('is-disabled')) return;

    const card = btn.closest('.vol-ministry');
    if (!card || card.classList.contains('is-locked')) return;

    setModality(card.dataset.slug, card.dataset.name, btn.dataset.modality);
  });

  searchInput.addEventListener('input', filterMinistries);

  form.addEventListener('submit', async (e) => {
    if (submitting) return;

    e.preventDefault();

    const { ok, firstInvalid } = await validateAllFields();

    if (!ok || selections.size < 1 || selections.size > MAX) {
      returnToSubmitPending = true;
      if (firstInvalid) {
        firstInvalid.focus({ preventScroll: false });
        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      } else if (selections.size < 1 && submitZone) {
        // Pendência de ministérios: mantém o contexto próximo ao envio.
        submitZone.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
      return;
    }

    returnToSubmitPending = false;
    submitting = true;
    submitBtn.classList.add('is-loading');
    submitBtn.disabled = true;
    const label = submitBtn.querySelector('.btn-label');
    if (label) label.textContent = 'Enviando…';

    form.submit();
  });

  applyPhoneMask();
  applyNameFormat();
  render();
})();
