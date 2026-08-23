<?php

namespace Tests\Feature;

use App\Models\MemberInvite;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberInviteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['super_admin', 'manager', 'collaborator', 'fotografia_lider', 'fotografia_colaborador', 'member'] as $role) {
            Role::query()->create(['name' => $role, 'guard_name' => 'web']);
        }
    }

    private function createInvite(int $maxUses = 1): array
    {
        $generated = MemberInvite::generateCode();
        $invite = MemberInvite::create([
            'code_hash' => $generated['hash'],
            'code_prefix' => $generated['prefix'],
            'max_uses' => $maxUses,
            'uses_count' => 0,
            'is_active' => true,
        ]);

        return [$invite, $generated['code']];
    }

    private function payload(string $code, string $email): array
    {
        return [
            'invite_code' => $code,
            'name' => 'Fulano de Tal',
            'email' => $email,
            'password' => 'senha12345',
            'password_confirmation' => 'senha12345',
            'phone' => '(61) 90000-0000',
            'birth_date' => '1990-05-10',
            'accept_terms' => '1',
        ];
    }

    public function test_valid_invite_creates_member(): void
    {
        [, $code] = $this->createInvite(1);

        $response = $this->post(route('member.register.post'), $this->payload($code, 'novo@ex.com'));

        $response->assertRedirect();
        $user = User::where('email', 'novo@ex.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->fresh()->isMember());
    }

    public function test_invite_limit_is_enforced(): void
    {
        [$invite, $code] = $this->createInvite(1);

        $this->post(route('member.register.post'), $this->payload($code, 'um@ex.com'))->assertRedirect();

        // O primeiro cadastro autentica o usuário; encerramos a sessão para
        // simular outra pessoa tentando usar o mesmo código.
        $this->post(route('member.logout'));
        $this->app['auth']->guard()->logout();

        // Segundo cadastro com o mesmo código deve falhar (limite atingido).
        $second = $this->from(route('member.register'))
            ->post(route('member.register.post'), $this->payload($code, 'dois@ex.com'));

        $second->assertSessionHasErrors('invite_code');
        $this->assertNull(User::where('email', 'dois@ex.com')->first());
        $this->assertEquals(1, $invite->fresh()->uses_count);
    }

    public function test_minor_requires_guardian_consent(): void
    {
        [, $code] = $this->createInvite(1);

        $payload = $this->payload($code, 'menor@ex.com');
        $payload['birth_date'] = now()->subYears(15)->format('Y-m-d');

        $response = $this->from(route('member.register'))
            ->post(route('member.register.post'), $payload);

        $response->assertSessionHasErrors('guardian_consent');
        $this->assertNull(User::where('email', 'menor@ex.com')->first());
    }

    public function test_deleted_unused_invite_cannot_create_an_account(): void
    {
        [$invite, $code] = $this->createInvite();
        $invite->delete();

        $response = $this->from(route('member.register'))
            ->post(route('member.register.post'), $this->payload($code, 'sem-convite@ex.com'));

        $response->assertSessionHasErrors('invite_code');
        $this->assertNull(User::where('email', 'sem-convite@ex.com')->first());
        $this->assertGuest();
    }

    public function test_member_cannot_access_admin_panel(): void
    {
        $member = User::create([
            'name' => 'Membro',
            'email' => 'membro@ex.com',
            'password' => 'senha12345',
            'is_active' => true,
        ]);
        $member->syncRoles(['member']);

        $this->actingAs($member, 'web')->get('/admin')->assertRedirect(route('admin.login'));

        // Mesmo autenticado no guard errado, sem papel de painel o middleware de role bloqueia.
        $this->actingAs($member, 'admin')->get('/admin')->assertForbidden();
    }

    public function test_member_account_with_same_email_as_admin_has_no_panel_access(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'mesmo@ex.com',
            'password' => 'senha12345',
            'is_active' => true,
        ]);
        $admin->syncRoles(['super_admin']);

        [, $code] = $this->createInvite(1);
        $this->post(route('member.register.post'), $this->payload($code, 'mesmo@ex.com'))->assertRedirect();

        $member = User::query()->members()->where('email', 'mesmo@ex.com')->first();
        $this->assertNotNull($member);
        $this->assertNotEquals($admin->id, $member->id);
        $this->assertFalse($member->canAccessAdminPanel());
        $this->actingAs($member, 'web')->get('/admin')->assertRedirect(route('admin.login'));
    }
}
