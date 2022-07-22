<?php

namespace App\UseCase\Wallet;

use App\Repositories\WalletRepository;
use App\Responders\WalletResponder;
use App\UseCase\IUseCase;
use Illuminate\Support\Facades\Auth;

class WalletShowHandler implements IUseCase
{
    /** @var WalletRepository */
    protected WalletRepository $repository;

    /** @var  WalletResponder */
    protected WalletResponder $responder;

    /**
     * __construct
     *
     * @param  WalletRepository $repository
     * @param  WalletResponder $responder
     * @return void
     */
    public function __construct(WalletRepository $repository, WalletResponder $responder)
    {
        $this->repository = $repository;
        $this->responder = $responder;
    }

    /**
     * handle
     *
     * @param  mixed $request
     * @return mixed
     */
    public function handle(mixed $data): mixed
    {
        if ($this->repository->checkOwn($data)) {
            return $this->responder->resourceRespond($data);
        }
        return $this->responder->failed('You dont own this wallet',403);
    }
}
