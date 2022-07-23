<?php

namespace App\Repositories\Interfaces;

use App\Models\Wallet;

interface IWalletRepository
{    
    /**
     * Define that the current user is the owner of wallet or not.
     *
     * @param  Wallet $wallet
     * @return bool
     */
    public function checkOwn(Wallet $wallet): bool;
}
