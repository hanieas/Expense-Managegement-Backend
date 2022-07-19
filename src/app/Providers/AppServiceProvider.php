<?php

namespace App\Providers;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Responders\UserResponder;
use App\UseCase\User\UserLoginHandler;
use App\UseCase\User\UserSignUpHandler;
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
        $this->app->tag([UserRepository::class,UserSignUpHandler::class],'user');
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
