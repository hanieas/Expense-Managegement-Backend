<?php

namespace App\Http\Controllers;

use App\Http\Requests\Wallet\WalletStoreRequest;
use App\Repositories\Interfaces\IWalletRepository;
use App\Repositories\WalletRepository;
use App\Responders\WalletResponder;
use App\Responders\IResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return response()->json($request->validated());
        $response = $this->repository->create($request->validated());
        return $this->responder->respondResource($response);
    }
}
