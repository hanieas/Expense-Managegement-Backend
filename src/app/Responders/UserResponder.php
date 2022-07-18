<?php

namespace App\Responders;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;

class UserResponder extends BaseResponder implements IResponder
{
    public function resourceRespond(mixed $data, int $status = 200, array $headers = []): JsonResponse
    {
        $data = new UserResource($data);
        return $this->makeApiResponse($data);
    }
}
