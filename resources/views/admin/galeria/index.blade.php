@extends('admin.layout')

@php
    $activeNav = 'galeria';
    $openCreateAlbum = request()->boolean('novo') || ($errors->any() && old('_form') === 'create');
    if ($openCreateAlbum && $errors->any()) {
        view()->share('hideGlobalErrors', true);
    }
    $albumEditReturn = 'index';
    $albumEditOpenId = request('editar');

    // Só reaproveita os valores antigos se a falha de validação veio deste formulário.
    $createOld = fn (string $field, $default = null) => old('_form') === 'create' ? old($field, $default) : $default;
    $createError = fn (string $field) => old('_form') === 'create' && $errors->has($field);
@endphp
@section('title', 'Galeria de fotos')
@section('heading', 'Galeria de fotos')

@section('actions')
    <button type="button" class="btn" onclick="admOpenAlbumCreateModal()">
        <i class="bi bi-plus-lg"></i> Novo álbum
    </button>
@endsection

@section('content')
    <div class="card">
        <form method="GET" class="filters">
            <div class="field">
                <label>Buscar</label>
                <input type="text" name="q" value="{{ request('q') }}" class="input" placeholder="Título ou slug...">
            </div>
            <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filtrar</button>
        </form>

        <div class="table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Álbum</th>
                        <th class="text-center">Data</th>
                        <th class="text-center">Fotos</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Atualizado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($albums as $album)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:12px;">
                                    @if ($album->coverThumbUrl())
                                        <img src="{{ $album->coverThumbUrl() }}" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:6px;" loading="lazy" decoding="async">
                                    @else
                                        <div style="width:48px;height:48px;border-radius:6px;background:#e8eef5;display:flex;align-items:center;justify-content:center;color:#003366;"><i class="bi bi-image"></i></div>
                                    @endif
                                    <div>
                                        <strong>{{ $album->title }}</strong>
                                        <div class="text-muted" style="font-size:0.8rem;"><code>{{ $album->slug }}</code></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted nowrap text-center">{{ $album->dateShort() ?? '—' }}</td>
                            <td class="text-center">{{ $album->photos_count }}</td>
                            <td class="text-center">
                                @if ($album->is_published)
                                    <span class="badge" style="background:#e6f4ea;color:#137333;">Publicado</span>
                                @else
                                    <span class="badge" style="background:#f1f3f4;color:#5f6368;">Rascunho</span>
                                @endif
                            </td>
                            <td class="text-muted nowrap text-center">{{ $album->updated_at?->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('admin.galeria.show', $album) }}" class="btn btn-secondary btn-sm" title="Fotos"><i class="bi bi-images"></i></a>
                                    <button
                                        type="button"
                                        class="btn btn-secondary btn-sm"
                                        title="Editar"
                                        data-album-edit
                                        data-album-id="{{ $album->id }}"
                                        data-action="{{ route('admin.galeria.update', $album) }}"
                                        data-album-title="{{ $album->title }}"
                                        data-album-date="{{ $album->event_date?->format('Y-m-d') }}"
                                        data-album-description="{{ $album->description }}"
                                        data-album-published="{{ $album->is_published ? '1' : '0' }}"
                                        onclick="admOpenAlbumEditModal(this)"
                                    ><i class="bi bi-pencil"></i></button>
                                    <form method="POST" action="{{ route('admin.galeria.destroy', $album) }}" onsubmit="return admConfirm('Remover este álbum e todas as fotos? Esta ação não pode ser desfeita.', this, { title: 'Remover álbum' });">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Remover"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="bi bi-images"></i>Nenhum álbum cadastrado.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="adm-pagination">{{ $albums->links() }}</div>
    </div>

    <dialog id="album-create-modal" class="adm-dialog adm-dialog--album" aria-labelledby="album-create-title">
        <form method="POST" action="{{ route('admin.galeria.store') }}" id="album-create-form">
            @csrf
            <input type="hidden" name="_form" value="create">

            <div class="adm-album-modal-head">
                <div>
                    <h3 id="album-create-title">Novo álbum</h3>
                    <p>Defina o título e os detalhes do evento. Você poderá enviar as fotos em seguida.</p>
                </div>
                <button type="button" class="adm-album-modal-close" onclick="admCloseDialog('album-create-modal')" aria-label="Fechar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="adm-album-modal-body">
                @if ($openCreateAlbum && $errors->any())
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
                    <label for="album-title">Título <span class="req">*</span></label>
                    <input
                        type="text"
                        id="album-title"
                        name="title"
                        class="input {{ $createError('title') ? 'has-error' : '' }}"
                        value="{{ $createOld('title') }}"
                        required
                        maxlength="255"
                        placeholder="Ex.: Culto de Ação de Graças"
                        autocomplete="off"
                    >
                </div>

                <div class="form-row">
                    <div class="field">
                        <label for="album-event-date">Data do evento</label>
                        <input
                            type="date"
                            id="album-event-date"
                            name="event_date"
                            class="input {{ $createError('event_date') ? 'has-error' : '' }}"
                            value="{{ $createOld('event_date', now()->format('Y-m-d')) }}"
                        >
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
                                <input type="checkbox" name="is_published" value="1" {{ $createOld('is_published', '1') == '1' ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label for="album-description">Descrição</label>
                    <textarea
                        id="album-description"
                        name="description"
                        class="input {{ $createError('description') ? 'has-error' : '' }}"
                        rows="3"
                        maxlength="5000"
                        placeholder="Opcional — contexto do evento ou do álbum"
                    >{{ $createOld('description') }}</textarea>
                </div>
            </div>

            <div class="adm-album-modal-foot">
                <button type="button" class="btn btn-secondary" onclick="admCloseDialog('album-create-modal')">Cancelar</button>
                <button type="submit" class="btn"><i class="bi bi-check-lg"></i> Criar álbum</button>
            </div>
        </form>
    </dialog>

    @include('admin.galeria.partials.edit-modal')
@endsection

@push('scripts')
<script>
    function admOpenAlbumCreateModal(keepValues) {
        if (!keepValues) {
            var form = document.getElementById('album-create-form');
            if (form) form.reset();

            var date = document.getElementById('album-event-date');
            if (date) {
                var now = new Date();
                now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                date.value = now.toISOString().slice(0, 10);
            }
        }

        admOpenDialog('album-create-modal');
        requestAnimationFrame(function () {
            var title = document.getElementById('album-title');
            if (title) title.focus();
        });
    }
    @if ($openCreateAlbum)
    document.addEventListener('DOMContentLoaded', function () {
        admOpenAlbumCreateModal({{ $errors->any() ? 'true' : 'false' }});
    });
    @endif
</script>
@endpush
