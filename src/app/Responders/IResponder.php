<?php

namespace App\Responders;

use App\Http\Resources\IResource;
use Illuminate\Http\JsonResponse;

interface IResponder
{
    public function message(string $message, int $status = 200, array $data = []): JsonResponse;

    public function failed(string $message, int $status = 422, mixed $data = []): JsonResponse;

    public function makeApiResponse(JsonResponse $data, $status = 200): JsonResponse;

    public function respondResource(mixed $data, int $status = 200, array $headers = []): JsonResponse;

    public function respondCollection(mixed $data, int $status = 200, array $headers = []): JsonResponse;
}
