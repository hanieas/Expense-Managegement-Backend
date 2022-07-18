<?php

namespace App\Responders;

use App\Http\Resources\IResource;
use Illuminate\Http\JsonResponse;

interface IResponder
{
    public function message(string $message, int $status = 200, array $data = []): JsonResponse;

    public function failed(string $message, int $status = 422, array $data = []): JsonResponse;

    public function makeApiResponse(IResource $data, $status = 200): JsonResponse;

    public function resourceRespond(mixed $data, int $status = 200, array $headers = []): JsonResponse;

}
