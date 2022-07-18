<?php

namespace App\Responders;

use App\Http\Resources\IResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;

abstract class BaseResponder extends ResponseFactory implements IResponder
{
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

    public function failed(string $message, int $status = 422, array $data = []): JsonResponse
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
