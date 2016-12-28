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

Route::get('/', 'HomeController@login');


Auth::routes();

// Admin Middlwares
Route::group(['middleware' => ['admin']], function () {
    Route::get('/admin', 'AdminController@index');

    /* Account */
    Route::get('/admin/editAccount', 'AdminController@editAccount');
    Route::post('/admin/editAccount', 'AdminController@editAccount');

    /* User */
    Route::get('/admin/addUser', 'AdminController@addUserGet');
    Route::post('/admin/addUser', 'AdminController@addUserPost');
    Route::get('/admin/editUser/{id}', 'AdminController@editUser');
    Route::post('/admin/editUser/{id}', 'AdminController@editUser');
    Route::get('/admin/deleteUser/{id}', 'AdminController@deleteUser');

    /* Shops */

    Route::get('/admin/shops', 'ShopController@index');
    Route::get('/admin/addShop', 'ShopController@addGet');
    Route::post('/admin/addShop', 'ShopController@addPost');
    Route::get('/admin/editShop/{id}', 'ShopController@edit');
    Route::post('/admin/editShop/{id}', 'ShopController@edit');
    Route::get('/admin/deleteShop/{id}', 'ShopController@delete');

    /* Gifts */
    Route::get('/admin/gifts', 'GiftController@index');
    Route::get('/admin/addGift', 'GiftController@addGet');
    Route::post('/admin/addGift', 'GiftController@addPost');
    Route::get('/admin/editGift/{id}', 'GiftController@edit');
    Route::post('/admin/editGift/{id}', 'GiftController@edit');
    Route::get('/admin/deleteGift/{id}', 'GiftController@delete');
    Route::get('/admin/assignGift', 'GiftController@assignGet');
    Route::post('/admin/assignGift', 'GiftController@assignPost');

    /* Counts */
    Route::get('/admin/giftShops', 'CountController@giftShops');

    /* Consumers */
    Route::get('/admin/consumerSlips', 'CountController@consumerSlips');
    Route::get('/admin/consumerAvailableGifts', 'CountController@consumerAvailableGifts');
    Route::get('/admin/consumerTakenGifts', 'CountController@consumerTakenGifts');
    Route::post('/admin/getGiftsAjax', 'CountController@getGiftsAjax');

    Route::get('/admin/sendSmsSandbox', 'CountController@sendSmsSendbox');
    Route::get('/admin/sendSmsReal', 'CountController@sendSmsReal');

});

Route::group(['middleware' => ['slip']], function () {
    Route::get('/slip/home', 'HomeController@index');
    Route::post('/addSlip', 'HomeController@addSlip');
});

Route::group(['middleware' => ['gift']], function(){
    Route::get('/gift/home', 'HomeController@getGifts');
    Route::post('/getClient', 'HomeController@getClient');
});
