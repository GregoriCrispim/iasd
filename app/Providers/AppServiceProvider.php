<?php

namespace App\Providers;

use App\Models\User;
use App\Support\CmsWorkflow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
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

        View::composer('admin.layout', function ($view): void {
            $user = Auth::guard('admin')->user();
            $count = $user instanceof User ? CmsWorkflow::pendingApprovalsCount($user) : 0;
            $view->with('pendingApprovalsCount', $count);
        });
    }
}
