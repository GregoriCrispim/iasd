<?php

namespace Tests\Feature;

use App\Models\Doutores\Profile;
use App\Models\Role;
use App\Models\User;
use App\Support\DoutoresAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoutoresAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([
            'super_admin', 'admin', 'manager', 'member',
            'doutores_admin', 'doutores_coordenador', 'doutores_voluntario',
        ] as $role) {
            Role::query()->firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }

    private function makeUser(string $email, array $roles, ?string $status = Profile::STATUS_ATIVO): User
    {
        $user = User::create([
            'name' => 'User '.$email,
            'email' => $email,
            'password' => 'senha12345',
            'is_active' => true,
        ]);
        $user->syncRoles($roles);

        if ($status !== null && $user->hasAnyRoleName(DoutoresAuth::doutoresRoleNames())) {
            Profile::create([
                'user_id' => $user->id,
                'status' => $status,
                'termos_aceitos_em' => now(),
            ]);
        }

        return $user->fresh(['roles', 'doutoresProfile']);
    }

    public function test_volunteer_only_logs_into_doutores_guard_not_admin(): void
    {
        $vol = $this->makeUser('vol@ex.com', ['doutores_voluntario']);

        $this->post(route('doutores.login.post'), [
            'email' => $vol->email,
            'password' => 'senha12345',
        ])->assertRedirect(route('doutores.area.inicio'));

        $this->assertAuthenticatedAs($vol, 'doutores');
        $this->assertGuest('admin');
        $this->assertGuest('web');
        $this->assertSame(DoutoresAuth::PERFIL_VOL, session(DoutoresAuth::SESSION_PERFIL_KEY));

        $this->get(route('doutores.area.inicio'))->assertOk();
    }

    public function test_doutores_admin_login_goes_to_home(): void
    {
        $user = $this->makeUser('dadmin@ex.com', ['doutores_admin']);

        $this->post(route('doutores.login.post'), [
            'email' => $user->email,
            'password' => 'senha12345',
        ])->assertRedirect(route('doutores.home'));

        $this->assertSame(DoutoresAuth::PERFIL_ADMIN, session(DoutoresAuth::SESSION_PERFIL_KEY));
        $this->get(route('doutores.home'))->assertOk();
    }

    public function test_pending_volunteer_cannot_login(): void
    {
        $vol = $this->makeUser('pendente@ex.com', ['doutores_voluntario'], Profile::STATUS_PENDENTE);

        $this->from(route('doutores.login'))
            ->post(route('doutores.login.post'), [
                'email' => $vol->email,
                'password' => 'senha12345',
            ])
            ->assertSessionHasErrors('email');

        $this->assertGuest('doutores');
        $this->assertGuest('admin');
    }

    public function test_wrong_password_is_rejected(): void
    {
        $vol = $this->makeUser('vol-x@ex.com', ['doutores_voluntario']);

        $this->post(route('doutores.login.post'), ['email' => $vol->email, 'password' => 'errada123'])
            ->assertSessionHasErrors('email');
        $this->assertGuest('doutores');
    }

    public function test_super_admin_does_not_get_doutores_access(): void
    {
        $admin = $this->makeUser('sa@ex.com', ['super_admin'], null);

        // login do portal Doutores recusa super_admin
        $this->post(route('doutores.login.post'), [
            'email' => $admin->email,
            'password' => 'senha12345',
        ])->assertSessionHasErrors('email');
        $this->assertGuest('doutores');

        // login do painel não cria sessão Doutores
        $this->post(route('admin.login.post'), [
            'email' => $admin->email,
            'password' => 'senha12345',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin, 'admin');
        $this->assertGuest('doutores');
        $this->assertNull(session(DoutoresAuth::SESSION_PERFIL_KEY));

        // e mesmo autenticado no painel não acessa o portal
        $this->get(route('doutores.home'))->assertRedirect(route('doutores.login'));
        $this->assertSame([], DoutoresAuth::availablePerfis($admin->fresh('roles')));
        $this->assertFalse(DoutoresAuth::canActAsAdmin($admin->fresh('roles')));
        $this->assertFalse(DoutoresAuth::canLoginDoutores($admin->fresh('roles')));
    }

    public function test_member_cannot_login_to_doutores(): void
    {
        $member = $this->makeUser('so-membro@ex.com', ['member'], null);

        $this->from(route('doutores.login'))
            ->post(route('doutores.login.post'), [
                'email' => $member->email,
                'password' => 'senha12345',
            ])
            ->assertSessionHasErrors('email');

        $this->assertGuest('doutores');
        $this->assertGuest('web');
        $this->assertGuest('admin');
    }

    public function test_doutores_user_cannot_use_admin_panel_login(): void
    {
        $vol = $this->makeUser('vol-painel@ex.com', ['doutores_voluntario']);

        $this->post(route('admin.login.post'), [
            'email' => $vol->email,
            'password' => 'senha12345',
        ])->assertSessionHasErrors('email');

        $this->assertGuest('admin');
        $this->assertGuest('doutores');
    }

    public function test_doutores_logout_keeps_admin_and_web_sessions(): void
    {
        $admin = $this->makeUser('sa2@ex.com', ['super_admin'], null);
        $member = $this->makeUser('membro@ex.com', ['member'], null);
        $vol = $this->makeUser('vol3@ex.com', ['doutores_voluntario']);

        $this->post(route('admin.login.post'), ['email' => $admin->email, 'password' => 'senha12345']);
        $this->post(route('member.login.post'), ['email' => $member->email, 'password' => 'senha12345']);
        $this->post(route('doutores.login.post'), ['email' => $vol->email, 'password' => 'senha12345']);

        $this->assertAuthenticatedAs($admin, 'admin');
        $this->assertAuthenticatedAs($member, 'web');
        $this->assertAuthenticatedAs($vol, 'doutores');

        $this->post(route('doutores.logout'))->assertRedirect(route('doutores.login'));

        $this->assertGuest('doutores');
        $this->assertNull(session(DoutoresAuth::SESSION_PERFIL_KEY));
        $this->assertAuthenticatedAs($admin, 'admin');
        $this->assertAuthenticatedAs($member, 'web');
    }

    public function test_admin_logout_does_not_touch_doutores(): void
    {
        $admin = $this->makeUser('sa3@ex.com', ['super_admin'], null);
        $vol = $this->makeUser('vol4@ex.com', ['doutores_voluntario']);

        $this->post(route('admin.login.post'), ['email' => $admin->email, 'password' => 'senha12345']);
        $this->post(route('doutores.login.post'), ['email' => $vol->email, 'password' => 'senha12345']);

        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));

        $this->assertGuest('admin');
        $this->assertAuthenticatedAs($vol, 'doutores');
        $this->assertSame(DoutoresAuth::PERFIL_VOL, session(DoutoresAuth::SESSION_PERFIL_KEY));
    }

    public function test_multi_perfil_switch(): void
    {
        $user = $this->makeUser('multi@ex.com', ['doutores_coordenador', 'doutores_voluntario']);

        $this->post(route('doutores.login.post'), [
            'email' => $user->email,
            'password' => 'senha12345',
        ])->assertRedirect(route('doutores.home'));

        $this->assertSame(DoutoresAuth::PERFIL_COORD, session(DoutoresAuth::SESSION_PERFIL_KEY));

        $this->get(route('doutores.perfil.escolher'))->assertOk()->assertSee('Voluntário');

        $this->post(route('doutores.perfil.trocar'), [
            'perfil' => DoutoresAuth::PERFIL_VOL,
        ])->assertRedirect(route('doutores.area.inicio'));

        $this->assertSame(DoutoresAuth::PERFIL_VOL, session(DoutoresAuth::SESSION_PERFIL_KEY));
    }

    public function test_user_model_preserves_site_roles_when_syncing_doutores(): void
    {
        $user = $this->makeUser('combo@ex.com', ['manager'], null);
        $user->syncDoutoresRoles(['doutores_voluntario']);
        $user = $user->fresh('roles');

        $this->assertEqualsCanonicalizing(['manager', 'doutores_voluntario'], $user->roles->pluck('name')->all());
        $this->assertTrue(DoutoresAuth::canAccessDoutoresLogin($user));
        $this->assertStringStartsNotWith('doutores.', $user->adminHomeRouteName());
    }

    public function test_same_email_can_login_admin_and_doutores_independently(): void
    {
        $user = $this->makeUser('hibrido@ex.com', ['super_admin'], null);
        $user->update(['password' => 'senha12345']);
        $user->syncDoutoresRoles(['doutores_admin']);
        \App\Models\Doutores\Profile::query()->updateOrCreate(
            ['user_id' => $user->id],
            ['status' => 'ativo', 'termos_aceitos_em' => now()]
        );

        $this->post(route('admin.login.post'), [
            'email' => 'hibrido@ex.com',
            'password' => 'senha12345',
        ])->assertRedirect();
        $this->assertAuthenticatedAs($user, 'admin');
        $this->assertGuest('doutores');

        $this->post(route('doutores.login.post'), [
            'email' => 'hibrido@ex.com',
            'password' => 'senha12345',
        ])->assertRedirect(route('doutores.home'));
        $this->assertAuthenticatedAs($user, 'doutores');
        $this->assertAuthenticatedAs($user, 'admin');
    }
}
