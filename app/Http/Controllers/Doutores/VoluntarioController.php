<?php

namespace App\Http\Controllers\Doutores;

use App\Models\Doutores\Anexo;
use App\Models\Doutores\Dominio;
use App\Models\Doutores\Inscricao;
use App\Models\Doutores\Profile;
use App\Models\Doutores\Testemunho;
use App\Models\Role;
use App\Models\User;
use App\Support\DoutoresAuth;
use App\Support\DoutoresPoloScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VoluntarioController extends DoutoresController
{
    public function index(Request $request): View
    {
        $user = $this->user();

        $query = Profile::query()
            ->with(['user', 'coordenadorResponsavel'])
            ->whereHas('user.roles', fn (Builder $q) => $q->where('name', DoutoresAuth::ROLE_VOL));

        DoutoresPoloScope::filterProfileQuery($query, $user, $request);

        $query
            ->when($request->filled('status'), fn (Builder $q) => $q->where('status', $request->string('status')->toString()))
            ->when($request->filled('hospital'), fn (Builder $q) => $q->where('hospital_preferido', $request->string('hospital')->toString()))
            ->when($request->filled('q'), function (Builder $q) use ($request) {
                $term = '%'.$request->string('q')->toString().'%';
                $q->where(function (Builder $w) use ($term) {
                    $w->where('cpf', 'like', $term)
                        ->orWhere('telefone', 'like', $term)
                        ->orWhereHas('user', fn (Builder $u) => $u->where('name', 'like', $term)->orWhere('email', 'like', $term));
                });
            });

        $profiles = $query->orderByRaw("CASE status WHEN 'pendente' THEN 0 WHEN 'ativo' THEN 1 ELSE 2 END")
            ->latest('id')
            ->paginate(30)
            ->withQueryString();

        return view('doutores.voluntarios.index', [
            'profiles' => $profiles,
            'hospitais' => $this->hospitalNames(),
            'hospitaisAtivos' => $this->hospitalNames(true),
            'funcoes' => Dominio::opcoes('funcao'),
            'igrejas' => Dominio::opcoes('igreja'),
            'openCreate' => $request->boolean('novo') || old('_form') === 'create',
        ]);
    }

    public function show(Profile $profile): View
    {
        $this->assertProfile($profile);
        $profile->load(['user.roles', 'coordenadorResponsavel']);

        $coordenadores = User::query()
            ->whereHas('roles', fn (Builder $q) => $q->whereIn('name', [DoutoresAuth::ROLE_COORD, DoutoresAuth::ROLE_ADMIN]))
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('doutores.voluntarios.show', [
            'profile' => $profile,
            'anexos' => Anexo::query()->where('user_id', $profile->user_id)->latest()->get(),
            'inscricoes' => Inscricao::query()->with('plantao')->where('user_id', $profile->user_id)->latest()->limit(20)->get(),
            'testemunhos' => Testemunho::query()->with('plantao')->where('user_id', $profile->user_id)->latest()->limit(10)->get(),
            'coordenadores' => $coordenadores,
        ]);
    }

    public function update(Request $request, Profile $profile): RedirectResponse
    {
        $this->assertProfile($profile);

        $data = $request->validate([
            'coordenador_responsavel_id' => ['nullable', 'integer', 'exists:users,id'],
            'hospital_preferido' => ['nullable', 'string', 'max:255'],
            'observacoes' => ['nullable', 'string', 'max:5000'],
        ]);

        if (! empty($data['hospital_preferido'])) {
            $this->assertHospital($data['hospital_preferido']);
        }

        $profile->update([
            'coordenador_responsavel_id' => $data['coordenador_responsavel_id'] ?? null,
            'hospital_preferido' => $data['hospital_preferido'] ?? null,
            'observacoes' => $data['observacoes'] ?? null,
        ]);

        $this->log('voluntario.atualizado', 'voluntario', 'Dados do voluntário atualizados.', $profile->user?->email);

        return back()->with('success', 'Voluntário atualizado.');
    }

    public function approve(Profile $profile): RedirectResponse
    {
        $this->assertProfile($profile);

        $this->ensureRole(DoutoresAuth::ROLE_VOL);
        $profile->update(['status' => Profile::STATUS_ATIVO]);

        if ($profile->user && ! $profile->user->hasAnyRoleName([DoutoresAuth::ROLE_VOL])) {
            $profile->user->loadMissing('roles');
            $profile->user->syncDoutoresRoles(array_merge($profile->user->doutoresRoleNames(), [DoutoresAuth::ROLE_VOL]));
        }

        if (! $profile->coordenador_responsavel_id && $this->perfilAtivo() === DoutoresAuth::PERFIL_COORD) {
            $profile->update(['coordenador_responsavel_id' => $this->user()->id]);
        }

        $this->log('voluntario.aprovado', 'voluntario', 'Cadastro de voluntário aprovado.', $profile->user?->email);

        return back()->with('success', 'Voluntário aprovado.');
    }

    public function reject(Request $request, Profile $profile): RedirectResponse
    {
        $this->assertProfile($profile);

        $data = $request->validate([
            'motivo' => ['nullable', 'string', 'max:1000'],
        ]);

        $update = ['status' => Profile::STATUS_INATIVO];
        if (! empty($data['motivo'])) {
            $update['observacoes'] = trim(($profile->observacoes ? $profile->observacoes."\n" : '').'Reprovado: '.$data['motivo']);
        }
        $profile->update($update);

        $this->log('voluntario.reprovado', 'voluntario', $data['motivo'] ?? 'Cadastro reprovado/inativado.', $profile->user?->email, null, 'warning');

        return back()->with('success', 'Voluntário marcado como inativo.');
    }

    public function store(Request $request): RedirectResponse
    {
        $actor = $this->user();

        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
                'password' => ['nullable', 'string', 'min:8'],
                'cpf' => ['nullable', 'string', 'max:14'],
                'telefone' => ['nullable', 'string', 'max:20'],
                'cidade' => ['nullable', 'string', 'max:255'],
                'hospital_preferido' => ['nullable', 'string', 'max:255'],
                'igreja' => ['nullable', 'string', 'max:255'],
                'cargo' => ['nullable', 'string', 'max:255'],
                'status' => ['nullable', Rule::in([Profile::STATUS_ATIVO, Profile::STATUS_PENDENTE, Profile::STATUS_INATIVO])],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e->redirectTo(route('doutores.voluntarios.index', ['novo' => 1]));
        }

        $cpf = isset($data['cpf']) ? preg_replace('/\D/', '', $data['cpf']) : null;
        if ($cpf !== null && $cpf !== '') {
            if (strlen($cpf) !== 11) {
                return back()->withInput()->withErrors(['cpf' => 'CPF deve ter 11 dígitos.']);
            }
            if (Profile::query()->where('cpf', $cpf)->exists()) {
                return back()->withInput()->withErrors(['cpf' => 'Este CPF já está cadastrado.']);
            }
        } else {
            $cpf = null;
        }

        if (! empty($data['hospital_preferido'])) {
            $this->assertHospital($data['hospital_preferido']);
        } elseif (! $this->unrestricted()) {
            return back()->withInput()->withErrors(['hospital_preferido' => 'Selecione um hospital do seu polo.']);
        }

        $senha = $data['password'] ?? null;
        $senhaGerada = false;
        if (! $senha) {
            $senha = Str::password(12, symbols: false);
            $senhaGerada = true;
        }

        $this->ensureRole(DoutoresAuth::ROLE_VOL);

        $newUser = DB::transaction(function () use ($data, $senha, $cpf, $actor) {
            $newUser = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $senha,
                'phone' => $data['telefone'] ?? null,
                'is_active' => true,
                'created_by' => $actor->id,
            ]);
            $newUser->syncDoutoresRoles([DoutoresAuth::ROLE_VOL]);

            Profile::create([
                'user_id' => $newUser->id,
                'cpf' => $cpf,
                'status' => $data['status'] ?? Profile::STATUS_ATIVO,
                'telefone' => isset($data['telefone']) ? preg_replace('/\D/', '', $data['telefone']) : null,
                'cidade' => $data['cidade'] ?? null,
                'hospital_preferido' => $data['hospital_preferido'] ?? null,
                'igreja' => $data['igreja'] ?? null,
                'cargo' => $data['cargo'] ?? null,
                'coordenador_responsavel_id' => $this->perfilAtivo() === DoutoresAuth::PERFIL_COORD ? $actor->id : null,
            ]);

            return $newUser;
        });

        $this->log('voluntario.criado', 'voluntario', 'Voluntário cadastrado por '.$actor->name.'.', $newUser->email);

        $msg = 'Voluntário criado.';
        if ($senhaGerada) {
            $msg .= ' Senha temporária: '.$senha.' (anote e repasse ao voluntário).';
        }

        return redirect()->route('doutores.voluntarios.index')->with('success', $msg);
    }

    protected function ensureRole(string $name): void
    {
        Role::query()->firstOrCreate(['name' => $name, 'guard_name' => 'web']);
    }
}
