<div class="form-overlay" id="formOverlay" aria-hidden="true">
    <div class="form-modal" id="formOverlayModal" role="dialog" aria-modal="true" aria-label="Formulário">
        <button type="button" class="form-modal-close" id="formOverlayClose" aria-label="Fechar" style="padding: 0;">
            <i class="bi bi-x-lg"></i>
        </button>

        <div class="form-modal-header">
            <h3 class="form-modal-title" id="formOverlayTitle">Preencha o formulário</h3>
            <p class="form-modal-subtitle" id="formOverlaySubtitle"></p>
        </div>

        <div class="form-modal-body">
            <div class="form-errors" id="formOverlayErrors" style="display:none;"></div>
            <form id="dynamicOverlayForm" action="#" method="POST"></form>
        </div>
    </div>
</div>


