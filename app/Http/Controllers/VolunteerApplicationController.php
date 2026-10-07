<?php

namespace App\Http\Controllers;

use App\Models\VolunteerApplication;
use App\Models\VolunteerApplicationChoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class VolunteerApplicationController extends Controller
{
    public function show(): View
    {
        return view('pages.talento', [
            'ministries' => config('ministries'),
            'success' => session('talento_success'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $ministrySlugs = array_keys(config('ministries'));

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'choices' => ['required', 'array', 'min:1', 'max:3'],
            'choices.*.ministry' => ['required', 'string', Rule::in($ministrySlugs)],
            'choices.*.modality' => ['required', 'string', Rule::in([
                VolunteerApplicationChoice::MODALITY_LIDERANCA,
                VolunteerApplicationChoice::MODALITY_EQUIPE,
            ])],
        ], [
            'name.required' => 'Informe seu nome completo.',
            'phone.required' => 'Informe seu WhatsApp.',
            'email.required' => 'Informe seu e-mail.',
            'email.email' => 'Informe um e-mail válido.',
            'choices.required' => 'Selecione ao menos 1 ministério.',
            'choices.min' => 'Selecione ao menos 1 ministério.',
            'choices.max' => 'Você pode se candidatar a no máximo 3 ministérios.',
        ]);

        $slugs = collect($validated['choices'])->pluck('ministry');
        if ($slugs->count() !== $slugs->unique()->count()) {
            throw ValidationException::withMessages([
                'choices' => 'Não é permitido se candidatar duas vezes ao mesmo ministério.',
            ]);
        }

        $application = DB::transaction(function () use ($validated) {
            $application = VolunteerApplication::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ]);

            foreach ($validated['choices'] as $choice) {
                $application->choices()->create([
                    'ministry_slug' => $choice['ministry'],
                    'modality' => $choice['modality'],
                ]);
            }

            return $application->load('choices');
        });

        $successChoices = $application->choices->map(fn (VolunteerApplicationChoice $choice) => [
            'ministry' => $choice->ministryLabel(),
            'modality' => $choice->modalityLabel(),
            'modality_key' => $choice->modality,
        ])->all();

        return redirect()
            ->route('talento.show')
            ->with('talento_success', [
                'name' => $application->name,
                'choices' => $successChoices,
            ]);
    }
}
