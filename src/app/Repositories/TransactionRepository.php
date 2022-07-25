<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\ITransactionRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TransactionRepository extends BaseRepository implements ITransactionRepository
{
    /**
     * __construct
     *
     * @param  Transaction $model
     * @return void
     */
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    /**
     * Checking if the user own the transaction.
     *
     * @param  Transaction $transaction
     * @return bool
     */
    public function checkOwn(Transaction $transaction): bool
    {
        if (Gate::allows('check-transaction-own', $transaction)) {
            return true;
        }
        return false;
    }

    /**
     * getUserTransactions
     *
     * @return LengthAwarePaginator
     */
    public function getUserTransactions(): LengthAwarePaginator
    {
        /** @var User */
        $user = Auth::user();
        return $user->transactions()->paginate(10);
    }

    /**
     * Get list of specific wallet's transactions
     *
     * @param  Wallet $wallet
     * @return LengthAwarePaginator
     */
    public function getWalletTransactions(Wallet $wallet): LengthAwarePaginator
    {
        return $wallet->transactions()->paginate(10)->appends($_GET);
    }

    /**
     * @param  Wallet $wallet
     * @param  Transaction $transaction
     * @return Model
     */
    public function walletAfterCreatingTransaction(Wallet $wallet, Transaction $transaction): Model
    {
        if ($transaction->status === '+') {
            $wallet->inventory += $transaction->amount;
        } else if ($transaction->status === '-') {
            $wallet->inventory -= $transaction->amount;
        }
        return $wallet;
    }

    /**
     * @param  Wallet $wallet
     * @param  Transaction $transaction
     * @return Model
     */
    public function walletAfterDeletingTransaction(Wallet $wallet, Transaction $transaction): Model
    {
        if ($transaction->status == '+') {
            $wallet->inventory -= $transaction->amount;
        } else if ($transaction->status === '-') {
            $wallet->inventory += $transaction->amount;
        }
        return $wallet;
    }
}
