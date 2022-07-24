<?php

namespace Tests\Feature\Wallet;

use App\Models\Currency;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;
use App\Responders\Message;


class WalletUpdateTest extends TestCase
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
        $this->wallet = Wallet::factory()->create(['user_id' => $this->user->id]);
        $this->url = $this->wallet->path. '/' . $this->wallet->id;
    }

    public function test_an_unautenticated_user_cant_update_wallet()
    {
        $response = $this->callRequest('put', $this->url, []);
        $response->assertJson(['message' => Message::ONLY_AUTHENTICATED_USER]);
    }

    public function test_name_is_required()
    {
        $token = $this->generateToken($this->user);
        Gate::define('check-wallet-own', function () {
            return true;
        });
        $response = $this->callRequest('put', $this->url, [
            'Authorization' => 'Bearer ' . $token,
            'inventory' => 10000,
        ]);
        $response->assertJson(['message' => Message::WALLET_NAME_IS_REQUIRED]);
    }

    public function test_inventory_is_required()
    {
        $token = $this->generateToken($this->user);
        Gate::define('check-wallet-own', function () {
            return true;
        });
        $response = $this->callRequest('put', $this->url, [
            'Authorization' => 'Bearer ' . $token,
            'name' => $this->name,
        ]);
        $response->assertJson(['message' => Message::WALLET_INVENTORY_IS_REUQIRED]);
    }

    public function test_inventory_should_be_integer()
    {
        $token = $this->generateToken($this->user);
        Gate::define('check-wallet-own', function () {
            return true;
        });
        $response = $this->callRequest('put', $this->url, [
            'Authorization' => 'Bearer ' . $token,
            'name' => $this->name,
            'inventory' => 'string'
        ]);
        $response->assertJson(['message' => Message::WALLET_INVENTORY_SHOULD_BE_INTEGER]);
    }

    public function test_just_wallet_owner_can_update_wallet()
    {
        $token = $this->generateToken(User::factory()->create());
        $response = $this->callRequest('put', $this->url, [
            'Authorization' => 'Bearer ' . $token,
            'name'=> $this->name,
            'inventory'=> 0
        ]);
        $response->assertJson(['error' => Message::ONLY_WALLET_OWNER_CAN_GET_WALLET])
            ->assertStatus(403);
    }

    public function test_a_signed_in_owner_user_can_update_wallet()
    {
        $token = $this->generateToken($this->user);
        Gate::define('check-wallet-own', function () {
            return true;
        });
        $response = $this->callRequest('put', $this->url, [
            'Authorization' => 'Bearer ' . $token,
            'name' => 'Updated Name',
            'inventory' => 10000,
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('wallets', [
            'id' => $this->wallet->id,
            'name' => 'Updated Name',
            'inventory' => 10000,
        ]);
    }

    public function test_a_user_cant_update_a_wallet_with_duplicated_name()
    {
        $token = $this->generateToken($this->user);
        $wallet2 = Wallet::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Wallet2'
        ]);
        $response = $this->callRequest('put', $this->url,[
            'Authorization' => 'Bearer ' . $token,
            'name' => $wallet2->name,
            'inventory' => 10000
        ]);
        $response->assertJson(['message' => Message::WALLET_NAME_SHOULD_BE_UNIQUE]);
    }
}
