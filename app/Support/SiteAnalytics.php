<?php

namespace App\Support;

use App\Models\FormSubmission;
use App\Models\PageView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SiteAnalytics
{
    /** @var list<string> */
    private const SKIP_PATH_PREFIXES = [
        'admin',
        'up',
        'api',
        'livewire',
        '_debugbar',
        'telescope',
        'horizon',
    ];

    /** @var list<string> */
    private const SKIP_ROUTE_NAMES = [
        'sitemap',
        'galeria.thumb',
        'galeria.faces.progress',
        'galeria.busca-facial',
        'campanha.sementes',
        'campanha.redirect',
        'admin.login',
        'admin.login.post',
        'admin.logout',
    ];

    public function __construct(
        private readonly VisitFingerprint $fingerprint,
    ) {
    }

    public function shouldRecordPageView(Request $request, Response $response): bool
    {
        if (! in_array($request->method(), ['GET', 'HEAD'], true)) {
            return false;
        }

        if ($request->method() === 'HEAD') {
            return false;
        }

        if ($request->expectsJson() && ! $request->acceptsHtml()) {
            return false;
        }

        $path = trim($request->path(), '/');
        foreach (self::SKIP_PATH_PREFIXES as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix.'/')) {
                return false;
            }
        }

        $routeName = $request->route()?->getName();
        if (is_string($routeName)) {
            if (str_starts_with($routeName, 'admin.')) {
                return false;
            }
            if (in_array($routeName, self::SKIP_ROUTE_NAMES, true)) {
                return false;
            }
        }

        $status = $response->getStatusCode();
        if ($status < 200 || $status >= 400) {
            return false;
        }

        // Respostas binárias / downloads não contam como visualização de página.
        $disposition = (string) $response->headers->get('Content-Disposition', '');
        if (str_contains(strtolower($disposition), 'attachment')) {
            return false;
        }

        return true;
    }

    public function recordPageView(Request $request, Response $response): void
    {
        try {
            if (! $this->shouldRecordPageView($request, $response)) {
                return;
            }

            $attrs = $this->fingerprint->fromRequest($request, 'site');
            $path = '/'.ltrim($request->path(), '/');
            if ($path === '//') {
                $path = '/';
            }

            PageView::query()->create([
                'viewed_at' => now(),
                'method' => $request->method(),
                'path' => mb_substr($this->normalizePath($path), 0, 500),
                'route_name' => $request->route()?->getName(),
                'status' => $response->getStatusCode(),
                ...$attrs,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Falha ao registrar page view', [
                'path' => $request->path(),
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $meta
     */
    public function recordFormSubmission(Request $request, string $formKey, bool $success = true, array $meta = []): void
    {
        try {
            $attrs = $this->fingerprint->fromRequest($request, 'site');
            $path = '/'.ltrim($request->path(), '/');

            FormSubmission::query()->create([
                'submitted_at' => now(),
                'form_key' => $formKey,
                'route_name' => $request->route()?->getName(),
                'path' => mb_substr($this->normalizePath($path), 0, 500),
                'success' => $success,
                'meta' => $meta === [] ? null : $meta,
                'visitor_key' => $attrs['visitor_key'],
                'ip_hash' => $attrs['ip_hash'],
                'user_agent' => $attrs['user_agent'],
                'device_type' => $attrs['device_type'],
                'browser' => $attrs['browser'],
                'platform' => $attrs['platform'],
                'accept_language' => $attrs['accept_language'],
                'referer' => $attrs['referer'],
                'country_code' => $attrs['country_code'],
                'is_bot' => $attrs['is_bot'],
            ]);
        } catch (\Throwable $e) {
            Log::warning('Falha ao registrar envio de formulário', [
                'form' => $formKey,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function normalizePath(string $path): string
    {
        if ($path === '/' || $path === '') {
            return '/';
        }

        $trimmed = rtrim($path, '/');

        return $trimmed === '' ? '/' : $trimmed;
    }
}
