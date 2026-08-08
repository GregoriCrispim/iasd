(function() {
    'use strict';

    const toggle = document.getElementById('boletim-preview-toggle');
    const grid = document.getElementById('boletim-preview-grid');

    if (!toggle || !grid) {
        return;
    }

    const label = toggle.querySelector('.boletim-preview-toggle__label');
    const labelTexts = {
        more: 'Ver mais tópicos',
        less: 'Ver menos tópicos'
    };

    toggle.addEventListener('click', function() {
        const isExpanded = toggle.getAttribute('aria-expanded') === 'true';

        if (isExpanded) {
            grid.classList.remove('is-expanded');
            toggle.setAttribute('aria-expanded', 'false');
            label.textContent = labelTexts.more;

            // Scroll suavemente de volta para o topo do grid
            grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            grid.classList.add('is-expanded');
            toggle.setAttribute('aria-expanded', 'true');
            label.textContent = labelTexts.less;
        }
    });
})();
