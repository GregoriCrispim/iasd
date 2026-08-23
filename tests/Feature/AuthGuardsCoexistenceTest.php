<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthGuardsCoexistenceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['super_admin', 'member'] as $role) {
            Role::query()->create(['name' => $role, 'guard_name' => 'web']);
        }
    }

    private function admin(): User
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@ex.com',
            'password' => 'senha12345',
            'is_active' => true,
        ]);
        $user->syncRoles(['super_admin']);

        return $user;
    }

    private function member(): User
    {
        $user = User::create([
            'name' => 'Membro',
            'email' => 'membro@ex.com',
            'password' => 'senha12345',
            'birth_date' => '1990-01-01',
            'is_active' => true,
        ]);
        $user->syncRoles(['member']);

        return $user;
    }

    private function registerPayload(string $email): array
    {
        return [
            'name' => 'Pessoa Membro',
            'email' => $email,
            'password' => 'membroSenha9',
            'password_confirmation' => 'membroSenha9',
            'phone' => '(61) 99999-0000',
            'birth_date' => '1990-05-10',
            'congregation' => 'visitante',
            'accept_terms' => '1',
        ];
    }

    public function test_admin_and_member_sessions_can_coexist(): void
    {
        $admin = $this->admin();
        $member = $this->member();

        $this->post(route('admin.login.post'), [
            'email' => $admin->email,
            'password' => 'senha12345',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin, 'admin');
        $this->assertGuest('web');

        $this->post(route('member.login.post'), [
            'email' => $member->email,
            'password' => 'senha12345',
        ])->assertRedirect(route('galeria'));

        $this->assertAuthenticatedAs($admin, 'admin');
        $this->assertAuthenticatedAs($member, 'web');
    }

    public function test_logging_out_admin_keeps_member_session(): void
    {
        $admin = $this->admin();
        $member = $this->member();

        $this->post(route('admin.login.post'), [
            'email' => $admin->email,
            'password' => 'senha12345',
        ]);
        $this->post(route('member.login.post'), [
            'email' => $member->email,
            'password' => 'senha12345',
        ]);

        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));

        $this->assertGuest('admin');
        $this->assertAuthenticatedAs($member, 'web');
    }

    public function test_logging_out_member_keeps_admin_session(): void
    {
        $admin = $this->admin();
        $member = $this->member();

        $this->post(route('admin.login.post'), [
            'email' => $admin->email,
            'password' => 'senha12345',
        ]);
        $this->post(route('member.login.post'), [
            'email' => $member->email,
            'password' => 'senha12345',
        ]);

        $this->post(route('member.logout'))->assertRedirect(route('galeria'));

        $this->assertGuest('web');
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_admin_login_does_not_block_member_register_page(): void
    {
        $admin = $this->admin();

        $this->post(route('admin.login.post'), [
            'email' => $admin->email,
            'password' => 'senha12345',
        ]);

        $this->get(route('member.register'))->assertOk();
        $this->get(route('member.login'))->assertOk();
    }

    public function test_admin_on_web_is_redirected_away_from_register(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin, 'web');

        $this->get(route('member.register'))
            ->assertRedirect(route('galeria'));

        $this->assertAuthenticatedAs($admin, 'web');
    }

    public function test_member_cannot_use_admin_login(): void
    {
        $member = $this->member();

        $this->from(route('admin.login'))
            ->post(route('admin.login.post'), [
                'email' => $member->email,
                'password' => 'senha12345',
            ])
            ->assertSessionHasErrors('email');

        $this->assertGuest('admin');
        $this->assertGuest('web');
    }

    public function test_admin_can_login_on_site_with_same_credentials(): void
    {
        $admin = $this->admin();

        $this->post(route('member.login.post'), [
            'email' => $admin->email,
            'password' => 'senha12345',
        ])->assertRedirect(route('galeria'));

        $this->assertAuthenticatedAs($admin, 'web');
        $this->assertGuest('admin');
    }

    public function test_admin_and_site_login_redirect_by_form(): void
    {
        $admin = $this->admin();

        $this->post(route('admin.login.post'), [
            'email' => $admin->email,
            'password' => 'senha12345',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin, 'admin');

        $this->post(route('member.login.post'), [
            'email' => $admin->email,
            'password' => 'senha12345',
        ])->assertRedirect(route('galeria'));

        $this->assertAuthenticatedAs($admin, 'admin');
        $this->assertAuthenticatedAs($admin, 'web');
    }

    public function test_cannot_register_member_with_existing_panel_email(): void
    {
        $admin = $this->admin();

        $this->from(route('member.register'))
            ->post(route('member.register.post'), $this->registerPayload($admin->email))
            ->assertSessionHasErrors('email');

        $this->assertSame(1, User::query()->where('email', $admin->email)->count());
        $this->assertGuest('web');
    }
}
