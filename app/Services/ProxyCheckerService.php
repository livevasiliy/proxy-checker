<?php declare(strict_types=1);

namespace App\Services;

class ProxyCheckerService
{

    public function __construct(private readonly IpApiService $ipApiService)
    {
    }

    public function check(array $chunks)
    {
        $result = [];
        foreach ($chunks as $proxies) {
            foreach ($proxies as $proxy) {
                $result[] = $this->pingProxy($proxy);
            }
        }

        return $result;
    }

    private function pingProxy(string $proxy): array
    {
        [$ipAddress, $port] = explode(':', $proxy);

        $result = [
            'ip'   => $ipAddress,
            'port' => $port,
        ];

        $result = $this->pingProxyAsHttp($ipAddress, $port, $result);
        if ($result['available'] === false) {
            $result = $this->pingProxyAsSocks5($ipAddress, $port, $result);
        }

        $result['country']     = $this->fetchInfoAboutIp($ipAddress)['country'];
        $result['city']        = $this->fetchInfoAboutIp($ipAddress)['city'];
        $result['countryCode'] = $this->fetchInfoAboutIp($ipAddress)['countryCode'];

        return $result;
    }

    private function fetchInfoAboutIp(string $ipAddress)
    {
        return $this->ipApiService->check($ipAddress);
    }

    private function pingProxyAsSocks5(string $ipAddress, string $port, array $result): array
    {
        $loadingTime = microtime(true);
        $socksUrl    = 'socks5://proxy.example.com';
        $chSocks     = curl_init();
        curl_setopt($chSocks, CURLOPT_URL, $socksUrl);
        curl_setopt($chSocks, CURLOPT_PROXY, $ipAddress);
        curl_setopt($chSocks, CURLOPT_PROXYPORT, $port);
        curl_setopt($chSocks, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chSocks, CURLOPT_TIMEOUT, 10);
        curl_exec($chSocks);

        $loadingTime = floor((microtime(true) - $loadingTime) * 1000);

        if (curl_errno($chSocks)) {
            $result['available'] = false;
        }
        else {
            $result['available'] = true;
            $result['type']      = 'socks5';
        }

        $result['ttl'] = $loadingTime;

        curl_close($chSocks);

        return $result;
    }

    private function pingProxyAsHttp(string $ipAddress, string $port, array $result): array
    {
        $loadingTime = microtime(true);
        $httpUrl     = 'http://example.com';
        $chHttp      = curl_init();
        curl_setopt($chHttp, CURLOPT_URL, $httpUrl);
        curl_setopt($chHttp, CURLOPT_PROXY, $ipAddress);
        curl_setopt($chHttp, CURLOPT_PROXYPORT, $port);
        curl_setopt($chHttp, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chHttp, CURLOPT_TIMEOUT, 10);
        curl_exec($chHttp);

        curl_close($chHttp);

        $loadingTime = floor((microtime(true) - $loadingTime) * 1000);

        if (curl_errno($chHttp)) {
            $result['available'] = false;
        }
        else {
            $result['available'] = true;
            $result['type']      = 'http';
        }
        $result['ttl'] = $loadingTime;

        return $result;
    }
}
