<?php

namespace App\Http\Controllers\Doutores;

use App\Models\Doutores\LogEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogController extends DoutoresController
{
    public function index(Request $request): View
    {
        $this->abortUnlessAdmin();

        $logs = LogEntry::query()
            ->when($request->filled('categoria'), fn (Builder $q) => $q->where('categoria', $request->string('categoria')->toString()))
            ->when($request->filled('severidade'), fn (Builder $q) => $q->where('severidade', $request->string('severidade')->toString()))
            ->when($request->filled('de'), fn (Builder $q) => $q->whereDate('registrado_em', '>=', $request->string('de')->toString()))
            ->when($request->filled('ate'), fn (Builder $q) => $q->whereDate('registrado_em', '<=', $request->string('ate')->toString()))
            ->when($request->filled('q'), function (Builder $q) use ($request) {
                $term = '%'.$request->string('q')->toString().'%';
                $q->where(fn (Builder $w) => $w->where('acao', 'like', $term)
                    ->orWhere('descricao', 'like', $term)
                    ->orWhere('executado_por', 'like', $term)
                    ->orWhere('usuario_alvo', 'like', $term));
            })
            ->orderByDesc('registrado_em')
            ->orderByDesc('id')
            ->paginate(50)
            ->withQueryString();

        return view('doutores.logs.index', [
            'logs' => $logs,
            'categorias' => LogEntry::query()->distinct()->orderBy('categoria')->pluck('categoria'),
        ]);
    }
}
