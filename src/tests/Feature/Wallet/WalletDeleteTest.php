<?php

namespace Tests\Feature\Wallet;

use App\Models\Currency;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;
use Tests\Utilities\MiddlewareMessage;
use Tests\Utilities\ValidationMessage;

class WalletDeleteTest extends TestCase
{
    use DatabaseMigrations;

    /** @var User */
    protected User $user;

    /** @var Currency */
    protected Currency $currency;

    /** @var Wallet */
    protected Wallet $wallet;

    /** @var string */
    protected string $url, $name, $correct_inventory, $incorrect_inventory, $new_name;

    public function setUp(): void
    {
        parent::setUp();

        $this->currency = Currency::factory()->create();
        $this->user = User::factory()->create();
        $this->name = 'Wallet';
        $this->wallet = Wallet::factory()->create();
        $this->url = $this->wallet->path;
    }

    public function test_an_unautenticated_user_cant_delete_wallet()
    {
        $response = $this->callRequest('delete', $this->url . '/' . $this->wallet->id, []);
        $response->assertJson(['message' => MiddlewareMessage::AUTHENTICATED]);
    }

    public function test_just_wallet_owner_can_delete_wallet()
    {
        $token = $this->generateToken(User::factory()->create());
        $response = $this->callRequest('delete', $this->url . '/' . $this->wallet->id, [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertJson(['error' => ValidationMessage::ONLY_WALLET_OWNER_CAN_GET_WALLET])
            ->assertStatus(403);
    }

    public function test_a_signed_in_owner_user_can_delete_wallet()
    {
        $token = $this->generateToken($this->user);
        Gate::define('check-wallet-own', function () {
            return true;
        });
        $response = $this->callRequest('delete', $this->url . '/' . $this->wallet->id, [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $this->assertSoftDeleted('wallets',[
            'id' => $this->wallet->id,
        ]);;
    }
}
