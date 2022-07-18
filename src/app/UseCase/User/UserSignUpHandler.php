<?php

namespace App\UseCase\User;

use App\Repositories\Interfaces\IUserRepository;
use App\Responders\IResponder;
use App\UseCase\IUseCase;

class UserSignUpHandler implements IUseCase
{
    /**
     * @var IResponder
     */
    protected IResponder $responder;

    /**
     * @var IUserRepository
     */
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
        $request['password'] = bcrypt($request['password']);
        $data = $this->repository->signup($request);
        return $this->responder->resourceRespond($data);
    }
}
