<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface IUserRepository
{    
    /**
     * @param  array $request
     * @return Model
     */
    public function signup(array $request):Model;
}
