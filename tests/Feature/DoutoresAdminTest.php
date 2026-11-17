<?php

namespace Tests\Feature;

use App\Models\Doutores\Anexo;
use App\Models\Doutores\Aviso;
use App\Models\Doutores\AvisoLeitura;
use App\Models\Doutores\Dominio;
use App\Models\Doutores\Hospital;
use App\Models\Doutores\Inscricao;
use App\Models\Doutores\Plantao;
use App\Models\Doutores\Polo;
use App\Models\Doutores\Profile;
use App\Models\Doutores\SolicitacaoEstudo;
use App\Models\Doutores\Testemunho;
use App\Models\Role;
use App\Models\User;
use App\Support\DoutoresAuth;
use App\Support\DoutoresPoloScope;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DoutoresAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['super_admin', 'admin', 'member', 'doutores_admin', 'doutores_coordenador', 'doutores_voluntario'] as $role) {
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

        return $user->fresh();
    }

    private function asDoutores(User $user, string $perfil): static
    {
        return $this->actingAs($user, 'doutores')
            ->withSession([DoutoresAuth::SESSION_PERFIL_KEY => $perfil]);
    }

    private function adminUser(): User
    {
        return $this->makeUser('admin@ex.com', ['doutores_admin']);
    }

    private function seedStructure(): array
    {
        $poloA = Polo::create(['nome' => 'Polo Asa Sul', 'estado' => 'DF']);
        $poloB = Polo::create(['nome' => 'Polo Taguatinga', 'estado' => 'DF']);
        $hospA = Hospital::create(['nome' => 'HRAN', 'polo_id' => $poloA->id, 'endereco' => 'SMHN']);
        $hospB = Hospital::create(['nome' => 'HRT', 'polo_id' => $poloB->id]);

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

    public function test_admin_can_open_all_manager_and_admin_pages(): void
    {
        [, , $hospA] = $this->seedStructure();
        $plantao = Plantao::create($this->plantaoPayload('HRAN'));
        $polo = Polo::first();
        $estudo = SolicitacaoEstudo::create(['nome_paciente' => 'Maria', 'hospital' => 'HRAN']);
        $profile = Profile::create([
            'user_id' => $this->makeUser('vol0@ex.com', ['doutores_voluntario'], null)->id,
            'status' => 'pendente',
        ]);

        $admin = $this->adminUser();
        $this->asDoutores($admin, 'administrador');

        $routes = [
            route('doutores.home'),
            route('doutores.dashboard'),
            route('doutores.voluntarios.index'),
            route('doutores.voluntarios.show', $profile),
            route('doutores.plantoes.index'),
            route('doutores.plantoes.create'),
            route('doutores.plantoes.show', $plantao),
            route('doutores.plantoes.edit', $plantao),
            route('doutores.plantoes.equipes', $plantao),
            route('doutores.avisos.index'),
            route('doutores.avisos.create'),
            route('doutores.estudos.index'),
            route('doutores.estudos.show', $estudo),
            route('doutores.anexos.index'),
            route('doutores.relatorios.index'),
            route('doutores.polos.index'),
            route('doutores.polos.create'),
            route('doutores.polos.edit', $polo),
            route('doutores.hospitais.index'),
            route('doutores.hospitais.create'),
            route('doutores.hospitais.edit', $hospA),
            route('doutores.dominios.index'),
            route('doutores.publicacoes.index'),
            route('doutores.publicacoes.create'),
            route('doutores.portal.edit'),
            route('doutores.logs.index'),
            route('doutores.usuarios.index'),
        ];

        foreach ($routes as $url) {
            $this->get($url)->assertOk();
        }

        $this->get(route('doutores.relatorios.export', ['tipo' => 'plantoes']))->assertOk();
        $this->get(route('doutores.relatorios.export', ['tipo' => 'voluntarios']))->assertOk();
    }

    public function test_admin_crud_polo_hospital_dominio_portal_publicacao(): void
    {
        $admin = $this->adminUser();
        $this->asDoutores($admin, 'administrador');

        $this->post(route('doutores.polos.store'), ['nome' => 'Polo X', 'estado' => 'df', 'status' => 'ativo'])
            ->assertRedirect(route('doutores.polos.index'));
        $polo = Polo::where('nome', 'Polo X')->firstOrFail();
        $this->assertSame('DF', $polo->estado);

        $coord = $this->makeUser('coord@ex.com', ['doutores_coordenador']);
        $this->put(route('doutores.polos.update', $polo), [
            'nome' => 'Polo X2', 'estado' => 'DF', 'status' => 'ativo', 'coordenadores' => [$coord->id],
        ])->assertRedirect();
        $this->assertSame([$coord->id], $polo->coordenadores()->pluck('users.id')->all());

        $this->post(route('doutores.hospitais.store'), [
            'nome' => 'Hospital X', 'polo_id' => $polo->id, 'uf' => 'df', 'status' => 'ativo',
        ])->assertRedirect(route('doutores.hospitais.index'));
        $hospital = Hospital::where('nome', 'Hospital X')->firstOrFail();

        $plantao = Plantao::create($this->plantaoPayload('Hospital X'));
        $this->put(route('doutores.hospitais.update', $hospital), [
            'nome' => 'Hospital Y', 'polo_id' => $polo->id, 'uf' => 'DF', 'status' => 'ativo',
        ])->assertRedirect();
        $this->assertSame('Hospital Y', $plantao->fresh()->hospital);

        // polo com hospital não pode ser removido
        $this->delete(route('doutores.polos.destroy', $polo))->assertSessionHas('error');
        $this->delete(route('doutores.hospitais.destroy', $hospital))->assertRedirect();
        $this->delete(route('doutores.polos.destroy', $polo))->assertRedirect();
        $this->assertDatabaseMissing('doutores_polos', ['id' => $polo->id]);

        // domínios
        $this->post(route('doutores.dominios.store'), ['categoria' => 'funcao', 'descricao' => 'Música'])->assertRedirect();
        $dom = Dominio::firstOrFail();
        $this->post(route('doutores.dominios.store'), ['categoria' => 'funcao', 'descricao' => 'Música'])->assertSessionHas('error');
        $this->put(route('doutores.dominios.update', $dom), ['descricao' => 'Louvor'])->assertRedirect();
        $this->assertFalse($dom->fresh()->ativo);
        $this->get(route('doutores.dominios.index', ['categoria' => 'funcao']))->assertOk()->assertSee('Louvor');
        $this->delete(route('doutores.dominios.destroy', $dom))->assertRedirect();

        // portal
        $this->put(route('doutores.portal.update'), ['titulo_hero' => 'Olá', 'contato_email' => 'a@b.com'])->assertRedirect();
        $this->assertDatabaseHas('doutores_portal_config', ['id' => 'portal', 'titulo_hero' => 'Olá']);

        // publicações + comentários (disco público em public/img/doutores — sem storage:link)
        Storage::fake('doutores');
        $this->post(route('doutores.publicacoes.store'), [
            'titulo' => 'Notícia', 'conteudo' => 'Texto', 'status' => 'publicado',
            'imagens' => [UploadedFile::fake()->image('a.jpg')],
        ])->assertRedirect();
        $pub = \App\Models\Doutores\Publicacao::firstOrFail();
        $this->assertCount(1, $pub->imagens);
        Storage::disk('doutores')->assertExists($pub->imagens[0]);

        $coment = $pub->comentarios()->create(['nome' => 'Ana', 'mensagem' => 'Amei', 'status' => 'pendente']);
        $this->get(route('doutores.publicacoes.edit', $pub))->assertOk()->assertSee('Amei');
        $this->put(route('doutores.publicacoes.comentarios.moderar', $coment), ['status' => 'aprovado'])->assertRedirect();
        $this->assertSame('aprovado', $coment->fresh()->status);
        $this->put(route('doutores.publicacoes.update', $pub), [
            'titulo' => 'Notícia 2', 'status' => 'rascunho', 'remover_imagens' => [$pub->imagens[0]],
        ])->assertRedirect();
        $this->assertNull($pub->fresh()->imagens);
        $this->delete(route('doutores.publicacoes.destroy', $pub))->assertRedirect();

        $this->get(route('doutores.logs.index', ['q' => 'polo']))->assertOk();
        $this->assertDatabaseHas('doutores_logs', ['acao' => 'polo.criado']);
    }

    public function test_public_registration_then_approval_and_login(): void
    {
        [, , $hospA] = $this->seedStructure();

        $this->get(route('doutores.cadastrar'))->assertOk()->assertSee('HRAN');

        $payload = [
            'name' => 'Voluntário Novo',
            'email' => 'novo@ex.com',
            'password' => 'Senha1234',
            'password_confirmation' => 'Senha1234',
            'cpf' => '529.982.247-25',
            'telefone' => '(61) 99999-0000',
            'hospital_preferido' => 'HRAN',
            'accept_terms' => '1',
        ];

        $this->post(route('doutores.cadastrar.store'), array_merge($payload, ['cpf' => '111.111.111-11']))
            ->assertSessionHasErrors('cpf');

        $this->post(route('doutores.cadastrar.store'), $payload)
            ->assertRedirect(route('doutores.cadastrar'))
            ->assertSessionHas('cadastro_ok');

        $user = User::where('email', 'novo@ex.com')->firstOrFail();
        $this->assertTrue($user->hasAnyRoleName(['doutores_voluntario']));
        $this->assertSame('pendente', $user->doutoresProfile->status);
        $this->assertSame('52998224725', $user->doutoresProfile->cpf);

        // e-mail só do CMS pode se cadastrar no Doutores sem perder o papel do site
        $cms = $this->makeUser('cmsvol@ex.com', ['super_admin'], null);
        $this->post(route('doutores.cadastrar.store'), [
            'name' => 'Vol CMS',
            'email' => 'cmsvol@ex.com',
            'password' => 'Senha1234',
            'password_confirmation' => 'Senha1234',
            'cpf' => '390.533.447-05',
            'telefone' => '(61) 98888-0000',
            'hospital_preferido' => 'HRAN',
            'accept_terms' => '1',
        ])->assertRedirect(route('doutores.cadastrar'))->assertSessionHas('cadastro_ok');
        $cms = $cms->fresh('roles');
        $this->assertEqualsCanonicalizing(['super_admin', 'doutores_voluntario'], $cms->roles->pluck('name')->all());
        $this->assertSame('pendente', $cms->doutoresProfile->status);

        // não consegue entrar enquanto pendente
        $this->post(route('doutores.login.post'), ['email' => 'novo@ex.com', 'password' => 'Senha1234'])
            ->assertSessionHasErrors('email');

        // coordenador do polo aprova
        $coord = $this->makeUser('coord@ex.com', ['doutores_coordenador']);
        $coord->doutoresPolos()->attach(Polo::first()->id);
        $this->asDoutores($coord, 'coordenador')
            ->get(route('doutores.voluntarios.index', ['status' => 'pendente']))
            ->assertOk()->assertSee('Voluntário Novo');
        $this->post(route('doutores.voluntarios.approve', $user->doutoresProfile))->assertRedirect();
        $this->assertSame('ativo', $user->doutoresProfile->fresh()->status);

        auth('doutores')->logout();
        $this->flushSession();

        $this->post(route('doutores.login.post'), ['email' => 'novo@ex.com', 'password' => 'Senha1234'])
            ->assertRedirect(route('doutores.area.inicio'));
        $this->assertAuthenticated('doutores');
    }

    public function test_volunteer_flow_signup_confirm_notices_testimony_and_attachments(): void
    {
        Storage::fake('local');
        Storage::fake('doutores');
        $this->seedStructure();
        $plantao = Plantao::create($this->plantaoPayload('HRAN'));
        $vol = $this->makeUser('vol@ex.com', ['doutores_voluntario'], Profile::STATUS_ATIVO, ['cargo' => 'Música']);
        $other = $this->makeUser('vol2@ex.com', ['doutores_voluntario']);

        $this->asDoutores($vol, 'voluntario');

        foreach (['inicio', 'perfil', 'termos', 'plantoes', 'inscricoes', 'testemunhos', 'avisos'] as $name) {
            $this->get(route('doutores.area.'.$name))->assertOk();
        }
        $this->get(route('doutores.anexos.index'))->assertOk();

        // voluntário não acessa área gerencial
        $this->get(route('doutores.home'))->assertForbidden();
        $this->get(route('doutores.polos.index'))->assertForbidden();

        // perfil
        $this->put(route('doutores.area.perfil.update'), [
            'name' => 'Nome Novo', 'telefone' => '(61) 98888-7777', 'cidade' => 'Brasília',
        ])->assertRedirect();
        $this->assertSame('Nome Novo', $vol->fresh()->name);
        $this->assertSame('61988887777', $vol->doutoresProfile->fresh()->telefone);

        // inscrição
        $this->post(route('doutores.area.inscricoes.store'), ['plantao_id' => $plantao->id, 'papel' => 'Música'])
            ->assertRedirect(route('doutores.area.inscricoes'));
        $insc = Inscricao::firstOrFail();
        $this->assertSame('pendente', $insc->status);
        $this->post(route('doutores.area.inscricoes.store'), ['plantao_id' => $plantao->id])->assertSessionHas('error');
        $this->post(route('doutores.area.inscricoes.confirmar', $insc))->assertRedirect();
        $this->assertNotNull($insc->fresh()->confirmada_por_voluntario_em);

        // outro voluntário não pode mexer
        $this->actingAs($other, 'doutores')->delete(route('doutores.area.inscricoes.destroy', $insc))->assertForbidden();
        $this->actingAs($vol, 'doutores');

        // gestor confirma, gera equipes e envia aviso
        $admin = $this->adminUser();
        $this->asDoutores($admin, 'administrador');
        $this->put(route('doutores.inscricoes.status', $insc), ['status' => 'confirmada'])->assertRedirect();
        $this->assertSame([$vol->id], $plantao->fresh()->voluntarios);

        $this->post(route('doutores.plantoes.equipes.gerar', $plantao), ['acao' => 'gerar', 'tamanho' => 2])->assertRedirect();
        $equipes = $plantao->fresh()->equipes;
        $this->assertCount(1, $equipes);
        $this->assertSame('Música', $equipes[0]['papel']);
        $this->get(route('doutores.plantoes.equipes', $plantao))->assertOk()->assertSee('Equipe 1');

        $this->post(route('doutores.avisos.store'), ['plantao_id' => $plantao->id, 'titulo' => 'Levar crachá', 'conteudo' => 'Ok'])->assertRedirect();
        $this->post(route('doutores.avisos.store'), ['plantao_id' => $plantao->id, 'equipe_numero' => 9, 'titulo' => 'Outra equipe'])->assertRedirect();
        $aviso = Aviso::where('titulo', 'Levar crachá')->firstOrFail();

        // voluntário vê só o aviso da sua equipe/geral e marca como lido
        $this->asDoutores($vol, 'voluntario');
        $this->get(route('doutores.area.avisos'))->assertOk()->assertSee('Levar crachá')->assertDontSee('Outra equipe');
        $this->post(route('doutores.area.avisos.lido', $aviso))->assertRedirect();
        $this->assertSame(1, AvisoLeitura::count());
        $outro = Aviso::where('titulo', 'Outra equipe')->first();
        $this->post(route('doutores.area.avisos.lido', $outro))->assertForbidden();

        // testemunho só após o plantão
        $this->post(route('doutores.area.testemunhos.store'), ['plantao_id' => $plantao->id, 'texto' => 'Foi lindo demais!'])->assertRedirect();
        $this->assertSame(1, Testemunho::count());
        $plantao->update(['data' => now()->subDay()]);
        $this->get(route('doutores.area.testemunhos'))->assertOk();
        $t = Testemunho::first();
        $this->put(route('doutores.area.testemunhos.update', $t), ['texto' => 'Texto atualizado ok'])->assertRedirect();
        $this->assertSame('Texto atualizado ok', $t->fresh()->texto);

        // anexos (disco privado — download autenticado, sem symlink)
        $this->post(route('doutores.anexos.store'), [
            'tipo' => 'documento_identidade', 'arquivo' => UploadedFile::fake()->create('rg.pdf', 100, 'application/pdf'),
        ])->assertRedirect();
        $anexo = Anexo::firstOrFail();
        Storage::disk('local')->assertExists($anexo->path);
        $this->get(route('doutores.anexos.download', $anexo))->assertOk();
        $this->actingAs($other, 'doutores')->get(route('doutores.anexos.download', $anexo))->assertForbidden();
        $this->asDoutores($vol, 'voluntario')->put(route('doutores.anexos.revisar', $anexo), ['status' => 'aprovado'])->assertForbidden();

        $this->asDoutores($admin, 'administrador')->put(route('doutores.anexos.revisar', $anexo), ['status' => 'aprovado'])->assertRedirect();
        $this->assertSame('aprovado', $anexo->fresh()->status);

        $this->asDoutores($vol, 'voluntario')->delete(route('doutores.anexos.destroy', $anexo))->assertSessionHas('error');

        // cancelamento
        $this->delete(route('doutores.area.inscricoes.destroy', $insc))->assertRedirect();
        $this->assertSame('cancelada', $insc->fresh()->status);
    }

    public function test_volunteer_without_terms_is_redirected_to_terms_on_signup(): void
    {
        $this->seedStructure();
        $plantao = Plantao::create($this->plantaoPayload('HRAN'));
        $vol = $this->makeUser('vol@ex.com', ['doutores_voluntario'], Profile::STATUS_ATIVO, ['termos_aceitos_em' => null]);

        $this->asDoutores($vol, 'voluntario')
            ->post(route('doutores.area.inscricoes.store'), ['plantao_id' => $plantao->id])
            ->assertRedirect(route('doutores.area.termos'));

        $this->post(route('doutores.area.termos.aceitar'), [])->assertSessionHasErrors('aceito');
        $this->post(route('doutores.area.termos.aceitar'), ['aceito' => '1'])->assertRedirect(route('doutores.area.inicio'));
        $this->assertNotNull($vol->doutoresProfile->fresh()->termos_aceitos_em);
    }

    public function test_coordinator_is_scoped_to_polo_hospitals(): void
    {
        [$poloA, , $hospA] = $this->seedStructure();
        $plantaoA = Plantao::create($this->plantaoPayload('HRAN'));
        $plantaoB = Plantao::create($this->plantaoPayload('HRT'));
        $estudoB = SolicitacaoEstudo::create(['nome_paciente' => 'Paciente B', 'hospital' => 'HRT']);
        $estudoA = SolicitacaoEstudo::create(['nome_paciente' => 'Paciente A', 'hospital' => 'HRAN']);

        $coord = $this->makeUser('coord@ex.com', ['doutores_coordenador']);
        $coord->doutoresPolos()->attach($poloA->id);

        $this->assertSame([$poloA->id], DoutoresPoloScope::getPoloIds($coord));
        $this->assertSame(['HRAN'], DoutoresPoloScope::hospitalNamesInScope($coord));
        $admin = $this->adminUser();
        $this->assertNull(DoutoresPoloScope::getPoloIds($admin));
        $this->assertSame(['HRAN', 'HRT'], DoutoresPoloScope::hospitalNamesInScope($admin));

        $this->asDoutores($coord, 'coordenador');

        $this->get(route('doutores.plantoes.index'))->assertOk()->assertSee('HRAN')->assertDontSee('HRT');
        $this->get(route('doutores.plantoes.show', $plantaoA))->assertOk();
        $this->get(route('doutores.plantoes.show', $plantaoB))->assertForbidden();
        $this->get(route('doutores.estudos.show', $estudoB))->assertForbidden();
        $this->get(route('doutores.estudos.index'))->assertOk()->assertSee('Paciente A')->assertDontSee('Paciente B');

        $this->post(route('doutores.plantoes.store'), $this->plantaoPayload('HRT'))->assertForbidden();
        $this->post(route('doutores.plantoes.store'), $this->plantaoPayload('HRAN'))->assertRedirect();
        $this->put(route('doutores.plantoes.resultados', $plantaoA), [
            'quartos_visitados' => 10, 'visitas_realizadas' => 8, 'livros_distribuidos' => 5,
        ])->assertRedirect();
        $this->assertSame('realizado', $plantaoA->fresh()->status);

        $this->put(route('doutores.estudos.update', $estudoA), ['status' => 'encaminhado', 'atribuido_a' => 'Fulano'])->assertRedirect();
        $this->assertNotNull($estudoA->fresh()->encaminhado_em);
        $this->assertSame(1, $estudoA->logs()->count());

        // áreas de administrador são bloqueadas
        $this->get(route('doutores.polos.index'))->assertForbidden();
        $this->get(route('doutores.logs.index'))->assertForbidden();

        // voluntário de outro polo
        $volB = $this->makeUser('volb@ex.com', ['doutores_voluntario'], 'pendente', ['hospital_preferido' => 'HRT']);
        $this->post(route('doutores.voluntarios.approve', $volB->doutoresProfile))->assertForbidden();
        $this->post(route('doutores.voluntarios.store'), [
            'name' => 'Fora', 'email' => 'fora@ex.com', 'hospital_preferido' => 'HRT',
        ])->assertForbidden();
        $this->post(route('doutores.voluntarios.store'), [
            'name' => 'Dentro', 'email' => 'dentro@ex.com', 'hospital_preferido' => 'HRAN', 'cpf' => '52998224725',
        ])->assertRedirect(route('doutores.voluntarios.index'));
        $novo = User::where('email', 'dentro@ex.com')->firstOrFail();
        $this->assertTrue($novo->hasAnyRoleName(['doutores_voluntario']));
        $this->assertSame($coord->id, (int) $novo->doutoresProfile->coordenador_responsavel_id);

        // dashboard e relatórios
        $this->get(route('doutores.dashboard'))->assertOk();
        $csv = $this->get(route('doutores.relatorios.export', ['tipo' => 'plantoes']));
        $csv->assertOk();
        $content = $csv->streamedContent();
        $this->assertStringContainsString('HRAN', $content);
        $this->assertStringNotContainsString('HRT', $content);
    }

    public function test_profile_switch_changes_active_perfil(): void
    {
        $user = $this->makeUser('multi@ex.com', ['doutores_coordenador', 'doutores_voluntario']);

        $this->asDoutores($user, 'coordenador')
            ->get(route('doutores.home'))->assertOk()->assertSee('ddPerfilSwitch');

        $this->post(route('doutores.perfil.trocar'), ['perfil' => 'administrador'])->assertSessionHasErrors('perfil');
        $this->post(route('doutores.perfil.trocar'), ['perfil' => 'voluntario'])
            ->assertRedirect(route('doutores.area.inicio'));
        $this->get(route('doutores.area.inicio'))->assertOk();
        $this->get(route('doutores.home'))->assertForbidden();
    }

    public function test_doutores_user_management_uses_only_doutores_roles(): void
    {
        $admin = $this->adminUser();
        $poloA = Polo::create(['nome' => 'P1', 'estado' => 'DF']);

        $this->asDoutores($admin, 'administrador');

        $this->post(route('doutores.usuarios.store'), [
            'name' => 'Coord Só', 'email' => 'coordso@ex.com', 'password' => 'senha1234',
            'doutores_roles' => ['doutores_coordenador'], 'polo_ids' => [$poloA->id],
        ])->assertRedirect(route('doutores.usuarios.index'));

        $u = User::where('email', 'coordso@ex.com')->firstOrFail();
        $this->assertSame(['doutores_coordenador'], $u->roles->pluck('name')->all());
        $this->assertSame([$poloA->id], $u->doutoresPolos()->pluck('doutores_polos.id')->all());
        $this->assertSame('ativo', $u->doutoresProfile->status);

        // exige ao menos um papel
        $this->post(route('doutores.usuarios.store'), [
            'name' => 'Ninguém', 'email' => 'nin@ex.com', 'password' => 'senha1234',
        ])->assertSessionHasErrors('doutores_roles');

        // papéis do CMS não são aceitos
        $this->post(route('doutores.usuarios.store'), [
            'name' => 'Hacker', 'email' => 'h@ex.com', 'password' => 'senha1234',
            'doutores_roles' => ['super_admin'],
        ])->assertSessionHasErrors('doutores_roles.0');

        // atualização substitui papéis e limpa polos quando deixa de ser coordenador
        $this->put(route('doutores.usuarios.update', $u), [
            'name' => 'Coord Só', 'email' => 'coordso@ex.com', 'status' => 'ativo',
            'doutores_roles' => ['doutores_voluntario'], 'polo_ids' => [$poloA->id],
        ])->assertRedirect(route('doutores.usuarios.index'));
        $u = $u->fresh();
        $this->assertSame(['doutores_voluntario'], $u->roles->pluck('name')->all());
        $this->assertSame(0, $u->doutoresPolos()->count());

        $this->get(route('doutores.usuarios.index', ['editar' => $u->id]))->assertOk()->assertSee('Editar usuário');

        // contas só do CMS/site (sem papel Doutores) não são gerenciáveis aqui
        $cms = $this->makeUser('cms@ex.com', ['super_admin'], null);
        $this->put(route('doutores.usuarios.update', $cms), [
            'name' => 'X', 'email' => 'cms@ex.com', 'status' => 'ativo', 'doutores_roles' => ['doutores_admin'],
        ])->assertNotFound();
        $this->assertSame(['super_admin'], $cms->fresh()->roles->pluck('name')->all());

        // conta híbrida: pode receber papéis Doutores sem perder o CMS
        $this->post(route('doutores.usuarios.store'), [
            'name' => 'CMS Híbrido', 'email' => 'cms@ex.com', 'password' => 'senha1234',
            'doutores_roles' => ['doutores_coordenador'],
        ])->assertRedirect(route('doutores.usuarios.index'));
        $cms = $cms->fresh('roles');
        $this->assertEqualsCanonicalizing(['super_admin', 'doutores_coordenador'], $cms->roles->pluck('name')->all());
        $this->assertSame('ativo', $cms->doutoresProfile->status);

        // remover acesso Doutores preserva a conta do site
        $this->delete(route('doutores.usuarios.destroy', $cms))->assertRedirect(route('doutores.usuarios.index'));
        $cms = $cms->fresh('roles');
        $this->assertSame(['super_admin'], $cms->roles->pluck('name')->all());
        $this->assertNull($cms->doutoresProfile);

        // não remove a si mesmo nem seu próprio acesso
        $this->put(route('doutores.usuarios.update', $admin), [
            'name' => 'Admin', 'email' => 'admin@ex.com', 'status' => 'ativo', 'doutores_roles' => ['doutores_voluntario'],
        ])->assertSessionHasErrors('doutores_roles');
        $this->delete(route('doutores.usuarios.destroy', $admin))->assertSessionHas('error');

        $this->delete(route('doutores.usuarios.destroy', $u))->assertRedirect(route('doutores.usuarios.index'));
        $this->assertDatabaseMissing('users', ['id' => $u->id]);

        // coordenador não acessa usuários
        $coord = $this->makeUser('coord9@ex.com', ['doutores_coordenador']);
        $this->asDoutores($coord, 'coordenador')->get(route('doutores.usuarios.index'))->assertForbidden();
    }

    public function test_public_publicacoes_and_comments(): void
    {
        $pub = \App\Models\Doutores\Publicacao::create(['titulo' => 'Visita ao HRAN', 'conteudo' => 'Foi lindo', 'status' => 'publicado']);
        $rascunho = \App\Models\Doutores\Publicacao::create(['titulo' => 'Oculta', 'status' => 'rascunho']);
        $pub->comentarios()->create(['nome' => 'Aprovada', 'mensagem' => 'Visível', 'status' => 'aprovado']);
        $pub->comentarios()->create(['nome' => 'Pendente', 'mensagem' => 'Escondido', 'status' => 'pendente']);

        \App\Models\Doutores\PortalConfig::query()->create(['id' => 'portal', 'titulo_hero' => 'Portal X', 'contato_email' => 'contato@portal.test']);

        $this->get(route('doutores.public.publicacoes.index'))
            ->assertOk()->assertSee('Visita ao HRAN')->assertDontSee('Oculta')->assertSee('Portal X')->assertSee('contato@portal.test');

        $this->get(route('doutores.public.publicacoes.show', $pub))
            ->assertOk()->assertSee('Foi lindo')->assertSee('Visível')->assertDontSee('Escondido');
        $this->get(route('doutores.public.publicacoes.show', $rascunho))->assertNotFound();

        $this->post(route('doutores.public.publicacoes.comentar', $pub), ['nome' => 'Bia', 'mensagem' => 'Parabéns!'])
            ->assertRedirect(route('doutores.public.publicacoes.show', $pub));
        $this->assertDatabaseHas('doutores_comentarios_publicacao', ['nome' => 'Bia', 'status' => 'pendente']);
        $this->post(route('doutores.public.publicacoes.comentar', $pub), ['nome' => '', 'mensagem' => ''])
            ->assertSessionHasErrors(['nome', 'mensagem']);
        $this->post(route('doutores.public.publicacoes.comentar', $rascunho), ['nome' => 'Bia', 'mensagem' => 'Oi!'])->assertNotFound();

        $this->get(route('doutores-da-esperanca'))->assertOk()->assertSee('Portal X')->assertSee('contato@portal.test');
    }

    public function test_old_admin_routes_redirect_and_guests_go_to_doutores_login(): void
    {
        $this->get('/admin/doutores/publicacoes')->assertRedirect('/doutores/gestao/publicacoes');
        $this->get('/admin/doutores')->assertRedirect('/doutores');
        $this->get('/doutores-da-esperanca/cadastrar')->assertRedirect('/doutores/cadastrar');

        $this->get(route('doutores.home'))->assertRedirect(route('doutores.login'));
        $this->get(route('doutores.area.inicio'))->assertRedirect(route('doutores.login'));
        $this->get(route('doutores.usuarios.index'))->assertRedirect(route('doutores.login'));
        $this->get(route('doutores.login'))->assertOk()->assertSee('Doutores de Esperança');
    }
}
