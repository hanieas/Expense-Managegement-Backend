<?php

namespace App\Observers;

use App\Models\Wallet;

class WalletObserver
{
    /**
     * Handle the Wallet "created" event.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return void
     */
    public function created(Wallet $wallet)
    {
        //
    }

    /**
     * Handle the Wallet "updated" event.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return void
     */
    public function updated(Wallet $wallet)
    {
        //
    }

    /**
     * Handle the Wallet "deleted" event.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return void
     */
    public function deleted(Wallet $wallet)
    {
        $wallet->transactions()->delete();
    }

    /**
     * Handle the Wallet "restored" event.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return void
     */
    public function restored(Wallet $wallet)
    {
        //
    }

    /**
     * Handle the Wallet "force deleted" event.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return void
     */
    public function forceDeleted(Wallet $wallet)
    {
        //
    }
}
