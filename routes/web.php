<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/registrations','RegistrationController@index');
Route::get('/registrations/table','RegistrationController@getRegistrations');
Route::get('/registrations/graph','RegistrationController@graph');
Route::get('/registrations/register','RegistrationController@register');
Route::post('/registrations/register','RegistrationController@register');
Route::get('/users/profile','UsersController@edit')->name('profile.edit');
Route::patch('/users/profile','UsersController@update')->name('profile.update');
Route::delete('/users/profile','UsersController@destroy');
