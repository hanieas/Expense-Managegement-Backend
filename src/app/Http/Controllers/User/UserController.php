<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Requests\User\UserSignUpRequest;
use App\UseCase\User\UserLoginHandler;
use App\UseCase\User\UserSignUpHandler;
use Illuminate\Support\Facades\App;

class UserController extends Controller
{    
    /**
     * Signup
     *
     * @param  UserSignUpRequest $request
     * @return void
     * @group User Authentication
     * @bodyParam email required The email of the user. Example:hanieasemi@gmail.com
     * @bodyParam password required The password of the user. Example:password
     * @bodyParam currency_id int required The currency_id of the user. Example:9
     * @bodyParam username string required The username of the user. Example:username
     */
    public function signup(UserSignUpRequest $request)
    {
        $useCase = App::make(UserSignUpHandler::class);
        return $useCase->handle($request->validated());
    }
    
    /**
     * Login
     *
     * @param  UserLoginRequest $request
     * @return void
     * @group User Authentication
     * @bodyParam email required The email of user. Example:hanieasemi@gmail.com
     * @bodyParam password required The password of user. Example:password
     */
    public function login(UserLoginRequest $request)
    {
        $useCase = App::make(UserLoginHandler::class);
        return $useCase->handle($request->validated());
    }

    public function logout()
    {
    }
}
