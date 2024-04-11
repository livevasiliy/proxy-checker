<?php declare(strict_types=1);

namespace App\Services;

use App\Jobs\ProxyCheckJob;

class ProxyCheckerService
{

    public function __construct()
    {
    }

    public function check(array $proxies)
    {
        foreach ($proxies as $proxy) {
            ProxyCheckJob::dispatch($proxy);
        }
    }
}
