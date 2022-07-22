<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Wallet;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Responders\UserResponder;
use App\Responders\WalletResponder;
use App\UseCase\User\UserLoginHandler;
use App\UseCase\User\UserLogoutHandler;
use App\UseCase\User\UserSignUpHandler;
use App\UseCase\Wallet\WalletStoreHandler;
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
        $this->app->bind(UserSignUpHandler::class,function($app){
            return new UserSignUpHandler($app->make(UserRepository::class),$app->make(UserResponder::class));
        });
        $this->app->bind(UserLoginHandler::class,function($app){
            return new UserLoginHandler($app->make(UserRepository::class),$app->make(UserResponder::class));
        });
        $this->app->bind(UserLogoutHandler::class,function($app){
            return new UserLogoutHandler($app->make(UserRepository::class),$app->make(UserResponder::class));
        });
        $this->app->tag([UserRepository::class,UserSignUpHandler::class,UserLoginHandler::class,UserLogoutHandler::class],'user');

        //Wallet 
        $this->app->bind(WalletRepository::class,function($app){
            return new WalletRepository($app->make(Wallet::class));
        });
        $this->app->bind(WalletStoreHandler::class,function($app){
            return new WalletStoreHandler($app->make(WalletRepository::class),$app->make(WalletResponder::class));
        });
        $this->app->tag([WalletRepository::class,WalletStoreHandler::class],'wallet');
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
