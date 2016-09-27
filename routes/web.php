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

// Auth + Books Middleware

Route::group(['middleware' => ['auth','book']], function () {
    Route::get('/books/addBook', 'BooksController@addBook');
    Route::post('/books/addBook', 'BooksController@addBook');
    Route::get('/books/editBook/{id}', 'BooksController@editBook');
    Route::post('/books/editBook/{id}', 'BooksController@editBook');
    Route::get('/books/deleteBook/{id}', 'BooksController@deleteBook');
});

// Auth + Admin Middlwares
Route::group(['middleware' => ['auth','admin']], function () {
    Route::get('/admin', 'AdminController@index');
    Route::get('/admin/editUser/{id}', 'AdminController@editUser');
    Route::get('/admin/deleteUser/{id}', 'AdminController@deleteUser');
    Route::get('/admin/superFeatures', 'AdminController@superFeatures');
    Route::post('/admin/editUser/{id}', 'AdminController@editUser');
    Route::post('/admin/deleteUser/{id}', 'AdminController@deleteUser');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/chat', 'HomeController@chat');
Route::post('sendmessage', 'HomeController@sendMessage');
