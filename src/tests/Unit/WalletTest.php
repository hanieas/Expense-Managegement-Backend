<?php

namespace Tests\Unit;

use App\Models\Currency;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Currency
     */
    protected Currency $currency;

    /**
     * @var User
     */
    protected User $user;

    /** @var Wallet */
    protected Wallet $wallets;

    public function setUp():void
    {
        parent::setUp();
        
        $this->currency = Currency::factory()->create();
        $this->user = User::factory()->create();
        $this->wallet = Wallet::factory()->create(['user_id'=>$this->user->id]);
    }

    public function test_a_wallet_belongs_to_a_user()
    {
        $this->assertInstanceOf(User::class,$this->wallet->user);
    }
}
