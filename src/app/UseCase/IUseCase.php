<?php

namespace App\UseCase;

use App\DTO\IDTO;

interface IUseCase
{
    /**
     * @param  array $request
     * @return mixed
     */
    public function handle(array $request): mixed;
}
