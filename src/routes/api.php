<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => env('VERSION')],function()
{
    Route::group(['middleware' => ['guest:api']],function()
    {
        Route::post('/user/signup', [UserController::class, 'signup']);
        Route::post('/user/login', [UserController::class, 'login']);
    });

    Route::group(['middleware' => ['auth:api','getUser']],function()
    {
        Route::post('/user/logout',[UserController::class,'logout']);
        Route::apiResource('/wallets',WalletController::class);
    });
});
