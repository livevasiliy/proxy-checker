<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CheckProxyRequest;
use App\Services\ProxyCheckerService;
use Illuminate\Http\JsonResponse;

class ProxyController extends Controller
{
    public function check(CheckProxyRequest $request, ProxyCheckerService $proxyCheckerService): JsonResponse
    {
        $proxyCheckerService->check($request->validated('proxies'));

        return new JsonResponse(['message' => 'Proxies are being processed!'], JsonResponse::HTTP_OK);
    }
}
