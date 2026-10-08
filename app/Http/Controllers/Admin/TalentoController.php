<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VolunteerApplication;
use App\Models\VolunteerApplicationChoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TalentoController extends Controller
{
    public function index(Request $request): View
    {
        $ministries = config('ministries');
        $viewMode = $request->string('view')->toString() === 'ministerio' ? 'ministerio' : 'lista';
        $ministryFilter = $request->string('ministry')->toString();
        $modalityFilter = $request->string('modality')->toString();
        $q = Str::limit(trim($request->string('q')->toString()), 100, '');

        if ($ministryFilter !== '' && ! array_key_exists($ministryFilter, $ministries)) {
            $ministryFilter = '';
        }

        if (! in_array($modalityFilter, ['', VolunteerApplicationChoice::MODALITY_LIDERANCA, VolunteerApplicationChoice::MODALITY_EQUIPE], true)) {
            $modalityFilter = '';
        }

        $baseQuery = VolunteerApplication::query()
            ->when($q !== '', function ($query) use ($q) {
                // Escapa curingas do LIKE para evitar abusos de busca ampla.
                $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $q);
                $term = '%'.$escaped.'%';
                $query->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('phone', 'like', $term);
                });
            })
            ->when($ministryFilter !== '' || $modalityFilter !== '', function ($query) use ($ministryFilter, $modalityFilter) {
                $query->whereHas('choices', function ($choices) use ($ministryFilter, $modalityFilter) {
                    if ($ministryFilter !== '') {
                        $choices->where('ministry_slug', $ministryFilter);
                    }
                    if ($modalityFilter !== '') {
                        $choices->where('modality', $modalityFilter);
                    }
                });
            });

        $stats = [
            'volunteers' => (clone $baseQuery)->count(),
            'lideranca' => VolunteerApplicationChoice::query()
                ->where('modality', VolunteerApplicationChoice::MODALITY_LIDERANCA)
                ->whereIn('volunteer_application_id', (clone $baseQuery)->select('id'))
                ->count(),
            'equipe' => VolunteerApplicationChoice::query()
                ->where('modality', VolunteerApplicationChoice::MODALITY_EQUIPE)
                ->whereIn('volunteer_application_id', (clone $baseQuery)->select('id'))
                ->count(),
        ];

        $applications = null;
        $byMinistry = null;

        if ($viewMode === 'lista') {
            $applications = (clone $baseQuery)
                ->with(['choices' => fn ($q) => $q->orderBy('id')])
                ->orderByDesc('created_at')
                ->paginate(30)
                ->withQueryString();
        } else {
            $counts = VolunteerApplicationChoice::query()
                ->select(
                    'ministry_slug',
                    'modality',
                    DB::raw('COUNT(*) as total')
                )
                ->whereIn('volunteer_application_id', (clone $baseQuery)->select('id'))
                ->when($ministryFilter !== '', fn ($q) => $q->where('ministry_slug', $ministryFilter))
                ->when($modalityFilter !== '', fn ($q) => $q->where('modality', $modalityFilter))
                ->groupBy('ministry_slug', 'modality')
                ->get();

            $namesByMinistry = VolunteerApplicationChoice::query()
                ->with('application:id,name')
                ->whereIn('volunteer_application_id', (clone $baseQuery)->select('id'))
                ->when($ministryFilter !== '', fn ($q) => $q->where('ministry_slug', $ministryFilter))
                ->when($modalityFilter !== '', fn ($q) => $q->where('modality', $modalityFilter))
                ->orderBy('id')
                ->get()
                ->groupBy('ministry_slug');

            $byMinistry = [];
            foreach ($ministries as $slug => $ministry) {
                if ($ministryFilter !== '' && $slug !== $ministryFilter) {
                    continue;
                }

                $label = is_array($ministry) ? ($ministry['name'] ?? $slug) : $ministry;

                $lideranca = (int) $counts
                    ->first(fn ($row) => $row->ministry_slug === $slug && $row->modality === VolunteerApplicationChoice::MODALITY_LIDERANCA)
                    ?->total;

                $equipe = (int) $counts
                    ->first(fn ($row) => $row->ministry_slug === $slug && $row->modality === VolunteerApplicationChoice::MODALITY_EQUIPE)
                    ?->total;

                $candidates = ($namesByMinistry->get($slug) ?? collect())->map(fn ($choice) => [
                    'name' => $choice->application?->name ?? '—',
                    'modality' => $choice->modality,
                    'modality_label' => $choice->modalityLabel(),
                ])->values()->all();

                $byMinistry[] = [
                    'slug' => $slug,
                    'label' => $label,
                    'lideranca' => $lideranca,
                    'equipe' => $equipe,
                    'total' => $lideranca + $equipe,
                    'candidates' => $candidates,
                ];
            }
        }

        return view('admin.talento.index', [
            'ministries' => $ministries,
            'viewMode' => $viewMode,
            'stats' => $stats,
            'applications' => $applications,
            'byMinistry' => $byMinistry,
            'ministryFilter' => $ministryFilter,
            'modalityFilter' => $modalityFilter,
            'q' => $q,
        ]);
    }
}
