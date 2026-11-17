<?php

namespace App\Http\Controllers\Doutores;

use App\Models\Doutores\Hospital;
use App\Models\Doutores\Plantao;
use App\Models\Doutores\Polo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HospitalController extends DoutoresController
{
    public function index(): View
    {
        $this->abortUnlessAdmin();

        return view('doutores.hospitais.index', [
            'hospitais' => Hospital::query()->with('polo')->orderBy('nome')->get(),
        ]);
    }

    public function create(): View
    {
        $this->abortUnlessAdmin();

        return view('doutores.hospitais.form', $this->formData(new Hospital(['uf' => 'DF', 'status' => 'ativo'])));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->abortUnlessAdmin();

        $hospital = Hospital::create($this->validated($request));

        $this->log('hospital.criado', 'hospital', "Hospital {$hospital->nome} criado.", null, "hospital:{$hospital->id}");

        return redirect()->route('doutores.hospitais.index')->with('success', 'Hospital criado.');
    }

    public function edit(Hospital $hospital): View
    {
        $this->abortUnlessAdmin();

        return view('doutores.hospitais.form', $this->formData($hospital));
    }

    public function update(Request $request, Hospital $hospital): RedirectResponse
    {
        $this->abortUnlessAdmin();

        $antigo = $hospital->nome;
        $hospital->update($this->validated($request, $hospital));

        // Mantém plantões já cadastrados vinculados ao nome atualizado.
        if ($antigo !== $hospital->nome) {
            Plantao::query()->where('hospital', $antigo)->update(['hospital' => $hospital->nome]);
        }

        $this->log('hospital.atualizado', 'hospital', "Hospital {$hospital->nome} atualizado.", null, "hospital:{$hospital->id}");

        return redirect()->route('doutores.hospitais.index')->with('success', 'Hospital atualizado.');
    }

    public function destroy(Hospital $hospital): RedirectResponse
    {
        $this->abortUnlessAdmin();

        $nome = $hospital->nome;
        $hospital->delete();

        $this->log('hospital.removido', 'hospital', "Hospital {$nome} removido.", null, null, 'warning');

        return redirect()->route('doutores.hospitais.index')->with('success', 'Hospital removido.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function formData(Hospital $hospital): array
    {
        return [
            'hospital' => $hospital,
            'polos' => Polo::query()->orderBy('nome')->get(['id', 'nome']),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function validated(Request $request, ?Hospital $hospital = null): array
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255', Rule::unique('doutores_hospitais', 'nome')->ignore($hospital?->id)],
            'polo_id' => ['nullable', 'integer', 'exists:doutores_polos,id'],
            'cep' => ['nullable', 'string', 'max:9'],
            'endereco' => ['nullable', 'string', 'max:255'],
            'cidade' => ['nullable', 'string', 'max:255'],
            'uf' => ['required', 'string', 'size:2'],
            'status' => ['required', Rule::in(['ativo', 'inativo'])],
        ]);

        $data['uf'] = strtoupper($data['uf']);
        $data['cep'] = isset($data['cep']) ? preg_replace('/\D/', '', $data['cep']) : null;

        return $data;
    }
}
