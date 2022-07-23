<?php

namespace App\Repositories;

use App\Models\Wallet;
use App\Repositories\Interfaces\IWalletRepository;
use Illuminate\Support\Facades\Gate;

class WalletRepository extends BaseRepository implements IWalletRepository
{    
    /**
     * WalletRepository Constructor
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(Wallet $model)
    {
        parent::__construct($model);
    }
        
    /**
     * Define that the current user is the owner of wallet or not.
     *
     * @param  Wallet $wallet
     * @return bool
     */
    public function checkOwn(Wallet $wallet): bool
    {
        return Gate::allows('check-wallet-own', $wallet);
    }
}
