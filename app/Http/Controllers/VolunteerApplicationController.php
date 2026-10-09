<?php

namespace App\Http\Controllers;

use App\Models\VolunteerApplication;
use App\Models\VolunteerApplicationChoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class VolunteerApplicationController extends Controller
{
    private const MIN_SUBMIT_SECONDS = 2;

    private const MAX_NAME_LENGTH = 120;

    private const MAX_EMAIL_LENGTH = 255;

    public function show(): View|Response
    {
        session([
            'talento_form_loaded_at' => now()->timestamp,
            'talento_form_nonce' => (string) Str::uuid(),
        ]);

        return response()
            ->view('pages.talento', [
                'ministries' => config('ministries'),
                'success' => $this->sanitizedSuccess(session('talento_success')),
                'formNonce' => session('talento_form_nonce'),
            ])
            ->withHeaders($this->securityHeaders());
    }

    public function store(Request $request): RedirectResponse
    {
        $this->rejectAutomatedSubmission($request);

        $ministrySlugs = array_keys(config('ministries'));

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:'.self::MAX_NAME_LENGTH,
                'regex:/^[\p{L}\p{M}][\p{L}\p{M}\s\'\-.]*$/u',
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/',
            ],
            'email' => [
                'required',
                'string',
                'email:filter',
                'max:'.self::MAX_EMAIL_LENGTH,
            ],
            // Honeypot: deve permanecer vazio.
            'website' => ['nullable', 'string', 'max:0'],
            'form_nonce' => ['required', 'string', 'uuid'],
            'choices' => ['required', 'array', 'min:1', 'max:3'],
            'choices.*.ministry' => ['required', 'string', 'max:64', Rule::in($ministrySlugs)],
            'choices.*.modality' => ['required', 'string', Rule::in([
                VolunteerApplicationChoice::MODALITY_LIDERANCA,
                VolunteerApplicationChoice::MODALITY_EQUIPE,
            ])],
        ], [
            'name.required' => 'Informe seu nome completo.',
            'name.min' => 'Digite seu nome completo.',
            'name.max' => 'O nome informado é longo demais.',
            'name.regex' => 'Informe um nome válido.',
            'phone.required' => 'Informe seu WhatsApp.',
            'phone.regex' => 'Informe um WhatsApp válido com DDD.',
            'email.required' => 'Informe seu e-mail.',
            'email.email' => 'Informe um e-mail válido.',
            'website.max' => 'Não foi possível enviar a inscrição.',
            'form_nonce.required' => 'Recarregue a página e tente novamente.',
            'form_nonce.uuid' => 'Recarregue a página e tente novamente.',
            'choices.required' => 'Selecione ao menos 1 ministério.',
            'choices.min' => 'Selecione ao menos 1 ministério.',
            'choices.max' => 'Você pode se candidatar a no máximo 3 ministérios.',
        ]);

        $expectedNonce = (string) session('talento_form_nonce', '');
        if ($expectedNonce === '' || ! hash_equals($expectedNonce, $validated['form_nonce'])) {
            throw ValidationException::withMessages([
                'form_nonce' => 'Recarregue a página e tente novamente.',
            ]);
        }

        $choices = collect($validated['choices'])
            ->map(fn (array $choice) => [
                'ministry' => (string) $choice['ministry'],
                'modality' => (string) $choice['modality'],
            ])
            ->values();

        $slugs = $choices->pluck('ministry');
        if ($slugs->count() !== $slugs->unique()->count()) {
            throw ValidationException::withMessages([
                'choices' => 'Não é permitido se candidatar duas vezes ao mesmo ministério.',
            ]);
        }

        $liderancaCount = $choices
            ->where('modality', VolunteerApplicationChoice::MODALITY_LIDERANCA)
            ->count();
        if ($liderancaCount > 1) {
            throw ValidationException::withMessages([
                'choices' => 'Você pode se candidatar à liderança em apenas 1 ministério.',
            ]);
        }

        $ministriesConfig = config('ministries');
        foreach ($choices as $choice) {
            $ministryConfig = $ministriesConfig[$choice['ministry']] ?? null;
            $allowsLideranca = ! is_array($ministryConfig) || ($ministryConfig['allows_lideranca'] ?? true);
            if (
                $choice['modality'] === VolunteerApplicationChoice::MODALITY_LIDERANCA
                && ! $allowsLideranca
            ) {
                $ministryName = is_array($ministryConfig)
                    ? ($ministryConfig['name'] ?? $choice['ministry'])
                    : $choice['ministry'];

                throw ValidationException::withMessages([
                    'choices' => "O ministério {$ministryName} aceita apenas candidatura à Equipe.",
                ]);
            }
        }

        $name = $this->sanitizePlainText($validated['name'], self::MAX_NAME_LENGTH);
        $email = Str::lower(trim($validated['email']));
        $phone = $this->normalizePhone($validated['phone']);

        $postSanitizeErrors = [];
        if ($name === '') {
            $postSanitizeErrors['name'] = 'Informe um nome válido.';
        }
        if (strlen($phone) < 10 || strlen($phone) > 11) {
            $postSanitizeErrors['phone'] = 'Informe um WhatsApp válido com DDD.';
        }
        if ($this->emailTaken($email)) {
            $postSanitizeErrors['email'] = 'Este e-mail já foi usado em uma inscrição.';
        }
        if ($this->phoneTaken($phone)) {
            $postSanitizeErrors['phone'] = 'Este WhatsApp já foi usado em uma inscrição.';
        }
        if ($postSanitizeErrors !== []) {
            throw ValidationException::withMessages($postSanitizeErrors);
        }

        $application = DB::transaction(function () use ($name, $email, $phone, $choices) {
            $application = VolunteerApplication::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
            ]);

            foreach ($choices as $choice) {
                $application->choices()->create([
                    'ministry_slug' => $choice['ministry'],
                    'modality' => $choice['modality'],
                ]);
            }

            return $application->load('choices');
        });

        session()->forget(['talento_form_loaded_at', 'talento_form_nonce']);

        $successChoices = $application->choices->map(fn (VolunteerApplicationChoice $choice) => [
            'ministry' => $choice->ministryLabel(),
            'modality' => $choice->modalityLabel(),
            'modality_key' => $choice->isLideranca()
                ? VolunteerApplicationChoice::MODALITY_LIDERANCA
                : VolunteerApplicationChoice::MODALITY_EQUIPE,
        ])->all();

        return redirect()
            ->route('talento.show')
            ->with('talento_success', [
                'name' => $application->name,
                'choices' => $successChoices,
            ]);
    }

    public function check(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'field' => ['required', 'string', Rule::in(['email', 'phone'])],
            'value' => ['required', 'string', 'max:255'],
        ]);

        $field = $validated['field'];
        $value = $validated['value'];
        $available = true;
        $message = null;

        if ($field === 'email') {
            $email = Str::lower(trim($value));
            if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'available' => false,
                    'valid' => false,
                    'message' => 'Informe um e-mail válido.',
                ]);
            }

            $available = ! $this->emailTaken($email);
            $message = $available ? null : 'Este e-mail já foi usado em uma inscrição.';
        }

        if ($field === 'phone') {
            $phone = $this->normalizePhone($value);
            if (strlen($phone) < 10 || strlen($phone) > 11) {
                return response()->json([
                    'available' => false,
                    'valid' => false,
                    'message' => 'Informe um WhatsApp válido com DDD.',
                ]);
            }

            $available = ! $this->phoneTaken($phone);
            $message = $available ? null : 'Este WhatsApp já foi usado em uma inscrição.';
        }

        return response()
            ->json([
                'available' => $available,
                'valid' => true,
                'message' => $message,
            ])
            ->withHeaders($this->securityHeaders());
    }

    private function emailTaken(string $email): bool
    {
        return VolunteerApplication::query()
            ->whereRaw('LOWER(email) = ?', [Str::lower($email)])
            ->exists();
    }

    private function phoneTaken(string $phone): bool
    {
        if ($phone === '') {
            return false;
        }

        // Cobre registros já normalizados e legados com máscara.
        return VolunteerApplication::query()
            ->where(function ($query) use ($phone) {
                $query->where('phone', $phone)
                    ->orWhereRaw(
                        "REPLACE(REPLACE(REPLACE(REPLACE(phone, '(', ''), ')', ''), '-', ''), ' ', '') = ?",
                        [$phone]
                    );
            })
            ->exists();
    }

    private function rejectAutomatedSubmission(Request $request): void
    {
        // Campo isca para bots (não deve ser preenchido por humanos).
        if (filled($request->input('website'))) {
            throw ValidationException::withMessages([
                'website' => 'Não foi possível enviar a inscrição.',
            ]);
        }

        $loadedAt = (int) session('talento_form_loaded_at', 0);
        if ($loadedAt <= 0 || (now()->timestamp - $loadedAt) < self::MIN_SUBMIT_SECONDS) {
            throw ValidationException::withMessages([
                'form_nonce' => 'Aguarde um instante e envie novamente.',
            ]);
        }
    }

    private function sanitizePlainText(string $value, int $max): string
    {
        $value = strip_tags($value);
        $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value) ?? '';
        $value = preg_replace('/\s+/u', ' ', $value) ?? '';
        $value = trim($value);

        return Str::limit($value, $max, '');
    }

    private function normalizePhone(string $value): string
    {
        return substr(preg_replace('/\D+/', '', $value) ?? '', 0, 11);
    }

    /**
     * @param  mixed  $success
     * @return array{name: string, choices: list<array{ministry: string, modality: string, modality_key: string}>}|null
     */
    private function sanitizedSuccess(mixed $success): ?array
    {
        if (! is_array($success) || ! isset($success['name'], $success['choices']) || ! is_array($success['choices'])) {
            return null;
        }

        $choices = [];
        foreach (array_slice($success['choices'], 0, 3) as $choice) {
            if (! is_array($choice)) {
                continue;
            }

            $ministry = $this->sanitizePlainText((string) ($choice['ministry'] ?? ''), 120);
            $modalityKey = (string) ($choice['modality_key'] ?? '');
            if ($ministry === '' || ! in_array($modalityKey, [
                VolunteerApplicationChoice::MODALITY_LIDERANCA,
                VolunteerApplicationChoice::MODALITY_EQUIPE,
            ], true)) {
                continue;
            }

            $choices[] = [
                'ministry' => $ministry,
                'modality' => $modalityKey === VolunteerApplicationChoice::MODALITY_LIDERANCA
                    ? 'Liderança'
                    : 'Equipe',
                'modality_key' => $modalityKey,
            ];
        }

        $name = $this->sanitizePlainText((string) $success['name'], self::MAX_NAME_LENGTH);
        if ($name === '' || $choices === []) {
            return null;
        }

        return [
            'name' => $name,
            'choices' => $choices,
        ];
    }

    /**
     * @return array<string, string>
     */
    private function securityHeaders(): array
    {
        return [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=()',
            'Cross-Origin-Opener-Policy' => 'same-origin',
        ];
    }
}
