<?php

namespace App\Responders;

use App\Http\Resources\Wallet\WalletCollection;
use App\Http\Resources\Wallet\WalletResource;
use Illuminate\Http\JsonResponse;

class WalletResponder extends BaseResponder implements IResponder
{    
    /**
     * @param  mixed $data
     * @param  int $status
     * @param  array $headers
     * @return JsonResponse
     */
    public function respondResource(mixed $data, int $status = 200, array $headers = []): JsonResponse
    {
        $data = new WalletResource($data);
        return $this->makeApiResponse($data,$status);
    }
    
    /**
     * Respond a Collection
     *
     * @param  mixed $data
     * @param  int $status
     * @param  array $headers
     * @return JsonResponse
     */
    public function respondCollection(mixed $data, int $status = 200, array $headers = []): JsonResponse
    {
        $data = new WalletCollection($data);
        return $this->makeApiResponse($data,$status);
    }

}
