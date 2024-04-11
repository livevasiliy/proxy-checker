<?php declare(strict_types=1);

namespace App\Jobs;

use App\Events\ProxyIsCheckedEvent;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Proxy;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProxyCheckJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $proxy;

    protected Proxy $proxyModel;

    public function __construct(string $proxy)
    {
        $this->proxy = $proxy;
    }

    public function handle(): void
    {
        [$ip, $port] = explode(':', $this->proxy);

        // Проверка HTTP прокси
        $this->checkProtocol('http', $ip, $port);

        // Проверка HTTPS прокси
        $this->checkProtocol("https", $ip, $port);

        // Проверка SOCKS5 прокси
        $this->checkSocks5($ip, $port);

        event(new ProxyIsCheckedEvent($this->proxyModel));
    }

    private function checkProtocol($protocol, $ip, $port): void
    {
        $client = new Client([
            'timeout'  => 2.0,
            'proxy' => sprintf("%s://%s:%s", $protocol, $ip, $port),
        ]);

        try {
            $response = $client->request(Request::METHOD_GET, 'https://example.com');
            if ($response->getStatusCode() == Response::HTTP_OK) {
                $this->proxyModel = Proxy::create([
                    'ip' => $ip,
                    'port' => $port,
                    'protocol' => $protocol,
                    'is_working' => true,
                ]);
            }
        } catch (GuzzleException|Exception $e) {
            Log::error($e->getMessage());
            $this->proxyModel = Proxy::create([
                'ip' => $ip,
                'port' => $port,
                'protocol' => $protocol,
                'is_working' => false,
            ]);
        }
    }

    private function checkSocks5($ip, $port)
    {
        $client = new Client([
            'timeout'  => 2.0,
            'proxy' => "socks5://$ip:$port",
        ]);

        try {
            $response = $client->request(Request::METHOD_GET, 'https://example.com');
            if ($response->getStatusCode() == Response::HTTP_OK) {
                $this->proxyModel = Proxy::create([
                    'ip' => $ip,
                    'port' => $port,
                    'protocol' => 'socks5',
                    'is_working' => true,
                ]);
            }
        } catch (GuzzleException|Exception $e) {
            Log::error($e->getMessage());
            $this->proxyModel = Proxy::create([
                'ip' => $ip,
                'port' => $port,
                'protocol' => 'socks5',
                'is_working' => false,
            ]);
        }
    }
}
