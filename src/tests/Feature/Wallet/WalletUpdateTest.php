<?php

namespace Tests\Feature\Wallet;

use App\Models\Currency;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;
use App\Responders\Message;
use Laravel\Passport\Passport;

class WalletUpdateTest extends TestCase
{
    use DatabaseMigrations;

    /** @var User */
    protected User $user;

    /** @var Wallet */
    protected Wallet $wallet;

    /** @var string */
    protected string $url, $name, $correct_inventory, $incorrect_inventory, $new_name;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser(1)[0];
        $this->name = 'Wallet';
        $this->wallet = $this->createWallet(1, ['user_id' => $this->user->id])[0];
        $this->url = $this->wallet->path . '/' . $this->wallet->id;
    }

    public function test_an_unautenticated_user_cant_update_wallet()
    {
        $response = $this->callRequest('put', $this->url, []);
        $response->assertJson(['message' => Message::ONLY_AUTHENTICATED_USER]);
    }

    public function test_required_fields()
    {
        Passport::actingAs($this->user);
        Gate::define('check-wallet-own', function () {
            return true;
        });
        $response = $this->callRequest('put', $this->url);
        $response->assertInvalid([
            'name' => Message::WALLET_NAME_IS_REQUIRED,
            'inventory' => Message::WALLET_INVENTORY_IS_REUQIRED
        ]);
    }

    public function test_inventory_should_be_integer()
    {
        Passport::actingAs($this->user);
        Gate::define('check-wallet-own', function () {
            return true;
        });
        $response = $this->callRequest('put', $this->url, [
            'name' => $this->name,
            'inventory' => 'string'
        ]);
        $response->assertInvalid(['inventory' => Message::WALLET_INVENTORY_SHOULD_BE_INTEGER]);
    }

    public function test_just_wallet_owner_can_update_wallet()
    {
        Passport::actingAs($this->user);
        $response = $this->callRequest('put', $this->url, [
            'name' => $this->name,
            'inventory' => 0
        ]);
        $response->assertJson(['error' => Message::ONLY_WALLET_OWNER_CAN_GET_WALLET])
            ->assertStatus(403);
    }

    public function test_a_signed_in_owner_user_can_update_wallet()
    {
        Passport::actingAs($this->user);
        Gate::define('check-wallet-own', function () {
            return true;
        });
        $response = $this->callRequest('put', $this->url, [
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
        Passport::actingAs($this->user);
        $wallet2 = Wallet::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Wallet2'
        ]);
        $response = $this->callRequest('put', $this->url, [
            'name' => $wallet2->name,
            'inventory' => 10000
        ]);
        $response->assertJson(['message' => Message::WALLET_NAME_SHOULD_BE_UNIQUE]);
    }
}
