<?php

namespace Tests\Feature\Transaction;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Responders\Message;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TransactionCreateTest extends TestCase
{
    use DatabaseMigrations;

    /** @var Category */
    protected Category $category;

    /** @var User */
    protected User $user;

    /** @var Wallet */
    protected Wallet $wallet;

    /** @var Transaction */
    protected Transaction $transaction;

    /** @var string */
    protected string $url, $name, $token;

    /** @var int */
    protected int $amount;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:install');
        $this->user = $this->createUser(1)[0];
        $this->wallet = $this->createWallet(1)[0];
        $this->category = $this->createCategory(1)[0];
        $this->transaction = $this->createTransaction(1, ['wallet_id' => $this->wallet->id])[0];
        $this->url = $this->transaction->path;
        $this->amount = 10;
        $this->method = 'post';
    }

    public function test_an_unautenticated_user_cant_create_transaction()
    {
        $response = $this->callRequest($this->method, $this->url, []);
        $response->assertJson(['message' => Message::ONLY_AUTHENTICATED_USER]);
    }

    public function test_amount_is_required()
    {
        /** @var User */
        Passport::actingAs($this->user);
        $response = $this->callRequest($this->method, $this->url, [
            'wallet_id' => $this->wallet->id,
            'status' => Transaction::INCOME_SIGN,
            'category_id' => $this->category->id
        ]);
        $response->assertJson(['message' => Message::TRANSACTION_AMOUNT_IS_REQUIRED]);
    }

    public function test_amount_should_be_integer()
    {
        /** @var User */
        Passport::actingAs($this->user);
        $response = $this->callRequest($this->method, $this->url, [
            'amount' => 'string',
            'wallet_id' => $this->wallet->id,
            'status' => Transaction::INCOME_SIGN,
            'category_id' => $this->category->id
        ]);
        $response->assertJson(['message' => Message::TRANSACTION_AMOUNT_SHOUD_BE_INTEGER]);
    }

    public function test_wallet_id_is_required()
    {
        /** @var User */
        Passport::actingAs($this->user);
        $response = $this->callRequest($this->method, $this->url, [
            'amount' => $this->amount,
            'status' => Transaction::INCOME_SIGN,
            'category_id' => $this->category->id
        ]);
        $response->assertJson(['message' => Message::TRANSACTION_WALLET_ID_IS_REQUIRED]);
    }

    public function test_wallet_id_should_be_integer()
    {
        /** @var User */
        Passport::actingAs($this->user);
        $response = $this->callRequest($this->method, $this->url, [
            'amount' => $this->amount,
            'wallet_id' => 'string',
            'status' => Transaction::INCOME_SIGN,
            'category_id' => $this->category->id
        ]);
        $response->assertJson(['message' => Message::TRANSACTION_WALLET_ID_SHOUD_BE_INTEGER]);
    }

    public function test_wallet_id_should_exist()
    {
        /** @var User */
        Passport::actingAs($this->user);
        $response = $this->callRequest($this->method, $this->url, [
            'amount' => $this->amount,
            'wallet_id' => 100,
            'status' => Transaction::INCOME_SIGN,
            'category_id' => $this->category->id,
        ]);
        $response->assertJson(['message' => Message::TRANSACTION_WALLET_ID_IS_INVALID]);
    }

    public function test_category_id_should_be_integer()
    {
        /** @var User */
        Passport::actingAs($this->user);
        $response = $this->callRequest($this->method, $this->url, [
            'amount' => $this->amount,
            'wallet_id' => $this->wallet->id,
            'status' => Transaction::INCOME_SIGN,
            'category_id' => 'string'
        ]);
        $response->assertJson(['message' => Message::TRANSACTION_CATEGOEY_ID_SHOUD_BE_INTEGER]);
    }

    public function test_category_id_is_required()
    {
        /** @var User */
        Passport::actingAs($this->user);
        $response = $this->callRequest($this->method, $this->url, [
            'amount' => $this->amount,
            'wallet_id' => $this->wallet->id,
            'status' => Transaction::INCOME_SIGN,
        ]);
        $response->assertJson(['message' => Message::TRANSACTION_CATEGOEY_ID_IS_REQUIRED]);
    }

    public function test_category_id_should_exist()
    {
        /** @var User */
        Passport::actingAs($this->user);
        $response = $this->callRequest($this->method, $this->url, [
            'amount' => $this->amount,
            'wallet_id' => $this->wallet->id,
            'status' => Transaction::INCOME_SIGN,
            'category_id' => 1000
        ]);
        $response->assertJson(['message' => Message::TRANSACTION_CATEGORY_ID_IS_INVALID]);
    }

    public function test_status_is_required()
    {
        /** @var User */
        Passport::actingAs($this->user);
        $response = $this->callRequest($this->method, $this->url, [
            'amount' => $this->amount,
            'wallet_id' => $this->wallet->id,
            'category_id' => $this->category->id
        ]);
        $response->assertJson(['message' => Message::TRANSACTION_STATUS_IS_REQUIRED]);
    }

    public function test_status_should_be_valid()
    {
        /** @var User */
        Passport::actingAs($this->user);
        $response = $this->callRequest($this->method, $this->url, [
            'amount' => $this->amount,
            'wallet_id' => $this->wallet->id,
            'status' => 'status',
            'category_id' => $this->category->id
        ]);
        $response->assertJson(['message' => Message::TRANSACTION_STATUS_IS_INVALID]);
    }
}
