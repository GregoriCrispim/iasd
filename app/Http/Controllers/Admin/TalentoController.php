<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VolunteerApplication;
use App\Models\VolunteerApplicationChoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TalentoController extends Controller
{
    public function index(Request $request): View
    {
        $ministries = config('ministries');
        $filters = $this->resolvedFilters($request, $ministries);
        $viewMode = $request->string('view')->toString() === 'ministerio' ? 'ministerio' : 'lista';
        $baseQuery = $this->filteredApplicationsQuery($filters);

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
                ->with(['choices' => function ($q) {
                    $q->orderByRaw('CASE WHEN modality = ? THEN 0 ELSE 1 END', [
                        VolunteerApplicationChoice::MODALITY_LIDERANCA,
                    ])->orderBy('id');
                }])
                ->orderByDesc('created_at')
                ->paginate(30)
                ->withQueryString();
        } else {
            $byMinistry = $this->buildByMinistry($ministries, $baseQuery, $filters, includeEmpty: true);
        }

        return view('admin.talento.index', [
            'ministries' => $ministries,
            'viewMode' => $viewMode,
            'stats' => $stats,
            'applications' => $applications,
            'byMinistry' => $byMinistry,
            'ministryFilter' => $filters['ministry'],
            'modalityFilter' => $filters['modality'],
            'q' => $filters['q'],
        ]);
    }

    public function pdf(Request $request): Response
    {
        $ministries = config('ministries');
        $filters = $this->resolvedFilters($request, $ministries);
        $baseQuery = $this->filteredApplicationsQuery($filters);
        $byMinistry = $this->buildByMinistry($ministries, $baseQuery, $filters, includeEmpty: false, withContact: true);

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
            'ministries_with_candidates' => count($byMinistry),
        ];

        $generatedAt = now('America/Sao_Paulo');
        $filename = 'meu-talento-inscritos-'.$generatedAt->format('Y-m-d-His').'.pdf';

        $logoPath = public_path('img/logo_iasd.png');
        $logoDataUri = is_file($logoPath)
            ? 'data:image/png;base64,'.base64_encode((string) file_get_contents($logoPath))
            : null;

        $pdf = Pdf::loadView('admin.talento.pdf', [
            'byMinistry' => $byMinistry,
            'stats' => $stats,
            'filters' => $filters,
            'ministries' => $ministries,
            'generatedAt' => $generatedAt,
            'logoDataUri' => $logoDataUri,
        ])->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }

    /**
     * @param  array<string, mixed>  $ministries
     * @return array{q: string, ministry: string, modality: string}
     */
    private function resolvedFilters(Request $request, array $ministries): array
    {
        $ministryFilter = $request->string('ministry')->toString();
        $modalityFilter = $request->string('modality')->toString();
        $q = Str::limit(trim($request->string('q')->toString()), 100, '');

        if ($ministryFilter !== '' && ! array_key_exists($ministryFilter, $ministries)) {
            $ministryFilter = '';
        }

        if (! in_array($modalityFilter, ['', VolunteerApplicationChoice::MODALITY_LIDERANCA, VolunteerApplicationChoice::MODALITY_EQUIPE], true)) {
            $modalityFilter = '';
        }

        return [
            'q' => $q,
            'ministry' => $ministryFilter,
            'modality' => $modalityFilter,
        ];
    }

    /**
     * @param  array{q: string, ministry: string, modality: string}  $filters
     */
    private function filteredApplicationsQuery(array $filters): Builder
    {
        return VolunteerApplication::query()
            ->when($filters['q'] !== '', function ($query) use ($filters) {
                $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $filters['q']);
                $term = '%'.$escaped.'%';
                $query->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('phone', 'like', $term);
                });
            })
            ->when($filters['ministry'] !== '' || $filters['modality'] !== '', function ($query) use ($filters) {
                $query->whereHas('choices', function ($choices) use ($filters) {
                    if ($filters['ministry'] !== '') {
                        $choices->where('ministry_slug', $filters['ministry']);
                    }
                    if ($filters['modality'] !== '') {
                        $choices->where('modality', $filters['modality']);
                    }
                });
            });
    }

    /**
     * @param  array<string, mixed>  $ministries
     * @param  array{q: string, ministry: string, modality: string}  $filters
     * @return list<array<string, mixed>>
     */
    private function buildByMinistry(
        array $ministries,
        Builder $baseQuery,
        array $filters,
        bool $includeEmpty,
        bool $withContact = false,
    ): array {
        $counts = VolunteerApplicationChoice::query()
            ->select(
                'ministry_slug',
                'modality',
                DB::raw('COUNT(*) as total')
            )
            ->whereIn('volunteer_application_id', (clone $baseQuery)->select('id'))
            ->when($filters['ministry'] !== '', fn ($q) => $q->where('ministry_slug', $filters['ministry']))
            ->when($filters['modality'] !== '', fn ($q) => $q->where('modality', $filters['modality']))
            ->groupBy('ministry_slug', 'modality')
            ->get();

        $namesByMinistry = VolunteerApplicationChoice::query()
            ->with('application:id,name,email,phone,created_at')
            ->whereIn('volunteer_application_id', (clone $baseQuery)->select('id'))
            ->when($filters['ministry'] !== '', fn ($q) => $q->where('ministry_slug', $filters['ministry']))
            ->when($filters['modality'] !== '', fn ($q) => $q->where('modality', $filters['modality']))
            ->orderByRaw('CASE WHEN modality = ? THEN 0 ELSE 1 END', [
                VolunteerApplicationChoice::MODALITY_LIDERANCA,
            ])
            ->orderBy('id')
            ->get()
            ->groupBy('ministry_slug');

        $palette = [
            '#0b3d5c',
            '#1d4ed8',
            '#0f766e',
            '#b45309',
            '#166534',
            '#9a3412',
            '#1e3a8a',
            '#0e7490',
        ];

        $byMinistry = [];
        $colorIndex = 0;

        foreach ($ministries as $slug => $ministry) {
            if ($filters['ministry'] !== '' && $slug !== $filters['ministry']) {
                continue;
            }

            $label = is_array($ministry) ? ($ministry['name'] ?? $slug) : $ministry;

            $lideranca = (int) $counts
                ->first(fn ($row) => $row->ministry_slug === $slug && $row->modality === VolunteerApplicationChoice::MODALITY_LIDERANCA)
                ?->total;

            $equipe = (int) $counts
                ->first(fn ($row) => $row->ministry_slug === $slug && $row->modality === VolunteerApplicationChoice::MODALITY_EQUIPE)
                ?->total;

            $total = $lideranca + $equipe;

            if ($filters['modality'] !== '' && $total === 0) {
                continue;
            }

            if (! $includeEmpty && $total === 0) {
                continue;
            }

            $candidates = ($namesByMinistry->get($slug) ?? collect())->map(function ($choice) use ($withContact) {
                $row = [
                    'name' => $choice->application?->name ?? '—',
                    'modality' => $choice->modality,
                    'modality_label' => $choice->modalityLabel(),
                ];

                if ($withContact) {
                    $row['email'] = $choice->application?->email ?? '—';
                    $row['phone'] = $this->formatBrPhone($choice->application?->phone);
                    $row['created_at'] = $choice->application?->created_at
                        ?->timezone('America/Sao_Paulo')
                        ->format('d/m/Y H:i') ?? '—';
                }

                return $row;
            })->values()->all();

            $byMinistry[] = [
                'slug' => $slug,
                'label' => $label,
                'lideranca' => $lideranca,
                'equipe' => $equipe,
                'total' => $total,
                'candidates' => $candidates,
                'color' => $palette[$colorIndex % count($palette)],
            ];
            $colorIndex++;
        }

        return $byMinistry;
    }

    private function formatBrPhone(?string $phone): string
    {
        $digits = substr(preg_replace('/\D+/', '', (string) $phone) ?? '', 0, 11);

        if ($digits === '') {
            return '—';
        }

        $ddd = substr($digits, 0, 2);
        $rest = substr($digits, 2);

        if (strlen($digits) <= 10) {
            $part1 = substr($rest, 0, 4);
            $part2 = substr($rest, 4, 4);

            return $part2 !== ''
                ? "({$ddd}) {$part1}-{$part2}"
                : ($part1 !== '' ? "({$ddd}) {$part1}" : "({$ddd})");
        }

        $part1 = substr($rest, 0, 5);
        $part2 = substr($rest, 5, 4);

        return $part2 !== ''
            ? "({$ddd}) {$part1}-{$part2}"
            : "({$ddd}) {$part1}";
    }
}
