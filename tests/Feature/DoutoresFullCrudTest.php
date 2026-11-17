<?php

namespace Tests\Feature;

use App\Models\Doutores\Anexo;
use App\Models\Doutores\Aviso;
use App\Models\Doutores\ComentarioPublicacao;
use App\Models\Doutores\Dominio;
use App\Models\Doutores\Hospital;
use App\Models\Doutores\Inscricao;
use App\Models\Doutores\Plantao;
use App\Models\Doutores\Polo;
use App\Models\Doutores\Profile;
use App\Models\Doutores\Publicacao;
use App\Models\Doutores\SolicitacaoEstudo;
use App\Models\Doutores\SolicitacaoEstudoLog;
use App\Models\Doutores\Testemunho;
use App\Models\Role;
use App\Models\User;
use App\Support\DoutoresAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Suíte completa de CRUD, validações e regras de negócio do portal Doutores.
 * Complementa DoutoresAdminTest / DoutoresAuthTest com os caminhos ainda descobertos.
 */
class DoutoresFullCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([
            'super_admin', 'admin', 'member',
            'doutores_admin', 'doutores_coordenador', 'doutores_voluntario',
        ] as $role) {
            Role::query()->firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }

    private function makeUser(string $email, array $roles, ?string $profileStatus = Profile::STATUS_ATIVO, array $profile = []): User
    {
        $user = User::create([
            'name' => 'User '.$email,
            'email' => $email,
            'password' => 'senha12345',
            'is_active' => true,
        ]);
        $user->syncRoles($roles);

        if ($profileStatus !== null) {
            Profile::create(array_merge([
                'user_id' => $user->id,
                'status' => $profileStatus,
                'termos_aceitos_em' => now(),
            ], $profile));
        }

        return $user->fresh(['roles', 'doutoresProfile']);
    }

    private function asDoutores(User $user, string $perfil): static
    {
        return $this->actingAs($user, 'doutores')
            ->withSession([DoutoresAuth::SESSION_PERFIL_KEY => $perfil]);
    }

    private function admin(): User
    {
        return $this->makeUser('admin-full@ex.com', ['doutores_admin']);
    }

    /** @return array{0:Polo,1:Polo,2:Hospital,3:Hospital} */
    private function seedStructure(): array
    {
        $poloA = Polo::create(['nome' => 'Polo Asa Sul', 'estado' => 'DF', 'status' => 'ativo']);
        $poloB = Polo::create(['nome' => 'Polo Taguatinga', 'estado' => 'DF', 'status' => 'ativo']);
        $hospA = Hospital::create(['nome' => 'HRAN', 'polo_id' => $poloA->id, 'uf' => 'DF', 'status' => 'ativo', 'endereco' => 'SMHN']);
        $hospB = Hospital::create(['nome' => 'HRT', 'polo_id' => $poloB->id, 'uf' => 'DF', 'status' => 'ativo']);

        return [$poloA, $poloB, $hospA, $hospB];
    }

    private function plantaoPayload(string $hospital, array $extra = []): array
    {
        return array_merge([
            'data' => now()->addDays(10)->format('Y-m-d'),
            'hora_inicio' => '08:00',
            'hora_fim' => '12:00',
            'hospital' => $hospital,
            'tipo' => 'visita_mensal',
            'status' => 'aberto',
        ], $extra);
    }

    // -------------------------------------------------------------------------
    // Auth / perfil
    // -------------------------------------------------------------------------

    public function test_auth_validation_and_inactive_states(): void
    {
        $this->post(route('doutores.login.post'), [])->assertSessionHasErrors(['email', 'password']);

        $inactive = $this->makeUser('inativo@ex.com', ['doutores_voluntario']);
        $inactive->update(['is_active' => false]);
        $this->post(route('doutores.login.post'), [
            'email' => 'inativo@ex.com', 'password' => 'senha12345',
        ])->assertSessionHasErrors('email');

        $blocked = $this->makeUser('bloqueado@ex.com', ['doutores_voluntario'], Profile::STATUS_INATIVO);
        $this->post(route('doutores.login.post'), [
            'email' => 'bloqueado@ex.com', 'password' => 'senha12345',
        ])->assertSessionHasErrors('email');

        $coord = $this->makeUser('coord-login@ex.com', ['doutores_coordenador']);
        $this->post(route('doutores.login.post'), [
            'email' => 'coord-login@ex.com', 'password' => 'senha12345',
        ])->assertRedirect(route('doutores.home'));
        $this->assertAuthenticatedAs($coord, 'doutores');
        $this->assertSame(DoutoresAuth::PERFIL_COORD, session(DoutoresAuth::SESSION_PERFIL_KEY));

        $this->get(route('doutores.login'))->assertRedirect(route('doutores.home'));
    }

    public function test_perfil_switch_validation_and_guest_logout(): void
    {
        $this->post(route('doutores.logout'))->assertRedirect(route('doutores.login'));

        $user = $this->makeUser('multi@ex.com', ['doutores_admin', 'doutores_voluntario']);
        $this->asDoutores($user, 'administrador')
            ->post(route('doutores.perfil.trocar'), [])
            ->assertSessionHasErrors('perfil');

        $this->post(route('doutores.perfil.trocar'), ['perfil' => 'coordenador'])
            ->assertSessionHasErrors('perfil');
    }

    // -------------------------------------------------------------------------
    // Cadastro público — validações
    // -------------------------------------------------------------------------

    public function test_public_registration_validation_matrix(): void
    {
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
        $this->seedStructure();

        $base = [
            'name' => 'Vol Teste',
            'email' => 'volcad@ex.com',
            'password' => 'Senha1234',
            'password_confirmation' => 'Senha1234',
            'cpf' => '529.982.247-25',
            'telefone' => '(61) 99999-0000',
            'hospital_preferido' => 'HRAN',
            'accept_terms' => '1',
        ];

        $this->post(route('doutores.cadastrar.store'), [])
            ->assertSessionHasErrors(['name', 'email', 'password', 'cpf', 'telefone', 'accept_terms']);

        $this->post(route('doutores.cadastrar.store'), array_merge($base, [
            'password' => 'senha', 'password_confirmation' => 'senha',
        ]))->assertSessionHasErrors('password');

        $this->post(route('doutores.cadastrar.store'), array_merge($base, [
            'password_confirmation' => 'outra',
        ]))->assertSessionHasErrors('password');

        $this->post(route('doutores.cadastrar.store'), array_merge($base, [
            'accept_terms' => null,
        ]))->assertSessionHasErrors('accept_terms');

        $this->post(route('doutores.cadastrar.store'), array_merge($base, [
            'genero' => 'invalido',
        ]))->assertSessionHasErrors('genero');

        $this->post(route('doutores.cadastrar.store'), $base)
            ->assertRedirect(route('doutores.cadastrar'))
            ->assertSessionHas('cadastro_ok');

        $this->post(route('doutores.cadastrar.store'), array_merge($base, [
            'email' => 'outro@ex.com',
            'cpf' => '529.982.247-25',
        ]))->assertSessionHasErrors('cpf');

        $this->post(route('doutores.cadastrar.store'), array_merge($base, [
            'email' => 'volcad@ex.com',
            'cpf' => '390.533.447-05',
        ]))->assertSessionHasErrors('email');
    }

    // -------------------------------------------------------------------------
    // Plantões — CRUD completo + validações + resultados + equipes
    // -------------------------------------------------------------------------

    public function test_plantao_full_crud_validation_results_and_equipes(): void
    {
        $this->seedStructure();
        $admin = $this->admin();
        $this->asDoutores($admin, 'administrador');

        $this->post(route('doutores.plantoes.store'), [])
            ->assertSessionHasErrors(['data', 'hospital', 'tipo', 'status']);

        $this->post(route('doutores.plantoes.store'), $this->plantaoPayload('HRAN', [
            'tipo' => 'tipo_invalido',
        ]))->assertSessionHasErrors('tipo');

        $this->post(route('doutores.plantoes.store'), $this->plantaoPayload('HRAN'))
            ->assertRedirect();
        $plantao = Plantao::firstOrFail();
        $this->assertSame('HRAN', $plantao->hospital);
        $this->assertSame('SMHN', $plantao->endereco);

        $this->get(route('doutores.plantoes.show', $plantao))->assertOk()->assertSee('HRAN');
        $this->get(route('doutores.plantoes.edit', $plantao))->assertOk();

        $this->put(route('doutores.plantoes.update', $plantao), $this->plantaoPayload('HRAN', [
            'tipo' => 'visita_especial',
            'status' => 'fechado',
            'observacoes' => 'Atualizado',
            'hora_inicio' => '09:00',
        ]))->assertRedirect(route('doutores.plantoes.show', $plantao));

        $plantao = $plantao->fresh();
        $this->assertSame('visita_especial', $plantao->tipo);
        $this->assertSame('fechado', $plantao->status);
        $this->assertSame('Atualizado', $plantao->observacoes);

        $this->put(route('doutores.plantoes.resultados', $plantao), [])
            ->assertSessionHasErrors(['quartos_visitados', 'visitas_realizadas', 'livros_distribuidos']);

        $this->put(route('doutores.plantoes.resultados', $plantao), [
            'quartos_visitados' => 12,
            'visitas_realizadas' => 8,
            'livros_distribuidos' => 40,
            'marcar_realizado' => '1',
        ])->assertRedirect();
        $plantao = $plantao->fresh();
        $this->assertSame(12, $plantao->quartos_visitados);
        $this->assertSame('realizado', $plantao->status);
        $this->assertNotNull($plantao->resultados_registrados_em);

        $vol = $this->makeUser('vol-eq@ex.com', ['doutores_voluntario'], Profile::STATUS_ATIVO, ['cargo' => 'Música']);
        $insc = Inscricao::create([
            'user_id' => $vol->id,
            'plantao_id' => $plantao->id,
            'hospital' => 'HRAN',
            'data' => $plantao->data,
            'papel' => 'Música',
            'status' => Inscricao::STATUS_CONFIRMADA,
        ]);
        $plantao->syncVoluntarios();

        $this->post(route('doutores.plantoes.equipes.gerar', $plantao), [
            'acao' => 'gerar', 'tamanho' => 2, 'somente_confirmadas' => '1',
        ])->assertRedirect(route('doutores.plantoes.equipes', $plantao));
        $this->assertNotEmpty($plantao->fresh()->equipes);

        $this->from(route('doutores.plantoes.equipes', $plantao))
            ->post(route('doutores.plantoes.equipes.gerar', $plantao), ['acao' => 'limpar'])
            ->assertRedirect(route('doutores.plantoes.equipes', $plantao))
            ->assertSessionHas('success');
        $this->assertNull($plantao->fresh()->equipes);

        $this->delete(route('doutores.plantoes.destroy', $plantao))
            ->assertRedirect(route('doutores.plantoes.index'));
        $this->assertDatabaseMissing('doutores_plantoes', ['id' => $plantao->id]);

        // Voluntário não acessa gestão de plantões
        $this->asDoutores($vol, 'voluntario')
            ->get(route('doutores.plantoes.index'))
            ->assertForbidden();
    }

    // -------------------------------------------------------------------------
    // Voluntários gestão — update, reject, validações store
    // -------------------------------------------------------------------------

    public function test_voluntario_management_update_reject_and_store_validation(): void
    {
        [$poloA] = $this->seedStructure();
        $admin = $this->admin();
        $coord = $this->makeUser('coord-vol@ex.com', ['doutores_coordenador']);
        $coord->doutoresPolos()->attach($poloA->id);

        $pendente = $this->makeUser('pend@ex.com', ['doutores_voluntario'], Profile::STATUS_PENDENTE, [
            'hospital_preferido' => 'HRAN',
        ]);
        $profile = $pendente->doutoresProfile;

        $this->asDoutores($admin, 'administrador');

        $this->post(route('doutores.voluntarios.store'), [
            'name' => 'X', 'email' => 'not-an-email',
        ])->assertSessionHasErrors('email');

        $this->post(route('doutores.voluntarios.store'), [
            'name' => 'Vol Criado',
            'email' => 'volcriado@ex.com',
            'password' => 'senha1234',
            'hospital_preferido' => 'HRAN',
            'status' => 'ativo',
            'cpf' => '390.533.447-05',
        ])->assertRedirect(route('doutores.voluntarios.index'));
        $this->assertDatabaseHas('users', ['email' => 'volcriado@ex.com']);

        $this->post(route('doutores.voluntarios.store'), [
            'name' => 'Dup', 'email' => 'volcriado@ex.com', 'password' => 'senha1234',
        ])->assertSessionHasErrors('email');

        $this->put(route('doutores.voluntarios.update', $profile), [
            'hospital_preferido' => 'HRAN',
            'observacoes' => 'Obs atualizada',
            'coordenador_responsavel_id' => $coord->id,
        ])->assertRedirect();
        $profile = $profile->fresh();
        $this->assertSame('Obs atualizada', $profile->observacoes);
        $this->assertSame($coord->id, $profile->coordenador_responsavel_id);

        $this->post(route('doutores.voluntarios.reject', $profile), [
            'motivo' => 'Documentação incompleta',
        ])->assertRedirect();
        $profile = $profile->fresh();
        $this->assertSame(Profile::STATUS_INATIVO, $profile->status);
        $this->assertStringContainsString('Documentação incompleta', (string) $profile->observacoes);

        $outro = $this->makeUser('outro-polo@ex.com', ['doutores_voluntario'], Profile::STATUS_PENDENTE, [
            'hospital_preferido' => 'HRT',
        ]);
        $this->asDoutores($coord, 'coordenador')
            ->get(route('doutores.voluntarios.show', $outro->doutoresProfile))
            ->assertForbidden();

        $vol = $this->makeUser('so-vol@ex.com', ['doutores_voluntario']);
        $this->asDoutores($vol, 'voluntario')
            ->get(route('doutores.voluntarios.index'))
            ->assertForbidden();
    }

    // -------------------------------------------------------------------------
    // Estudos bíblicos — store, update transitions, destroy
    // -------------------------------------------------------------------------

    public function test_estudo_crud_status_transitions_and_admin_destroy(): void
    {
        [$poloA] = $this->seedStructure();
        $admin = $this->admin();
        $coord = $this->makeUser('coord-est@ex.com', ['doutores_coordenador']);
        $coord->doutoresPolos()->attach($poloA->id);

        $this->asDoutores($admin, 'administrador');

        $this->post(route('doutores.estudos.store'), [])
            ->assertSessionHasErrors('nome_paciente');

        $this->post(route('doutores.estudos.store'), [
            'nome_paciente' => 'João Paciente',
            'hospital' => 'HRAN',
            'telefone' => '61999990000',
            'email' => 'joao@ex.com',
            'observacoes' => 'Leito 12',
        ])->assertRedirect();
        $estudo = SolicitacaoEstudo::firstOrFail();
        $this->assertSame('pendente', $estudo->status);
        $this->assertSame(1, SolicitacaoEstudoLog::count());

        $this->put(route('doutores.estudos.update', $estudo), [
            'status' => 'encaminhado',
            'atribuido_a' => 'Pastor Local',
            'observacoes_log' => 'Encaminhado à equipe',
        ])->assertRedirect();
        $estudo = $estudo->fresh();
        $this->assertSame('encaminhado', $estudo->status);
        $this->assertNotNull($estudo->encaminhado_em);
        $this->assertSame(2, SolicitacaoEstudoLog::count());

        $this->put(route('doutores.estudos.update', $estudo), [
            'status' => 'em_andamento',
        ])->assertRedirect();
        $this->assertNotNull($estudo->fresh()->em_andamento_em);

        $this->put(route('doutores.estudos.update', $estudo), [
            'status' => 'concluido',
        ])->assertRedirect();
        $this->assertNotNull($estudo->fresh()->concluido_em);

        $estudo2 = SolicitacaoEstudo::create([
            'nome_paciente' => 'Outro', 'hospital' => 'HRT', 'status' => 'pendente',
        ]);
        $this->asDoutores($coord, 'coordenador')
            ->get(route('doutores.estudos.show', $estudo2))
            ->assertForbidden();
        $this->delete(route('doutores.estudos.destroy', $estudo))
            ->assertForbidden();

        $this->asDoutores($admin, 'administrador')
            ->delete(route('doutores.estudos.destroy', $estudo))
            ->assertRedirect(route('doutores.estudos.index'));
        $this->assertDatabaseMissing('doutores_solicitacoes_estudo', ['id' => $estudo->id]);
    }

    // -------------------------------------------------------------------------
    // Avisos — validação + destroy + permissões
    // -------------------------------------------------------------------------

    public function test_aviso_store_validation_destroy_and_permissions(): void
    {
        $this->seedStructure();
        $plantao = Plantao::create($this->plantaoPayload('HRAN'));
        $admin = $this->admin();
        $vol = $this->makeUser('vol-aviso@ex.com', ['doutores_voluntario']);

        $this->asDoutores($admin, 'administrador');

        $this->post(route('doutores.avisos.store'), [])
            ->assertSessionHasErrors(['plantao_id', 'titulo']);

        $this->post(route('doutores.avisos.store'), [
            'plantao_id' => $plantao->id,
            'titulo' => 'Levar crachá',
            'conteudo' => 'Importante',
        ])->assertRedirect(route('doutores.avisos.index'));

        $aviso = Aviso::firstOrFail();
        $this->delete(route('doutores.avisos.destroy', $aviso))
            ->assertRedirect();
        $this->assertDatabaseMissing('doutores_avisos', ['id' => $aviso->id]);

        $this->asDoutores($vol, 'voluntario')
            ->get(route('doutores.avisos.index'))
            ->assertForbidden();
    }

    // -------------------------------------------------------------------------
    // Inscrições — regras de negócio + status gestor
    // -------------------------------------------------------------------------

    public function test_inscricao_business_rules_and_manager_status(): void
    {
        $this->seedStructure();
        Dominio::create(['categoria' => 'funcao', 'descricao' => 'Música', 'ativo' => true]);

        $aberto = Plantao::create($this->plantaoPayload('HRAN'));
        $fechado = Plantao::create($this->plantaoPayload('HRAN', [
            'status' => 'fechado',
            'data' => now()->addDays(20)->format('Y-m-d'),
        ]));

        $vol = $this->makeUser('vol-insc@ex.com', ['doutores_voluntario'], Profile::STATUS_ATIVO, ['cargo' => 'Música']);
        $admin = $this->admin();

        $this->asDoutores($vol, 'voluntario');

        $this->post(route('doutores.area.inscricoes.store'), [
            'plantao_id' => $fechado->id,
        ])->assertSessionHas('error');

        $this->post(route('doutores.area.inscricoes.store'), [
            'plantao_id' => $aberto->id,
            'papel' => 'Função Inexistente',
        ])->assertSessionHas('error');

        $this->post(route('doutores.area.inscricoes.store'), [
            'plantao_id' => $aberto->id,
            'papel' => 'Música',
        ])->assertRedirect(route('doutores.area.inscricoes'));

        $insc = Inscricao::firstOrFail();

        $this->post(route('doutores.area.inscricoes.store'), [
            'plantao_id' => $aberto->id,
        ])->assertSessionHas('error');

        $this->asDoutores($admin, 'administrador')
            ->put(route('doutores.inscricoes.status', $insc), ['status' => 'confirmada'])
            ->assertRedirect();
        $this->assertSame(Inscricao::STATUS_CONFIRMADA, $insc->fresh()->status);

        $this->asDoutores($vol, 'voluntario')
            ->put(route('doutores.inscricoes.status', $insc), ['status' => 'recusada'])
            ->assertForbidden();

        $this->delete(route('doutores.area.inscricoes.destroy', $insc), [
            'justificativa' => 'Imprevisto',
        ])->assertRedirect();
        $this->assertSame(Inscricao::STATUS_CANCELADA, $insc->fresh()->status);

        $this->post(route('doutores.area.inscricoes.confirmar', $insc))
            ->assertSessionHas('error');
    }

    // -------------------------------------------------------------------------
    // Testemunhos — regras e validação
    // -------------------------------------------------------------------------

    public function test_testemunho_rules_and_validation(): void
    {
        $this->seedStructure();
        $plantao = Plantao::create($this->plantaoPayload('HRAN', [
            'data' => now()->subDay()->format('Y-m-d'),
            'status' => 'realizado',
        ]));
        $vol = $this->makeUser('vol-test@ex.com', ['doutores_voluntario']);
        $outro = $this->makeUser('vol-test2@ex.com', ['doutores_voluntario']);

        $this->asDoutores($vol, 'voluntario');

        $this->post(route('doutores.area.testemunhos.store'), [
            'plantao_id' => $plantao->id,
            'texto' => 'curto',
        ])->assertSessionHasErrors('texto');

        $this->post(route('doutores.area.testemunhos.store'), [
            'plantao_id' => $plantao->id,
            'texto' => 'Um testemunho válido com mais de dez caracteres.',
        ])->assertSessionHas('error');

        Inscricao::create([
            'user_id' => $vol->id,
            'plantao_id' => $plantao->id,
            'hospital' => 'HRAN',
            'data' => $plantao->data,
            'status' => Inscricao::STATUS_CONFIRMADA,
        ]);

        $this->post(route('doutores.area.testemunhos.store'), [
            'plantao_id' => $plantao->id,
            'texto' => 'Um testemunho válido com mais de dez caracteres.',
        ])->assertRedirect(route('doutores.area.testemunhos'));

        $t = Testemunho::firstOrFail();

        $this->post(route('doutores.area.testemunhos.store'), [
            'plantao_id' => $plantao->id,
            'texto' => 'Outro texto longo o suficiente para passar.',
        ])->assertSessionHas('error');

        $this->asDoutores($outro, 'voluntario')
            ->put(route('doutores.area.testemunhos.update', $t), [
                'texto' => 'Tentativa de edição alheia com texto longo.',
            ])->assertForbidden();

        $this->asDoutores($vol, 'voluntario')
            ->put(route('doutores.area.testemunhos.update', $t), [
                'texto' => 'Texto atualizado com tamanho adequado.',
            ])->assertRedirect(route('doutores.area.testemunhos'));
        $this->assertSame('Texto atualizado com tamanho adequado.', $t->fresh()->texto);
    }

    // -------------------------------------------------------------------------
    // Anexos — validação, review escopo, destroy gestor
    // -------------------------------------------------------------------------

    public function test_anexo_validation_review_and_manager_destroy(): void
    {
        Storage::fake('local');
        [$poloA] = $this->seedStructure();
        $vol = $this->makeUser('vol-anexo@ex.com', ['doutores_voluntario'], Profile::STATUS_ATIVO, [
            'hospital_preferido' => 'HRAN',
        ]);
        $coord = $this->makeUser('coord-anexo@ex.com', ['doutores_coordenador']);
        $coord->doutoresPolos()->attach($poloA->id);
        $admin = $this->admin();

        $this->asDoutores($vol, 'voluntario');

        $this->post(route('doutores.anexos.store'), [
            'tipo' => 'tipo_x',
            'arquivo' => UploadedFile::fake()->create('doc.txt', 10, 'text/plain'),
        ])->assertSessionHasErrors(['tipo', 'arquivo']);

        $this->post(route('doutores.anexos.store'), [
            'tipo' => 'documento_identidade',
            'arquivo' => UploadedFile::fake()->create('rg.pdf', 100, 'application/pdf'),
        ])->assertRedirect();
        $anexo = Anexo::firstOrFail();
        Storage::disk('local')->assertExists($anexo->path);

        $this->asDoutores($coord, 'coordenador')
            ->put(route('doutores.anexos.revisar', $anexo), ['status' => 'aprovado'])
            ->assertRedirect();
        $this->assertSame('aprovado', $anexo->fresh()->status);

        $this->asDoutores($admin, 'administrador')
            ->delete(route('doutores.anexos.destroy', $anexo))
            ->assertRedirect();
        $this->assertDatabaseMissing('doutores_anexos', ['id' => $anexo->id]);
    }

    // -------------------------------------------------------------------------
    // Perfil voluntário — validação + foto (disco público)
    // -------------------------------------------------------------------------

    public function test_volunteer_profile_validation_and_photo_upload(): void
    {
        Storage::fake('doutores');
        $vol = $this->makeUser('vol-perfil@ex.com', ['doutores_voluntario']);

        $this->asDoutores($vol, 'voluntario');

        $this->put(route('doutores.area.perfil.update'), [
            'telefone' => '6199999',
        ])->assertSessionHasErrors('name');

        $this->put(route('doutores.area.perfil.update'), [
            'name' => 'Nome Atualizado',
            'genero' => 'xyz',
        ])->assertSessionHasErrors('genero');

        $this->put(route('doutores.area.perfil.update'), [
            'name' => 'Nome Atualizado',
            'telefone' => '(61) 98888-7777',
            'cidade' => 'Brasília',
            'foto' => UploadedFile::fake()->image('foto.jpg', 200, 200),
        ])->assertRedirect(route('doutores.area.perfil'));

        $profile = $vol->doutoresProfile->fresh();
        $this->assertSame('Nome Atualizado', $vol->fresh()->name);
        $this->assertNotNull($profile->foto_path);
        Storage::disk('doutores')->assertExists($profile->foto_path);

        $this->post(route('doutores.area.termos.aceitar'), [])
            ->assertSessionHasErrors('aceito');
    }

    // -------------------------------------------------------------------------
    // Polos / Hospitais / Domínios — validações + permissões
    // -------------------------------------------------------------------------

    public function test_polo_hospital_dominio_validation_and_forbidden_for_coord(): void
    {
        $this->seedStructure();
        $admin = $this->admin();
        $coord = $this->makeUser('coord-cfg@ex.com', ['doutores_coordenador']);

        $this->asDoutores($admin, 'administrador');

        $this->post(route('doutores.polos.store'), [])
            ->assertSessionHasErrors(['nome', 'estado', 'status']);

        $this->post(route('doutores.hospitais.store'), [
            'nome' => 'HRAN', 'uf' => 'DF', 'status' => 'ativo',
        ])->assertSessionHasErrors('nome');

        $this->post(route('doutores.hospitais.store'), [
            'nome' => 'HUB', 'uf' => 'D', 'status' => 'ativo',
        ])->assertSessionHasErrors('uf');

        $this->post(route('doutores.dominios.store'), [
            'categoria' => 'categoria_x', 'descricao' => 'X',
        ])->assertSessionHasErrors('categoria');

        $this->post(route('doutores.dominios.store'), [
            'categoria' => 'funcao',
        ])->assertSessionHasErrors('descricao');

        $this->asDoutores($coord, 'coordenador');
        $this->post(route('doutores.polos.store'), [
            'nome' => 'Polo X', 'estado' => 'DF', 'status' => 'ativo',
        ])->assertForbidden();
        $this->post(route('doutores.hospitais.store'), [
            'nome' => 'HX', 'uf' => 'DF', 'status' => 'ativo',
        ])->assertForbidden();
        $this->post(route('doutores.dominios.store'), [
            'categoria' => 'funcao', 'descricao' => 'Y',
        ])->assertForbidden();
    }

    // -------------------------------------------------------------------------
    // Publicações — validações, destroy comentário, imagem pública
    // -------------------------------------------------------------------------

    public function test_publicacao_validation_image_storage_and_comment_destroy(): void
    {
        Storage::fake('doutores');
        $admin = $this->admin();
        $coord = $this->makeUser('coord-pub@ex.com', ['doutores_coordenador']);
        $this->asDoutores($admin, 'administrador');

        $this->post(route('doutores.publicacoes.store'), [])
            ->assertSessionHasErrors(['titulo', 'status']);

        $this->post(route('doutores.publicacoes.store'), [
            'titulo' => 'Notícia completa',
            'conteudo' => 'Corpo',
            'status' => 'publicado',
            'imagens' => [UploadedFile::fake()->image('capa.jpg')],
        ])->assertRedirect();

        $pub = Publicacao::firstOrFail();
        $this->assertCount(1, $pub->imagens);
        Storage::disk('doutores')->assertExists($pub->imagens[0]);

        $coment = ComentarioPublicacao::create([
            'publicacao_id' => $pub->id,
            'nome' => 'Ana',
            'mensagem' => 'Ótimo',
            'status' => 'pendente',
        ]);

        $this->put(route('doutores.publicacoes.comentarios.moderar', $coment), [
            'status' => 'status_x',
        ])->assertSessionHasErrors('status');

        $this->put(route('doutores.publicacoes.comentarios.moderar', $coment), [
            'status' => 'rejeitado',
        ])->assertRedirect();
        $this->assertSame('rejeitado', $coment->fresh()->status);

        $this->delete(route('doutores.publicacoes.comentarios.destroy', $coment))
            ->assertRedirect();
        $this->assertDatabaseMissing('doutores_comentarios_publicacao', ['id' => $coment->id]);

        $this->asDoutores($coord, 'coordenador')
            ->get(route('doutores.publicacoes.index'))
            ->assertForbidden();
    }

    public function test_public_comment_email_and_length_validation(): void
    {
        $pub = Publicacao::create(['titulo' => 'Pública', 'status' => 'publicado', 'conteudo' => 'x']);

        $this->post(route('doutores.public.publicacoes.comentar', $pub), [
            'nome' => 'A',
            'email' => 'nao-email',
            'mensagem' => 'oi',
        ])->assertSessionHasErrors(['nome', 'email', 'mensagem']);
    }

    // -------------------------------------------------------------------------
    // Portal + usuários + relatórios/dashboard permissões
    // -------------------------------------------------------------------------

    public function test_portal_users_reports_and_role_matrix(): void
    {
        $admin = $this->admin();
        $vol = $this->makeUser('vol-perm@ex.com', ['doutores_voluntario']);
        $coord = $this->makeUser('coord-perm@ex.com', ['doutores_coordenador']);

        $this->asDoutores($admin, 'administrador');

        $this->put(route('doutores.portal.update'), [
            'contato_email' => 'nao-e-email',
        ])->assertSessionHasErrors('contato_email');

        $this->put(route('doutores.portal.update'), [
            'titulo_hero' => 'Hero OK',
            'contato_email' => 'contato@doutores.test',
        ])->assertRedirect();

        $this->post(route('doutores.usuarios.store'), [
            'name' => 'U',
            'email' => 'u@ex.com',
            'password' => '123',
            'doutores_roles' => ['doutores_voluntario'],
        ])->assertSessionHasErrors('password');

        $this->get(route('doutores.relatorios.index'))->assertOk();
        $this->get(route('doutores.relatorios.export', ['tipo' => 'plantoes']))->assertOk();
        $this->get(route('doutores.relatorios.export', ['tipo' => 'voluntarios']))->assertOk();
        $this->get(route('doutores.dashboard'))->assertOk();
        $this->get(route('doutores.logs.index'))->assertOk();

        $this->asDoutores($vol, 'voluntario');
        $this->get(route('doutores.dashboard'))->assertForbidden();
        $this->get(route('doutores.relatorios.index'))->assertForbidden();
        $this->get(route('doutores.logs.index'))->assertForbidden();
        $this->get(route('doutores.usuarios.index'))->assertForbidden();

        $this->asDoutores($coord, 'coordenador');
        $this->get(route('doutores.dashboard'))->assertOk();
        $this->get(route('doutores.relatorios.export', ['tipo' => 'plantoes']))->assertOk();
        $this->get(route('doutores.logs.index'))->assertForbidden();
        $this->get(route('doutores.usuarios.index'))->assertForbidden();
        $this->get(route('doutores.area.inicio'))->assertForbidden();
    }

    public function test_hybrid_cms_email_can_be_promoted_to_doutores_admin_via_command_path(): void
    {
        $cms = User::create([
            'name' => 'CMS User',
            'email' => 'hibrido-crud@ex.com',
            'password' => Hash::make('senha12345'),
            'is_active' => true,
        ]);
        $cms->syncRoles(['super_admin']);

        $admin = $this->admin();
        $this->asDoutores($admin, 'administrador')
            ->post(route('doutores.usuarios.store'), [
                'name' => 'CMS Híbrido',
                'email' => 'hibrido-crud@ex.com',
                'password' => 'senha12345',
                'doutores_roles' => ['doutores_admin'],
            ])->assertRedirect(route('doutores.usuarios.index'));

        $cms = $cms->fresh('roles');
        $this->assertEqualsCanonicalizing(['super_admin', 'doutores_admin'], $cms->roles->pluck('name')->all());
        $this->assertSame('ativo', $cms->doutoresProfile->status);
    }
}
