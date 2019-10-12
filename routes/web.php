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

Route::get('/', 'WelcomeController@index')->name('welcome');

Auth::routes([
    'register' => false,
]);

Route::get('/otp', 'Auth\SecondFactorController@index')->name('otp');
Route::post('/otp', 'Auth\LoginController@loginSecondFactorOneTimePassword');

Route::get('/reauthenticate', 'Auth\ReauthenticateController@index')->name('reauthenticate');
Route::post('/reauthenticate', 'Auth\ReauthenticateController@reauthenticate');

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('companies', 'CompanyController');
Route::resource('projects', 'ProjectController');
