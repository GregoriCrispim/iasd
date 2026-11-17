/**
 * Seletores customizados do portal Doutores.
 * Envolve automaticamente todo <select class="select"> (e os já marcados com data-dd-select).
 */
(function () {
    const chevron = '<svg class="dd-select-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>';

    function selectedLabel(select) {
        const opt = select.options[select.selectedIndex];
        return opt ? opt.textContent.trim() : '';
    }

    function isPlaceholder(select) {
        return !select.value;
    }

    function closeAll(except) {
        document.querySelectorAll('[data-dd-select].is-open').forEach((wrap) => {
            if (wrap !== except) {
                wrap.classList.remove('is-open');
                const trigger = wrap.querySelector('.dd-select-trigger');
                if (trigger) trigger.setAttribute('aria-expanded', 'false');
            }
        });
    }

    function ensureWrap(select) {
        let wrap = select.closest('[data-dd-select]');
        if (wrap) {
            select.classList.add('dd-select-native');
            return wrap;
        }

        wrap = document.createElement('div');
        wrap.className = 'dd-select';
        wrap.setAttribute('data-dd-select', '');
        if (select.style.width === 'auto') {
            wrap.classList.add('dd-select--auto');
        }
        select.parentNode.insertBefore(wrap, select);
        wrap.appendChild(select);
        select.classList.add('dd-select-native');
        select.classList.remove('select');
        return wrap;
    }

    function buildSelect(wrap) {
        const select = wrap.querySelector('select');
        if (!select || wrap.dataset.ready === '1') return;
        wrap.dataset.ready = '1';

        const placeholder = select.dataset.placeholder
            || (select.options[0] && !select.options[0].value ? select.options[0].textContent.trim() : 'Selecione');
        const trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'dd-select-trigger';
        trigger.setAttribute('aria-haspopup', 'listbox');
        trigger.setAttribute('aria-expanded', 'false');
        if (select.id) trigger.id = select.id + '-trigger';

        const label = document.createElement('span');
        label.className = 'dd-select-trigger-label';
        trigger.appendChild(label);
        trigger.insertAdjacentHTML('beforeend', chevron);

        const menu = document.createElement('ul');
        menu.className = 'dd-select-menu';
        menu.setAttribute('role', 'listbox');
        if (select.id) menu.setAttribute('aria-labelledby', select.id);

        function syncLabel() {
            const text = selectedLabel(select) || placeholder;
            label.textContent = text;
            label.classList.toggle('is-placeholder', isPlaceholder(select));
        }

        function rebuildOptions() {
            menu.innerHTML = '';
            Array.from(select.options).forEach((opt, index) => {
                const li = document.createElement('li');
                li.setAttribute('role', 'none');
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'dd-select-option';
                btn.setAttribute('role', 'option');
                btn.dataset.value = opt.value;
                btn.dataset.index = String(index);
                btn.textContent = opt.textContent.trim();
                if (opt.disabled) {
                    btn.disabled = true;
                    btn.classList.add('is-disabled');
                }
                if (opt.selected) btn.classList.add('is-selected');
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (opt.disabled) return;
                    select.selectedIndex = index;
                    select.dispatchEvent(new Event('change', { bubbles: true }));
                    syncLabel();
                    rebuildOptions();
                    wrap.classList.remove('is-open');
                    trigger.setAttribute('aria-expanded', 'false');
                    trigger.focus();
                });
                li.appendChild(btn);
                menu.appendChild(li);
            });
        }

        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            if (select.disabled) return;
            const willOpen = !wrap.classList.contains('is-open');
            closeAll(wrap);
            wrap.classList.toggle('is-open', willOpen);
            trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
        });

        select.addEventListener('change', () => {
            syncLabel();
            rebuildOptions();
        });

        wrap.appendChild(trigger);
        wrap.appendChild(menu);
        syncLabel();
        rebuildOptions();

        if (select.id) {
            const fieldLabel = document.querySelector('label[for="' + select.id + '"]');
            if (fieldLabel) {
                fieldLabel.addEventListener('click', (e) => {
                    e.preventDefault();
                    trigger.focus();
                    trigger.click();
                });
            }
        }
    }

    function init() {
        document.querySelectorAll('select.select, select.dd-select-native, [data-dd-select] select').forEach((select) => {
            if (select.hasAttribute('data-dd-native')) return;
            buildSelect(ensureWrap(select));
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    document.addEventListener('click', (e) => {
        if (!e.target.closest('[data-dd-select]')) closeAll();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAll();
    });

    window.ddInitSelects = init;
})();
