<?php

namespace App\Responders;

use App\Http\Resources\Wallet\WalletResource;
use Illuminate\Http\JsonResponse;

class WalletResponder extends BaseResponder implements IResponder
{
    public function resourceRespond(mixed $data, int $status = 200, array $headers = []): JsonResponse
    {
        $data = new WalletResource($data);
        return $this->makeApiResponse($data);
    }
}
