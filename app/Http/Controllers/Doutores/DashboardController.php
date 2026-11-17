<?php

namespace App\Http\Controllers\Doutores;

use App\Models\Doutores\Inscricao;
use App\Models\Doutores\Plantao;
use App\Models\Doutores\Profile;
use App\Models\Doutores\SolicitacaoEstudo;
use App\Support\DoutoresPoloScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends DoutoresController
{
    public function index(Request $request): View
    {
        $user = $this->user();
        $inicio = now()->startOfMonth()->subMonths(11);

        $meses = collect(range(0, 11))->map(fn (int $i) => $inicio->copy()->addMonths($i));

        $plantoes = $this->porMes(
            $this->scopeByHospital(Plantao::query())->whereDate('data', '>=', $inicio->toDateString()),
            'data',
            $meses
        );

        $inscricoes = $this->porMes(
            $this->scopeByHospital(Inscricao::query())->where('created_at', '>=', $inicio),
            'created_at',
            $meses
        );

        $voluntarios = $this->porMes(
            DoutoresPoloScope::filterProfileQuery(Profile::query(), $user, $request)->where('created_at', '>=', $inicio),
            'created_at',
            $meses
        );

        $estudos = $this->porMes(
            $this->scopeByHospital(SolicitacaoEstudo::query(), 'hospital', true)->where('created_at', '>=', $inicio),
            'created_at',
            $meses
        );

        $resultados = $this->scopeByHospital(Plantao::query())
            ->where('status', Plantao::STATUS_REALIZADO)
            ->selectRaw('coalesce(sum(quartos_visitados),0) as quartos, coalesce(sum(visitas_realizadas),0) as visitas, coalesce(sum(livros_distribuidos),0) as livros')
            ->first();

        return view('doutores.dashboard', [
            'labels' => $meses->map(fn (Carbon $m) => $m->translatedFormat('M/y'))->all(),
            'series' => [
                'Plantões' => $plantoes,
                'Inscrições' => $inscricoes,
                'Novos voluntários' => $voluntarios,
                'Solicitações de estudo' => $estudos,
            ],
            'resultados' => [
                'quartos' => (int) ($resultados->quartos ?? 0),
                'visitas' => (int) ($resultados->visitas ?? 0),
                'livros' => (int) ($resultados->livros ?? 0),
            ],
        ]);
    }

    /**
     * Contagem por mês (agrupamento em PHP para ser agnóstico ao banco).
     *
     * @param  Builder<\Illuminate\Database\Eloquent\Model>  $query
     * @param  Collection<int, Carbon>  $meses
     * @return list<int>
     */
    protected function porMes(Builder $query, string $coluna, Collection $meses): array
    {
        $contagem = $query->pluck($coluna)
            ->filter()
            ->map(fn ($valor) => Carbon::parse($valor)->format('Y-m'))
            ->countBy();

        return $meses->map(fn (Carbon $m) => (int) ($contagem[$m->format('Y-m')] ?? 0))->all();
    }
}
