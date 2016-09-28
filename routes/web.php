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

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

// Admin Middlwares
Route::group(['middleware' => ['admin']], function () {
    Route::get('/admin', 'AdminController@index');
    Route::get('/admin/editUser/{id}', 'AdminController@editUser');
    Route::get('/admin/deleteUser/{id}', 'AdminController@deleteUser');
    Route::get('/admin/departments', 'AdminController@departments');
    Route::get('/admin/addDepartment', 'AdminController@addDepartment');
    Route::post('/admin/addDepartment', 'AdminController@addDepartment');
    Route::get('/admin/editDepartment/{id}', 'AdminController@editDepartment');
    Route::post('/admin/editDepartment/{id}', 'AdminController@editDepartment');
    Route::get('/admin/deleteDepartment/{id}', 'AdminController@deleteDepartment');
    Route::post('/admin/deleteDepartment/{id}', 'AdminController@deleteDepartment');
    Route::post('/admin/editUser/{id}', 'AdminController@editUser');
    Route::post('/admin/deleteUser/{id}', 'AdminController@deleteUser');
});

// Add/Edit User Tickets
Route::group(['middleware' => ['ticket']], function () {
    Route::get('/tickets/add', 'TicketsController@add');
    Route::post('/tickets/add', 'TicketsController@add');
    Route::get('/tickets/edit/{id}', 'TicketsController@edit');
});

Route::get('/home', 'HomeController@index');
Route::get('/tickets', 'HomeController@tickets');
Route::get('/departments', 'HomeController@departments');
Route::post('sendmessage', 'HomeController@sendMessage');
