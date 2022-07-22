<?php

namespace App\UseCase\Wallet;

use App\Repositories\WalletRepository;
use App\Responders\WalletResponder;
use App\UseCase\IUseCase;
use Illuminate\Support\Facades\Auth;

class WalletIndexHandler implements IUseCase
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
    public function handle(array $request): mixed
    {
        $user = Auth::user();
        $data = $this->repository->create($request);
        return $this->responder->resourceRespond($data);
    }
}
