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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/user-profile/{user}', 'ProfileController@showProfile');
Route::get('/user-profile/{user}/edit', 'ProfileController@edit');
Route::put('/user-profile/{user}/update', 'ProfileController@update');
Route::put('/user-profile/{user}/change-password', 'ProfileController@changePassword');
Route::delete('/user-profile/{user}/destroy', 'ProfileController@destroy');

Route::get('/posts/category/{category}', 'PostsController@showCategory');
Route::get('/posts/keyword/{keyword}', 'PostsController@showKeyword');
Route::post('/posts/{post}/like', 'PostsController@like');
Route::post('/posts/{post}/addcomment', 'PostsController@addComment');
Route::delete('/posts/{post}/deletecomment/{comment}', 'PostsController@deleteComment');
Route::resource('posts', 'PostsController');
