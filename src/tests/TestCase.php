<?php

namespace Tests;

use App\Models\Category;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @param  array $attributes
     * @return mixed
     */
    protected function callRequest($method = 'post', string $url, array $attributes = []): mixed
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->{$method}($url, $attributes);
        return $response;
    }


    protected function createCurrency($count = 1)
    {
        return Currency::factory($count)->create();
    }

    protected function createUser($count, $attributes = [])
    {
        if (!in_array('currency_id', $attributes)) {
            $currency = Currency::factory()->create();
        }
        return User::factory($count)->create(['currency_id' => $currency->id]);
    }

    protected function createCategory($count, $attributes = [])
    {
        return Category::factory($count)->create($attributes);
    }

    protected function createWallet($count, $attributes = [])
    {
        return Wallet::factory($count)->create($attributes);
    }

    protected function createTransaction($count, $attributes = [])
    {
        if (!in_array('category_id', $attributes)) {
            Category::factory()->create();
        }
        if (!in_array('wallet_id', $attributes)) {
            Wallet::factory()->create();
        }
        return Transaction::factory($count)->create($attributes);
    }
}
