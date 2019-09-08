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

Route::group(
    [
        'prefix'=>'v4'
    ],
    function () {
        Route::group(
            [
                'prefix' => 'users'
            ],
            function () {
                Route::post('login', 'API\UsersController@login');
                Route::get('refresh', 'API\UsersController@refresh');
                Route::post('register', 'API\UsersController@register');
            }
        );

        Route::group(
            [
                'middleware' => 'auth:api',
                'prefix'=>'users'
            ],
            function () {
                Route::post('logout', 'API\UsersController@refresh');
                Route::apiResource('entitlements', API\EntitlementsController::class);
                Route::apiResource('users', API\UsersController::class);
                Route::apiResource('wallets', API\WalletsController::class);
            }
        );
    }
);
