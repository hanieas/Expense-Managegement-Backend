<?php

namespace App\Repositories\Interfaces;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Collection;

interface IWalletRepository
{
    /**
     * Define that the current user is the owner of wallet or not.
     *
     * @param  Wallet $wallet
     * @return bool
     */
    public function checkOwn(Wallet $wallet): bool;

    /**
     * Get the list of wallets for the signed in user.
     *
     * @return Collection
     */
    public function getList(): Collection;
}
