<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

class IpApiService
{
    private const DEFAULT_LANG_RESPONSE = 'ru';

    private const BASE_API_ENDPOINT = 'http://ip-api.com/json';

    public function check(string $ip, string $langResponse = self::DEFAULT_LANG_RESPONSE): array
    {
        return Http::get(sprintf('%s/%s?lang=%s', self::BASE_API_ENDPOINT, $ip, $langResponse))->json();
    }
}
