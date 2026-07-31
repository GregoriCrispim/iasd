<?php

namespace App\Http\Controllers;

use App\Models\FaceSearchConsent;
use App\Models\GalleryAlbum;
use App\Models\User;
use App\Services\Face\FaceDescriptorService;
use App\Services\Face\FaceMatchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FaceSearchController extends Controller
{
    public function __construct(
        private readonly FaceDescriptorService $descriptors,
        private readonly FaceMatchService $matcher,
    ) {}

    public function search(Request $request, string $evento): JsonResponse
    {
        abort_unless((bool) config('face.enabled', true), 404);

        /** @var User|null $user */
        $user = $request->user('web');
        abort_unless($user instanceof User, 401);

        // O convite é apenas uma credencial de cadastro. A busca só é liberada
        // depois que ele é consumido por uma conta de membro ativa.
        abort_unless($user->isActiveMember(), 403, 'A busca facial é exclusiva para membros ativos.');

        $album = GalleryAlbum::query()
            ->published()
            ->where('slug', $evento)
            ->firstOrFail();

        $data = $request->validate([
            'descriptor' => ['required', 'array', 'size:128'],
            'descriptor.*' => ['required', 'numeric'],
            'source' => ['required', 'in:camera,upload'],
            'consent_self' => ['accepted'],
            'consent_biometric' => ['accepted'],
            'consent_limitations' => ['accepted'],
            'guardian_declaration' => ['nullable', 'boolean'],
        ], [
            'consent_self.accepted' => 'É necessário confirmar que a foto é sua.',
            'consent_biometric.accepted' => 'É necessário autorizar o uso temporário do descriptor biométrico.',
            'consent_limitations.accepted' => 'É necessário reconhecer as limitações do recurso.',
        ]);

        if ($user->isMinor() && ! $request->boolean('guardian_declaration')) {
            throw ValidationException::withMessages([
                'guardian_declaration' => 'Para menores de 18 anos, o responsável legal deve declarar o consentimento.',
            ]);
        }

        try {
            $query = $this->descriptors->validate($data['descriptor']);
        } catch (\Throwable) {
            throw ValidationException::withMessages([
                'descriptor' => 'O descriptor facial enviado é inválido.',
            ]);
        }

        FaceSearchConsent::create([
            'user_id' => $user->id,
            'gallery_album_id' => $album->id,
            'terms_version' => (string) config('face.consent_version', 'v1'),
            'source' => $data['source'],
            'is_guardian_declared' => $request->boolean('guardian_declaration'),
            'ip_hash' => hash('sha256', (string) $request->ip()),
            'consented_at' => now(),
        ]);

        $result = $this->matcher->search($album, $query);

        // Nunca devolvemos vetores nem selfies: apenas IDs estáveis das fotos.
        return response()->json([
            'photo_ids' => $result['photo_ids'],
            'count' => count($result['photo_ids']),
        ]);
    }
}
