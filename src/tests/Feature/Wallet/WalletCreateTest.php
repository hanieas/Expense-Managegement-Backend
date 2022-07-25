<?php

namespace Tests\Feature\Wallet;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Responders\Message;
use Laravel\Passport\Passport;

class WalletCreateTest extends TestCase
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
        $this->name = 'Wallet1';
        $this->wallet = $this->createWallet(1, [
            'user_id' => $this->user->id,
            'name' => $this->name
        ])[0];
        $this->url = $this->wallet->path;
        $this->correct_inventory = 10000;
        $this->incorrect_inventory = 'string';
        $this->new_name = 'New Wallet';
    }

    public function test_an_unautenticated_user_cant_create_wallet()
    {
        $response = $this->callRequest('post', $this->url);
        $response->assertJson(['message' => Message::ONLY_AUTHENTICATED_USER]);
    }

    public function test_required_fields()
    {
        Passport::actingAs($this->user);
        $response = $this->callRequest('post', $this->url);
        $response->assertInvalid([
            'name' => Message::WALLET_NAME_IS_REQUIRED
        ]);
    }

    public function test_inventory_should_be_integer()
    {
        Passport::actingAs($this->user);
        $response = $this->callRequest('post', $this->url, [
            'name' => $this->new_name,
            'inventory' => $this->incorrect_inventory
        ]);
        $response->assertJson(['message' => Message::WALLET_INVENTORY_SHOULD_BE_INTEGER]);
    }

    public function test_a_user_cant_create_a_wallet_with_duplicated_name()
    {
        Passport::actingAs($this->user);
        $response = $this->callRequest('post', $this->url, [
            'name' => $this->name,
        ]);
        $response->assertJson(['message' => Message::WALLET_NAME_SHOULD_BE_UNIQUE]);
    }
}
