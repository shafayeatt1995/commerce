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

Route::group(['middleware' => 'api', 'namespace' => 'App\Http\Controllers\Api\Admin'], function () {

    //Brand Controller
    Route::post('brand', 'BrandController@index');
    Route::get('brand-list', 'BrandController@brandList');
    Route::post('create-brand', 'BrandController@createBrand');
    Route::post('update-brand/{brand}', 'BrandController@updateBrand');
    Route::post('delete-brand/{brand}', 'BrandController@deleteBrand');

    //Category Controller
    Route::post('category', 'CategoryController@index');
    Route::get('category-list', 'CategoryController@categoryList');
    Route::post('create-category', 'CategoryController@createCategory');
    Route::post('update-category/{category}', 'CategoryController@updateCategory');
    Route::post('delete-category/{category}', 'CategoryController@deleteCategory');
});
