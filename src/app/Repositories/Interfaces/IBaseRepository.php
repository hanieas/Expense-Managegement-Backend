<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface IBaseRepository
{
    public function create(array $request): Model;

    public function show(int $id): ?Model;
}
