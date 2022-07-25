<?php

namespace App\Responders;

use App\Http\Resources\Transaction\TransactionCollection;
use App\Http\Resources\Transaction\TransactionResource;
use Illuminate\Http\JsonResponse;

class TransactionResponder extends BaseResponder implements IResponder
{
    public function respondResource(mixed $data, int $status = 200, array $headers = []): JsonResponse
    {
        $data = new TransactionResource($data);
        return $this->makeApiResponse($data,$status);
    }

    public function respondCollection(mixed $data, int $status = 200, array $headers = []): JsonResponse
    {
        $data = new TransactionCollection($data);
        return $this->makeApiResponse($data,$status);
    }
}
