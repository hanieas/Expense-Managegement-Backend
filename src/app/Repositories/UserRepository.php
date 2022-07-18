<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Database\Eloquent\Model;

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
    public function signup(array $request):Model
    {
        $user = $this->model->create($request);
        $user->token = $user->createToken('Api Token')->accessToken;
        return $user;
    }
}
