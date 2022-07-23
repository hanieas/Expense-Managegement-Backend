<?php

namespace App\Providers;

use App\Http\Controllers\UserController;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Responders\UserResponder;
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
