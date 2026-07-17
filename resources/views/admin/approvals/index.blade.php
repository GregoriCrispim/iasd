@extends('admin.layout')

@php
    use App\Support\CmsWorkflow;
    $activeNav = 'approvals';
@endphp
@section('title', 'Aprovações')
@section('heading', 'Caixa de aprovações')

@section('content')
    <div class="card">
        <div class="table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Página</th>
                        <th>Bloco</th>
                        <th>Autor</th>
                        <th>Status</th>
                        <th>Enviado</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($revisions as $revision)
                        @php $meta = CmsWorkflow::statusMeta($revision->status); @endphp
                        <tr>
                            <td><strong>{{ $revision->block?->page?->label ?? '—' }}</strong></td>
                            <td>{{ $revision->block?->label ?? '—' }}</td>
                            <td class="text-muted">{{ $revision->author?->name ?? '—' }}</td>
                            <td><span class="badge {{ $meta['class'] }}">{{ $meta['label'] }}</span></td>
                            <td class="text-muted nowrap">{{ $revision->submitted_at?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td>
                                <div class="row-actions">
                                    @if ($revision->block?->page?->cms_enabled)
                                        <a href="{{ route('admin.cms.preview', $revision) }}" target="_blank" class="btn btn-ghost btn-sm" title="Preview"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('admin.cms.compare', $revision) }}" target="_blank" class="btn btn-ghost btn-sm" title="Comparar"><i class="bi bi-arrow-left-right"></i></a>
                                    @endif
                                    <form method="POST" action="{{ route('admin.approvals.approve', $revision) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-lg"></i> Aprovar</button>
                                    </form>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="admOpenDialog('reject-{{ $revision->id }}')"><i class="bi bi-x-lg"></i> Rejeitar</button>
                                </div>

                                <dialog id="reject-{{ $revision->id }}" class="adm-dialog">
                                    <form method="POST" action="{{ route('admin.approvals.reject', $revision) }}">
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
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="bi bi-inbox"></i>Nenhuma revisão aguardando sua aprovação.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="adm-pagination">{{ $revisions->links() }}</div>
    </div>
@endsection
