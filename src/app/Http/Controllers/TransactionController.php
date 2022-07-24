<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\TransactionStoreRequest;
use App\Models\Transaction;
use App\Repositories\Interfaces\ITransactionRepository;
use App\Repositories\Interfaces\IWalletRepository;
use App\Responders\IResponder;
use App\Responders\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Responders\TransactionResponder;

class TransactionController extends Controller
{
    /** @var TransactionRepository */
    protected ITransactionRepository $repository;

    /** @var TransactionResponder */
    protected IResponder $responder;

    /** @var WalletRepository */
    protected IWalletRepository $walletRepository;

    public function __construct(
        ITransactionRepository $repository,
        IResponder $responder,
        IWalletRepository $walletRepository
    ) {
        $this->repository = $repository;
        $this->responder = $responder;
        $this->walletRepository = $walletRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TransactionStoreRequest  $request
     * @return JsonResponse
     */
    public function store(TransactionStoreRequest $request): JsonResponse
    {
        $request = $request->validated();
        $walletInventory = $this->walletRepository->find($request['wallet_id'])->inventory;
        if ($request['status'] === '-' && $walletInventory <= $request['amount']) {
            return $this->responder->failed(Message::TRANSACTION_AMOUNT_ERROR, 422);
        }
        $response = $this->repository->create($request);
        return $this->responder->respondResource($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
