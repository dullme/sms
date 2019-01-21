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

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/reset_password', 'Auth\ResetPasswordController@index')->name('reset_password');
Route::post('/reset_password', 'Auth\ResetPasswordController@resetPassword')->name('reset_password');
Route::get('/detail', 'HomeController@detail')->name('detail');
Route::get('/info/edit', 'HomeController@infoEdit')->name('info_edit');
Route::post('/info/edit', 'HomeController@saveInfoEdit')->name('info_edit');
Route::get('/info/withdraw', 'HomeController@infoWithdraw')->name('info_withdraw');
Route::post('/info/withdraw', 'HomeController@saveInfoWithdraw')->name('info_withdraw');
Route::get('/info/transfer', 'HomeController@infoTransfer')->name('info_transfer');
Route::post('/info/transfer', 'HomeController@saveInfoTransfer');
Route::get('/info/transaction', 'HomeController@infoTransaction')->name('info_transaction');
Route::get('/info/config', 'HomeController@config')->name('config');
Route::post('/info/config', 'HomeController@saveConfig')->name('config');
Route::post('/user/info', 'HomeController@searchUser');
Route::get('/user/device', 'HomeController@getMyDevice');
Route::post('/user/device', 'HomeController@setMyDevice');
Route::post('/user/send/message', 'HomeController@sendMessage');
