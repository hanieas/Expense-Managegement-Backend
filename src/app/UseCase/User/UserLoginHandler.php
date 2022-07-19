<?php

namespace App\UseCase\User;

use App\Repositories\Interfaces\IUserRepository;
use App\Responders\IResponder;
use App\UseCase\IUseCase;
use Illuminate\Support\Facades\Auth;

class UserLoginHandler implements IUseCase
{
    /** @var IResponder */
    protected IResponder $responder;

    /** @var  IUserRepository */
    protected IUserRepository $repository;
    
    /**
     * @param  IUserRepository $repository
     * @param  IResponder $responder
     * @return void
     */
    public function __construct(IUserRepository $repository, IResponder $responder)
    {
        $this->repository = $repository;
        $this->responder = $responder;
    }
    
    /**
     * @param  array $request
     * @return mixed
     */
    public function handle(array $request): mixed
    {
        if (Auth::attempt($request)) {
            $data = $this->repository->login();
            return $this->responder->resourceRespond($data);
        }
        return $this->responder->failed('email or password is incorrect', 422, $request);
    }
}
