<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PhonebookController@index');

Route::post('/add', 'PhonebookController@addRecord');

Route::post('/modify', 'PhonebookController@modifyRecord');

Route::post('/delete', 'PhonebookController@deleteRecord');

