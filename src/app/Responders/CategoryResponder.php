<?php

namespace App\Responders;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\CategoryCollection;
use Illuminate\Http\JsonResponse;

class CategoryResponder extends BaseResponder implements IResponder
{    
    /**
     * @param  mixed $data
     * @param  int $status
     * @param  array $headers
     * @return JsonResponse
     */
    public function respondResource(mixed $data, int $status = 200, array $headers = []): JsonResponse
    {
        $data = new CategoryResource($data);
        return $this->makeApiResponse($data,$status);
    }
    
    /**
     * @param  mixed $data
     * @param  int $status
     * @param  array $headers
     * @return JsonResponse
     */
    public function respondCollection(mixed $data, int $status = 200, array $headers = []): JsonResponse
    {
        $data = new CategoryCollection($data);
        return $this->makeApiResponse($data,$status);
    }
}
