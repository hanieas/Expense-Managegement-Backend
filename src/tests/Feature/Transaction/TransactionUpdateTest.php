<?php

namespace Tests\Feature\Transaction;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Responders\Message;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TransactionUpdateTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser(1)[0];
        $this->wallet = $this->createWallet(1, ['user_id' => $this->user->id, 'inventory' => 200])[0];
        $this->category = $this->createCategory(1)[0];
        $this->amount = 10;
        $this->transaction = $this->createTransaction(1, [
            'user_id' => $this->user->id,
            'wallet_id' => $this->wallet->id,
            'amount' => $this->amount,
            'status' => '+',
        ])[0];
        $this->url = $this->transaction->path . '/' . $this->transaction->id;

        $this->method = 'put';
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

    public function test_wallet_inventory_should_be_updated_with_changing_amount()
    {
        Passport::actingAs($this->user);
        Gate::define('check-transaction-own', function () {
            return true;
        });
        $wallet = Wallet::find($this->wallet->id);
        $expectedInventory = $wallet->inventory - $this->amount + ($this->amount + 20);
        $response = $this->callRequest($this->method, $this->url, [
            'amount' => $this->amount + 20,
            'wallet_id' => $this->wallet->id,
            'status' => "+",
            'category_id' => $this->category->id,
        ]);
        $actualInventory = Wallet::find($this->wallet->id)->inventory;
        $response->assertStatus(200);
        $this->assertEquals($expectedInventory, $actualInventory);
    }

    public function test_wallet_inventory_should_be_updated_with_changing_status()
    {
        Passport::actingAs($this->user);
        Gate::define('check-transaction-own', function () {
            return true;
        });
        $wallet = Wallet::find($this->wallet->id);
        $expectedInventory = $wallet->inventory - (2*$this->amount);
        $response = $this->callRequest($this->method, $this->url, [
            'amount' => $this->amount,
            'wallet_id' => $this->wallet->id,
            'status' => "-",
            'category_id' => $this->category->id,
        ]);
        $actualInventory = Wallet::find($this->wallet->id)->inventory;
        $response->assertStatus(200);
        $this->assertEquals($expectedInventory, $actualInventory);
    }
}
