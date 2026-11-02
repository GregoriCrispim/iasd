<?php

namespace App\Support;

use Illuminate\Http\Request;

class CampaignVisitRecorder
{
    public function __construct(
        private readonly VisitFingerprint $fingerprint,
    ) {
    }

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
    public function attributesFromRequest(Request $request, int $campaignId): array
    {
        $attrs = $this->fingerprint->fromRequest($request, (string) $campaignId);

        // Campanhas mantêm o critério anterior: UA vazio não marca bot automaticamente.
        if (($attrs['user_agent'] ?? null) === null) {
            $attrs['is_bot'] = false;
        } else {
            $attrs['is_bot'] = $this->fingerprint->isBot((string) $attrs['user_agent'], null);
        }

        return $attrs;
    }

    public function detectDevice(string $ua): string
    {
        return $this->fingerprint->detectDevice($ua);
    }

    public function detectBrowser(string $ua): string
    {
        return $this->fingerprint->detectBrowser($ua);
    }

    public function detectPlatform(string $ua): string
    {
        return $this->fingerprint->detectPlatform($ua);
    }

    public function isBot(string $ua): bool
    {
        return $this->fingerprint->isBot($ua, null);
    }

    public function detectCountry(Request $request): ?string
    {
        return $this->fingerprint->detectCountry($request);
    }
}
