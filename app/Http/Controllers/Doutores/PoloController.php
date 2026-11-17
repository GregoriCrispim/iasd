<?php

namespace App\Http\Controllers\Doutores;

use App\Models\Doutores\Polo;
use App\Models\User;
use App\Support\DoutoresAuth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PoloController extends DoutoresController
{
    public function index(): View
    {
        $this->abortUnlessAdmin();

        return view('doutores.polos.index', [
            'polos' => Polo::query()->withCount(['hospitais', 'coordenadores'])->orderBy('nome')->get(),
        ]);
    }

    public function create(): View
    {
        $this->abortUnlessAdmin();

        return view('doutores.polos.form', $this->formData(new Polo(['estado' => 'DF', 'status' => 'ativo'])));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->abortUnlessAdmin();

        $data = $this->validated($request);
        $coordenadores = $data['coordenadores'] ?? [];
        unset($data['coordenadores']);

        $polo = Polo::create($data);
        $polo->coordenadores()->sync($coordenadores);

        $this->log('polo.criado', 'polo', "Polo {$polo->nome} criado.", null, "polo:{$polo->id}");

        return redirect()->route('doutores.polos.index')->with('success', 'Polo criado.');
    }

    public function edit(Polo $polo): View
    {
        $this->abortUnlessAdmin();

        return view('doutores.polos.form', $this->formData($polo));
    }

    public function update(Request $request, Polo $polo): RedirectResponse
    {
        $this->abortUnlessAdmin();

        $data = $this->validated($request);
        $coordenadores = $data['coordenadores'] ?? [];
        unset($data['coordenadores']);

        $polo->update($data);
        $polo->coordenadores()->sync($coordenadores);

        $this->log('polo.atualizado', 'polo', "Polo {$polo->nome} atualizado.", null, "polo:{$polo->id}");

        return redirect()->route('doutores.polos.index')->with('success', 'Polo atualizado.');
    }

    public function destroy(Polo $polo): RedirectResponse
    {
        $this->abortUnlessAdmin();

        if ($polo->hospitais()->exists()) {
            return back()->with('error', 'Remova ou reatribua os hospitais deste polo antes de excluí-lo.');
        }

        $nome = $polo->nome;
        $polo->delete();

        $this->log('polo.removido', 'polo', "Polo {$nome} removido.", null, null, 'warning');

        return redirect()->route('doutores.polos.index')->with('success', 'Polo removido.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function formData(Polo $polo): array
    {
        return [
            'polo' => $polo,
            'coordenadoresDisponiveis' => User::query()
                ->whereHas('roles', fn (Builder $q) => $q->where('name', DoutoresAuth::ROLE_COORD))
                ->orderBy('name')
                ->get(['id', 'name', 'email']),
            'coordenadoresSelecionados' => $polo->exists ? $polo->coordenadores()->pluck('users.id')->all() : [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cep' => ['nullable', 'string', 'max:9'],
            'endereco' => ['nullable', 'string', 'max:255'],
            'cidade' => ['nullable', 'string', 'max:255'],
            'estado' => ['required', 'string', 'size:2'],
            'telefone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'coordenador' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['ativo', 'inativo'])],
            'coordenadores' => ['nullable', 'array'],
            'coordenadores.*' => ['integer', 'exists:users,id'],
        ]);

        $data['estado'] = strtoupper($data['estado']);
        $data['cep'] = isset($data['cep']) ? preg_replace('/\D/', '', $data['cep']) : null;

        return $data;
    }
}
