@extends('admin.layout')

@php $activeNav = 'galeria'; @endphp
@section('title', 'Reconhecimento facial — '.$album->title)
@section('heading', 'Reconhecimento facial')

@section('actions')
    <a href="{{ route('admin.galeria.show', $album) }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar ao álbum</a>
@endsection

@section('content')
    <div class="card" style="margin-bottom:16px;">
        <div class="card-head"><h2>{{ $album->title }}</h2></div>
        <div class="card-body">
            <p class="text-muted mt-0">
                O processamento acontece no seu navegador: cada foto é analisada aqui e apenas os descritores
                (vetores numéricos) são enviados ao servidor. As imagens não saem do seu computador durante a indexação.
            </p>

            <div class="face-stats" style="display:flex;gap:24px;flex-wrap:wrap;margin:12px 0;">
                <div><strong id="faceStatTotal">{{ $album->photos_count ?? $album->photos()->count() }}</strong> <span class="text-muted">fotos</span></div>
                <div><strong id="faceStatReady">{{ $album->ready_count ?? 0 }}</strong> <span class="text-muted">indexadas</span></div>
                <div><strong id="faceStatPending">{{ $album->pending_count ?? 0 }}</strong> <span class="text-muted">pendentes/falhas</span></div>
            </div>

            <div class="gal-progress" id="faceProgress" hidden>
                <div class="gal-progress-meta">
                    <span id="faceProgressLabel">Pronto para iniciar</span>
                    <span id="faceProgressPct">0%</span>
                </div>
                <div class="gal-progress-track" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" id="faceProgressBar">
                    <div class="gal-progress-fill" id="faceProgressFill"></div>
                </div>
                <div class="gal-progress-sub" id="faceProgressSub"></div>
            </div>

            <div class="gal-upload-actions" style="display:flex;gap:8px;flex-wrap:wrap;">
                <button type="button" class="btn" id="faceStart"><i class="bi bi-play-fill"></i> Processar pendentes</button>
                <button type="button" class="btn btn-secondary" id="faceReprocess"><i class="bi bi-arrow-repeat"></i> Reprocessar tudo</button>
            </div>

            <div class="gal-log-panel" id="faceLogPanel" hidden style="margin-top:14px;">
                <button type="button" class="gal-log-toggle" id="faceLogToggle" aria-expanded="false">
                    <i class="bi bi-terminal"></i>
                    <span>Log de processamento</span>
                    <i class="bi bi-chevron-down gal-log-chevron"></i>
                </button>
                <pre class="gal-log" id="faceLog" hidden></pre>
            </div>
        </div>
    </div>

    <script type="application/json" id="faceConfig">{!! json_encode([
        'scriptUrl' => config('face.script_url'),
        'modelsUrl' => config('face.models_url'),
        'modelVersion' => $modelVersion,
        'csrf' => csrf_token(),
        'queueUrl' => route('admin.galeria.faces.queue', $album),
        'storeTemplate' => route('admin.galeria.faces.store', [$album, '__PID__']),
        'photo' => [
            'minScore' => (float) config('face.detection.photo.min_score', 0.30),
            'minSizeRatio' => (float) config('face.detection.photo.min_size_ratio', 0.01),
            'maxFaces' => (int) config('face.detection.photo.max_faces', 80),
            'maxSide' => (int) config('face.detection.photo.analysis_max_side', 1536),
        ],
    ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    <script src="{{ asset('js/face-engine.js') }}?v={{ filemtime(public_path('js/face-engine.js')) }}"></script>
    <script>
    (function () {
        var CONFIG = {};
        try { CONFIG = JSON.parse(document.getElementById('faceConfig').textContent || '{}'); } catch (e) {}
        window.FACE_CONFIG = CONFIG;
        var cfg = CONFIG.photo || {};
        var csrf = CONFIG.csrf;
        var queueUrl = CONFIG.queueUrl;
        var storeTemplate = CONFIG.storeTemplate;

        var startBtn = document.getElementById('faceStart');
        var reprocessBtn = document.getElementById('faceReprocess');
        var progress = document.getElementById('faceProgress');
        var progressLabel = document.getElementById('faceProgressLabel');
        var progressPct = document.getElementById('faceProgressPct');
        var progressFill = document.getElementById('faceProgressFill');
        var progressBar = document.getElementById('faceProgressBar');
        var progressSub = document.getElementById('faceProgressSub');
        var logPanel = document.getElementById('faceLogPanel');
        var logToggle = document.getElementById('faceLogToggle');
        var logEl = document.getElementById('faceLog');
        var statReady = document.getElementById('faceStatReady');
        var statPending = document.getElementById('faceStatPending');

        var queue = [];
        var idx = 0;
        var running = false;
        var paused = false;
        var logOpen = false;
        var hasLogEntries = false;
        var readyCount = parseInt(statReady.textContent, 10) || 0;
        var pendingCount = parseInt(statPending.textContent, 10) || 0;

        function setStartAppearance() {
            startBtn.classList.remove('btn-warning');
            startBtn.innerHTML = '<i class="bi bi-play-fill"></i> Processar pendentes';
        }

        function setPauseAppearance() {
            startBtn.classList.add('btn-warning');
            startBtn.innerHTML = '<i class="bi bi-pause-fill"></i> Pausar';
        }

        function openLogPanel() {
            if (!logOpen) {
                logOpen = true;
                logEl.hidden = false;
                logToggle.setAttribute('aria-expanded', 'true');
                logToggle.classList.add('is-open');
            }
        }

        function syncLogVisibility() {
            var show = hasLogEntries && running && !paused;
            logPanel.hidden = !show;
            if (show) openLogPanel();
        }

        function resetLog() {
            hasLogEntries = false;
            logEl.textContent = '';
            logOpen = false;
            logEl.hidden = true;
            logToggle.setAttribute('aria-expanded', 'false');
            logToggle.classList.remove('is-open');
            logPanel.hidden = true;
        }

        function log(msg, level) {
            var wasEmpty = !hasLogEntries;
            hasLogEntries = true;
            var stamp = new Date().toLocaleTimeString('pt-BR');
            var prefix = level === 'error' ? '[erro] ' : (level === 'warn' ? '[aviso] ' : '');
            logEl.textContent += stamp + '  ' + prefix + msg + '\n';
            logEl.scrollTop = logEl.scrollHeight;
            if (wasEmpty) syncLogVisibility();
            else if (hasLogEntries && running && !paused) logPanel.hidden = false;
        }

        function setProgress(done, total, label, sub) {
            progress.hidden = false;
            var pct = total ? Math.round((done / total) * 100) : 0;
            progressFill.style.width = pct + '%';
            progressBar.setAttribute('aria-valuenow', String(pct));
            progressPct.textContent = pct + '%';
            if (label != null) progressLabel.textContent = label;
            if (sub != null) progressSub.textContent = sub;
        }

        function updateStats() {
            statReady.textContent = String(readyCount);
            statPending.textContent = String(Math.max(0, pendingCount));
        }

        function postResult(photoId, payload) {
            var url = storeTemplate.replace('__PID__', encodeURIComponent(photoId));
            return fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            }).then(function (r) {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            });
        }

        function processNext() {
            if (paused || !running) return;
            if (idx >= queue.length) { finish(); return; }

            var item = queue[idx];
            var total = queue.length;
            setProgress(idx, total, 'Analisando ' + (idx + 1) + ' de ' + total, 'Foto #' + item.id);

            window.FaceEngine.loadImage(item.url).then(function (img) {
                return window.FaceEngine.detectAll(img, {
                    maxSide: cfg.maxSide,
                    minScore: cfg.minScore,
                    minSizeRatio: cfg.minSizeRatio,
                    maxFaces: cfg.maxFaces
                });
            }).then(function (faces) {
                var status = faces.length ? 'ready' : 'no_face';
                return postResult(item.id, {
                    status: status,
                    faces: faces,
                    reason: faces.length ? null : 'Nenhum rosto detectado.'
                }).then(function (res) {
                    if (res.status === 'ready') {
                        readyCount++;
                        if (item.status === 'pending' || item.status === 'failed') pendingCount--;
                        log('Foto #' + item.id + ': ' + res.faces + ' rosto(s) indexado(s).');
                    } else {
                        if (item.status === 'pending' || item.status === 'failed') pendingCount--;
                        log('Foto #' + item.id + ': nenhum rosto detectado.', 'warn');
                    }
                    updateStats();
                });
            }).catch(function (err) {
                log('Foto #' + item.id + ': falha — ' + (err && err.message ? err.message : err), 'error');
                return postResult(item.id, { status: 'failed', reason: String(err && err.message ? err.message : err).slice(0, 200) })
                    .catch(function () {});
            }).then(function () {
                idx++;
                setProgress(idx, total, null, null);
                // Cede o event loop para manter a UI responsiva.
                setTimeout(processNext, 0);
            });
        }

        function finish() {
            setProgress(queue.length, queue.length, 'Concluído', queue.length + ' foto(s) processada(s)');
            log('Processamento concluído.');
            running = false;
            paused = false;
            setStartAppearance();
            reprocessBtn.disabled = false;
            syncLogVisibility();
            if (typeof window.admToast === 'function') window.admToast('Indexação facial concluída.', 'success');
        }

        function pauseProcessing() {
            if (!running || paused) return;
            paused = true;
            setStartAppearance();
            reprocessBtn.disabled = false;
            log('Pausado.');
            syncLogVisibility();
        }

        function resumeProcessing() {
            if (!paused || idx >= queue.length) return;
            paused = false;
            running = true;
            setPauseAppearance();
            reprocessBtn.disabled = true;
            log('Retomado.');
            syncLogVisibility();
            processNext();
        }

        function begin(scope) {
            if (running && !paused) return;
            paused = false;
            running = false;
            resetLog();
            reprocessBtn.disabled = true;
            setStartAppearance();
            startBtn.disabled = true;
            log('Carregando modelos faciais…');
            // Mostra o painel já no carregamento (há registro + sessão iniciada).
            running = true;
            syncLogVisibility();
            setProgress(0, 1, 'Carregando modelos…', '');

            fetch(queueUrl + '?scope=' + scope, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    queue = data.photos || [];
                    idx = 0;
                    startBtn.disabled = false;
                    if (!queue.length) {
                        log('Nenhuma foto para processar.');
                        setProgress(1, 1, 'Nada a fazer', '');
                        running = false;
                        setStartAppearance();
                        reprocessBtn.disabled = false;
                        syncLogVisibility();
                        return;
                    }
                    return window.FaceEngine.preload().then(function () {
                        running = true;
                        paused = false;
                        setPauseAppearance();
                        reprocessBtn.disabled = true;
                        log('Modelos carregados. Processando ' + queue.length + ' foto(s) (' + scope + ').');
                        syncLogVisibility();
                        processNext();
                    });
                })
                .catch(function (err) {
                    var msg = err && err.message ? err.message : String(err);
                    log('Falha ao iniciar: ' + msg, 'error');
                    setProgress(0, 1, 'Falha ao carregar modelos', '');
                    running = false;
                    paused = false;
                    startBtn.disabled = false;
                    setStartAppearance();
                    reprocessBtn.disabled = false;
                    syncLogVisibility();
                });
        }

        startBtn.addEventListener('click', function () {
            if (running && !paused) {
                pauseProcessing();
                return;
            }
            if (paused && idx < queue.length) {
                resumeProcessing();
                return;
            }
            begin('pending');
        });
        reprocessBtn.addEventListener('click', function () {
            if (running && !paused) return;
            if (typeof window.admConfirm === 'function') {
                window.admConfirm('Reprocessar TODAS as fotos deste álbum? Os descritores atuais serão substituídos.', function () { begin('all'); }, { title: 'Reprocessar tudo', confirmLabel: 'Reprocessar', confirmIcon: 'arrow-repeat' });
            } else {
                begin('all');
            }
        });
        logToggle.addEventListener('click', function () {
            if (logPanel.hidden) return;
            logOpen = !logOpen;
            logEl.hidden = !logOpen;
            logToggle.setAttribute('aria-expanded', logOpen ? 'true' : 'false');
            logToggle.classList.toggle('is-open', logOpen);
        });
    })();
    </script>
@endsection
