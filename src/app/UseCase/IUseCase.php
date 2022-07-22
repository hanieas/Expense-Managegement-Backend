<?php

namespace App\UseCase;

interface IUseCase
{
    /**
     * @param  mixed $data
     * @return mixed
     */
    public function handle(mixed $data): mixed;
}
