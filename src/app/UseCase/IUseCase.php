<?php

namespace App\UseCase;

interface IUseCase
{
    /**
     * @param  mixed $request
     * @return mixed
     */
    public function handle(array $request): mixed;
}
