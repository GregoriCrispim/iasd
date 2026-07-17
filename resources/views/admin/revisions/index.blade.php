@extends('admin.layout')

@php
    use App\Support\CmsWorkflow;
    $activeNav = 'revisions';
@endphp
@section('title', 'Revisões')
@section('heading', 'Revisões')

@section('actions')
    @if ($user->isSuperAdmin() || $user->pages()->wherePivot('can_edit', true)->exists())
        <a href="{{ route('admin.revisions.create') }}" class="btn"><i class="bi bi-plus-lg"></i> Nova revisão</a>
    @endif
@endsection

@section('content')
    <div class="card">
        <form method="GET" class="filters">
            <div class="field">
                <label>Status</label>
                <select name="status" class="select" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <noscript><button type="submit" class="btn btn-secondary">Filtrar</button></noscript>
        </form>

        <div class="table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Página</th>
                        <th>Bloco</th>
                        <th>Status</th>
                        <th>Autor</th>
                        <th>Criado</th>
                        <th>Publicado</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($revisions as $revision)
                        @php $meta = CmsWorkflow::statusMeta($revision->status); @endphp
                        <tr>
                            <td><strong>{{ $revision->block?->page?->label ?? '—' }}</strong></td>
                            <td>{{ $revision->block?->label ?? '—' }}</td>
                            <td><span class="badge {{ $meta['class'] }}">{{ $meta['label'] }}</span></td>
                            <td class="text-muted">{{ $revision->author?->name ?? '—' }}</td>
                            <td class="text-muted nowrap">{{ $revision->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="text-muted nowrap">{{ $revision->published_at?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td>
                                <div class="row-actions">
                                    @if ($revision->block?->page?->cms_enabled)
                                        <a href="{{ route('admin.cms.preview', $revision) }}" target="_blank" class="btn btn-ghost btn-sm" title="Preview"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('admin.cms.compare', $revision) }}" target="_blank" class="btn btn-ghost btn-sm" title="Comparar"><i class="bi bi-arrow-left-right"></i></a>
                                    @endif

                                    @if (CmsWorkflow::canEdit($user, $revision))
                                        <a href="{{ route('admin.revisions.edit', $revision) }}" class="btn btn-secondary btn-sm"><i class="bi bi-pencil"></i></a>
                                    @endif

                                    @if (CmsWorkflow::canSubmit($user, $revision))
                                        <form method="POST" action="{{ route('admin.revisions.submit', $revision) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm"><i class="bi bi-send"></i> Enviar</button>
                                        </form>
                                    @endif

                                    @if (CmsWorkflow::canApproveAsManager($user, $revision))
                                        <form method="POST" action="{{ route('admin.revisions.approveManager', $revision) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-lg"></i> Aprovar</button>
                                        </form>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="admOpenDialog('reject-{{ $revision->id }}')"><i class="bi bi-x-lg"></i></button>
                                    @endif

                                    @if (CmsWorkflow::canApproveAsSuperAdmin($user, $revision))
                                        <form method="POST" action="{{ route('admin.revisions.approveSuper', $revision) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-shield-check"></i> Publicar</button>
                                        </form>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="admOpenDialog('reject-{{ $revision->id }}')"><i class="bi bi-x-lg"></i></button>
                                    @endif

                                    @if ($user->isSuperAdmin())
                                        <form method="POST" action="{{ route('admin.revisions.destroy', $revision) }}" onsubmit="return confirm('Remover esta revisão?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-sm" title="Remover"><i class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
                                </div>

                                @if (CmsWorkflow::canApproveAsManager($user, $revision) || CmsWorkflow::canApproveAsSuperAdmin($user, $revision))
                                    <dialog id="reject-{{ $revision->id }}" class="adm-dialog">
                                        <form method="POST" action="{{ route('admin.revisions.reject', $revision) }}">
                                            @csrf
                                            <div class="dlg-head"><h3>Rejeitar revisão</h3></div>
                                            <div class="dlg-body">
                                                <div class="field">
                                                    <label>Motivo <span class="req">*</span></label>
                                                    <textarea name="comment" class="input" rows="4" required></textarea>
                                                </div>
                                            </div>
                                            <div class="dlg-foot">
                                                <button type="button" class="btn btn-secondary" onclick="admCloseDialog('reject-{{ $revision->id }}')">Cancelar</button>
                                                <button type="submit" class="btn btn-danger">Rejeitar</button>
                                            </div>
                                        </form>
                                    </dialog>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="empty-state"><i class="bi bi-pencil-square"></i>Nenhuma revisão encontrada.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="adm-pagination">{{ $revisions->links() }}</div>
    </div>
@endsection
