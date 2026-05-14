(() => {
  function qs(sel, root = document) {
    return root.querySelector(sel);
  }

  function qsa(sel, root = document) {
    return Array.from(root.querySelectorAll(sel));
  }

  function getCsrf() {
    const meta = qs('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
  }

  function safeJsonParse(value) {
    try {
      return JSON.parse(value);
    } catch {
      return null;
    }
  }

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

  function applyMasks(root) {
    const phones = qsa('input[data-mask="br-phone"]', root);
    phones.forEach((input) => {
      const handler = () => {
        const next = formatBrPhone(input.value);
        if (input.value !== next) input.value = next;
      };

      // Inicial
      handler();

      input.addEventListener('input', handler);
      input.addEventListener('blur', handler);
      input.addEventListener('paste', () => setTimeout(handler, 0));
    });
  }

  function buildField(field) {
    const row = document.createElement('div');
    row.className = 'form-row';

    if (field.type === 'checkbox') {
      const wrapper = document.createElement('label');
      wrapper.className = 'form-checkbox';

      const input = document.createElement('input');
      input.type = 'checkbox';
      input.name = field.name;
      input.value = field.value ?? '1';
      if (field.required) input.required = true;
      if (field.checked) input.checked = true;

      const text = document.createElement('span');
      text.textContent = field.label ?? field.name;

      wrapper.appendChild(input);
      wrapper.appendChild(text);
      row.appendChild(wrapper);
      return row;
    }

    const label = document.createElement('label');
    label.className = 'form-label';
    label.setAttribute('for', field.id || field.name);
    label.textContent = field.label ?? field.name;
    if (field.required) {
      const req = document.createElement('span');
      req.className = 'req';
      req.textContent = '*';
      label.appendChild(req);
    }

    let control;
    if (field.type === 'textarea') {
      control = document.createElement('textarea');
      control.rows = field.rows ?? 8;
    } else {
      control = document.createElement('input');
      control.type = field.type ?? 'text';
    }

    control.className = 'form-control';
    control.name = field.name;
    control.id = field.id || field.name;
    if (field.placeholder) control.placeholder = field.placeholder;
    if (field.required) control.required = true;
    if (field.autocomplete) control.autocomplete = field.autocomplete;
    if (field.maxlength) control.maxLength = field.maxlength;
    if (field.value != null) control.value = field.value;
    if (field.inputmode) control.setAttribute('inputmode', field.inputmode);
    if (field.pattern) control.setAttribute('pattern', field.pattern);
    if (field.mask) control.setAttribute('data-mask', field.mask);

    row.appendChild(label);
    row.appendChild(control);
    return row;
  }

  function ensureOverlay() {
    const overlay = qs('#formOverlay');
    const modal = qs('#formOverlayModal');
    const closeBtn = qs('#formOverlayClose');
    const title = qs('#formOverlayTitle');
    const subtitle = qs('#formOverlaySubtitle');
    const errors = qs('#formOverlayErrors');
    const form = qs('#dynamicOverlayForm');

    return { overlay, modal, closeBtn, title, subtitle, errors, form };
  }

  function setOpen(open) {
    const { overlay } = ensureOverlay();
    if (!overlay) return;
    overlay.classList.toggle('is-open', open);
    overlay.setAttribute('aria-hidden', open ? 'false' : 'true');
    document.body.style.overflow = open ? 'hidden' : '';
  }

  function setErrorsHtml(html) {
    const { errors } = ensureOverlay();
    if (!errors) return;
    if (!html) {
      errors.style.display = 'none';
      errors.innerHTML = '';
      return;
    }
    errors.style.display = 'block';
    errors.innerHTML = html;
  }

  function openForm(config, serverErrorsHtml = '') {
    const { title, subtitle, form } = ensureOverlay();
    if (!form) return;

    // Reset
    form.innerHTML = '';
    setErrorsHtml(serverErrorsHtml);

    // Title/subtitle
    if (title) title.textContent = config.title || 'Preencha o formulário';
    if (subtitle) subtitle.textContent = config.subtitle || '';

    // Action/method
    form.action = config.action;
    form.method = config.method || 'POST';

    // CSRF
    const csrf = getCsrf();
    if (csrf) {
      const token = document.createElement('input');
      token.type = 'hidden';
      token.name = '_token';
      token.value = csrf;
      form.appendChild(token);
    }

    // Fields
    (config.fields || []).forEach((field) => {
      form.appendChild(buildField(field));
    });

    // Masks
    applyMasks(form);

    // Actions
    const actions = document.createElement('div');
    actions.className = 'form-actions';

    const submit = document.createElement('button');
    submit.type = 'submit';
    submit.className = 'btn-primary-solid form-submit';
    submit.textContent = config.submitLabel || 'Enviar';

    actions.appendChild(submit);
    form.appendChild(actions);

    setOpen(true);

    // Bloquear números no campo nome
    const nomeField = form.querySelector('input[name="nome"]');
    if (nomeField) {
      nomeField.addEventListener('keypress', (e) => {
        if (/\d/.test(e.key)) {
          e.preventDefault();
        }
      });
      nomeField.addEventListener('paste', (e) => {
        const paste = (e.clipboardData || window.clipboardData).getData('text');
        if (/\d/.test(paste)) {
          e.preventDefault();
        }
      });
    }

    // Focus first control
    const first = form.querySelector('input, textarea, select, button');
    if (first) setTimeout(() => first.focus(), 50);
  }

  function closeForm() {
    setOpen(false);
    setErrorsHtml('');
    const { form } = ensureOverlay();
    if (form) form.innerHTML = '';
  }

  function init() {
    const { overlay, modal, closeBtn } = ensureOverlay();
    if (!overlay || !modal) return;

    // Open buttons
    qsa('.form-open-btn[data-form-config]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const cfg = safeJsonParse(btn.getAttribute('data-form-config'));
        if (!cfg) return;
        const errorsEl = btn.closest('.form-cta')?.querySelector('.form-server-errors');
        const errorsHtml = errorsEl ? errorsEl.innerHTML : '';
        openForm(cfg, errorsHtml);
      });
    });

    // Click outside modal closes
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) closeForm();
    });

    // Prevent bubbling from modal
    modal.addEventListener('click', (e) => e.stopPropagation());

    // Close button
    if (closeBtn) closeBtn.addEventListener('click', closeForm);

    // ESC closes
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && overlay.classList.contains('is-open')) closeForm();
    });

    // Auto-open on validation errors
    qsa('.form-cta[data-open-on-load="1"] .form-open-btn[data-form-config]').forEach((btn) => {
      const cfg = safeJsonParse(btn.getAttribute('data-form-config'));
      if (!cfg) return;
      const errorsEl = btn.closest('.form-cta')?.querySelector('.form-server-errors');
      if (!errorsEl) return;
      openForm(cfg, errorsEl.innerHTML);
    });
  }

  document.addEventListener('DOMContentLoaded', init);
})();


