<?php

namespace App\Http\Controllers\Doutores;

use App\Http\Controllers\Controller;
use App\Models\Doutores\Dominio;
use App\Models\Doutores\Hospital;
use App\Models\Doutores\Profile;
use App\Models\Role;
use App\Models\User;
use App\Support\DoutoresAuth;
use App\Support\DoutoresLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Cadastro público de voluntários — o perfil nasce "pendente" até a aprovação da coordenação.
 */
class CadastroPublicoController extends Controller
{
    public function show(): View
    {
        return view('doutores.cadastrar', [
            'hospitais' => Hospital::query()->where('status', 'ativo')->orderBy('nome')->pluck('nome'),
            'dias' => Dominio::opcoes('dia_preferido'),
            'turnos' => Dominio::opcoes('turno_preferido'),
            'igrejas' => Dominio::opcoes('igreja'),
            'funcoes' => Dominio::opcoes('funcao'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'cpf' => preg_replace('/\D/', '', (string) $request->input('cpf')),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'email' => [
                'required',
                'email',
                'max:190',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $existing = User::query()->where('email', $value)->with(['roles', 'doutoresProfile'])->first();
                    if (! $existing) {
                        return;
                    }

                    // Conta só do CMS/site pode se cadastrar no Doutores com o mesmo e-mail.
                    if (DoutoresAuth::canAccessDoutoresLogin($existing) || $existing->doutoresProfile) {
                        $fail('Este e-mail já possui cadastro no Doutores da Esperança. Entre pelo painel ou fale com a coordenação.');
                    }
                },
            ],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'cpf' => [
                'required',
                'string',
                'size:11',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! self::cpfValido((string) $value)) {
                        $fail('CPF inválido.');

                        return;
                    }
                    if (Profile::query()->where('cpf', $value)->exists()) {
                        $fail('Este CPF já está cadastrado.');
                    }
                },
            ],
            'telefone' => ['required', 'string', 'max:20'],
            'genero' => ['nullable', Rule::in(['feminino', 'masculino', 'outro'])],
            'cep' => ['nullable', 'string', 'max:9'],
            'endereco' => ['nullable', 'string', 'max:255'],
            'bairro' => ['nullable', 'string', 'max:255'],
            'cidade' => ['nullable', 'string', 'max:255'],
            'hospital_preferido' => ['nullable', 'string', 'max:255'],
            'dia_preferido' => ['nullable', 'string', 'max:255'],
            'horario_preferido' => ['nullable', 'string', 'max:255'],
            'igreja' => ['nullable', 'string', 'max:255'],
            'cargo' => ['nullable', 'string', 'max:255'],
            'accept_terms' => ['accepted'],
        ], [
            'accept_terms.accepted' => 'É necessário aceitar os termos de voluntariado para se cadastrar.',
            'cpf.size' => 'O CPF deve ter 11 dígitos.',
            'password.confirmed' => 'A confirmação de senha não confere.',
        ]);

        Role::query()->firstOrCreate(['name' => DoutoresAuth::ROLE_VOL, 'guard_name' => 'web']);

        $user = DB::transaction(function () use ($data) {
            $user = User::query()->where('email', $data['email'])->first();

            if ($user) {
                $user->update([
                    'name' => $data['name'],
                    'password' => $data['password'],
                    'phone' => $data['telefone'],
                    'is_active' => true,
                ]);
            } else {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'phone' => $data['telefone'],
                    'is_active' => true,
                ]);
            }

            $user->syncDoutoresRoles(array_values(array_unique(array_merge(
                $user->doutoresRoleNames(),
                [DoutoresAuth::ROLE_VOL]
            ))));

            Profile::query()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'cpf' => $data['cpf'],
                    'status' => Profile::STATUS_PENDENTE,
                    'genero' => $data['genero'] ?? null,
                    'telefone' => preg_replace('/\D/', '', $data['telefone']),
                    'cep' => isset($data['cep']) ? preg_replace('/\D/', '', $data['cep']) : null,
                    'endereco' => $data['endereco'] ?? null,
                    'bairro' => $data['bairro'] ?? null,
                    'cidade' => $data['cidade'] ?? null,
                    'hospital_preferido' => $data['hospital_preferido'] ?? null,
                    'dia_preferido' => $data['dia_preferido'] ?? null,
                    'horario_preferido' => $data['horario_preferido'] ?? null,
                    'igreja' => $data['igreja'] ?? null,
                    'cargo' => $data['cargo'] ?? null,
                    'termos_aceitos_em' => now(),
                ]
            );

            return $user->fresh(['roles', 'doutoresProfile']);
        });

        DoutoresLogger::record(
            'cadastro.publico',
            'voluntario',
            'Cadastro público de voluntário recebido (aguardando aprovação).',
            $user,
            $user->email
        );

        return redirect()
            ->route('doutores.cadastrar')
            ->with('cadastro_ok', 'Cadastro recebido! A coordenação irá analisá-lo e você poderá entrar no painel assim que for aprovado.');
    }

    /**
     * Validação de dígitos verificadores do CPF.
     */
    public static function cpfValido(string $cpf): bool
    {
        $cpf = preg_replace('/\D/', '', $cpf);

        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $soma = 0;
            for ($c = 0; $c < $t; $c++) {
                $soma += (int) $cpf[$c] * (($t + 1) - $c);
            }
            $digito = ((10 * $soma) % 11) % 10;
            if ((int) $cpf[$t] !== $digito) {
                return false;
            }
        }

        return true;
    }
}
