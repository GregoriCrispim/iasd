@php
    $albumEditReopen = $errors->any() && old('_form') === 'edit' && old('_album_id');
    if ($albumEditReopen) {
        view()->share('hideGlobalErrors', true);
    }
@endphp

<dialog id="album-edit-modal" class="adm-dialog adm-dialog--album" aria-labelledby="album-edit-title">
    <form method="POST" action="" id="album-edit-form">
        @csrf
        @method('PUT')
        <input type="hidden" name="_form" value="edit">
        <input type="hidden" name="_album_id" id="album-edit-id" value="">
        <input type="hidden" name="_return" value="{{ $albumEditReturn ?? 'index' }}">

        <div class="adm-album-modal-head">
            <div>
                <h3 id="album-edit-title">Editar álbum</h3>
                <p>Atualize o título, a data e a visibilidade deste álbum.</p>
            </div>
            <button type="button" class="adm-album-modal-close" onclick="admCloseDialog('album-edit-modal')" aria-label="Fechar">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="adm-album-modal-body">
            @if ($albumEditReopen)
                <div class="alert alert-danger" style="margin:0;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="field">
                <label for="album-edit-title-input">Título <span class="req">*</span></label>
                <input
                    type="text"
                    id="album-edit-title-input"
                    name="title"
                    class="input"
                    required
                    maxlength="255"
                    placeholder="Ex.: Culto de Ação de Graças"
                    autocomplete="off"
                >
            </div>

            <div class="form-row">
                <div class="field">
                    <label for="album-edit-event-date">Data do evento</label>
                    <input type="date" id="album-edit-event-date" name="event_date" class="input">
                </div>
                <div class="field">
                    <label>Publicação</label>
                    <div class="adm-album-publish">
                        <div>
                            <strong>Visível no site</strong>
                            <span>Aparece na galeria pública</span>
                        </div>
                        <input type="hidden" name="is_published" value="0">
                        <label class="switch" title="Publicar álbum">
                            <input type="checkbox" id="album-edit-published" name="is_published" value="1">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="field">
                <label for="album-edit-description">Descrição</label>
                <textarea
                    id="album-edit-description"
                    name="description"
                    class="input"
                    rows="3"
                    maxlength="5000"
                    placeholder="Opcional — contexto do evento ou do álbum"
                ></textarea>
            </div>

        </div>

        <div class="adm-album-modal-foot">
            <button type="button" class="btn btn-secondary" onclick="admCloseDialog('album-edit-modal')">Cancelar</button>
            <button type="submit" class="btn"><i class="bi bi-check-lg"></i> Salvar alterações</button>
        </div>
    </form>
</dialog>

@push('scripts')
<script>
    function admOpenAlbumEditModal(trigger, overrides) {
        var data = trigger.dataset;
        var values = overrides || {};

        document.getElementById('album-edit-form').setAttribute('action', data.action);
        document.getElementById('album-edit-id').value = data.albumId;
        document.getElementById('album-edit-title-input').value = values.title !== undefined ? values.title : data.albumTitle;
        document.getElementById('album-edit-event-date').value = values.event_date !== undefined ? values.event_date : data.albumDate;
        document.getElementById('album-edit-description').value = values.description !== undefined ? values.description : data.albumDescription;
        document.getElementById('album-edit-published').checked = values.is_published !== undefined
            ? values.is_published
            : data.albumPublished === '1';

        admOpenDialog('album-edit-modal');
        requestAnimationFrame(function () {
            document.getElementById('album-edit-title-input').focus();
        });
    }

    function admOpenAlbumEditModalById(albumId, overrides) {
        var trigger = document.querySelector('[data-album-edit][data-album-id="' + albumId + '"]');
        if (trigger) admOpenAlbumEditModal(trigger, overrides);
    }

    @if ($albumEditReopen)
    document.addEventListener('DOMContentLoaded', function () {
        admOpenAlbumEditModalById(@json(old('_album_id')), {
            title: @json(old('title')),
            event_date: @json(old('event_date')),
            description: @json(old('description')),
            is_published: @json(old('is_published') === '1'),
        });
    });
    @elseif (!empty($albumEditOpenId))
    document.addEventListener('DOMContentLoaded', function () {
        admOpenAlbumEditModalById(@json((string) $albumEditOpenId));
    });
    @endif
</script>
@endpush
