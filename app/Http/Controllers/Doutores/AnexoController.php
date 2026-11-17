<?php

namespace App\Http\Controllers\Doutores;

use App\Models\Doutores\Anexo;
use App\Support\DoutoresPoloScope;
use App\Support\DoutoresStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnexoController extends DoutoresController
{
    /** Anexos do usuário; coordenação/administração também vê os pendentes de revisão. */
    public function index(Request $request): View
    {
        $user = $this->user();

        $meus = Anexo::query()->where('user_id', $user->id)->latest()->get();

        $revisao = collect();
        if ($this->isManagerPerfil()) {
            $query = Anexo::query()
                ->with('user')
                ->where('user_id', '!=', $user->id)
                ->when(
                    $request->filled('status'),
                    fn ($q) => $q->where('status', $request->string('status')->toString()),
                    fn ($q) => $q->where('status', 'enviado')
                );
            DoutoresPoloScope::filterByOwnerProfile($query, $user, $request);
            $revisao = $query->latest()->limit(100)->get();
        }

        return view('doutores.anexos.index', [
            'meus' => $meus,
            'revisao' => $revisao,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $this->user();

        $data = $request->validate([
            'tipo' => ['required', Rule::in(array_keys(Anexo::TIPOS))],
            'arquivo' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'arquivo.mimes' => 'Envie um arquivo PDF, JPG, PNG ou WEBP.',
            'arquivo.max' => 'O arquivo pode ter no máximo 5 MB.',
        ]);

        $file = $request->file('arquivo');
        // Disco privado (storage/app/private): não depende de storage:link e não fica
        // exposto em URL pública — download só via rota autenticada (HostGator-safe).
        $path = DoutoresStorage::storePrivate($file, 'doutores/anexos');

        $anexo = Anexo::create([
            'user_id' => $user->id,
            'tipo' => $data['tipo'],
            'nome_arquivo' => mb_substr($file->getClientOriginalName(), 0, 255),
            'path' => $path,
            'mime' => $file->getMimeType(),
            'tamanho' => (int) $file->getSize(),
            'status' => 'enviado',
        ]);

        $this->log('anexo.enviado', 'anexo', "Anexo #{$anexo->id} ({$data['tipo']}) enviado.", $user->email, $anexo->nome_arquivo);

        return redirect()->route('doutores.anexos.index')->with('success', 'Arquivo enviado.');
    }

    public function destroy(Anexo $anexo): RedirectResponse
    {
        $user = $this->user();
        $this->authorizeAnexo($anexo, true);

        if ((int) $anexo->user_id === (int) $user->id && $anexo->status === 'aprovado' && ! $this->isManagerPerfil()) {
            return back()->with('error', 'Anexos aprovados não podem ser removidos pelo voluntário.');
        }

        DoutoresStorage::deletePrivate($anexo->path);
        $nome = $anexo->nome_arquivo;
        $dono = $anexo->user?->email;
        $anexo->delete();

        $this->log('anexo.removido', 'anexo', 'Anexo removido.', $dono, $nome, 'warning');

        return back()->with('success', 'Anexo removido.');
    }

    public function download(Anexo $anexo): StreamedResponse
    {
        $this->authorizeAnexo($anexo, false);

        $disk = DoutoresStorage::resolvePrivateDisk($anexo->path);
        abort_unless($disk !== null, 404);

        return $disk->download($anexo->path, $anexo->nome_arquivo);
    }

    /** Coordenação/administração aprova ou rejeita um anexo. */
    public function review(Request $request, Anexo $anexo): RedirectResponse
    {
        $this->abortUnlessManager();
        $this->authorizeAnexo($anexo, false);

        $data = $request->validate([
            'status' => ['required', Rule::in(['aprovado', 'rejeitado', 'enviado'])],
        ]);

        $anexo->update(['status' => $data['status']]);

        $this->log('anexo.revisado', 'anexo', "Anexo #{$anexo->id} marcado como {$data['status']}.", $anexo->user?->email, $anexo->nome_arquivo);

        return back()->with('success', 'Anexo atualizado.');
    }

    /**
     * Dono do anexo ou gestor com o voluntário dentro do escopo.
     */
    protected function authorizeAnexo(Anexo $anexo, bool $forDelete): void
    {
        $user = $this->user();

        if ((int) $anexo->user_id === (int) $user->id) {
            return;
        }

        $this->abortUnlessManager();

        $anexo->loadMissing('user.doutoresProfile');
        $profile = $anexo->user?->doutoresProfile;

        if (! $profile) {
            abort_unless($this->unrestricted(), 403);

            return;
        }

        DoutoresPoloScope::assertProfileInScope($user, $profile, request());
    }
}
