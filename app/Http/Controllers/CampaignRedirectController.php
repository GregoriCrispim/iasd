<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignVisit;
use App\Support\CampaignVisitRecorder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CampaignRedirectController extends Controller
{
    public function __construct(
        private readonly CampaignVisitRecorder $recorder,
    ) {
    }

    public function sementes(Request $request): RedirectResponse
    {
        return $this->redirect($request, 'sementes');
    }

    public function show(Request $request, string $slug): RedirectResponse
    {
        return $this->redirect($request, $slug);
    }

    private function redirect(Request $request, string $slug): RedirectResponse
    {
        $campaign = Campaign::query()
            ->active()
            ->where('slug', $slug)
            ->first();

        if (! $campaign) {
            throw new NotFoundHttpException();
        }

        $this->recordVisit($request, $campaign);

        $destination = $campaign->destination_route;
        if (! is_string($destination) || $destination === '' || ! \Illuminate\Support\Facades\Route::has($destination)) {
            $destination = 'estudo-biblico';
        }

        return redirect()->route($destination);
    }

    private function recordVisit(Request $request, Campaign $campaign): void
    {
        try {
            $attrs = $this->recorder->attributesFromRequest($request, (int) $campaign->id);

            CampaignVisit::query()->create([
                'campaign_id' => $campaign->id,
                'visited_at' => now(),
                ...$attrs,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Falha ao registrar visita de campanha', [
                'campaign' => $campaign->slug,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
