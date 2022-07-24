<?php

namespace App\Providers;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Observers\TransactionObserver;
use App\Repositories\CategoryRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Responders\CategoryResponder;
use App\Responders\TransactionResponder;
use App\Responders\UserResponder;
use App\Responders\WalletResponder;
use Illuminate\Support\ServiceProvider;
use App\Repositories\TransactionRepository;

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
        $this->app->bind(UserRepository::class, function ($app) {
            return new UserRepository($app->make(User::class));
        });
        $this->app->bind(UserController::class, function ($app) {
            return new UserController($app->make(UserRepository::class), $app->make(UserResponder::class));
        });

        //Wallet
        $this->app->bind(WalletRepository::class, function ($app) {
            return new WalletRepository($app->make(Wallet::class));
        });
        $this->app->bind(WalletController::class, function ($app) {
            return new WalletController($app->make(WalletRepository::class), $app->make(WalletResponder::class));
        });

        // Category
        $this->app->bind(CategoryRepository::class, function ($app) {
            return new CategoryRepository($app->make(Category::class));
        });
        $this->app->bind(CategoryController::class, function ($app) {
            return new CategoryController($app->make(CategoryRepository::class), $app->make(CategoryResponder::class));
        });

        // Transaction
        $this->app->bind(TransactionRepository::class, function ($app) {
            return new TransactionRepository($app->make(Transaction::class));
        });
        $this->app->bind(TransactionController::class, function ($app) {
            return new TransactionController(
                $app->make(TransactionRepository::class),
                $app->make(TransactionResponder::class),
                $app->make(WalletRepository::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Transaction::observe(TransactionObserver::class);
    }
}
