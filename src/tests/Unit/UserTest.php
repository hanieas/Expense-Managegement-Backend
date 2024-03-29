<?php

namespace Tests\Unit;

use App\Models\Currency;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserTest extends TestCase
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

    /** @var Collection */
    protected Collection $wallets;

    public function setUp(): void
    {
        parent::setUp();

        $this->currency = Currency::factory()->create();
        $this->user = User::factory()->create(['currency_id' => $this->currency->id]);
        $this->wallets = Wallet::factory(10)->create(['user_id' => $this->user->id]);
    }

    function test_a_user_has_a_currency()
    {
        $this->assertInstanceOf(Currency::class, $this->user->currency);
    }

    function test_a_user_has_many_wallets()
    {
        $this->assertInstanceOf(Collection::class, $this->user->wallets);
    }

    function test_a_user_has_many_categories()
    {
        $this->assertInstanceOf(Collection::class, $this->user->categories);
    }
}
