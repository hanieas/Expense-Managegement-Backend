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

class TransactionDeleteTest extends TestCase
{
    use DatabaseMigrations;

    /** @var User */
    protected User $user;

    /** @var Transaction */
    protected Transaction $wallet;

    /** @var string */
    protected string $url, $name;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:install');
        $this->user = $this->createUser(1)[0];
        $this->transactions = $this->createTransaction(1)[0];
        $this->url = $this->transactions->path . '/' . $this->transactions->id;
        $this->method = 'delete';
    }

    public function test_an_unautenticated_user_cant_delete_transaction()
    {
        $response = $this->callRequest($this->method, $this->url, []);
        $response->assertJson(['message' => Message::ONLY_AUTHENTICATED_USER]);
    }

    public function test_just_transaction_owner_can_delete_transaction()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->callRequest($this->method, $this->url);
        $response->assertJson(['error' => Message::ONLY_TRANSACTION_OWNER_CAN_GET_IT])
            ->assertStatus(403);
    }

    public function test_a_signed_in_owner_user_can_delete_transaction()
    {
        Passport::actingAs($this->user);
        Gate::define('check-transaction-own', function () {
            return true;
        });
        $response = $this->callRequest($this->method, $this->url);
        $response->assertStatus(200);
        $this->assertDatabaseCount('transactions', 0);
    }
}
