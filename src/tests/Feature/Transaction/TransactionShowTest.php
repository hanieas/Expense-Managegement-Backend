<?php

namespace Tests\Feature\Transaction;

use App\Models\Transaction;
use App\Models\User;
use App\Responders\Message;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TransactionShowTest extends TestCase
{
    use DatabaseMigrations;

    /** @var  Transaction */
    protected Transaction $transaction;

    /** @var string */
    protected string $url, $method;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:install');
        $this->user = $this->createUser(1)[0];
        $this->transaction = $this->createTransaction(1)[0];
        $this->url = $this->transaction->path . '/' . $this->transaction->id;
        $this->method = 'get';
    }

    public function test_an_unautenticated_user_cant_show_transaction()
    {
        $response = $this->callRequest('get', $this->url,);
        $response->assertJson(['message' => Message::ONLY_AUTHENTICATED_USER]);
    }

    public function test_just_wallet_owner_can_show_transaction()
    {
        $user = $this->createUser(1)[0];
        /** @var User */
        Passport::actingAs($user);
        $response = $this->callRequest(
            $this->method,
            $this->url,
        );
        $response->assertJson(['error' => Message::ONLY_TRANSACTION_OWNER_CAN_GET_IT])
            ->assertStatus(403);
    }

    public function test_a_signed_in_owner_user_can_get_transaction()
    {
        /** @var User */
        Passport::actingAs($this->user);
        Gate::define('check-transaction-own', function () {
            return true;
        });
        $response = $this->callRequest(
            $this->method,
            $this->url,
        );
        $response->assertStatus(200)
            ->assertJsonPath('data.amount', $this->transaction->amount)
            ->assertJsonPath('data.wallet_id', $this->transaction->wallet_id)
            ->assertJsonPath('data.status', $this->transaction->status)
            ->assertJsonPath('data.category_id', $this->transaction->category_id);
    }
}
