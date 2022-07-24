<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $randomNote = $this->faker->boolean();

        return [
            'amount' => $this->faker->numberBetween(1,100000),
            'user_id' => User::inRandomOrder()->first(),
            'wallet_id' => Wallet::inRandomOrder()->first(),
            'status' => $this->faker->randomElement([Transaction::INCOME_SIGN,Transaction::EXPENSE_SIGN]),
            'note' => $randomNote ? $this->faker->sentence() : null,
            'date' => $this->faker->date(),
            'category_id' => Category::inRandomOrder()->first(),
        ];
    }
}
