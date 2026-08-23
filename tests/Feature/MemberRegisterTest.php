<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberRegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['super_admin', 'member'] as $role) {
            Role::query()->create(['name' => $role, 'guard_name' => 'web']);
        }
    }

    private function payload(string $email, string $congregation = 'visitante'): array
    {
        return [
            'name' => 'Fulano de Tal',
            'email' => $email,
            'password' => 'senha12345',
            'password_confirmation' => 'senha12345',
            'phone' => '(61) 90000-0000',
            'birth_date' => '1990-05-10',
            'congregation' => $congregation,
            'accept_terms' => '1',
        ];
    }

    public function test_register_creates_member(): void
    {
        $response = $this->post(route('member.register.post'), $this->payload('novo@ex.com', 'membro_batizado'));

        $response->assertRedirect();
        $user = User::where('email', 'novo@ex.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->fresh()->isMember());
        $this->assertSame('membro_batizado', $user->congregation);
        $this->assertTrue($user->is_church_member);
        $this->assertSame('Membro batizado', $user->membershipLinkLabel());
    }

    public function test_congregation_is_required(): void
    {
        $payload = $this->payload('sem-vinculo@ex.com');
        unset($payload['congregation']);

        $response = $this->from(route('member.register'))
            ->post(route('member.register.post'), $payload);

        $response->assertSessionHasErrors('congregation');
        $this->assertNull(User::where('email', 'sem-vinculo@ex.com')->first());
    }

    public function test_duplicate_member_email_is_rejected(): void
    {
        $this->post(route('member.register.post'), $this->payload('mesmo@ex.com'))->assertRedirect();
        $this->post(route('member.logout'));

        $second = $this->from(route('member.register'))
            ->post(route('member.register.post'), $this->payload('mesmo@ex.com'));

        $second->assertSessionHasErrors('email');
        $this->assertEquals(1, User::query()->members()->where('email', 'mesmo@ex.com')->count());
    }

    public function test_minor_requires_guardian_consent(): void
    {
        $payload = $this->payload('menor@ex.com');
        $payload['birth_date'] = now()->subYears(15)->format('Y-m-d');

        $response = $this->from(route('member.register'))
            ->post(route('member.register.post'), $payload);

        $response->assertSessionHasErrors('guardian_consent');
        $this->assertNull(User::where('email', 'menor@ex.com')->first());
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

    public function test_cannot_register_with_existing_panel_email(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'mesmo@ex.com',
            'password' => 'senha12345',
            'is_active' => true,
        ]);
        $admin->syncRoles(['super_admin']);

        $this->from(route('member.register'))
            ->post(route('member.register.post'), $this->payload('mesmo@ex.com'))
            ->assertSessionHasErrors('email');

        $this->assertSame(1, User::query()->where('email', 'mesmo@ex.com')->count());
        $this->assertTrue($admin->fresh()->canAccessAdminPanel());
        $this->assertGuest('web');
    }
}
