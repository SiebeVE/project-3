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


Route::get('/', 'HomeController@index')->name('home');

Auth::routes();


Route::get('/notifications', 'HomeController@getNotifications')->name('notifications');

// User books routes
Route::get('/book/add/new', 'BookController@getAddNew')->name('book.add.new');
Route::get('/book/add/finish', 'BookController@getAddFinish');
Route::get('/book/add', 'BookController@getAdd')->name('book.add');
Route::get('/book/edit', 'BookController@edit')->name('book.edit');
Route::get('/book/remove', 'BookController@remove')->name('book.remove');
Route::get('/my-books', 'BookController@index')->name('book.index');
Route::get('/book/add/{bookId}', 'BookController@getAddDetail');
Route::post('/book/add/{bookId}', 'BookController@postAddDetail');

Route::get('/book/transaction/{transaction}', 'BookController@getTransaction')->name('book.transaction');

// Book transaction routes
Route::get('/book/transaction/{type}/{bookUser}', 'BookController@getBuyOrBorrow')->name('book.buyorborrow');
Route::get('/book/transaction/{type}/confirm/{transaction}', 'BookController@getConfirmRecieved');
Route::get('/book/transaction/borrow/confirm/giveBack/{transaction}', 'BookController@getConfirmGiveBack');

// Library routes
Route::get('/book/find', 'BookController@getFind')->name('book.find');
Route::get('/book/{book}', 'BookController@view')->name('book.view');
Route::get('library', 'LibraryController@index')->name('library');


// Profile routes
Route::get('user/profile', 'UserController@view')->name('user.view');
Route::get('user/edit', 'UserController@edit')->name('user.edit');
Route::put('user/edit', 'UserController@update')->name('user.update');
