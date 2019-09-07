<?php

use Illuminate\Http\Request;

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

Route::post('login', 'API\UserController@login');
Route::get('refresh', 'API\UserController@refresh');
Route::post('register', 'API\UserController@register');

Route::group(
    [
        'middleware' => 'auth:api',
        'prefix'=>'v4'
    ],
    function () {
        Route::post('logout', 'API\UserController@refresh');
        Route::apiResource('entitlements', API\EntitlementsController::class);
        Route::apiResource('users', API\UsersController::class);
        Route::apiResource('wallets', API\WalletsController::class);
    }
);
