<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserSignUpRequest;
use App\UseCase\User\UserSignUpHandler;
use Illuminate\Support\Facades\App;

class UserController extends Controller
{    
    /**
     * signup
     *
     * @param  UserSignUpRequest $request
     * @return void
     */
    public function signup(UserSignUpRequest $request)
    {
        $useCase = App::make(UserSignUpHandler::class);
        return $useCase->handle($request->validated());
    }

    public function login()
    {
    }

    public function logout()
    {
    }
}
