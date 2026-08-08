<?php

namespace Tests\Feature;

use App\Models\MemberInvite;
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

    private function createInvite(): array
    {
        $generated = MemberInvite::generateCode();
        $invite = MemberInvite::create([
            'code' => $generated['code'],
            'code_hash' => $generated['hash'],
            'code_prefix' => $generated['prefix'],
            'max_uses' => 1,
            'uses_count' => 0,
            'is_active' => true,
        ]);

        return [$invite, $generated['code']];
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

    public function test_stale_web_admin_session_does_not_redirect_register_to_galeria(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin, 'web');

        $this->get(route('member.register'))
            ->assertOk()
            ->assertSee('Criar conta', false);

        $this->assertGuest('web');
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

    public function test_admin_account_cannot_login_as_member_without_member_account(): void
    {
        $admin = $this->admin();

        $this->from(route('member.login'))
            ->post(route('member.login.post'), [
                'email' => $admin->email,
                'password' => 'senha12345',
            ])
            ->assertSessionHasErrors([
                'email' => __('auth.failed'),
            ]);

        $this->assertGuest('web');
        $this->assertGuest('admin');
    }

    public function test_admin_can_create_separate_member_account_with_same_email(): void
    {
        $admin = $this->admin();
        [, $code] = $this->createInvite();

        $this->post(route('member.register.post'), [
            'invite_code' => $code,
            'name' => 'Admin como Membro',
            'email' => $admin->email,
            'password' => 'membroSenha9',
            'password_confirmation' => 'membroSenha9',
            'phone' => '(61) 99999-0000',
            'birth_date' => '1990-05-10',
            'accept_terms' => '1',
        ])->assertRedirect(route('galeria'));

        $member = User::query()->members()->where('email', $admin->email)->first();
        $this->assertNotNull($member);
        $this->assertNotEquals($admin->id, $member->id);
        $this->assertTrue($member->isMember());
        $this->assertFalse($member->canAccessAdminPanel());
        $this->assertTrue($admin->fresh()->isSuperAdmin());
        $this->assertFalse($admin->fresh()->isMember());
        $this->assertAuthenticatedAs($member, 'web');
    }

    public function test_same_email_admin_and_member_can_login_independently(): void
    {
        $admin = $this->admin();
        [, $code] = $this->createInvite();

        $this->post(route('member.register.post'), [
            'invite_code' => $code,
            'name' => 'Pessoa Membro',
            'email' => $admin->email,
            'password' => 'membroSenha9',
            'password_confirmation' => 'membroSenha9',
            'phone' => '(61) 99999-0000',
            'birth_date' => '1990-05-10',
            'accept_terms' => '1',
        ]);
        $this->post(route('member.logout'));

        $member = User::query()->members()->where('email', $admin->email)->first();

        $this->post(route('admin.login.post'), [
            'email' => $admin->email,
            'password' => 'senha12345',
        ])->assertRedirect(route('admin.dashboard'));

        $this->post(route('member.login.post'), [
            'email' => $admin->email,
            'password' => 'membroSenha9',
        ])->assertRedirect(route('galeria'));

        $this->assertAuthenticatedAs($admin, 'admin');
        $this->assertAuthenticatedAs($member, 'web');
    }
}
