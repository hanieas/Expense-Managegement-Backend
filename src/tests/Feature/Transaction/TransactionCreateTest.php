<?php

namespace Tests\Feature\Transaction;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Responders\Message;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TransactionCreateTest extends TestCase
{
    use DatabaseMigrations;

    /** @var Category */
    protected Category $category;
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

        $this->user = $this->createUser(1)[0];
        $this->wallet = $this->createWallet(1,['inventory' => 200])[0];
        $this->category = $this->createCategory(1)[0];
        $this->transaction = $this->createTransaction(1, ['wallet_id' => $this->wallet->id,'amount'=>20])[0];
        $this->url = $this->transaction->path;
        $this->amount = 10;
        $this->method = 'post';
    }

    public function test_an_unautenticated_user_cant_create_transaction()
    {
        $response = $this->callRequest($this->method, $this->url, []);
        $response->assertJson(['message' => Message::ONLY_AUTHENTICATED_USER]);
    }

    public function test_required_fields()
    {
        Passport::actingAs($this->user);
        $response = $this->callRequest($this->method, $this->url, []);
        $response->assertInvalid([
            'amount' => Message::TRANSACTION_AMOUNT_IS_REQUIRED,
            'wallet_id' => Message::TRANSACTION_WALLET_ID_IS_REQUIRED,
            'category_id' => Message::TRANSACTION_CATEGOEY_ID_IS_REQUIRED,
            'status' => Message::TRANSACTION_STATUS_IS_REQUIRED,
        ]);
    }

    public function test_amount_should_be_integer()
    {
        Passport::actingAs($this->user);
        $response = $this->callRequest($this->method, $this->url, [
            'amount' => 'string',
            'wallet_id' => $this->wallet->id,
            'status' => Transaction::INCOME_SIGN,
            'category_id' => $this->category->id
        ]);
        $response->assertJson(['message' => Message::TRANSACTION_AMOUNT_SHOUD_BE_INTEGER]);
    }

    public function test_wallet_id_should_be_integer()
    {
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
        Passport::actingAs($this->user);
        $response = $this->callRequest($this->method, $this->url, [
            'amount' => $this->amount,
            'wallet_id' => $this->wallet->id,
            'status' => Transaction::INCOME_SIGN,
            'category_id' => 'string'
        ]);
        $response->assertJson(['message' => Message::TRANSACTION_CATEGOEY_ID_SHOUD_BE_INTEGER]);
    }

    public function test_category_id_should_exist()
    {
        Passport::actingAs($this->user);
        $response = $this->callRequest($this->method, $this->url, [
            'amount' => $this->amount,
            'wallet_id' => $this->wallet->id,
            'status' => Transaction::INCOME_SIGN,
            'category_id' => 1000
        ]);
        $response->assertJson(['message' => Message::TRANSACTION_CATEGORY_ID_IS_INVALID]);
    }

    public function test_status_should_be_valid()
    {
        Passport::actingAs($this->user);
        $response = $this->callRequest($this->method, $this->url, [
            'amount' => $this->amount,
            'wallet_id' => $this->wallet->id,
            'status' => 'status',
            'category_id' => $this->category->id
        ]);
        $response->assertJson(['message' => Message::TRANSACTION_STATUS_IS_INVALID]);
    }

    public function test_wallet_inventory_should_increase_with_income_transaction()
    {
        Passport::actingAs($this->user);
        $wallet = $this->createWallet(1,['inventory' => 200])[0];
        $expectedInventory = $wallet->inventory+$this->amount;
        $response = $this->callRequest($this->method, $this->url, [
            'amount' => $this->amount,
            'wallet_id' => $wallet->id,
            'status' => "+",
            'category_id' => $this->category->id
        ]);
        $actualInventory = Wallet::find($wallet->id)->inventory;
        $response->assertStatus(200);
        $this->assertEquals($expectedInventory, $actualInventory);
    }

    public function test_wallet_inventory_should_decrease_with_expense_transaction()
    {
        Passport::actingAs($this->user);
        $wallet = $this->createWallet(1,['inventory' => 200])[0];
        $expectedInventory = $wallet->inventory-$this->amount;
        $response = $this->callRequest($this->method, $this->url, [
            'amount' => $this->amount,
            'wallet_id' => $wallet->id,
            'status' => "-",
            'category_id' => $this->category->id
        ]);
        $actualInventory = Wallet::find($wallet->id)->inventory;
        $response->assertStatus(200);
        $this->assertEquals($expectedInventory, $actualInventory);
    }

    public function test_wallet_inventory_should_be_greater_than_expense_transaction()
    {
        Passport::actingAs($this->user);
        $wallet = $this->createWallet(1,['inventory' => 5])[0];
        $response = $this->callRequest($this->method, $this->url, [
            'amount' => $this->amount,
            'wallet_id' => $wallet->id,
            'status' => "-",
            'category_id' => $this->category->id
        ]);
        $response->assertStatus(422)->assertJson(['error'=>Message::TRANSACTION_AMOUNT_ERROR]);
    }
}
