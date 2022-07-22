<?php

namespace App\UseCase\User;

use App\Repositories\Interfaces\IUserRepository;
use App\Responders\IResponder;
use App\UseCase\IUseCase;

class UserLogoutHandler implements IUseCase
{

    /** @var IResponder */
    protected IResponder $responder;

    /** @var IUserRepository */
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
    public function handle($data): mixed
    {
        if ($this->repository->logout()) {
            return $this->responder->message('You have been successfully logged out.');
        }
        return $this->responder->failed('Something went wrong! Try again');
    }
}
