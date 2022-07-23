<?php

namespace App\Responders;

use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;

class UserResponder extends BaseResponder implements IResponder
{    
    /**
     * Respond Resource
     *
     * @param  mixed $data
     * @param  int $status
     * @param  mixed $headers
     * @return JsonResponse
     */
    public function respondResource(mixed $data, int $status = 200, array $headers = []): JsonResponse
    {
        $data = new UserResource($data);
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
        $data = new UserCollection($data);
        return $this->makeApiResponse($data,$status);
    }
}
