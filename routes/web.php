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

    /* Account */
    Route::get('/admin/editAccount', 'AdminController@editAccount');
    Route::post('/admin/editAccount', 'AdminController@editAccount');

    /* User */
    Route::get('/admin/editUser/{id}', 'AdminController@editUser');
    Route::get('/admin/deleteUser/{id}', 'AdminController@deleteUser');

    /* Departments */
    Route::get('/admin/departments', 'AdminController@departments');
    Route::get('/admin/addDepartment', 'AdminController@addDepartment');
    Route::post('/admin/addDepartment', 'AdminController@addDepartment');
    Route::get('/admin/editDepartment/{id}', 'AdminController@editDepartment');
    Route::post('/admin/editDepartment/{id}', 'AdminController@editDepartment');
    Route::get('/admin/deleteDepartment/{id}', 'AdminController@deleteDepartment');
    Route::post('/admin/deleteDepartment/{id}', 'AdminController@deleteDepartment');

    /* Tickets */
    Route::get('/admin/tickets', 'AdminController@tickets');
    Route::get('/admin/tickets/manage/{id}', 'AdminController@manageTicket');
});

// Add/Edit User Tickets
Route::group(['middleware' => ['ticket']], function () {
    Route::get('/tickets/add', 'TicketsController@add');
    Route::post('/tickets/add', 'TicketsController@add');
    Route::get('/tickets/edit/{id}', 'TicketsController@edit');
    Route::post('/tickets/edit/{id}', 'TicketsController@edit');
    Route::get('/tickets/delete/{id}', 'TicketsController@delete');
    Route::post('/tickets/delete/{id}', 'TicketsController@delete');
});

// Department Administration
Route::group(['middleware' => ['department']], function () {
    Route::get('/departments/manage', 'DepartmentsController@manage');
    Route::get('/departments/tickets', 'DepartmentsController@tickets');
    Route::get('/departments/staff', 'DepartmentsController@staff');
});

/* Home Controller */
Route::get('/home', 'HomeController@index');
Route::get('/tickets', 'HomeController@tickets');
Route::get('/departments', 'HomeController@departments');
Route::post('sendmessage', 'HomeController@sendMessage');
