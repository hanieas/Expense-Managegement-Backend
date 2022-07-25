<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Repositories\Interfaces\ITransactionRepository;
use App\Repositories\Interfaces\IWalletRepository;

class TransactionObserver
{

    /** @var TransactionRepository */
    protected ITransactionRepository $repository;

    /** @var WalletRepository */
    protected IWalletRepository $walletRepository;

    /**
     * @param  ITransactionRepository $repository
     * @return void
     */
    public function __construct(ITransactionRepository $repository, IWalletRepository $walletRepository)
    {
        $this->repository = $repository;
        $this->walletRepository = $walletRepository;
    }

    /**
     * Handle the Transaction "created" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function created(Transaction $transaction)
    {
        $wallet = $transaction->wallet;
        $wallet = $this->repository->walletAfterCreatingTransaction($wallet, $transaction);
        $wallet->save();
    }

    /**
     * Handle the Transaction "updated" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function updated(Transaction $transaction)
    {
        //Refactor Wallet
        $oldStatus = $transaction->getOriginal('status');
        $oldAmount = $transaction->getOriginal('amount');
        $oldWalletID = $transaction->getOriginal('wallet_id');
        $oldWallet = $this->walletRepository->find($oldWalletID);
        if ($oldStatus == '+') {
            $oldWallet->inventory -= $oldAmount;
        } else if ($oldStatus === '-') {
            $oldWallet->inventory += $oldAmount;
        }
        $oldWallet->save();

        //Update Wallet
        $wallet = $transaction->wallet;
        $wallet = $this->repository->walletAfterCreatingTransaction($wallet, $transaction);
        $wallet->save();

    }
    
    /**
     * Handle the Transaction "deleted" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function deleted(Transaction $transaction)
    {
        $wallet = $transaction->wallet;
        $wallet = $this->repository->walletAfterDeletingTransaction($wallet, $transaction);
        $wallet->save();
    }

    /**
     * Handle the Transaction "restored" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function restored(Transaction $transaction)
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function forceDeleted(Transaction $transaction)
    {
        //
    }
}
