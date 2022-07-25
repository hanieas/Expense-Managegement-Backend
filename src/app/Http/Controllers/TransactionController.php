<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\TransactionStoreRequest;
use App\Http\Requests\Transaction\TransactionUpdateRequest;
use App\Models\Transaction;
use App\Repositories\Interfaces\ITransactionRepository;
use App\Repositories\Interfaces\IWalletRepository;
use App\Responders\IResponder;
use App\Responders\Message;
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
        if (isset($_GET['wallet_id'])) {
            $wallet = $this->walletRepository->find($_GET['wallet_id']);
            if ($wallet && $this->walletRepository->checkOwn($wallet)) {
                $response = $this->repository->getWalletTransactions($wallet);
            }
        }
        $response = $this->repository->getUserTransactions();
        return $this->responder->respondCollection($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TransactionStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionStoreRequest $request)
    {
        $request = $request->validated();
        $walletInventory = $this->walletRepository->find($request['wallet_id'])->inventory;
        if ($request['status'] === '-' && $walletInventory < $request['amount']) {
            return $this->responder->failed(Message::TRANSACTION_AMOUNT_ERROR, 422);
        }
        $response = $this->repository->create($request);
        return $this->responder->respondResource($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        if ($this->repository->checkOwn($transaction)) {
            return $this->responder->respondResource($transaction);
        }
        return $this->responder->failed(Message::ONLY_TRANSACTION_OWNER_CAN_GET_IT, 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TransactionUpdateRequest  $request
     * @param  Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(TransactionUpdateRequest $request, Transaction $transaction)
    {
        if ($this->repository->checkOwn($transaction)) {
            $wallet = $this->walletRepository->find($transaction->wallet_id);
            $wallet = $this->repository->walletAfterDeletingTransaction($wallet,$transaction);
            $newWalletInventory = $this->walletRepository->find($request['wallet_id'])->inventory;
            if ($request['status'] === '-' && ( $wallet->inventory < $request['amount'] || $newWalletInventory<$request['amount'])) {
                return $this->responder->failed(Message::TRANSACTION_AMOUNT_ERROR, 422);
            }
            $transaction->update($request->validated());
            return $this->responder->respondResource($transaction);
        }
        return $this->responder->failed(Message::ONLY_TRANSACTION_OWNER_CAN_GET_IT, 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        if ($this->repository->checkOwn($transaction)) {
            $transaction->delete();
            return $this->responder->message(Message::TRANSACTION_DELETED, 200);
        }
        return $this->responder->failed(Message::ONLY_TRANSACTION_OWNER_CAN_GET_IT, 403);
    }
}
