<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Models\CmsPage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('cms:sync-pages', function () {
    $excludedRouteNames = collect([
        'home',
        'boletim-digital',
        'faq',
        'time-de-desenvolvimento',
    ]);

    $routes = collect(Route::getRoutes()->getRoutes())
        ->filter(fn ($route) => in_array('GET', $route->methods(), true) || in_array('HEAD', $route->methods(), true))
        ->filter(fn ($route) => is_string($route->getName()) && $route->getName() !== '')
        ->reject(fn ($route) => Str::startsWith($route->getName(), ['filament.', 'livewire.', 'storage.']))
        ->reject(fn ($route) => Str::startsWith($route->uri(), ['api/', 'admin', 'up']))
        ->reject(fn ($route) => $route->uri() === 'sitemap.xml');

    $upserted = 0;

    foreach ($routes as $route) {
        $routeName = $route->getName();
        $uri = $route->uri();

        $cmsEnabled = true;

        if ($excludedRouteNames->contains($routeName)) {
            $cmsEnabled = false;
        }

        if (Str::startsWith($uri, 'noticias/')) {
            $cmsEnabled = false;
        }

        $viewPath = 'pages.' . Str::of($routeName)->replace('.', '-')->toString();
        $label = Str::of($routeName)->replace(['.', '-'], ' ')->title()->toString();

        CmsPage::query()->updateOrCreate(
            ['route_name' => $routeName],
            [
                'view_path' => $viewPath,
                'label' => $label,
                'section_slug' => null,
                'is_active' => true,
                'cms_enabled' => $cmsEnabled,
            ],
        );

        $upserted++;
    }

    $this->info("CMS pages synced: {$upserted}");
})->purpose('Sync CMS pages from named web routes');
