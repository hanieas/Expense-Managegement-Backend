<?php

namespace App\UseCase\Wallet;

use App\Repositories\WalletRepository;
use App\Responders\WalletResponder;
use App\UseCase\IUseCase;
use Illuminate\Support\Facades\Auth;

class WalletStoreHandler implements IUseCase
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
     * @param  mixed $data
     * @return mixed
     */
    public function handle(mixed $data): mixed
    {
        $data['user_id'] = Auth::user()->id;
        $response = $this->repository->create($data);
        return $this->responder->resourceRespond($response);
    }
}
