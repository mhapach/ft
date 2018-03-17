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

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:api');
Route::get('user', 'Api\UserController@index')->middleware('auth:api');
Route::get('countries', 'Api\CountriesController@index');
Route::get('cities/{country_id}', 'Api\CitiesController@index');
Route::get('resorts/{country_id}', 'Api\ResortsController@index');
Route::get('hotels/{country_id}/{city_id?}/{resort_id?}', 'Api\HotelsController@index');
Route::get('pansion/{country_id}/{city_id?}/{resort_id?}/{hotel_id?}', 'Api\PansionController@index');
Route::get('stars/{country_id}/{city_id?}/{resort_id?}/{hotel_id?}', 'Api\StarsController@index');
Route::get('search', 'Api\SearchHotelsController@index');