<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserRepository extends BaseRepository implements IUserRepository
{
    /**
     * @param  User $user
     * @return void
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * @param  array $request
     * @return Model
     */
    public function signup(array $request): Model
    {
        $user = $this->model->create($request);
        $user->token = $user->createToken('Api Token')->accessToken;
        return $user;
    }

    /**
     * @return Model
     */
    public function createToken(): Model
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->token = $user->createToken('Api Token')->accessToken;
        return $user;
    }
    
    /**
     * Authentication attempt
     *
     * @param  array $attributes
     * @return bool
     */
    public function authAttempt(array $attributes): bool
    {
        if (Auth::attempt($attributes)) {
           return true;
        }
        return false;
    }
    
    /**
     * @return bool
     */
    public function logout(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->token()->revoke();
        return true;
    }
}
