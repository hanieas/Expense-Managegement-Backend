<?php

namespace App\Http\Controllers;

use App\Http\Requests\Wallet\WalletStoreRequest;
use App\Http\Requests\Wallet\WalletUpdateRequest;
use App\Models\Wallet;
use App\Repositories\Interfaces\IWalletRepository;
use App\Repositories\WalletRepository;
use App\Responders\WalletResponder;
use App\Responders\IResponder;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    /** @var WalletRepository */
    protected IWalletRepository $repository;

    /** @var WalletResponder */
    protected IResponder $responder;

    /**
     * WalletController Constructor
     *
     * @param  WalletRepository $repository
     * @param  WalletResponder $responder
     * @return void
     */
    public function __construct(IWalletRepository $repository, IResponder $responder)
    {
        $this->repository = $repository;
        $this->responder = $responder;
    }

    /**
     * Store a Wallet
     * 
     * @param  WalletStoreRequest $request
     * @return JsonResponse
     * @group Wallet CRUD
     * @authenticated
     */
    public function store(WalletStoreRequest $request): JsonResponse
    {
        $response = $this->repository->create($request->validated());
        return $this->responder->respondResource($response);
    }

    /**
     * Show a Wallet
     *
     * @param  Wallet $wallet
     * @return JsonResponse
     * @group Wallet CRUD
     * @authenticated
     */
    public function show(Wallet $wallet): JsonResponse
    {
        if ($this->repository->checkOwn($wallet)) {
            return $this->responder->respondResource($wallet);
        }
        return $this->responder->failed('You dont own this wallet', 403);
    }
    
    /**
     * Update The Wallet
     *
     * @param  WalletUpdateRequest $request
     * @param  Wallet $wallet
     * @return JsonResponse
     * @group Wallet CRUD
     * @authenticated
     */
    public function update(WalletUpdateRequest $request,Wallet $wallet): JsonResponse
    {
        if ($this->repository->checkOwn($wallet)) {
            $wallet->update($request->validated());
            return $this->responder->respondResource($wallet);
        }
        return $this->responder->failed('You dont own this wallet', 403);
    }
    
    /**
     * Delete a Wallet
     *
     * @param  Wallet $wallet
     * @return JsonResponse
     * @group Wallet CRUD
     * @authenticated
     */
    public function destroy(Wallet $wallet): JsonResponse
    {
        if ($this->repository->checkOwn($wallet)) {
            $wallet->delete();
            return $this->responder->message('Your wallet is deleted successfully',200);
        }
        return $this->responder->failed('You dont own this wallet', 403);
    }
}
