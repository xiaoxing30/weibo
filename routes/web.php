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

Route::get('/','StaticPagesController@home')->name('home');
Route::get('/help','StaticPagesController@help')->name('help');
Route::get('/about','StaticPagesController@about')->name('about');

Route::get('signup','UsersController@create')->name('signup');

Route::resource('users','UsersController');
/*
 * 上面代码将等同于：
ROUTE::GET('/USERS', 'USERSCONTROLLER@INDEX')->NAME('USERS.INDEX');
ROUTE::GET('/USERS/CREATE', 'USERSCONTROLLER@CREATE')->NAME('USERS.CREATE');
ROUTE::GET('/USERS/{USER}', 'USERSCONTROLLER@SHOW')->NAME('USERS.SHOW');
ROUTE::POST('/USERS', 'USERSCONTROLLER@STORE')->NAME('USERS.STORE');
ROUTE::GET('/USERS/{USER}/EDIT', 'USERSCONTROLLER@EDIT')->NAME('USERS.EDIT');
ROUTE::PATCH('/USERS/{USER}', 'USERSCONTROLLER@UPDATE')->NAME('USERS.UPDATE');
ROUTE::DELETE('/USERS/{USER}', 'USERSCONTROLLER@DESTROY')->NAME('USERS.DESTROY');
*/

Route::get('login','SessionsController@create')->name('login');
Route::post('login','SessionsController@store')->name('login');
Route::delete('logout','SessionsController@destroy')->name('logout');

Route::get('signup/confirm/{token}','UsersController@confirmEmail')->name('confirm_email');

Route::get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}','Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset','Auth\ResetPasswordController@reset')->name('password.update');

Route::resource('statuses','StatusesController',['only'=>['store','destroy']]);

Route::get('/users/{user}/followings', 'UsersController@followings')->name('users.followings');
Route::get('/users/{user}/followers', 'UsersController@followers')->name('users.followers');

Route::post('/users/followers/{user}','FollowersController@store')->name('followers.store');
Route::delete('/users/followers/{user}','FollowersController@destroy')->name('followers.destroy');
