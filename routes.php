<?php

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

use Library\Route;

Route::get('/', 'TaskController@list');
Route::get('create', 'TaskController@create');
Route::get('edit', 'TaskController@edit');
Route::post('store', 'TaskController@store');
Route::put('update', 'TaskController@update');

//Login
Route::post('login', 'AdminController@login');
Route::get('logout', 'AdminController@logout');

//Api
Route::get('get-task', 'ApiController@getTask');
