<?php

namespace App\Providers;

use App\Support\Cms;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('cmsBlock', function (string $expression): string {
            // Usage:
            // @cmsBlock('intro')
            // @cmsBlock('intro', '<p>fallback</p>')
            return "<?php echo \\App\\Support\\Cms::block({$expression}); ?>";
        });
    }
}
