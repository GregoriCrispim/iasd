@extends('doutores.layouts.secured')

@php $activeNav = 'doutores-area-inscricoes'; @endphp
@section('title', 'Minhas inscrições')
@section('heading', 'Minhas inscrições')

@section('content')
    <div class="card">
        <div class="table-wrap">
            <table class="adm-table">
                <thead><tr><th>Data</th><th>Hospital</th><th>Função</th><th>Status</th><th>Presença</th><th class="col-actions">Ações</th></tr></thead>
                <tbody>
                    @forelse ($inscricoes as $i)
                        @php $ativa = in_array($i->status, ['pendente', 'confirmada'], true); @endphp
                        <tr>
                            <td>{{ $i->data?->format('d/m/Y') ?? '—' }}</td>
                            <td><strong>{{ $i->hospital ?? $i->plantao?->hospital }}</strong></td>
                            <td>{{ $i->papel ?? '—' }}</td>
                            <td>@include('doutores.partials.badge', ['status' => $i->status])</td>
                            <td>
                                @if ($i->confirmada_por_voluntario_em)
                                    <span class="badge badge-green">Confirmada em {{ $i->confirmada_por_voluntario_em->format('d/m') }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="col-actions">
                                @if ($ativa)
                                    <div class="row-actions">
                                        @unless ($i->confirmada_por_voluntario_em)
                                            <form method="POST" action="{{ route('doutores.area.inscricoes.confirmar', $i) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" title="Confirmar presença"><i class="bi bi-check2"></i></button>
                                            </form>
                                        @endunless
                                        <form method="POST" action="{{ route('doutores.area.inscricoes.destroy', $i) }}"
                                              onsubmit="return admConfirm('Cancelar sua inscrição neste plantão?', this, { title: 'Cancelar inscrição', confirmLabel: 'Cancelar inscrição', confirmIcon: 'x-circle' });">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Cancelar inscrição"><i class="bi bi-x-lg"></i></button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="bi bi-journal-x"></i>Você ainda não se inscreveu em nenhum plantão.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="adm-pagination">{{ $inscricoes->links() }}</div>
    </div>
@endsection
