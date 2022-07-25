<?php

namespace Tests\Feature\Wallet;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;
use App\Responders\Message;
use Laravel\Passport\Passport;

class WalletShowTest extends TestCase
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
        $this->wallet = Wallet::factory()->create([
            'user_id' => $this->user->id,
            'name' => $this->name
        ]);
        $this->url = $this->wallet->path . '/' . $this->wallet->id;
    }

    public function test_an_unautenticated_user_cant_show_wallet()
    {
        $response = $this->callRequest('get', $this->url,);
        $response->assertJson(['message' => Message::ONLY_AUTHENTICATED_USER]);
    }

    public function test_just_wallet_owner_can_show_wallet()
    {
        Passport::actingAs($this->user);
        $response = $this->callRequest(
            'get',
            $this->url
        );
        $response->assertJson(['error' => Message::ONLY_WALLET_OWNER_CAN_GET_WALLET])
            ->assertStatus(403);
    }

    public function test_a_signed_in_owner_user_can_get_wallet()
    {
        Passport::actingAs($this->user);
        Gate::define('check-wallet-own', function () {
            return true;
        });
        $response = $this->callRequest(
            'get',
            $this->url
        );
        $response->assertStatus(200)
            ->assertJsonPath('data.name', $this->wallet->name)
            ->assertJsonPath('data.inventory', $this->wallet->inventory);
    }
}
