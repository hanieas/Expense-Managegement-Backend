<?php

namespace App\Repositories\Interfaces;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

interface ITransactionRepository
{
    /**
     * Checking if the user own the transaction
     *
     * @param  Transaction $transaction
     * @return bool
     */
    public function checkOwn(Transaction $transaction): bool;

    /**
     * Get list of user's transactions
     *
     * @return LengthAwarePaginator
     */
    public function getUserTransactions(): LengthAwarePaginator;

    /**
     * Get list of specific wallet's transactions
     *
     * @param  Wallet $wallet
     * @return LengthAwarePaginator
     */
    public function getWalletTransactions(Wallet $wallet): LengthAwarePaginator;

    /**
     * Update the wallet of transaction after creating. 
     *
     * @param  Wallet $wallet
     * @param  Transaction $transaction
     * @return Model
     */
    public function walletAfterCreatingTransaction(Wallet $wallet, Transaction $transaction): Model;

    /**
     * Update the wallet of transaction after deleting. 
     *
     * @param  Wallet $wallet
     * @param  Transaction $transaction
     * @return Model
     */
    public function walletAfterDeletingTransaction(Wallet $wallet, Transaction $transaction): Model;
}
