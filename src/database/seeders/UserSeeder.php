<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoriesList = config('global.categoriesList');

        $users = User::factory()->count(30)->create();

        for ($i = 0; $i < count($users); $i++) {
            $user = $users[$i];
            for ($category = 0; $category < count($categoriesList); $category++) {
                Category::factory()->create([
                    'name' => $categoriesList[$category],
                    'user_id' => $user->id,
                ]);
            }

            Wallet::factory()->create([
                'user_id' => $user->id,
                'name' => 'Main Wallet',
                'inventory' => 0,
            ]);
        }
    }
}
