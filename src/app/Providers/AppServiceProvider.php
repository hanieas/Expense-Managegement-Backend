<?php

namespace App\Providers;

use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Models\User;
use App\Models\Wallet;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Responders\UserResponder;
use App\Responders\WalletResponder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // User
        $this->app->bind(UserRepository::class,function($app){
            return new UserRepository($app->make(User::class));
        });
        $this->app->bind(UserController::class,function($app){
            return new UserController($app->make(UserRepository::class),$app->make(UserResponder::class));
        });

        //Wallet
        $this->app->bind(WalletRepository::class,function($app){
            return new WalletRepository($app->make(Wallet::class));
        });
        $this->app->bind(WalletController::class,function($app){
            return new WalletController($app->make(WalletRepository::class),$app->make(WalletResponder::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
