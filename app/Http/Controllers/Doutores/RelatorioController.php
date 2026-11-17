<?php

namespace App\Http\Controllers\Doutores;

use App\Models\Doutores\Inscricao;
use App\Models\Doutores\Plantao;
use App\Models\Doutores\Profile;
use App\Support\DoutoresPoloScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RelatorioController extends DoutoresController
{
    public function index(Request $request): View
    {
        $plantoes = $this->plantoesQuery($request)
            ->withCount(['inscricoes as presentes_count' => fn (Builder $q) => $q->where('status', Inscricao::STATUS_CONFIRMADA)])
            ->orderByDesc('data')
            ->limit(200)
            ->get();

        $totais = [
            'plantoes' => $plantoes->count(),
            'realizados' => $plantoes->where('status', Plantao::STATUS_REALIZADO)->count(),
            'voluntarios_escalados' => $plantoes->sum('presentes_count'),
            'quartos' => $plantoes->sum('quartos_visitados'),
            'visitas' => $plantoes->sum('visitas_realizadas'),
            'livros' => $plantoes->sum('livros_distribuidos'),
        ];

        $voluntarios = $this->voluntariosQuery($request)->count();

        return view('doutores.relatorios.index', [
            'plantoes' => $plantoes,
            'totais' => $totais,
            'voluntariosTotal' => $voluntarios,
            'hospitais' => $this->hospitalNames(),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $tipo = $request->string('tipo')->toString() === 'voluntarios' ? 'voluntarios' : 'plantoes';

        $this->log('relatorio.exportado', 'relatorio', "Exportação CSV de {$tipo}.");

        $filename = "doutores-{$tipo}-".now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($request, $tipo) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM UTF-8 para Excel

            if ($tipo === 'plantoes') {
                $this->csvRow($out, ['ID', 'Data', 'Início', 'Fim', 'Hospital', 'Tipo', 'Status', 'Inscrições ativas', 'Quartos visitados', 'Visitas realizadas', 'Livros distribuídos']);

                $this->plantoesQuery($request)
                    ->withCount(['inscricoes as ativas_count' => fn (Builder $q) => $q->whereIn('status', [Inscricao::STATUS_PENDENTE, Inscricao::STATUS_CONFIRMADA])])
                    ->orderByDesc('data')
                    ->chunk(200, function ($rows) use ($out) {
                        foreach ($rows as $p) {
                            $this->csvRow($out, [
                                $p->id,
                                $p->data?->format('d/m/Y'),
                                $p->hora_inicio,
                                $p->hora_fim,
                                $p->hospital,
                                Plantao::TIPOS[$p->tipo] ?? $p->tipo,
                                Plantao::STATUSES[$p->status] ?? $p->status,
                                $p->ativas_count,
                                $p->quartos_visitados,
                                $p->visitas_realizadas,
                                $p->livros_distribuidos,
                            ]);
                        }
                    });
            } else {
                $this->csvRow($out, ['ID', 'Nome', 'E-mail', 'CPF', 'Telefone', 'Cidade', 'Hospital preferido', 'Igreja', 'Cargo', 'Status', 'Termos aceitos em', 'Cadastro']);

                $this->voluntariosQuery($request)
                    ->with('user')
                    ->orderBy('id')
                    ->chunk(200, function ($rows) use ($out) {
                        foreach ($rows as $p) {
                            $this->csvRow($out, [
                                $p->id,
                                $p->user?->name,
                                $p->user?->email,
                                $p->cpf,
                                $p->telefone,
                                $p->cidade,
                                $p->hospital_preferido,
                                $p->igreja,
                                $p->cargo,
                                $p->status,
                                $p->termos_aceitos_em?->format('d/m/Y H:i'),
                                $p->created_at?->format('d/m/Y H:i'),
                            ]);
                        }
                    });
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * @return Builder<Plantao>
     */
    protected function plantoesQuery(Request $request): Builder
    {
        return $this->scopeByHospital(Plantao::query())
            ->when($request->filled('hospital'), fn (Builder $q) => $q->where('hospital', $request->string('hospital')->toString()))
            ->when($request->filled('de'), fn (Builder $q) => $q->whereDate('data', '>=', $request->string('de')->toString()))
            ->when($request->filled('ate'), fn (Builder $q) => $q->whereDate('data', '<=', $request->string('ate')->toString()));
    }

    /**
     * @return Builder<Profile>
     */
    protected function voluntariosQuery(Request $request): Builder
    {
        $query = Profile::query()
            ->when($request->filled('hospital'), fn (Builder $q) => $q->where('hospital_preferido', $request->string('hospital')->toString()))
            ->when($request->filled('status'), fn (Builder $q) => $q->where('status', $request->string('status')->toString()));

        return DoutoresPoloScope::filterProfileQuery($query, $this->user(), $request);
    }

    /**
     * @param  resource  $out
     * @param  array<int, mixed>  $row
     */
    protected function csvRow($out, array $row): void
    {
        $row = array_map(function ($value) {
            $value = (string) ($value ?? '');
            // Evita injeção de fórmulas em planilhas.
            if ($value !== '' && in_array($value[0], ['=', '+', '-', '@', "\t", "\r"], true)) {
                $value = "'".$value;
            }

            return $value;
        }, $row);

        fputcsv($out, $row, ';');
    }
}
