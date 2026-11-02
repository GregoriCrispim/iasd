<?php

namespace App\Http\Middleware;

use App\Support\SiteAnalytics;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecordPageView
{
    public function __construct(
        private readonly SiteAnalytics $analytics,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $this->analytics->recordPageView($request, $response);
    }
}
