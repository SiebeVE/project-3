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


Route::get('/', function() {
    return redirect('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/book/add', 'BookController@getAdd');
Route::get('/book/add/finish', 'BookController@getAddFinish');
Route::get('/book/add/{bookId}', 'BookController@getAddDetail');
Route::post('/book/add/{bookId}', 'BookController@postAddDetail');