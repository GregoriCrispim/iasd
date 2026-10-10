<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignVisit;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignRedirectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['super_admin', 'admin', 'manager', 'member'] as $role) {
            Role::query()->firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }

    public function test_sementes_records_visit_and_redirects_to_estudo_biblico(): void
    {
        $this->assertDatabaseHas('campaigns', ['slug' => 'sementes', 'is_active' => true]);

        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
            'Accept-Language' => 'pt-BR,pt;q=0.9',
            'CF-IPCountry' => 'BR',
        ])->get('/sementes?utm_source=qr');

        $response->assertRedirect(route('estudo-biblico'));

        $this->assertDatabaseCount('campaign_visits', 1);

        $visit = CampaignVisit::query()->first();
        $this->assertNotNull($visit);
        $this->assertSame('mobile', $visit->device_type);
        $this->assertSame('Safari', $visit->browser);
        $this->assertSame('iOS', $visit->platform);
        $this->assertSame('BR', $visit->country_code);
        $this->assertFalse($visit->is_bot);
        $this->assertSame(['utm_source' => 'qr'], $visit->query);
        $this->assertNotNull($visit->ip_hash);
        $this->assertNotNull($visit->visitor_key);
    }

    public function test_generic_campaign_route_works(): void
    {
        $this->get('/c/sementes')->assertRedirect(route('estudo-biblico'));
        $this->assertDatabaseCount('campaign_visits', 1);
    }

    public function test_unknown_campaign_returns_404(): void
    {
        $this->get('/c/nao-existe')->assertNotFound();
        $this->assertDatabaseCount('campaign_visits', 0);
    }

    public function test_inactive_campaign_returns_404(): void
    {
        Campaign::query()->where('slug', 'sementes')->update(['is_active' => false]);

        $this->get('/sementes')->assertNotFound();
        $this->assertDatabaseCount('campaign_visits', 0);
    }

    public function test_bot_visits_are_marked(): void
    {
        $this->withHeaders([
            'User-Agent' => 'Googlebot/2.1 (+http://www.google.com/bot.html)',
        ])->get('/sementes')->assertRedirect(route('estudo-biblico'));

        $visit = CampaignVisit::query()->first();
        $this->assertTrue($visit->is_bot);
    }

    public function test_admin_can_view_campaign_metrics(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@ex.com',
            'password' => 'senha12345',
            'is_active' => true,
        ]);
        $admin->syncRoles(['super_admin']);

        $campaign = Campaign::query()->where('slug', 'sementes')->firstOrFail();
        CampaignVisit::query()->create([
            'campaign_id' => $campaign->id,
            'visited_at' => now(),
            'visitor_key' => hash('sha256', 'a'),
            'ip_hash' => hash('sha256', '1.1.1.1'),
            'device_type' => 'mobile',
            'browser' => 'Safari',
            'platform' => 'iOS',
            'is_bot' => false,
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.campanhas.show', 'sementes'))
            ->assertOk()
            ->assertSee('Entrega de sementes', false)
            ->assertSee('/sementes', false)
            ->assertSee('Total de scans', false)
            ->assertSee('Scans por dia (últimos 30 dias)', false)
            ->assertSee('campaignScansByDayChart', false);
    }

    public function test_guest_cannot_view_campaign_metrics(): void
    {
        $this->get(route('admin.campanhas.show', 'sementes'))
            ->assertRedirect(route('admin.login'));
    }
}
