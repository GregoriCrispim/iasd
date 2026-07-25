@extends('admin.layout')

@php
    $activeNav = 'galeria';
    $albumEditReturn = 'show';
@endphp
@section('title', $album->title)
@section('heading', $album->title)

@section('actions')
    <a href="{{ route('admin.galeria.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Álbuns</a>
    <button
        type="button"
        class="btn btn-secondary"
        data-album-edit
        data-album-id="{{ $album->id }}"
        data-action="{{ route('admin.galeria.update', $album) }}"
        data-album-title="{{ $album->title }}"
        data-album-date="{{ $album->event_date?->format('Y-m-d') }}"
        data-album-description="{{ $album->description }}"
        data-album-published="{{ $album->is_published ? '1' : '0' }}"
        onclick="admOpenAlbumEditModal(this)"
    ><i class="bi bi-pencil"></i> Editar</button>
    @if ($album->is_published)
        <a href="{{ route('galeria.show', $album->slug) }}" class="btn btn-secondary" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i> Ver no site</a>
    @endif
@endsection

@section('content')
    <div class="card" style="margin-bottom:16px;">
        <div class="card-body">
            <p class="mt-0 text-muted">
                {{ $album->dateLong() ?? 'Sem data' }}
                · {{ $album->photos->count() }} foto{{ $album->photos->count() !== 1 ? 's' : '' }}
                ·
                @if ($album->is_published)
                    <strong style="color:#137333;">Publicado</strong>
                @else
                    <strong style="color:#5f6368;">Rascunho</strong>
                    <span class="text-muted"> — ative “Visível no site” em Editar para aparecer na galeria pública</span>
                @endif
            </p>

            <div
                class="gal-upload"
                id="galUpload"
                data-url="{{ route('admin.galeria.upload', $album) }}"
                data-csrf="{{ csrf_token() }}"
                data-cover-url-template="{{ route('admin.galeria.photos.cover', [$album, '__PID__']) }}"
                data-destroy-url-template="{{ route('admin.galeria.photos.destroy', [$album, '__PID__']) }}"
            >
                <div class="gal-dropzone" id="galDropzone" tabindex="0" role="button" aria-label="Área para soltar fotos">
                    <div class="gal-dropzone-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                    <p class="gal-dropzone-title">Arraste fotos ou pastas aqui</p>
                    <p class="gal-dropzone-hint">JPEG, PNG, GIF ou WebP · até 15 MB cada · pastas são filtradas automaticamente</p>
                    <p class="gal-dropzone-hint">As fotos são reduzidas e convertidas para WebP no envio, com miniatura própria para o site</p>
                    <div class="gal-dropzone-actions">
                        <button type="button" class="btn btn-secondary btn-sm" id="galPickFiles">
                            <i class="bi bi-file-earmark-image"></i> Selecionar arquivos
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" id="galPickFolder">
                            <i class="bi bi-folder2-open"></i> Selecionar pasta
                        </button>
                    </div>
                    <input type="file" id="galFileInput" accept="image/jpeg,image/png,image/gif,image/webp,.jpg,.jpeg,.png,.gif,.webp" multiple hidden>
                    <input type="file" id="galFolderInput" accept="image/jpeg,image/png,image/gif,image/webp,.jpg,.jpeg,.png,.gif,.webp" multiple webkitdirectory directory hidden>
                </div>

                <div class="gal-progress" id="galProgress" hidden>
                    <div class="gal-progress-meta">
                        <span id="galProgressLabel">Preparando…</span>
                        <span id="galProgressPct">0%</span>
                    </div>
                    <div class="gal-progress-track" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" id="galProgressBar">
                        <div class="gal-progress-fill" id="galProgressFill"></div>
                    </div>
                    <div class="gal-progress-sub" id="galProgressSub"></div>
                </div>

                <div class="gal-queue" id="galQueue" hidden>
                    <div class="gal-queue-head">
                        <strong id="galQueueCount">0 imagens na fila</strong>
                        <button type="button" class="btn btn-ghost btn-sm gal-queue-clear" id="galClearQueue" disabled>
                            <i class="bi bi-x-circle"></i> Limpar
                        </button>
                    </div>
                    <ul class="gal-queue-list" id="galQueueList"></ul>
                </div>

                <div class="gal-log-panel" id="galLogPanel" hidden>
                    <button type="button" class="gal-log-toggle" id="galLogToggle" aria-expanded="false">
                        <i class="bi bi-terminal"></i>
                        <span>Log de envio</span>
                        <i class="bi bi-chevron-down gal-log-chevron"></i>
                    </button>
                    <pre class="gal-log" id="galLog" hidden></pre>
                </div>

                <div class="gal-upload-actions">
                    <button type="button" class="btn" id="galSubmit" disabled>
                        <i class="bi bi-upload"></i> <span id="galSubmitLabel">Enviar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-head"><h2>Fotos do álbum</h2> <span class="text-muted" id="galAlbumCount" style="margin-left:auto;font-size:0.85rem;">{{ $album->photos->count() }} foto{{ $album->photos->count() !== 1 ? 's' : '' }}</span></div>
        <div class="card-body">
            <div class="empty-state" id="galAlbumEmpty" @if ($album->photos->isNotEmpty()) hidden @endif><i class="bi bi-image"></i>Nenhuma foto ainda. Envie a primeira acima.</div>
            <div class="gal-photo-grid" id="galAlbumGrid" @if ($album->photos->isEmpty()) hidden @endif></div>
            <nav class="gal-pagination" id="galAlbumPagination" aria-label="Paginação das fotos" hidden></nav>
            <script type="application/json" id="galAlbumPhotosData">{!! json_encode($photos, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
        </div>
    </div>

    <div class="gal-lightbox" id="galLightbox" hidden>
        <button type="button" class="gal-lightbox-close" id="galLightboxClose" aria-label="Fechar">
            <i class="bi bi-x-lg"></i>
        </button>
        <img src="" alt="" id="galLightboxImg">
        <p class="gal-lightbox-caption" id="galLightboxCaption"></p>
    </div>

    @include('admin.galeria.partials.edit-modal')
@endsection

@push('scripts')
<script>
(function () {
    var root = document.getElementById('galUpload');
    if (!root) return;

    var uploadUrl = root.dataset.url;
    var csrf = root.dataset.csrf;
    var coverUrlTemplate = root.dataset.coverUrlTemplate;
    var destroyUrlTemplate = root.dataset.destroyUrlTemplate;
    var albumGrid = document.getElementById('galAlbumGrid');
    var albumPagination = document.getElementById('galAlbumPagination');
    var albumPhotosData = document.getElementById('galAlbumPhotosData');
    var albumEmpty = document.getElementById('galAlbumEmpty');
    var albumCount = document.getElementById('galAlbumCount');
    var dropzone = document.getElementById('galDropzone');
    var fileInput = document.getElementById('galFileInput');
    var folderInput = document.getElementById('galFolderInput');
    var pickFiles = document.getElementById('galPickFiles');
    var pickFolder = document.getElementById('galPickFolder');
    var queueWrap = document.getElementById('galQueue');
    var queueList = document.getElementById('galQueueList');
    var queueCount = document.getElementById('galQueueCount');
    var clearBtn = document.getElementById('galClearQueue');
    var submitBtn = document.getElementById('galSubmit');
    var submitLabel = document.getElementById('galSubmitLabel');
    var progressWrap = document.getElementById('galProgress');
    var progressLabel = document.getElementById('galProgressLabel');
    var progressPct = document.getElementById('galProgressPct');
    var progressFill = document.getElementById('galProgressFill');
    var progressBar = document.getElementById('galProgressBar');
    var progressSub = document.getElementById('galProgressSub');
    var logPanel = document.getElementById('galLogPanel');
    var logToggle = document.getElementById('galLogToggle');
    var logEl = document.getElementById('galLog');

    var ACCEPT_MIME = { 'image/jpeg': 1, 'image/png': 1, 'image/gif': 1, 'image/webp': 1 };
    var ACCEPT_EXT = /\.(jpe?g|png|gif|webp)$/i;
    var MAX_BYTES = 15 * 1024 * 1024;
    var PHOTO_PAGE_SIZE = 30;
    var queue = [];
    var uploading = false;
    var logOpen = false;
    var albumPhotos = [];
    var albumPhotoCount = 0;
    var currentAlbumPage = 1;
    var albumPageCache = {};
    var loadedThumbs = {};

    try {
        albumPhotos = JSON.parse(albumPhotosData ? albumPhotosData.textContent : '[]');
    } catch (error) {
        albumPhotos = [];
    }
    albumPhotoCount = albumPhotos.length;

    function markThumbLoaded(img, url) {
        if (!img) return;
        if (url) loadedThumbs[url] = 1;
        img.loading = 'eager';
        img.fetchPriority = 'auto';
        img.dataset.loaded = '1';
        img.removeAttribute('data-src');
    }

    function assignThumb(img, url) {
        if (!img || !url) return;
        if (img.dataset.loaded === '1' && (img.currentSrc === url || img.getAttribute('src') === url)) {
            return;
        }
        img.src = url;
        img.removeAttribute('data-src');
        if (loadedThumbs[url] || (img.complete && img.naturalWidth > 0)) {
            markThumbLoaded(img, url);
        }
    }

    function formatBytes(n) {
        if (n < 1024) return n + ' B';
        if (n < 1048576) return (n / 1024).toFixed(1) + ' KB';
        return (n / 1048576).toFixed(1) + ' MB';
    }

    function plural(n, one, many) {
        return n === 1 ? one : many;
    }

    function countPhrase(n, one, many) {
        return n + ' ' + plural(n, one, many);
    }

    function isImageFile(file) {
        if (!file || !file.name) return false;
        if (file.type && ACCEPT_MIME[file.type]) return true;
        if ((!file.type || file.type === 'application/octet-stream') && ACCEPT_EXT.test(file.name)) return true;
        return false;
    }

    function fileKey(file) {
        return [file.name, file.size, file.lastModified, file.webkitRelativePath || ''].join('|');
    }

    function appendLog(line, level) {
        logPanel.hidden = false;
        var stamp = new Date().toLocaleTimeString('pt-BR');
        var prefix = level === 'error' ? '[erro] ' : (level === 'warn' ? '[aviso] ' : '');
        logEl.textContent += stamp + '  ' + prefix + line + '\n';
        logEl.scrollTop = logEl.scrollHeight;
    }

    function setProgress(pct, label, sub) {
        var p = Math.max(0, Math.min(100, Math.round(pct)));
        progressWrap.hidden = false;
        progressFill.style.width = p + '%';
        progressBar.setAttribute('aria-valuenow', String(p));
        progressPct.textContent = p + '%';
        if (label != null) progressLabel.textContent = label;
        if (sub != null) progressSub.textContent = sub;
    }

    function resetProgress() {
        progressWrap.hidden = true;
        progressFill.style.width = '0%';
        progressBar.setAttribute('aria-valuenow', '0');
        progressPct.textContent = '0%';
        progressLabel.textContent = 'Preparando…';
        progressSub.textContent = '';
    }

    function hideLog() {
        logPanel.hidden = true;
        logEl.hidden = true;
        logEl.textContent = '';
        logOpen = false;
        logToggle.setAttribute('aria-expanded', 'false');
        logToggle.classList.remove('is-open');
    }

    function buildPhotoCard(photo) {
        var card = document.createElement('div');
        card.className = 'gal-photo-card';
        card.setAttribute('data-photo-id', String(photo.id));

        var coverUrl = coverUrlTemplate.replace('__PID__', encodeURIComponent(photo.id));
        var destroyUrl = destroyUrlTemplate.replace('__PID__', encodeURIComponent(photo.id));
        var name = escapeHtml(photo.filename || '');
        var thumbUrl = photo.thumb_url || photo.url;

        card.innerHTML =
            '<div class="gal-photo-thumb">' +
                '<img alt="' + name + '" decoding="async" data-full="' + escapeHtml(photo.url) + '">' +
                (photo.is_cover ? '<span class="gal-photo-cover-badge" data-cover-badge>Capa</span>' : '') +
            '</div>' +
            '<div class="gal-photo-body">' +
                '<div class="text-muted gal-photo-name" title="' + name + '">' + name + '</div>' +
                '<div class="gal-photo-actions" data-is-cover="' + (photo.is_cover ? '1' : '0') + '">' +
                    '<form method="POST" action="' + coverUrl + '" class="gal-photo-cover-form"' + (photo.is_cover ? ' hidden' : '') + '>' +
                        '<input type="hidden" name="_token" value="' + csrf + '">' +
                        '<button type="submit" class="btn btn-secondary btn-sm" style="width:100%;justify-content:center;" title="Definir como capa"><i class="bi bi-star"></i></button>' +
                    '</form>' +
                    '<form method="POST" action="' + destroyUrl + '" style="flex:1;" onsubmit="return admConfirm(\'Remover esta foto? Esta ação não pode ser desfeita.\', this, { title: \'Remover foto\' });">' +
                        '<input type="hidden" name="_token" value="' + csrf + '">' +
                        '<input type="hidden" name="_method" value="DELETE">' +
                        '<button type="submit" class="btn btn-danger btn-sm" style="width:100%;justify-content:center;" title="Remover"><i class="bi bi-trash"></i></button>' +
                    '</form>' +
                '</div>' +
            '</div>';

        var img = card.querySelector('img');
        if (loadedThumbs[thumbUrl]) {
            img.src = thumbUrl;
            markThumbLoaded(img, thumbUrl);
        } else {
            img.addEventListener('load', function () { markThumbLoaded(img, thumbUrl); }, { once: true });
            img.addEventListener('error', function () { markThumbLoaded(img, thumbUrl); }, { once: true });
            assignThumb(img, thumbUrl);
        }
        return card;
    }

    function albumTotalPages() {
        return Math.max(1, Math.ceil(albumPhotos.length / PHOTO_PAGE_SIZE) || 1);
    }

    function albumPageWindow(page, pages) {
        if (pages <= 7) {
            var all = [];
            for (var i = 1; i <= pages; i++) all.push(i);
            return all;
        }
        var items = [1];
        var start = Math.max(2, page - 1);
        var end = Math.min(pages - 1, page + 1);
        if (start > 2) items.push('…');
        for (var n = start; n <= end; n++) items.push(n);
        if (end < pages - 1) items.push('…');
        items.push(pages);
        return items;
    }

    function renderAlbumPagination() {
        if (!albumPagination) return;
        var pages = albumTotalPages();
        if (albumPhotos.length === 0 || pages <= 1) {
            albumPagination.hidden = true;
            albumPagination.innerHTML = '';
            return;
        }

        albumPagination.hidden = false;
        var start = (currentAlbumPage - 1) * PHOTO_PAGE_SIZE + 1;
        var end = Math.min(currentAlbumPage * PHOTO_PAGE_SIZE, albumPhotos.length);
        var html = '<button type="button" class="gal-page-btn" data-page="prev"' + (currentAlbumPage === 1 ? ' disabled' : '') + ' aria-label="Página anterior"><i class="bi bi-chevron-left"></i></button>';

        albumPageWindow(currentAlbumPage, pages).forEach(function (item) {
            if (item === '…') {
                html += '<span class="gal-page-ellipsis" aria-hidden="true">…</span>';
                return;
            }
            html += '<button type="button" class="gal-page-btn' + (item === currentAlbumPage ? ' is-active' : '') + '" data-page="' + item + '"' +
                (item === currentAlbumPage ? ' aria-current="page"' : '') +
                ' aria-label="Página ' + item + '">' + item + '</button>';
        });

        html += '<button type="button" class="gal-page-btn" data-page="next"' + (currentAlbumPage === pages ? ' disabled' : '') + ' aria-label="Próxima página"><i class="bi bi-chevron-right"></i></button>';
        html += '<div class="gal-page-info">' + start + '–' + end + ' de ' + albumPhotos.length + ' fotos</div>';
        albumPagination.innerHTML = html;
    }

    function buildAlbumPageCards(page) {
        var start = (page - 1) * PHOTO_PAGE_SIZE;
        return albumPhotos.slice(start, start + PHOTO_PAGE_SIZE).map(buildPhotoCard);
    }

    function showAlbumPage(page, opts) {
        opts = opts || {};
        if (!albumGrid) return;
        if (albumPhotos.length === 0) {
            albumGrid.hidden = true;
            if (albumEmpty) albumEmpty.hidden = false;
            if (albumPagination) {
                albumPagination.hidden = true;
                albumPagination.innerHTML = '';
            }
            return;
        }

        if (albumEmpty) albumEmpty.hidden = true;
        albumGrid.hidden = false;
        currentAlbumPage = Math.min(Math.max(1, page), albumTotalPages());

        var cards = albumPageCache[currentAlbumPage];
        if (!cards) {
            cards = buildAlbumPageCards(currentAlbumPage);
            albumPageCache[currentAlbumPage] = cards;
        }

        albumGrid.replaceChildren.apply(albumGrid, cards);
        renderAlbumPagination();

        if (opts.scroll) {
            albumGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function invalidateAlbumPagesFrom(page) {
        Object.keys(albumPageCache).forEach(function (key) {
            if (Number(key) >= page) delete albumPageCache[key];
        });
    }

    function appendUploadedPhoto(photo) {
        if (!albumGrid) return;
        albumPhotos.push(photo);
        albumPhotoCount++;
        invalidateAlbumPagesFrom(albumTotalPages());
        if (albumCount) {
            albumCount.textContent = albumPhotoCount + ' foto' + (albumPhotoCount !== 1 ? 's' : '');
        }
        showAlbumPage(albumTotalPages(), { scroll: false });
    }

    if (albumPagination) {
        albumPagination.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-page]');
            if (!btn || btn.disabled) return;
            var value = btn.getAttribute('data-page');
            if (value === 'prev') showAlbumPage(currentAlbumPage - 1, { scroll: true });
            else if (value === 'next') showAlbumPage(currentAlbumPage + 1, { scroll: true });
            else showAlbumPage(Number(value), { scroll: true });
        });
    }

    function renderQueue() {
        var n = queue.length;
        queueWrap.hidden = n === 0;
        queueCount.textContent = n === 0
            ? '0 imagens na fila'
            : (n === 1 ? '1 imagem na fila' : n + ' imagens na fila');
        clearBtn.disabled = n === 0 || uploading;
        submitBtn.disabled = n === 0 || uploading;
        submitLabel.textContent = uploading ? 'Enviando…' : 'Enviar';

        queueList.innerHTML = '';
        var maxShow = 40;
        queue.slice(0, maxShow).forEach(function (item, idx) {
            var li = document.createElement('li');
            li.className = 'gal-queue-item' + (item.status ? ' is-' + item.status : '');
            var path = item.file.webkitRelativePath || item.file.name;
            li.innerHTML =
                '<span class="gal-queue-name" title="' + escapeHtml(path) + '">' + escapeHtml(path) + '</span>' +
                '<span class="gal-queue-size">' + formatBytes(item.file.size) + '</span>' +
                '<span class="gal-queue-status">' + statusLabel(item.status) + '</span>' +
                (uploading ? '' : '<button type="button" class="gal-queue-remove" data-idx="' + idx + '" title="Remover" aria-label="Remover"><i class="bi bi-x"></i></button>');
            queueList.appendChild(li);
        });
        if (n > maxShow) {
            var more = document.createElement('li');
            more.className = 'gal-queue-more';
            more.textContent = '… e mais ' + (n - maxShow) + ' arquivo(s)';
            queueList.appendChild(more);
        }
    }

    function statusLabel(s) {
        if (s === 'ok') return 'OK';
        if (s === 'error') return 'Erro';
        if (s === 'uploading') return '…';
        if (s === 'skipped') return 'Ignorado';
        return 'Pronto';
    }

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function addFiles(fileList, sourceLabel) {
        if (uploading) return;
        var added = 0;
        var skipped = 0;
        var tooBig = 0;
        var seen = {};
        queue.forEach(function (q) { seen[fileKey(q.file)] = 1; });

        Array.prototype.forEach.call(fileList, function (file) {
            if (!isImageFile(file)) {
                skipped++;
                return;
            }
            if (file.size > MAX_BYTES) {
                tooBig++;
                appendLog('Ignorado (maior que 15 MB): ' + file.name + ' — ' + formatBytes(file.size), 'warn');
                return;
            }
            var key = fileKey(file);
            if (seen[key]) return;
            seen[key] = 1;
            queue.push({ file: file, status: 'pending', key: key });
            added++;
        });

        if (added || skipped || tooBig) {
            logPanel.hidden = false;
            var parts = [];
            var origem = sourceLabel === 'Pasta'
                ? 'Da pasta'
                : (sourceLabel === 'Arrastar e soltar' ? 'Pelo arrastar e soltar' : 'Da seleção');

            if (added > 0) {
                parts.push(origem + ', ' + countPhrase(added, 'imagem entrou', 'imagens entraram') + ' na fila');
            } else {
                parts.push(origem + ', nenhuma imagem válida foi encontrada');
            }
            if (skipped > 0) {
                parts.push(countPhrase(skipped, 'arquivo que não é imagem foi ignorado', 'arquivos que não são imagens foram ignorados'));
            }
            if (tooBig > 0) {
                parts.push(countPhrase(tooBig, 'arquivo passou de 15 MB', 'arquivos passaram de 15 MB'));
            }
            appendLog(parts.join('. ') + '.');
        }
        resetProgress();
        renderQueue();
    }

    function readEntry(entry) {
        return new Promise(function (resolve, reject) {
            if (entry.isFile) {
                entry.file(resolve, reject);
                return;
            }
            if (!entry.isDirectory) {
                resolve(null);
                return;
            }
            var reader = entry.createReader();
            var all = [];
            function readBatch() {
                reader.readEntries(function (entries) {
                    if (!entries.length) {
                        Promise.all(all.map(readEntry)).then(function (nested) {
                            resolve(nested.flat().filter(Boolean));
                        }).catch(reject);
                        return;
                    }
                    all = all.concat(entries);
                    readBatch();
                }, reject);
            }
            readBatch();
        });
    }

    function collectFromDataTransfer(dt) {
        return new Promise(function (resolve) {
            var items = dt && dt.items;
            if (items && items.length && typeof items[0].webkitGetAsEntry === 'function') {
                var entries = [];
                for (var i = 0; i < items.length; i++) {
                    var entry = items[i].webkitGetAsEntry();
                    if (entry) entries.push(entry);
                }
                if (entries.length) {
                    Promise.all(entries.map(readEntry))
                        .then(function (nested) { resolve(nested.flat().filter(Boolean)); })
                        .catch(function () { resolve(Array.from(dt.files || [])); });
                    return;
                }
            }
            resolve(Array.from((dt && dt.files) || []));
        });
    }

    function uploadOne(item, index, total) {
        return new Promise(function (resolve) {
            item.status = 'uploading';
            renderQueue();

            var form = new FormData();
            form.append('_token', csrf);
            form.append('photos[]', item.file, item.file.name);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', uploadUrl);
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.upload.onprogress = function (e) {
                if (!e.lengthComputable) return;
                var filePct = e.loaded / e.total;
                var overall = ((index + filePct) / total) * 100;
                setProgress(overall, 'Enviando ' + (index + 1) + ' de ' + total, item.file.name);
            };

            xhr.onload = function () {
                var ok = xhr.status >= 200 && xhr.status < 300;
                var msg = '';
                var json = null;
                try {
                    json = JSON.parse(xhr.responseText || '{}');
                    msg = json.message || (json.errors && Object.values(json.errors).flat().join(' ')) || '';
                } catch (err) {
                    msg = xhr.responseText ? 'Resposta inválida do servidor' : '';
                }

                if (ok) {
                    item.status = 'ok';
                    if (json && json.photos && json.photos.length) {
                        json.photos.forEach(appendUploadedPhoto);
                    }
                    appendLog('Enviado: ' + item.file.name);
                } else {
                    item.status = 'error';
                    appendLog('Falha ao enviar ' + item.file.name + ' — ' + (msg || ('HTTP ' + xhr.status)), 'error');
                }
                renderQueue();
                resolve(ok);
            };

            xhr.onerror = function () {
                item.status = 'error';
                appendLog('Falha de rede ao enviar ' + item.file.name, 'error');
                resolve(false);
            };

            xhr.send(form);
        });
    }

    async function startUpload() {
        if (uploading || !queue.length) return;
        uploading = true;
        dropzone.classList.add('is-disabled');
        pickFiles.disabled = true;
        pickFolder.disabled = true;
        logPanel.hidden = false;
        if (!logOpen) {
            logOpen = true;
            logEl.hidden = false;
            logToggle.setAttribute('aria-expanded', 'true');
            logToggle.classList.add('is-open');
        }
        var items = queue.slice();
        var total = items.length;
        appendLog('Iniciando envio de ' + countPhrase(total, 'imagem', 'imagens') + '…');
        setProgress(0, 'Enviando 1 de ' + total, items[0].file.name);
        renderQueue();

        var ok = 0;
        var fail = 0;

        for (var i = 0; i < total; i++) {
            var item = items[i];
            setProgress((i / total) * 100, 'Enviando ' + (i + 1) + ' de ' + total, item.file.name);
            var success = await uploadOne(item, i, total);
            if (success) {
                ok++;
                var idx = queue.indexOf(item);
                if (idx !== -1) queue.splice(idx, 1);
            } else {
                fail++;
            }
            renderQueue();
        }

        setProgress(
            100,
            'Concluído',
            countPhrase(ok, 'enviada', 'enviadas') + (fail ? ', ' + countPhrase(fail, 'com erro', 'com erro') : '')
        );
        if (fail === 0) {
            appendLog('Envio concluído: ' + countPhrase(ok, 'imagem enviada com sucesso', 'imagens enviadas com sucesso') + '.');
        } else {
            appendLog(
                'Envio concluído: ' + countPhrase(ok, 'imagem enviada', 'imagens enviadas') +
                ', ' + countPhrase(fail, 'falhou', 'falharam') + '. As que falharam continuam na fila.',
                'warn'
            );
        }

        uploading = false;
        pickFiles.disabled = false;
        pickFolder.disabled = false;
        dropzone.classList.remove('is-disabled');
        renderQueue();
        submitLabel.textContent = 'Enviar';

        if (ok > 0 && typeof window.admToast === 'function') {
            window.admToast(
                countPhrase(ok, 'foto enviada', 'fotos enviadas') + (fail ? ', ' + fail + ' com erro' : '') + '.',
                fail ? 'warning' : 'success'
            );
        }
    }

    dropzone.addEventListener('click', function (e) {
        if (uploading) return;
        if (e.target.closest('button, input, label')) return;
        fileInput.click();
    });
    dropzone.addEventListener('keydown', function (e) {
        if (uploading) return;
        if (e.key !== 'Enter' && e.key !== ' ') return;
        e.preventDefault();
        fileInput.click();
    });
    pickFiles.addEventListener('click', function (e) {
        e.stopPropagation();
        fileInput.click();
    });
    pickFolder.addEventListener('click', function (e) {
        e.stopPropagation();
        folderInput.click();
    });
    fileInput.addEventListener('change', function () {
        addFiles(fileInput.files, 'Arquivos');
        fileInput.value = '';
    });
    folderInput.addEventListener('change', function () {
        addFiles(folderInput.files, 'Pasta');
        folderInput.value = '';
    });

    function clearQueue() {
        queue = [];
        resetProgress();
        hideLog();
        renderQueue();
    }

    clearBtn.addEventListener('click', function () {
        if (uploading || !queue.length) return;
        if (typeof window.admConfirm === 'function') {
            window.admConfirm('Limpar a fila de envio? As imagens selecionadas serão descartadas.', clearQueue, {
                title: 'Limpar fila',
                confirmLabel: 'Limpar',
                confirmIcon: 'x-circle'
            });
        } else {
            clearQueue();
        }
    });

    queueList.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-idx]');
        if (!btn || uploading) return;
        var idx = parseInt(btn.getAttribute('data-idx'), 10);
        if (isNaN(idx)) return;
        queue.splice(idx, 1);
        renderQueue();
    });

    submitBtn.addEventListener('click', startUpload);

    logToggle.addEventListener('click', function () {
        logOpen = !logOpen;
        logEl.hidden = !logOpen;
        logToggle.setAttribute('aria-expanded', logOpen ? 'true' : 'false');
        logToggle.classList.toggle('is-open', logOpen);
    });

    ;['dragenter', 'dragover'].forEach(function (ev) {
        dropzone.addEventListener(ev, function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (!uploading) dropzone.classList.add('is-dragover');
        });
    });
    ;['dragleave', 'drop'].forEach(function (ev) {
        dropzone.addEventListener(ev, function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.remove('is-dragover');
        });
    });
    dropzone.addEventListener('drop', function (e) {
        if (uploading) return;
        collectFromDataTransfer(e.dataTransfer).then(function (files) {
            addFiles(files, 'Arrastar e soltar');
        });
    });

    (function setupLightbox() {
        var lightbox = document.getElementById('galLightbox');
        var lightboxImg = document.getElementById('galLightboxImg');
        var lightboxCaption = document.getElementById('galLightboxCaption');
        var lightboxClose = document.getElementById('galLightboxClose');
        if (!lightbox || !albumGrid) return;

        function openLightbox(src, caption) {
            lightboxImg.src = src;
            lightboxImg.alt = caption || '';
            lightboxCaption.textContent = caption || '';
            lightbox.hidden = false;
            requestAnimationFrame(function () {
                lightbox.classList.add('is-open');
            });
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            lightbox.classList.remove('is-open');
            window.setTimeout(function () {
                lightbox.hidden = true;
                lightboxImg.removeAttribute('src');
                lightboxImg.alt = '';
                lightboxCaption.textContent = '';
            }, 200);
            document.body.style.overflow = '';
        }

        albumGrid.addEventListener('click', function (e) {
            if (e.target.closest('.gal-photo-actions, form, button, a')) return;
            var thumb = e.target.closest('.gal-photo-thumb');
            if (!thumb) return;
            var img = thumb.querySelector('img');
            if (!img || !img.dataset.full) return;
            openLightbox(img.dataset.full, img.alt || '');
        });

        lightboxClose.addEventListener('click', closeLightbox);
        lightbox.addEventListener('click', function (e) {
            if (e.target === lightbox) closeLightbox();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !lightbox.hidden) closeLightbox();
        });
    })();

    showAlbumPage(1, { scroll: false });
    renderQueue();
})();
</script>
@endpush
