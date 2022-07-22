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
     * @param  mixed $data
     * @return mixed
     */
    public function handle(mixed $data): mixed
    {
        $data['password'] = bcrypt($data['password']);
        $response = $this->repository->signup($data);
        return $this->responder->resourceRespond($response);
    }
}
