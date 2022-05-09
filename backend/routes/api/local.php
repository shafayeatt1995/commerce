<?php

use Illuminate\Http\Request;
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

Route::group(['middleware' => 'api', 'namespace' => 'App\Http\Controllers\Api\Local'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('dashboard-login', 'AuthController@dashboardLogin');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('user', 'AuthController@user');
});

Route::group(['namespace' => 'App\Http\Controllers\Api\Local'], function () {
    Route::get('app', 'AppController@app');
});
