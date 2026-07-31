/**
 * Busca facial do membro na página do álbum.
 *
 * A selfie (câmera ou upload) é processada localmente pelo FaceEngine; ao
 * servidor enviamos apenas o descriptor de 128 posições. A grade é então
 * filtrada pelos IDs retornados via window.galeriaApplyFaceFilter.
 */
(function () {
    'use strict';

    function readConfig() {
        if (window.FACE_CONFIG) return window.FACE_CONFIG;
        var el = document.getElementById('faceConfig');
        if (el) {
            try { window.FACE_CONFIG = JSON.parse(el.textContent || '{}'); return window.FACE_CONFIG; }
            catch (e) { /* ignora */ }
        }
        return {};
    }

    document.addEventListener('DOMContentLoaded', function () {
        var cfg = readConfig();
        var openBtn = document.getElementById('galeriaFaceSearchBtn');
        var modal = document.getElementById('galeriaFaceModal');
        if (!openBtn || !modal || !cfg.searchUrl) return;

        var closeBtn = document.getElementById('galeriaFaceModalClose');
        var cancelBtn = document.getElementById('galeriaFaceCancel');
        var tabs = Array.prototype.slice.call(modal.querySelectorAll('.galeria-face-tab'));
        var stage = document.getElementById('galeriaFaceStage');
        var placeholder = document.getElementById('galeriaFacePlaceholder');
        var video = document.getElementById('galeriaFaceVideo');
        var preview = document.getElementById('galeriaFacePreview');
        var fileInput = document.getElementById('galeriaFaceFile');
        var cameraStartBtn = document.getElementById('galeriaFaceCameraStart');
        var captureBtn = document.getElementById('galeriaFaceCapture');
        var pickBtn = document.getElementById('galeriaFacePick');
        var retakeBtn = document.getElementById('galeriaFaceRetake');
        var submitBtn = document.getElementById('galeriaFaceSubmit');
        var statusEl = document.getElementById('galeriaFaceStatus');
        var guardianWrap = document.getElementById('galeriaFaceGuardian');

        var consentSelf = document.getElementById('galeriaConsentSelf');
        var consentBio = document.getElementById('galeriaConsentBiometric');
        var consentLim = document.getElementById('galeriaConsentLimitations');
        var consentGuardian = document.getElementById('galeriaConsentGuardian');

        var banner = document.getElementById('galeriaFaceBanner');
        var bannerText = document.getElementById('galeriaFaceBannerText');
        var clearBtn = document.getElementById('galeriaFaceClear');

        var mode = 'camera';
        var stream = null;
        var capturedSource = null; // <img> ou <canvas> pronto para o FaceEngine
        var busy = false;

        function setStatus(msg, isError) {
            statusEl.textContent = msg || '';
            statusEl.classList.toggle('is-error', !!isError);
        }

        function show(el) { if (el) el.classList.remove('galeria-face-hidden'); }
        function hide(el) { if (el) el.classList.add('galeria-face-hidden'); }

        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(function (t) { t.stop(); });
                stream = null;
            }
        }

        function resetStage() {
            stopCamera();
            capturedSource = null;
            hide(video);
            hide(preview);
            preview.removeAttribute('src');
            show(placeholder);
            captureBtn.hidden = true;
            retakeBtn.hidden = true;
            updateSubmit();
        }

        function consentsOk() {
            if (!consentSelf.checked || !consentBio.checked || !consentLim.checked) return false;
            if (cfg.isMinor && consentGuardian && !consentGuardian.checked) return false;
            return true;
        }

        function updateSubmit() {
            submitBtn.disabled = busy || !capturedSource || !consentsOk();
        }

        [consentSelf, consentBio, consentLim, consentGuardian].forEach(function (c) {
            if (c) c.addEventListener('change', updateSubmit);
        });

        function selectMode(next) {
            mode = next;
            tabs.forEach(function (t) { t.classList.toggle('is-active', t.dataset.mode === next); });
            resetStage();
            if (next === 'camera') {
                show(cameraStartBtn);
                hide(pickBtn);
                placeholder.textContent = 'Toque em “Ativar câmera” para começar.';
            } else {
                hide(cameraStartBtn);
                show(pickBtn);
                placeholder.textContent = 'Toque em “Escolher foto” para enviar uma imagem sua.';
            }
        }

        tabs.forEach(function (t) {
            t.addEventListener('click', function () { selectMode(t.dataset.mode); });
        });

        cameraStartBtn.addEventListener('click', function () {
            setStatus('Solicitando acesso à câmera…');
            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width: { ideal: 720 }, height: { ideal: 720 } }, audio: false })
                .then(function (s) {
                    stream = s;
                    video.srcObject = s;
                    return video.play();
                })
                .then(function () {
                    hide(placeholder);
                    show(video);
                    cameraStartBtn.hidden = true;
                    captureBtn.hidden = false;
                    setStatus('');
                })
                .catch(function () {
                    setStatus('Não foi possível acessar a câmera. Verifique as permissões ou use “Enviar foto”.', true);
                });
        });

        captureBtn.addEventListener('click', function () {
            var w = video.videoWidth, h = video.videoHeight;
            if (!w || !h) return;
            var canvas = document.createElement('canvas');
            canvas.width = w; canvas.height = h;
            canvas.getContext('2d').drawImage(video, 0, 0, w, h);
            preview.src = canvas.toDataURL('image/jpeg', 0.92);
            capturedSource = canvas;
            stopCamera();
            hide(video);
            show(preview);
            captureBtn.hidden = true;
            retakeBtn.hidden = false;
            updateSubmit();
            setStatus('Selfie capturada. Marque as autorizações e busque.');
        });

        pickBtn.addEventListener('click', function () { fileInput.click(); });
        fileInput.addEventListener('change', function () {
            var file = fileInput.files && fileInput.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function () {
                var img = new Image();
                img.onload = function () {
                    preview.src = img.src;
                    capturedSource = img;
                    hide(placeholder);
                    show(preview);
                    retakeBtn.hidden = false;
                    updateSubmit();
                    setStatus('Foto carregada. Marque as autorizações e busque.');
                };
                img.src = reader.result;
            };
            reader.readAsDataURL(file);
        });

        retakeBtn.addEventListener('click', function () { selectMode(mode); setStatus(''); });

        function open() {
            modal.classList.add('is-open');
            document.body.style.overflow = 'hidden';
            selectMode('camera');
            setStatus('');
        }
        function close() {
            modal.classList.remove('is-open');
            document.body.style.overflow = '';
            resetStage();
        }

        openBtn.addEventListener('click', open);
        closeBtn.addEventListener('click', close);
        cancelBtn.addEventListener('click', close);
        modal.addEventListener('click', function (e) { if (e.target === modal) close(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && modal.classList.contains('is-open')) close(); });

        submitBtn.addEventListener('click', function () {
            if (busy || !capturedSource || !consentsOk()) return;
            busy = true;
            updateSubmit();
            setStatus('Analisando o rosto no seu dispositivo…');

            window.FaceEngine.detectSingle(capturedSource, {
                maxSide: cfg.selfie.maxSide,
                minScore: cfg.selfie.minScore,
                minSizeRatio: cfg.selfie.minSizeRatio
            }).then(function (face) {
                setStatus('Comparando com as fotos do álbum…');
                return fetch(cfg.searchUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': cfg.csrf,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        descriptor: face.descriptor,
                        source: mode,
                        consent_self: consentSelf.checked ? 1 : 0,
                        consent_biometric: consentBio.checked ? 1 : 0,
                        consent_limitations: consentLim.checked ? 1 : 0,
                        guardian_declaration: (consentGuardian && consentGuardian.checked) ? 1 : 0
                    })
                });
            }).then(function (r) {
                return r.json().then(function (data) { return { ok: r.ok, status: r.status, data: data }; });
            }).then(function (res) {
                if (!res.ok) {
                    var msg = res.data && res.data.message
                        ? res.data.message
                        : (res.data && res.data.errors ? Object.values(res.data.errors).flat().join(' ') : 'Não foi possível concluir a busca.');
                    throw new Error(msg);
                }
                var ids = res.data.photo_ids || [];
                applyResult(ids);
                close();
            }).catch(function (err) {
                setStatus(err && err.message ? err.message : 'Falha na busca facial.', true);
            }).then(function () {
                busy = false;
                updateSubmit();
            });
        });

        function applyResult(ids) {
            if (typeof window.galeriaApplyFaceFilter !== 'function') return;
            var count = window.galeriaApplyFaceFilter(ids);
            if (banner && bannerText) {
                banner.hidden = false;
                bannerText.textContent = count > 0
                    ? (count + ' foto' + (count !== 1 ? 's' : '') + ' encontrada' + (count !== 1 ? 's' : '') + ' com o seu rosto.')
                    : 'Nenhuma correspondência encontrada neste álbum.';
            }
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                if (typeof window.galeriaClearFaceFilter === 'function') window.galeriaClearFaceFilter();
                if (banner) banner.hidden = true;
            });
        }
    });
})();
