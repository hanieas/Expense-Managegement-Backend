<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface IUserRepository
{
    /**
     * @param  array $request
     * @return Model
     */
    public function signup(array $request): Model;

    /**
     * @return Model
     */
    public function createToken(): Model;

    /**
     * @return bool
     */
    public function logout(): bool;
    
    /**
     * @param  array $attributes
     * @return bool
     */
    public function authAttempt(array $attributes): bool;
}
