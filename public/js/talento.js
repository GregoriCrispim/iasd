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

  /* —— Field validation (clear while typing, re-check on idle/blur) —— */
  const VALIDATE_IDLE_MS = 450;
  const validatedFields = Array.from(form.querySelectorAll('[data-validate]'));
  /** @type {Map<HTMLElement, number>} */
  const validateTimers = new Map();

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
    // Aligned with common HTML email shape (Laravel email rule is similar)
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(trimmed)) {
      return 'Informe um e-mail válido.';
    }
    return null;
  }

  function getFieldError(input) {
    const type = input.dataset.validate;
    const value = input.value || '';
    if (type === 'name') return validateName(value);
    if (type === 'phone') return validatePhone(value);
    if (type === 'email') return validateEmail(value);
    return null;
  }

  function runFieldValidation(input) {
    const message = getFieldError(input);
    if (message) {
      showFieldError(input, message);
      return false;
    }
    clearFieldError(input);
    return true;
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
      clearFieldError(input);
      scheduleFieldValidation(input);
    });

    input.addEventListener('blur', () => {
      cancelScheduledValidation(input);
      runFieldValidation(input);
    });
  }

  function validateAllFields() {
    let ok = true;
    let firstInvalid = null;
    validatedFields.forEach((input) => {
      cancelScheduledValidation(input);
      const valid = runFieldValidation(input);
      if (!valid) {
        ok = false;
        if (!firstInvalid) firstInvalid = input;
      }
    });
    return { ok, firstInvalid };
  }

  validatedFields.forEach(bindFieldValidation);

  function setModality(slug, name, modality) {
    const existing = selections.get(slug);
    const isSame = existing && existing.modality === modality;

    if (isSame) {
      selections.delete(slug);
    } else if (existing) {
      selections.set(slug, { slug, name, modality });
    } else if (selections.size >= MAX) {
      return;
    } else {
      selections.set(slug, { slug, name, modality });
    }

    render();
  }

  function renderCards() {
    const atLimit = selections.size >= MAX;

    cards.forEach((card) => {
      const slug = card.dataset.slug;
      const selected = selections.get(slug);
      const buttons = card.querySelectorAll('.vol-mod-btn');

      card.classList.toggle('is-selected', Boolean(selected));
      card.classList.toggle('is-locked', atLimit && !selected);

      buttons.forEach((btn) => {
        const active = selected && selected.modality === btn.dataset.modality;
        btn.classList.toggle('is-active', Boolean(active));
        btn.setAttribute('aria-pressed', active ? 'true' : 'false');
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
    if (!btn) return;

    const card = btn.closest('.vol-ministry');
    if (!card || card.classList.contains('is-locked')) return;

    setModality(card.dataset.slug, card.dataset.name, btn.dataset.modality);
  });

  searchInput.addEventListener('input', filterMinistries);

  form.addEventListener('submit', (e) => {
    const { ok, firstInvalid } = validateAllFields();

    if (!ok || selections.size < 1 || selections.size > MAX) {
      e.preventDefault();
      if (firstInvalid) {
        firstInvalid.focus({ preventScroll: false });
        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
      return;
    }

    submitBtn.classList.add('is-loading');
    submitBtn.disabled = true;
    const label = submitBtn.querySelector('.btn-label');
    if (label) label.textContent = 'Enviando…';
  });

  applyPhoneMask();
  render();
})();
