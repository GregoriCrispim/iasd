<?php

namespace App\Support;

use Illuminate\Http\Request;

class VisitFingerprint
{
    /**
     * @return array{
     *     visitor_key: string,
     *     ip_hash: string|null,
     *     user_agent: string|null,
     *     device_type: string,
     *     browser: string,
     *     platform: string,
     *     accept_language: string|null,
     *     referer: string|null,
     *     country_code: string|null,
     *     query: array<string, mixed>,
     *     is_bot: bool
     * }
     */
    public function fromRequest(Request $request, string $visitorSalt = 'site'): array
    {
        $ip = (string) $request->ip();
        $userAgent = $this->truncate((string) $request->userAgent(), 1000);
        $ipHash = $ip !== '' ? hash('sha256', $ip) : null;
        $visitorKey = hash('sha256', $visitorSalt.'|'.$ip.'|'.$userAgent);

        return [
            'visitor_key' => $visitorKey,
            'ip_hash' => $ipHash,
            'user_agent' => $userAgent !== '' ? $userAgent : null,
            'device_type' => $this->detectDevice($userAgent),
            'browser' => $this->detectBrowser($userAgent),
            'platform' => $this->detectPlatform($userAgent),
            'accept_language' => $this->truncate((string) $request->header('Accept-Language'), 120) ?: null,
            'referer' => $this->truncate((string) $request->headers->get('referer'), 500) ?: null,
            'country_code' => $this->detectCountry($request),
            'query' => $request->query(),
            'is_bot' => $this->isBot($userAgent, $request),
        ];
    }

    public function detectDevice(string $ua): string
    {
        if ($ua === '') {
            return 'unknown';
        }

        if (preg_match('/ipad|tablet|kindle|silk|(android(?!.*mobile))/i', $ua)) {
            return 'tablet';
        }

        if (preg_match('/mobile|iphone|ipod|android.*mobile|windows phone|blackberry/i', $ua)) {
            return 'mobile';
        }

        return 'desktop';
    }

    public function detectBrowser(string $ua): string
    {
        if ($ua === '') {
            return 'unknown';
        }

        $browsers = [
            'Edge' => '/Edg(?:e|A|iOS)?\//i',
            'Chrome' => '/Chrome\//i',
            'Firefox' => '/Firefox\//i',
            'Safari' => '/Safari\//i',
            'Opera' => '/OPR\/|Opera\//i',
            'Samsung Internet' => '/SamsungBrowser\//i',
            'IE' => '/MSIE |Trident\//i',
        ];

        foreach ($browsers as $name => $pattern) {
            if (preg_match($pattern, $ua)) {
                if ($name === 'Chrome' && preg_match('/Edg(?:e|A|iOS)?\//i', $ua)) {
                    continue;
                }
                if ($name === 'Safari' && preg_match('/Chrome\/|Chromium\//i', $ua)) {
                    continue;
                }

                return $name;
            }
        }

        return 'other';
    }

    public function detectPlatform(string $ua): string
    {
        if ($ua === '') {
            return 'unknown';
        }

        if (preg_match('/android/i', $ua)) {
            return 'Android';
        }
        if (preg_match('/iphone|ipad|ipod/i', $ua)) {
            return 'iOS';
        }
        if (preg_match('/windows/i', $ua)) {
            return 'Windows';
        }
        if (preg_match('/macintosh|mac os x/i', $ua)) {
            return 'macOS';
        }
        if (preg_match('/linux/i', $ua)) {
            return 'Linux';
        }

        return 'other';
    }

    public function isBot(string $ua, ?Request $request = null): bool
    {
        if ($ua === '') {
            return true;
        }

        if ($request) {
            $purpose = strtolower((string) ($request->headers->get('Purpose')
                ?? $request->headers->get('Sec-Purpose')
                ?? ''));
            if (str_contains($purpose, 'prefetch') || str_contains($purpose, 'prerender')) {
                return true;
            }
        }

        return (bool) preg_match(
            '/bot|crawler|spider|slurp|facebookexternalhit|preview|wget|curl|python-requests|headless|scrapy|httpclient|libwww|pingdom|uptimerobot|statuscake|monitor|lighthouse|pagespeed|gtmetrix|semrush|ahrefs|mj12bot|dotbot|petalbot|bytespider|gptbot|claudebot|anthropic|chatgpt|bingpreview|yandex|baidu|duckduckbot|applebot|twitterbot|linkedinbot|whatsapp|telegram|discordbot|slackbot|embedly/i',
            $ua
        );
    }

    public function detectCountry(Request $request): ?string
    {
        $candidates = [
            $request->header('CF-IPCountry'),
            $request->header('X-Country-Code'),
            $request->header('CloudFront-Viewer-Country'),
        ];

        foreach ($candidates as $code) {
            $code = strtoupper(trim((string) $code));
            if ($code !== '' && $code !== 'XX' && preg_match('/^[A-Z]{2}$/', $code)) {
                return $code;
            }
        }

        return null;
    }

    private function truncate(string $value, int $max): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        return mb_strlen($value) > $max ? mb_substr($value, 0, $max) : $value;
    }
}
