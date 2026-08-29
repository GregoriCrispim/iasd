/**
 * Acompanha o progresso da indexação facial do álbum na galeria pública.
 * Enquanto houver fotos pendentes, o botão mostra um loader verde com %.
 * Ao concluir, recarrega a página para liberar o reconhecimento.
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var btn = document.getElementById('galeriaFaceSearchBtn');
        if (!btn) return;

        var progressUrl = btn.getAttribute('data-face-progress-url');
        if (!progressUrl) return;

        var pctEl = document.getElementById('galeriaFaceBtnPct');
        var ringEl = document.getElementById('galeriaFaceBtnRing');
        var labelEl = document.getElementById('galeriaFaceBtnLabel');
        var circumference = 2 * Math.PI * 15.5;
        var timer = null;
        var busy = false;

        function applyPercent(percent) {
            var p = Math.max(0, Math.min(100, Math.round(Number(percent) || 0)));
            if (pctEl) pctEl.textContent = p + '%';
            if (ringEl) {
                ringEl.style.strokeDasharray = String(circumference);
                ringEl.style.strokeDashoffset = String(circumference * (1 - p / 100));
            }
            btn.setAttribute('data-face-percent', String(p));
        }

        function ensureProcessingUi() {
            if (btn.classList.contains('is-processing')) return;
            btn.classList.add('is-processing', 'galeria-toolbar-btn', 'primary', 'galeria-face-btn');
            btn.setAttribute('disabled', 'disabled');
            btn.setAttribute('aria-disabled', 'true');
            btn.setAttribute('title', 'Indexação facial em andamento');
            btn.innerHTML =
                '<span class="galeria-face-btn-loader" aria-hidden="true">' +
                '<svg viewBox="0 0 36 36" focusable="false">' +
                '<circle class="face-ring-bg" cx="18" cy="18" r="15.5"></circle>' +
                '<circle class="face-ring-fg" id="galeriaFaceBtnRing" cx="18" cy="18" r="15.5"></circle>' +
                '</svg>' +
                '<span class="galeria-face-btn-pct" id="galeriaFaceBtnPct">0%</span>' +
                '</span>' +
                '<span class="galeria-face-btn-label" id="galeriaFaceBtnLabel">Processando…</span>';
            pctEl = document.getElementById('galeriaFaceBtnPct');
            ringEl = document.getElementById('galeriaFaceBtnRing');
            labelEl = document.getElementById('galeriaFaceBtnLabel');
        }

        function poll() {
            if (busy) return;
            busy = true;
            fetch(progressUrl, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            }).then(function (r) {
                if (!r.ok) throw new Error('progress');
                return r.json();
            }).then(function (data) {
                var percent = Number(data.percent) || 0;
                var complete = !!data.complete;
                var total = Number(data.total) || 0;

                if (total > 0 && !complete) {
                    ensureProcessingUi();
                    applyPercent(percent);
                    if (labelEl) labelEl.textContent = 'Processando…';
                    return;
                }

                if (complete) {
                    applyPercent(100);
                    if (labelEl) labelEl.textContent = 'Concluído';
                    window.clearInterval(timer);
                    timer = null;
                    // Recarrega para montar o botão/modal corretos (login ou busca).
                    window.setTimeout(function () { window.location.reload(); }, 450);
                }
            }).catch(function () {
                /* silencioso: tenta de novo no próximo tick */
            }).then(function () {
                busy = false;
            });
        }

        if (btn.classList.contains('is-processing')) {
            applyPercent(btn.getAttribute('data-face-percent') || 0);
            timer = window.setInterval(poll, 2500);
            poll();
        }
    });
})();
