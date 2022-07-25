<?php

namespace Tests\Feature\Transaction;

use App\Models\User;
use App\Models\Wallet;
use App\Responders\Message;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TransactionListTest extends TestCase
{
    use DatabaseMigrations;

    protected User $user;

    protected Wallet $wallet;

    protected Collection $transactions;

    protected string $url, $method;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:install');
        $this->user = $this->createUser(1)[0];
        $this->wallet = $this->createWallet(1, ['user_id' => $this->user->id])[0];
        $this->transactions = $this->createTransaction(10, [
            'user_id' => $this->user->id,
            'wallet_id' => $this->wallet->id
        ]);
        $this->url = $this->transactions[0]->path;
        $this->method = 'get';
    }

    public function test_an_unautenticated_user_cant_get_transaction_list()
    {
        $response = $this->callRequest('get', $this->url,);
        $response->assertJson(['message' => Message::ONLY_AUTHENTICATED_USER]);
    }

    public function test_a_signed_in_owner_user_can_get_list_of_wallets()
    {
        Passport::actingAs($this->user);
        $response = $this->callRequest(
            $this->method,
            $this->url,
        );
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('meta')
                ->has('data.items', 10));
    }
}
