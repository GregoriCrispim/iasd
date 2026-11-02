<?php

namespace Tests\Feature;

use App\Models\FormSubmission;
use App\Models\PageView;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['super_admin', 'admin', 'manager', 'member'] as $role) {
            Role::query()->firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }

    public function test_public_page_view_is_recorded_for_humans(): void
    {
        $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Accept-Language' => 'pt-BR,pt;q=0.9',
        ])->get('/')->assertOk();

        $this->assertDatabaseCount('page_views', 1);

        $view = PageView::query()->first();
        $this->assertSame('home', $view->route_name);
        $this->assertSame('/', $view->path);
        $this->assertSame('desktop', $view->device_type);
        $this->assertSame('Chrome', $view->browser);
        $this->assertFalse($view->is_bot);
    }

    public function test_bot_page_views_are_marked_and_excluded_from_admin_defaults(): void
    {
        $this->withHeaders([
            'User-Agent' => 'Googlebot/2.1 (+http://www.google.com/bot.html)',
        ])->get('/igreja')->assertOk();

        $view = PageView::query()->first();
        $this->assertTrue($view->is_bot);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin-analytics@ex.com',
            'password' => 'senha12345',
            'is_active' => true,
        ]);
        $admin->syncRoles(['admin']);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.analytics.index'))
            ->assertOk()
            ->assertSee('Nenhuma visualização registrada ainda', false);

        $this->assertSame(0, PageView::query()->human()->count());
        $this->assertSame(1, PageView::query()->where('is_bot', true)->count());
    }

    public function test_admin_routes_are_not_tracked(): void
    {
        $this->get('/admin/login')->assertOk();

        $this->assertDatabaseCount('page_views', 0);
    }

    public function test_prefetch_requests_are_marked_as_bots(): void
    {
        $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Purpose' => 'prefetch',
        ])->get('/faq')->assertOk();

        $this->assertTrue(PageView::query()->first()->is_bot);
    }

    public function test_estudo_biblico_form_submission_is_recorded(): void
    {
        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
        ])->post(route('estudo-biblico.enviar'), [
            'nome' => 'Maria Silva',
            'email' => 'maria@example.com',
            'telefone' => '61999999999',
            'formato' => 'online',
            'mensagem' => 'Gostaria de estudar a Bíblia.',
        ]);

        $response->assertRedirect(route('estudo-biblico.formulario'));

        $this->assertDatabaseCount('form_submissions', 1);
        $submission = FormSubmission::query()->first();
        $this->assertSame('estudo-biblico', $submission->form_key);
        $this->assertTrue($submission->success);
        $this->assertFalse($submission->is_bot);
        $this->assertSame('online', $submission->meta['formato'] ?? null);
    }

    public function test_admin_analytics_page_requires_manager_role(): void
    {
        $member = User::create([
            'name' => 'Membro',
            'email' => 'member-analytics@ex.com',
            'password' => 'senha12345',
            'is_active' => true,
        ]);
        $member->syncRoles(['member']);

        $this->actingAs($member, 'admin')
            ->get(route('admin.analytics.index'))
            ->assertForbidden();

        $manager = User::create([
            'name' => 'Gestor',
            'email' => 'manager-analytics@ex.com',
            'password' => 'senha12345',
            'is_active' => true,
        ]);
        $manager->syncRoles(['manager']);

        $this->actingAs($manager, 'admin')
            ->get(route('admin.analytics.index'))
            ->assertOk()
            ->assertSee('Tráfego do site');
    }
}
