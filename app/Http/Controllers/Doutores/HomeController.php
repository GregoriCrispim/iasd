<?php

namespace App\Http\Controllers\Doutores;

use App\Models\Doutores\Inscricao;
use App\Models\Doutores\Plantao;
use App\Models\Doutores\Profile;
use App\Models\Doutores\SolicitacaoEstudo;
use App\Support\DoutoresPoloScope;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends DoutoresController
{
    public function index(Request $request): View
    {
        $user = $this->user();

        $profiles = fn () => DoutoresPoloScope::filterProfileQuery(Profile::query(), $user, $request);
        $plantoes = fn () => $this->scopeByHospital(Plantao::query());
        $estudos = fn () => $this->scopeByHospital(SolicitacaoEstudo::query(), 'hospital', true);

        $stats = [
            'voluntarios_ativos' => $profiles()->where('status', Profile::STATUS_ATIVO)->count(),
            'voluntarios_pendentes' => $profiles()->where('status', Profile::STATUS_PENDENTE)->count(),
            'plantoes_abertos' => $plantoes()->where('status', Plantao::STATUS_ABERTO)->count(),
            'estudos_pendentes' => $estudos()->where('status', 'pendente')->count(),
            'inscricoes_pendentes' => $this->scopeByHospital(Inscricao::query())
                ->where('status', Inscricao::STATUS_PENDENTE)
                ->count(),
        ];

        $proximosPlantoes = $plantoes()
            ->whereDate('data', '>=', now()->toDateString())
            ->whereIn('status', [Plantao::STATUS_ABERTO, Plantao::STATUS_FECHADO])
            ->withCount(['inscricoes' => fn ($q) => $q->whereIn('status', [Inscricao::STATUS_PENDENTE, Inscricao::STATUS_CONFIRMADA])])
            ->orderBy('data')
            ->limit(5)
            ->get();

        $estudosRecentes = $estudos()
            ->where('status', 'pendente')
            ->latest()
            ->limit(5)
            ->get();

        $pendentes = $profiles()
            ->where('status', Profile::STATUS_PENDENTE)
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('doutores.home', [
            'stats' => $stats,
            'proximosPlantoes' => $proximosPlantoes,
            'estudosRecentes' => $estudosRecentes,
            'pendentes' => $pendentes,
        ]);
    }
}
