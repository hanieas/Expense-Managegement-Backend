<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Gate::define('check-wallet-own',function($user,$wallet){
            return $user->id === $wallet->user_id;
        });

        Gate::define('check-category-own',function($user,$category){
            return $user->id === $category->user_id;
        });

        Gate::define('check-transaction-own',function($user,$transaction){
            return $user->id === $transaction->user_id;
        });
    }
}
