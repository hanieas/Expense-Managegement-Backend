<?php

namespace App\Responders;

use App\Http\Resources\IResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;

abstract class BaseResponder extends ResponseFactory implements IResponder
{    
    /**
     * Send a message as a response
     *
     * @param  string $message
     * @param  int $status
     * @param  array $data
     * @return JsonResponse
     */
    public function message(string $message, int $status = 200, array $data = []): JsonResponse
    {
        return $this->json([
            'message' => $message,
            'meta' => [
                'status' => $status,
                'current' => request()->fullUrl(),
                'payload' => $data
            ]
        ], $status);
    }
    
    /**
     * Send a failed message as a reponse
     *
     * @param  string $message
     * @param  int $status
     * @param  mixed $data
     * @return JsonResponse
     */
    public function failed(string $message, int $status = 422, mixed $data = []): JsonResponse
    {
        return $this->json([
            'error' => $message ?: $data,
            'meta' => [
                'status' => $status,
                'current' => request()->fullUrl(),
                'payload' => $data
            ]
        ], $status);
    }
    
    /**
     * Make Api Response
     *
     * @param  mixed $data
     * @param  int $status
     * @return JsonResponse
     */
    public function makeApiResponse(mixed $data, $status = 200): JsonResponse
    {
        return $this->json([
            'data' => $data,
            'meta' => [
                'status' => $status,
                'current' => request()->fullUrl()
            ]
        ], $status);
    }
}
