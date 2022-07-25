<?php

namespace Tests\Feature\Wallet;

use App\Models\Currency;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;
use App\Responders\Message;
use Illuminate\Database\Eloquent\Collection;

class WalletDeleteTest extends TestCase
{
    use DatabaseMigrations;

    /** @var User */
    protected User $user;

    /** @var Wallet */
    protected Wallet $wallet;

    /** @var Collection */
    protected Collection $transactions;

    /** @var string */
    protected string $url, $name;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser(1)[0];
        $this->name = 'Wallet';
        $this->wallet = $this->createWallet(1)[0];
        $this->transactions = $this->createTransaction(10,['wallet_id'=>$this->wallet->id]);
        $this->url = $this->wallet->path. '/' . $this->wallet->id;
    }

    public function test_an_unautenticated_user_cant_delete_wallet()
    {
        $response = $this->callRequest('delete', $this->url, []);
        $response->assertJson(['message' => Message::ONLY_AUTHENTICATED_USER]);
    }

    public function test_just_wallet_owner_can_delete_wallet()
    {
        $token = $this->generateToken(User::factory()->create());
        $response = $this->callRequest('delete', $this->url, [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertJson(['error' => Message::ONLY_WALLET_OWNER_CAN_GET_WALLET])
            ->assertStatus(403);
    }

    public function test_a_signed_in_owner_user_can_delete_wallet()
    {

        $token = $this->generateToken($this->user);
        Gate::define('check-wallet-own', function () {
            return true;
        });
        $response = $this->callRequest('delete', $this->url, [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseCount('transactions',0);
        $this->assertSoftDeleted('wallets',[
            'id' => $this->wallet->id,
        ]);;
    }
}
