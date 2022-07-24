<?php

namespace Tests\Feature\Wallet;

use App\Models\Currency;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Responders\Message;


class WalletCreateTest extends TestCase
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
        $this->name = 'Wallet1';
        $this->wallet = Wallet::factory()->create([
            'user_id' => $this->user->id,
            'name' => $this->name
        ]);
        $this->url = $this->wallet->path;
        $this->correct_inventory = 10000;
        $this->incorrect_inventory = 'string';
        $this->new_name = 'New Wallet';
    }

    public function test_an_unautenticated_user_cant_create_wallet()
    {
        $response = $this->callRequest('post', $this->url, ['name' => $this->name]);
        $response->assertJson(['message' => Message::ONLY_AUTHENTICATED_USER]);
    }

    public function test_name_is_required()
    {
        $token = $this->generateToken($this->user);
        $response = $this->callRequest('post',$this->url,[
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertJson(['message' => Message::WALLET_NAME_IS_REQUIRED]);
    }

    public function test_inventory_should_be_integer()
    {
        $token = $this->generateToken($this->user);
        $response = $this->callRequest('post',$this->url,[
            'Authorization' => 'Bearer ' . $token,
            'name' => $this->new_name,
            'inventory' => $this->incorrect_inventory
        ]);
        $response->assertJson(['message' => Message::WALLET_INVENTORY_SHOULD_BE_INTEGER]);
    }

    public function test_a_user_cant_create_a_wallet_with_duplicated_name()
    {
        $token = $this->generateToken($this->user);
        $response = $this->callRequest('post', $this->url,[
            'Authorization' => 'Bearer ' . $token,
            'name' => $this->name,
        ]);
        $response->assertJson(['message' => Message::WALLET_NAME_SHOULD_BE_UNIQUE]);
    }
}
