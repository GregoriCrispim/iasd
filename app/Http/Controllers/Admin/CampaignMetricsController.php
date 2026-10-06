<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CampaignMetricsController extends Controller
{
    public function show(Request $request, string $slug): View
    {
        $campaign = Campaign::query()->where('slug', $slug)->first();
        if (! $campaign) {
            throw new NotFoundHttpException();
        }

        $human = CampaignVisit::query()
            ->where('campaign_id', $campaign->id)
            ->human();

        $now = now();
        $todayStart = $now->copy()->startOfDay();
        $last7Start = $now->copy()->subDays(6)->startOfDay();
        $last30Start = $now->copy()->subDays(29)->startOfDay();

        $total = (clone $human)->count();
        $unique = (clone $human)->distinct('visitor_key')->count('visitor_key');
        $today = (clone $human)->where('visited_at', '>=', $todayStart)->count();
        $last7 = (clone $human)->where('visited_at', '>=', $last7Start)->count();

        $byDevice = (clone $human)
            ->select('device_type', DB::raw('COUNT(*) as total'))
            ->groupBy('device_type')
            ->orderByDesc('total')
            ->pluck('total', 'device_type')
            ->all();

        $byDayRaw = (clone $human)
            ->where('visited_at', '>=', $last30Start)
            ->select(DB::raw('DATE(visited_at) as day'), DB::raw('COUNT(*) as total'))
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->all();

        $byDay = [];
        for ($i = 29; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i)->toDateString();
            $byDay[$day] = (int) ($byDayRaw[$day] ?? 0);
        }

        $hourExpr = DB::connection()->getDriverName() === 'sqlite'
            ? "CAST(strftime('%H', visited_at) AS INTEGER)"
            : 'HOUR(visited_at)';

        $byHourRaw = (clone $human)
            ->select(DB::raw("{$hourExpr} as hour"), DB::raw('COUNT(*) as total'))
            ->groupBy('hour')
            ->pluck('total', 'hour')
            ->all();

        $byHour = [];
        for ($h = 0; $h < 24; $h++) {
            $byHour[$h] = (int) ($byHourRaw[$h] ?? $byHourRaw[(string) $h] ?? 0);
        }

        $recent = CampaignVisit::query()
            ->where('campaign_id', $campaign->id)
            ->human()
            ->orderByDesc('visited_at')
            ->limit(50)
            ->get();

        $maxDay = max(1, ...array_values($byDay));
        $maxHour = max(1, ...array_values($byHour));

        return view('admin.campanhas.show', [
            'campaign' => $campaign,
            'trackingUrl' => $campaign->trackingUrl(),
            'stats' => [
                'total' => $total,
                'unique' => $unique,
                'today' => $today,
                'last7' => $last7,
            ],
            'byDevice' => $byDevice,
            'byDay' => $byDay,
            'byHour' => $byHour,
            'maxDay' => $maxDay,
            'maxHour' => $maxHour,
            'recent' => $recent,
        ]);
    }
}
