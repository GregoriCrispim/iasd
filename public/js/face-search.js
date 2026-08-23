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
        var dropTitle = document.getElementById('galeriaFaceDropTitle');
        var dropHint = document.getElementById('galeriaFaceDropHint');
        var dropIcon = placeholder ? placeholder.querySelector('.face-drop-icon i') : null;
        var video = document.getElementById('galeriaFaceVideo');
        var preview = document.getElementById('galeriaFacePreview');
        var fileInput = document.getElementById('galeriaFaceFile');
        var buttonsWrap = document.getElementById('galeriaFaceButtons');
        var captureBtn = document.getElementById('galeriaFaceCapture');
        var retakeBtn = document.getElementById('galeriaFaceRetake');
        var submitBtn = document.getElementById('galeriaFaceSubmit');
        var statusEl = document.getElementById('galeriaFaceStatus');

        var consentSelf = document.getElementById('galeriaConsentSelf');
        var consentBio = document.getElementById('galeriaConsentBiometric');
        var consentLim = document.getElementById('galeriaConsentLimitations');
        var consentGuardian = document.getElementById('galeriaConsentGuardian');

        var banner = document.getElementById('galeriaFaceBanner');
        var bannerText = document.getElementById('galeriaFaceBannerText');
        var clearBtn = document.getElementById('galeriaFaceClear');
        var searchOverlay = document.getElementById('galeriaFaceSearchOverlay');
        var searchCard = document.getElementById('galeriaFaceSearchCard');
        var searchTitle = document.getElementById('galeriaFaceSearchTitle');
        var searchStatus = document.getElementById('galeriaFaceSearchStatus');
        var searchCount = document.getElementById('galeriaFaceSearchCount');
        var searchCountLabel = document.getElementById('galeriaFaceSearchCountLabel');
        var searchCheck = document.getElementById('galeriaFaceSearchCheck');
        var searchRetry = document.getElementById('galeriaFaceSearchRetry');

        var mode = 'camera';
        var stream = null;
        var capturedSource = null;
        var busy = false;
        var cameraStarting = false;
        var dragDepth = 0;
        var albumPhotoCount = Number(cfg.photoCount) || 0;
        var lastSearchError = null;

        function setStatus(msg, isError) {
            statusEl.textContent = msg || '';
            statusEl.classList.toggle('is-error', !!isError);
        }

        function show(el) { if (el) el.classList.remove('galeria-face-hidden'); }
        function hide(el) { if (el) el.classList.add('galeria-face-hidden'); }

        function setStageState(state) {
            stage.classList.remove('is-idle', 'is-live', 'is-preview', 'is-dragover');
            if (state) stage.classList.add(state);
            stage.tabIndex = state === 'is-idle' ? 0 : -1;
            if (state === 'is-idle') {
                stage.setAttribute('role', 'button');
            } else {
                stage.removeAttribute('role');
            }
        }

        function syncActionButtons() {
            var hasAction = !captureBtn.hidden || !retakeBtn.hidden;
            if (buttonsWrap) buttonsWrap.classList.toggle('is-empty', !hasAction);
        }

        function showCaptureOnly() {
            hide(retakeBtn);
            retakeBtn.hidden = true;
            show(captureBtn);
            captureBtn.hidden = false;
            syncActionButtons();
        }

        function showRetakeOnly() {
            hide(captureBtn);
            captureBtn.hidden = true;
            show(retakeBtn);
            retakeBtn.hidden = false;
            syncActionButtons();
        }

        function hideCaptureAndRetake() {
            hide(captureBtn);
            captureBtn.hidden = true;
            hide(retakeBtn);
            retakeBtn.hidden = true;
            syncActionButtons();
        }

        function setIdleCopy(forMode) {
            if (forMode === 'upload') {
                if (dropIcon) dropIcon.className = 'bi bi-image';
                if (dropTitle) dropTitle.textContent = 'Escolher foto';
                if (dropHint) dropHint.textContent = 'Clique aqui ou arraste uma imagem';
                stage.setAttribute('aria-label', 'Clique para escolher uma foto ou arraste uma imagem');
            } else {
                if (dropIcon) dropIcon.className = 'bi bi-camera';
                if (dropTitle) dropTitle.textContent = 'Ativar câmera';
                if (dropHint) dropHint.textContent = 'Clique aqui ou arraste uma foto para começar';
                stage.setAttribute('aria-label', 'Clique para ativar a câmera ou arraste uma imagem para carregar a foto');
            }
        }

        function waitForCameraFrame() {
            return new Promise(function (resolve, reject) {
                var tries = 0;
                function ready() {
                    return video.readyState >= 2 && video.videoWidth > 0 && video.videoHeight > 0;
                }
                function settle() {
                    requestAnimationFrame(function () {
                        requestAnimationFrame(resolve);
                    });
                }
                function tick() {
                    if (ready()) {
                        settle();
                        return;
                    }
                    tries++;
                    if (tries > 120) {
                        reject(new Error('A câmera não renderizou a tempo.'));
                        return;
                    }
                    requestAnimationFrame(tick);
                }
                if (ready()) {
                    settle();
                    return;
                }
                video.addEventListener('loadeddata', function onLoaded() {
                    video.removeEventListener('loadeddata', onLoaded);
                    tick();
                });
                video.addEventListener('playing', function onPlaying() {
                    video.removeEventListener('playing', onPlaying);
                    tick();
                });
                tick();
            });
        }

        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(function (t) { t.stop(); });
                stream = null;
            }
            if (video) video.srcObject = null;
        }

        function resetStage() {
            stopCamera();
            cameraStarting = false;
            capturedSource = null;
            dragDepth = 0;
            hide(video);
            hide(preview);
            preview.removeAttribute('src');
            preview.style.transform = '';
            show(placeholder);
            hideCaptureAndRetake();
            setStageState('is-idle');
            setIdleCopy(mode);
            updateSubmit();
        }

        function consentsOk() {
            if (!consentSelf || !consentSelf.checked) return false;
            if (!consentBio || !consentBio.checked) return false;
            if (!consentLim || !consentLim.checked) return false;
            if (cfg.isMinor && consentGuardian && !consentGuardian.checked) return false;
            return true;
        }

        function updateSubmit() {
            var ready = !busy && !!capturedSource && consentsOk();
            submitBtn.disabled = !ready;
            submitBtn.setAttribute('aria-disabled', ready ? 'false' : 'true');
            if (!capturedSource) {
                submitBtn.title = 'Capture ou envie uma foto para continuar';
            } else if (!consentsOk()) {
                submitBtn.title = 'Marque todas as autorizações obrigatórias para buscar';
            } else {
                submitBtn.removeAttribute('title');
            }
        }

        [consentSelf, consentBio, consentLim, consentGuardian].forEach(function (c) {
            if (c) c.addEventListener('change', updateSubmit);
        });

        function openFilePicker() {
            fileInput.value = '';
            fileInput.click();
        }

        function selectMode(next, opts) {
            opts = opts || {};
            mode = next;
            tabs.forEach(function (t) { t.classList.toggle('is-active', t.dataset.mode === next); });
            resetStage();
            if (next === 'upload' && opts.openPicker !== false) {
                openFilePicker();
            }
        }

        tabs.forEach(function (t) {
            t.addEventListener('click', function () { selectMode(t.dataset.mode); });
        });

        function startCamera() {
            if (cameraStarting || busy) return Promise.resolve();
            cameraStarting = true;
            mode = 'camera';
            tabs.forEach(function (t) { t.classList.toggle('is-active', t.dataset.mode === 'camera'); });
            setStatus('Solicitando acesso à câmera…');
            hideCaptureAndRetake();
            if (dropTitle) dropTitle.textContent = 'Ativando câmera…';
            if (dropHint) dropHint.textContent = 'Permita o acesso quando o navegador pedir';
            if (dropIcon) dropIcon.className = 'bi bi-camera-video';

            return navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width: { ideal: 720 }, height: { ideal: 720 } }, audio: false })
                .then(function (s) {
                    stream = s;
                    video.srcObject = s;
                    return video.play();
                })
                .then(function () {
                    return waitForCameraFrame();
                })
                .then(function () {
                    hide(placeholder);
                    show(video);
                    showCaptureOnly();
                    setStageState('is-live');
                    setStatus('');
                    cameraStarting = false;
                })
                .catch(function (err) {
                    cameraStarting = false;
                    setStatus(
                        (err && err.message === 'A câmera não renderizou a tempo.')
                            ? err.message
                            : 'Não foi possível acessar a câmera. Verifique as permissões ou arraste uma foto.',
                        true
                    );
                    resetStage();
                });
        }

        function loadImageFile(file) {
            if (!file || !String(file.type || '').startsWith('image/')) {
                setStatus('Envie um arquivo de imagem válido (JPG, PNG, WEBP…).', true);
                return;
            }

            mode = 'upload';
            tabs.forEach(function (t) { t.classList.toggle('is-active', t.dataset.mode === 'upload'); });
            stopCamera();
            cameraStarting = false;
            hide(video);
            setStatus('Carregando foto…');

            var reader = new FileReader();
            reader.onload = function () {
                var img = new Image();
                img.onload = function () {
                    preview.src = img.src;
                    preview.style.transform = '';
                    capturedSource = img;
                    hide(placeholder);
                    show(preview);
                    showRetakeOnly();
                    setStageState('is-preview');
                    updateSubmit();
                    setStatus('Foto carregada. Marque as autorizações e busque.');
                };
                img.onerror = function () {
                    setStatus('Não foi possível ler esta imagem.', true);
                    resetStage();
                };
                img.src = reader.result;
            };
            reader.onerror = function () {
                setStatus('Não foi possível ler esta imagem.', true);
                resetStage();
            };
            reader.readAsDataURL(file);
        }

        function onStageActivate() {
            if (!stage.classList.contains('is-idle') || busy || cameraStarting) return;
            if (mode === 'upload') {
                openFilePicker();
            } else {
                startCamera();
            }
        }

        stage.addEventListener('click', onStageActivate);
        stage.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                onStageActivate();
            }
        });

        function hasDragFiles(e) {
            var types = e.dataTransfer && e.dataTransfer.types;
            if (!types) return false;
            return Array.prototype.indexOf.call(types, 'Files') !== -1;
        }

        stage.addEventListener('dragenter', function (e) {
            if (!hasDragFiles(e) || busy || cameraStarting) return;
            e.preventDefault();
            dragDepth++;
            stage.classList.add('is-dragover');
            show(placeholder);
            hide(video);
            hide(preview);
            if (dropTitle) dropTitle.textContent = 'Solte para carregar';
            if (dropHint) dropHint.textContent = 'A imagem fica só no seu dispositivo';
            if (dropIcon) dropIcon.className = 'bi bi-cloud-arrow-up';
        });

        stage.addEventListener('dragover', function (e) {
            if (!hasDragFiles(e) || busy || cameraStarting) return;
            e.preventDefault();
            if (e.dataTransfer) e.dataTransfer.dropEffect = 'copy';
        });

        stage.addEventListener('dragleave', function (e) {
            if (!hasDragFiles(e)) return;
            e.preventDefault();
            dragDepth = Math.max(0, dragDepth - 1);
            if (dragDepth === 0) {
                stage.classList.remove('is-dragover');
                // Restaura o que estava visível antes do arraste.
                if (capturedSource) {
                    hide(placeholder);
                    show(preview);
                    setStageState('is-preview');
                } else if (stream) {
                    hide(placeholder);
                    show(video);
                    setStageState('is-live');
                } else {
                    setStageState('is-idle');
                    setIdleCopy(mode);
                }
            }
        });

        stage.addEventListener('drop', function (e) {
            e.preventDefault();
            dragDepth = 0;
            stage.classList.remove('is-dragover');
            var files = e.dataTransfer && e.dataTransfer.files;
            var file = files && files[0];
            if (!file) {
                if (stage.classList.contains('is-idle')) setIdleCopy(mode);
                return;
            }
            loadImageFile(file);
        });

        captureBtn.addEventListener('click', function () {
            var w = video.videoWidth, h = video.videoHeight;
            if (!w || !h) return;
            var canvas = document.createElement('canvas');
            canvas.width = w; canvas.height = h;
            canvas.getContext('2d').drawImage(video, 0, 0, w, h);
            preview.src = canvas.toDataURL('image/jpeg', 0.92);
            preview.style.transform = 'scaleX(-1)';
            capturedSource = canvas;
            stopCamera();
            hide(video);
            show(preview);
            showRetakeOnly();
            setStageState('is-preview');
            updateSubmit();
            setStatus('Selfie capturada. Marque as autorizações e busque.');
        });

        fileInput.addEventListener('change', function () {
            var file = fileInput.files && fileInput.files[0];
            if (!file) {
                setIdleCopy('upload');
                setStatus('');
                return;
            }
            loadImageFile(file);
        });

        retakeBtn.addEventListener('click', function () {
            setStatus('');
            capturedSource = null;
            hide(preview);
            preview.removeAttribute('src');
            preview.style.transform = '';
            hideCaptureAndRetake();
            updateSubmit();
            if (mode === 'camera') {
                show(placeholder);
                setStageState('is-idle');
                setIdleCopy('camera');
                if (dropTitle) dropTitle.textContent = 'Reiniciando câmera…';
                if (dropHint) dropHint.textContent = 'Aguarde um instante';
                startCamera();
            } else {
                selectMode('upload');
            }
        });

        function open() {
            modal.classList.add('is-open');
            document.body.style.overflow = 'hidden';
            selectMode('camera', { openPicker: false });
            setStatus('');
        }
        function close() {
            modal.classList.remove('is-open');
            document.body.style.overflow = '';
            resetStage();
        }

        function setSearchOverlayState(state) {
            if (!searchCard) return;
            searchCard.classList.remove('is-done', 'is-error');
            if (state) searchCard.classList.add(state);
            if (searchCheck) {
                var icon = searchCheck.querySelector('i');
                if (icon) {
                    icon.className = state === 'is-error' ? 'bi bi-x-lg' : 'bi bi-check-lg';
                }
            }
        }

        function showSearchOverlay() {
            if (!searchOverlay) return;
            searchOverlay.hidden = false;
            searchOverlay.classList.add('is-open');
            searchOverlay.setAttribute('aria-busy', 'true');
            document.body.style.overflow = 'hidden';
            setSearchOverlayState(null);
            if (searchTitle) searchTitle.textContent = 'Buscando fotos';
            if (searchStatus) searchStatus.textContent = 'Preparando…';
            if (searchCount) searchCount.textContent = '0';
            if (searchCountLabel) {
                searchCountLabel.textContent = albumPhotoCount > 0
                    ? ('de ' + albumPhotoCount + ' no álbum')
                    : 'fotos encontradas';
            }
        }

        function hideSearchOverlay() {
            if (!searchOverlay) return;
            searchOverlay.classList.remove('is-open');
            searchOverlay.hidden = true;
            searchOverlay.setAttribute('aria-busy', 'false');
            document.body.style.overflow = '';
            setSearchOverlayState(null);
        }

        function updateSearchProgress(title, status, count) {
            if (searchTitle && title) searchTitle.textContent = title;
            if (searchStatus && typeof status === 'string') searchStatus.textContent = status;
            if (searchCount && typeof count === 'number') searchCount.textContent = String(count);
        }

        function animateFoundCount(target) {
            return new Promise(function (resolve) {
                var end = Math.max(0, Number(target) || 0);
                if (searchCountLabel) {
                    searchCountLabel.textContent = end === 1 ? 'foto encontrada' : 'fotos encontradas';
                }
                if (end === 0) {
                    if (searchCount) searchCount.textContent = '0';
                    window.setTimeout(resolve, 650);
                    return;
                }
                var duration = Math.min(1400, 450 + end * 35);
                var start = null;
                function frame(now) {
                    if (start === null) start = now;
                    var t = Math.min(1, (now - start) / duration);
                    var eased = 1 - Math.pow(1 - t, 3);
                    var n = Math.round(end * eased);
                    if (searchCount) searchCount.textContent = String(n);
                    if (t < 1) {
                        requestAnimationFrame(frame);
                    } else {
                        if (searchCount) searchCount.textContent = String(end);
                        window.setTimeout(resolve, 550);
                    }
                }
                requestAnimationFrame(frame);
            });
        }

        function photosLabel(n) {
            return n === 1 ? 'foto' : 'fotos';
        }

        openBtn.addEventListener('click', open);
        closeBtn.addEventListener('click', close);
        cancelBtn.addEventListener('click', close);
        modal.addEventListener('click', function (e) { if (e.target === modal) close(); });
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            if (searchOverlay && searchOverlay.classList.contains('is-open') && searchCard && searchCard.classList.contains('is-error')) {
                hideSearchOverlay();
                return;
            }
            if (modal.classList.contains('is-open')) close();
        });

        try {
            var params = new URLSearchParams(window.location.search);
            if (params.get('face') === '1') {
                open();
                params.delete('face');
                var cleaned = window.location.pathname;
                var qs = params.toString();
                if (qs) cleaned += '?' + qs;
                cleaned += window.location.hash || '';
                window.history.replaceState({}, '', cleaned);
            }
        } catch (e) { /* ignora */ }

        submitBtn.addEventListener('click', function () {
            if (busy || !capturedSource || !consentsOk()) return;
            busy = true;
            updateSubmit();

            var source = capturedSource;
            var searchMode = mode;
            var consentPayload = {
                consent_self: consentSelf.checked ? 1 : 0,
                consent_biometric: consentBio.checked ? 1 : 0,
                consent_limitations: consentLim.checked ? 1 : 0,
                guardian_declaration: (consentGuardian && consentGuardian.checked) ? 1 : 0
            };
            lastSearchError = null;

            close();
            showSearchOverlay();
            updateSearchProgress('Analisando', 'Detectando o rosto no seu dispositivo…', 0);

            window.FaceEngine.detectSingle(source, {
                maxSide: cfg.selfie.maxSide,
                minScore: cfg.selfie.minScore,
                minSizeRatio: cfg.selfie.minSizeRatio
            }).then(function (face) {
                updateSearchProgress(
                    'Comparando',
                    albumPhotoCount > 0
                        ? ('Procurando o seu rosto em ' + albumPhotoCount + ' ' + photosLabel(albumPhotoCount) + '…')
                        : 'Comparando com as fotos do álbum…',
                    0
                );
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
                        source: searchMode,
                        consent_self: consentPayload.consent_self,
                        consent_biometric: consentPayload.consent_biometric,
                        consent_limitations: consentPayload.consent_limitations,
                        guardian_declaration: consentPayload.guardian_declaration
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
                setSearchOverlayState('is-done');
                updateSearchProgress(
                    ids.length > 0 ? 'Pronto!' : 'Sem correspondências',
                    ids.length > 0
                        ? 'Filtrando a galeria para as fotos em que você aparece…'
                        : 'Nenhuma foto deste álbum correspondeu ao seu rosto.',
                    0
                );
                return animateFoundCount(ids.length).then(function () { return ids; });
            }).then(function (ids) {
                applyResult(ids);
                hideSearchOverlay();
            }).catch(function (err) {
                lastSearchError = err && err.message ? err.message : 'Falha na busca facial.';
                setSearchOverlayState('is-error');
                if (searchTitle) searchTitle.textContent = 'Não foi possível buscar';
                if (searchStatus) searchStatus.textContent = lastSearchError;
                if (searchCount) searchCount.textContent = '—';
                if (searchCountLabel) searchCountLabel.textContent = 'tente novamente';
                if (searchOverlay) searchOverlay.setAttribute('aria-busy', 'false');
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
                    ? (count + ' ' + photosLabel(count) + ' com o seu rosto. Mostrando apenas estas.')
                    : 'Nenhuma correspondência encontrada neste álbum.';
            }
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                if (typeof window.galeriaClearFaceFilter === 'function') window.galeriaClearFaceFilter();
                if (banner) banner.hidden = true;
            });
        }

        if (searchRetry) {
            searchRetry.addEventListener('click', function () {
                hideSearchOverlay();
                open();
            });
        }
    });
})();
