<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//})->middleware('guest');

Route::get('/', 'IndexController@index');
Route::get('test', ['as' => 'test', 'uses' => 'TestController@index']);


Route::get('booking/{code}/{code1}/{code2}/{extra_bed_code1}/{dates_period}/{room_id?}', 'BookingController@index');
Route::get('basket_item/{id}', 'BasketItemController@index');
Route::post('basket_item/{id}', 'BasketItemController@createDogovor');
Route::get('basket_item/{bi_service_id}/{is_disabled}', 'BasketItemController@editDisabledStatus');

Route::get('order_info/{id}', 'DogovorInfoController@index');
Route::post('order_info/{id}', 'DogovorInfoController@createMessage');
Route::get('profile', 'ProfileController@index');
Route::post('profile', 'ProfileController@update');
Route::get('orders', 'DogovorsController@index');

Route::get('search', ['as' => 'search', 'uses' => 'SearchController@index']);
Route::get('searchDetailed', ['as' => 'search', 'uses' => 'SearchController@detailed']);


Route::get('about', 'AboutController@index')->name('about');
Route::get('registered', 'RegisteredController@index')->name('registered');
Route::get('contacts', 'ContactsController@index')->name('contacts');

//--- REPO ---
Route::get('voucher/{id}', 'Repo\VoucherController@index');
Route::get('invoice/{id}', 'Repo\InvoiceController@index');


Route::auth();

//Auth::routes(); //теже яйца что и Route::auth

// Activation user.
Route::get('activate/{id}/{token}', 'Auth\RegisterController@activation')->name('activation');


Route::get('/home', 'HomeController@index');