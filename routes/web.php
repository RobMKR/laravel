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


// Auth middleware
Route::group(['middleware' => ['auth']], function () {
    Route::get('/books/addBook', 'BooksController@addBook');
    Route::post('/books/addBook', 'BooksController@addBook');
    Route::get('/books/editBooks', 'BooksController@editBooks');
    Route::post('/books/editBooks', 'BooksController@editBooks');
});

// Auth + Admin Middlwares
Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/admin', 'AdminController@index');
    Route::get('/admin/editUser/{id}', 'AdminController@editUser');
    Route::get('/admin/deleteUser/{id}', 'AdminController@deleteUser');
    Route::get('/admin/superFeatures', 'AdminController@superFeatures');
    Route::post('/admin/editUser/{id}', 'AdminController@editUser');
    Route::post('/admin/deleteUser/{id}', 'AdminController@deleteUser');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
