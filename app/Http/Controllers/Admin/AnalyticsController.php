<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormSubmission;
use App\Models\PageView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        $includeBots = $request->boolean('bots');
        $now = now();
        $todayStart = $now->copy()->startOfDay();
        $last7Start = $now->copy()->subDays(6)->startOfDay();
        $last30Start = $now->copy()->subDays(29)->startOfDay();

        $views = PageView::query();
        $forms = FormSubmission::query()->where('success', true);

        if (! $includeBots) {
            $views->human();
            $forms->human();
        }

        $viewsTotal = (clone $views)->count();
        $viewsUnique = (clone $views)->distinct('visitor_key')->count('visitor_key');
        $viewsToday = (clone $views)->where('viewed_at', '>=', $todayStart)->count();
        $viewsLast7 = (clone $views)->where('viewed_at', '>=', $last7Start)->count();
        $botsTotal = PageView::query()->where('is_bot', true)->count();

        $formsTotal = (clone $forms)->count();
        $formsToday = (clone $forms)->where('submitted_at', '>=', $todayStart)->count();
        $formsLast7 = (clone $forms)->where('submitted_at', '>=', $last7Start)->count();

        $byRoute = (clone $views)
            ->select(
                DB::raw("COALESCE(route_name, path) as route_key"),
                DB::raw('MIN(path) as sample_path'),
                DB::raw('COUNT(*) as total'),
                DB::raw('COUNT(DISTINCT visitor_key) as uniques')
            )
            ->groupBy(DB::raw("COALESCE(route_name, path)"))
            ->orderByDesc('total')
            ->limit(40)
            ->get();

        $byForm = (clone $forms)
            ->select(
                'form_key',
                DB::raw('COUNT(*) as total'),
                DB::raw('COUNT(DISTINCT visitor_key) as uniques')
            )
            ->groupBy('form_key')
            ->orderByDesc('total')
            ->get()
            ->map(function ($row) {
                $row->label = FormSubmission::LABELS[$row->form_key] ?? $row->form_key;

                return $row;
            });

        $byDevice = (clone $views)
            ->select('device_type', DB::raw('COUNT(*) as total'))
            ->groupBy('device_type')
            ->orderByDesc('total')
            ->pluck('total', 'device_type')
            ->all();

        $byDayRaw = (clone $views)
            ->where('viewed_at', '>=', $last30Start)
            ->select(DB::raw('DATE(viewed_at) as day'), DB::raw('COUNT(*) as total'))
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->all();

        $formsByDayRaw = (clone $forms)
            ->where('submitted_at', '>=', $last30Start)
            ->select(DB::raw('DATE(submitted_at) as day'), DB::raw('COUNT(*) as total'))
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->all();

        $byDay = [];
        $formsByDay = [];
        for ($i = 29; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i)->toDateString();
            $byDay[$day] = (int) ($byDayRaw[$day] ?? 0);
            $formsByDay[$day] = (int) ($formsByDayRaw[$day] ?? 0);
        }

        $hourExpr = DB::connection()->getDriverName() === 'sqlite'
            ? "CAST(strftime('%H', viewed_at) AS INTEGER)"
            : 'HOUR(viewed_at)';

        $byHourRaw = (clone $views)
            ->select(DB::raw("{$hourExpr} as hour"), DB::raw('COUNT(*) as total'))
            ->groupBy('hour')
            ->pluck('total', 'hour')
            ->all();

        $byHour = [];
        for ($h = 0; $h < 24; $h++) {
            $byHour[$h] = (int) ($byHourRaw[$h] ?? $byHourRaw[(string) $h] ?? 0);
        }

        $recentViews = (clone $views)
            ->orderByDesc('viewed_at')
            ->limit(40)
            ->get();

        $recentForms = (clone $forms)
            ->orderByDesc('submitted_at')
            ->limit(40)
            ->get();

        $maxHour = max(1, ...array_values($byHour));

        return view('admin.analytics.index', [
            'includeBots' => $includeBots,
            'stats' => [
                'views_total' => $viewsTotal,
                'views_unique' => $viewsUnique,
                'views_today' => $viewsToday,
                'views_last7' => $viewsLast7,
                'bots_total' => $botsTotal,
                'forms_total' => $formsTotal,
                'forms_today' => $formsToday,
                'forms_last7' => $formsLast7,
            ],
            'byRoute' => $byRoute,
            'byForm' => $byForm,
            'byDevice' => $byDevice,
            'byDay' => $byDay,
            'formsByDay' => $formsByDay,
            'byHour' => $byHour,
            'maxHour' => $maxHour,
            'recentViews' => $recentViews,
            'recentForms' => $recentForms,
        ]);
    }
}
