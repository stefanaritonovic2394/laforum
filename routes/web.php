<?php

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

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'ThreadController@index')->name('threads.index');
Route::get('/threads', 'ThreadController@index')->name('threads.index');
Route::get('/threads/create', 'ThreadController@create')->name('threads.create');
Route::get('/threads/search', 'SearchController@show')->name('searches.show');
Route::get('/threads/{channel}/{thread}', 'ThreadController@show')->name('threads.show');
Route::patch('/threads/{channel}/{thread}', 'ThreadController@update')->name('threads.update');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy');
Route::post('/threads', 'ThreadController@store')->middleware('must-be-confirmed')->name('threads.store');
Route::get('/threads/{channel}', 'ThreadController@index');
//Route::patch('/threads/{channel}/{thread}', 'ThreadController@update')->name('threads.update');
Route::post('/locked-threads/{thread}', 'LockedThreadController@store')->name('locked-threads.store')->middleware('admin');
Route::delete('/locked-threads/{thread}', 'LockedThreadController@destroy')->name('locked-threads.destroy')->middleware('admin');

Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store')->name('replies.store');
Route::patch('/replies/{reply}', 'ReplyController@update');
Route::delete('/replies/{reply}', 'ReplyController@destroy')->name('replies.destroy');

Route::post('/replies/{reply}/best', 'BestReplyController@store')->name('best-replies.store');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@destroy')->middleware('auth');
//Route::resource('threads', 'ThreadController');

Route::post('/replies/{reply}/favorites', 'FavoriteController@store');
Route::delete('/replies/{reply}/favorites', 'FavoriteController@destroy');

Route::get('/profiles/{user}', 'ProfileController@show')->name('profile.show');
Route::get('/profiles/{user}/notifications', 'UserNotificationController@index');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationController@destroy');

Route::get('/register/confirm', 'Auth\RegisterConfirmationController@index')->name('register.confirm');
Route::get('api/users', 'Api\UsersController@index');
Route::post('api/users/{user}/avatar', 'Api\UserAvatarController@store')->middleware('auth')->name('avatar');
