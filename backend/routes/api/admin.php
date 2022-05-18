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

    //Sub Category Controller
    Route::post('sub-category', 'SubCategoryController@index');
    Route::get('sub-category-list', 'SubCategoryController@categoryList');
    Route::post('create-sub-category', 'SubCategoryController@createCategory');
    Route::post('update-sub-category/{sub_category}', 'SubCategoryController@updateCategory');
    Route::post('delete-sub-category/{sub_category}', 'SubCategoryController@deleteCategory');

    //Size Controller
    Route::post('size', 'SizeController@index');
    Route::get('size-list', 'SizeController@sizeList');
    Route::post('create-size', 'SizeController@createSize');
    Route::post('update-size/{size}', 'SizeController@updateSize');
    Route::post('delete-size/{size}', 'SizeController@deleteSize');

    //Material Controller
    Route::post('material', 'MaterialController@index');
    Route::get('material-list', 'MaterialController@materialList');
    Route::post('create-material', 'MaterialController@createMaterial');
    Route::post('update-material/{material}', 'MaterialController@updateMaterial');
    Route::post('delete-material/{material}', 'MaterialController@deleteMaterial');

    //Photo Controller
    Route::post('photo', 'PhotoController@index');
    Route::post('upload-photo', 'PhotoController@uploadPhoto');
    Route::post('delete-photo/{photo}', 'PhotoController@deletePhoto');
});
