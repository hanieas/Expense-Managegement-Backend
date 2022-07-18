<?php

namespace Tests\Unit;

use App\Models\Currency;
use App\Models\User;
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

    public function setUp():void
    {
        parent::setUp();
        
        $this->currency = Currency::factory()->create();
        $this->user = User::factory()->create(['currency_id' => $this->currency->id]);
    }

    function test_a_user_has_a_currency()
    {
        $this->assertInstanceOf(Currency::class, $this->user->currency);
    }
}
