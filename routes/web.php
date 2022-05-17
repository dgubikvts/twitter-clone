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

Route::view('/', 'index');

Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
Route::post('/tweet', 'App\Http\Controllers\PostsController@tweet')->name('tweet');
Route::get('/tweet/{entity}', 'App\Http\Controllers\PostsController@viewTweet')->name('view.tweet');
Route::get('/{username}', 'App\Http\Controllers\HomeController@profile')->name('profile');
Route::post('/follow/{user}', 'App\Http\Controllers\HomeController@follow')->name('follow');
Route::post('/like', 'App\Http\Controllers\PostsController@like')->name('like');
Route::post('/comment', 'App\Http\Controllers\PostsController@comment')->name('comment');