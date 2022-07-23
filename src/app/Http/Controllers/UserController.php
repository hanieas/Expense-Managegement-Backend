<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Requests\User\UserSignUpRequest;
use App\Repositories\Interfaces\IUserRepository;
use App\Responders\IResponder;

class UserController extends Controller
{
    /** @var UserRepository */
    protected IUserRepository $repository;

    /** @var UserResponder */
    protected IResponder $responder;

    /**
     * __construct
     *
     * @param  UserRepository $repository
     * @param  UserResponder $responder
     * @return void
     */
    public function __construct(IUserRepository $repository, IResponder $responder)
    {
        $this->repository = $repository;
        $this->responder = $responder;
    }

    /**
     * Signup
     *
     * @param  UserSignUpRequest $request
     * @return JsonResponse
     * @group User Authentication
     * @bodyParam email required The email of the user. Example:hanieasemi@gmail.com
     * @bodyParam password required The password of the user. Example:password
     * @bodyParam currency_id int required The currency_id of the user. Example:9
     * @bodyParam username string required The username of the user. Example:username
     */
    public function signup(UserSignUpRequest $request)
    {
        $request = $request->validated();
        $request['password'] = bcrypt($request['password']);
        $data = $this->repository->signup($request);
        return $this->responder->respondResource($data);
    }

    /**
     * Login
     *
     * @param  UserLoginRequest $request
     * @return JsonResponse
     * @group User Authentication
     * @bodyParam email required The email of user. Example:hanieasemi@gmail.com
     * @bodyParam password required The password of user. Example:password
     */
    public function login(UserLoginRequest $request)
    {
        if ($this->repository->authAttempt($request->validated())) {
            $data = $this->repository->createToken();
            return $this->responder->respondResource($data);
        }
        return $this->responder->failed('email or password is incorrect', 422, $request);
    }

    /**
     * Logout
     *
     * @return JsonResponse
     * @group User Authentication
     * @authenticated
     */
    public function logout()
    {
        if ($this->repository->logout()) {
            return $this->responder->message('You have been successfully logged out.');
        }
        return $this->responder->failed('Something went wrong! Try again');
    }
}
