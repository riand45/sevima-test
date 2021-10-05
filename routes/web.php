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

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', 'HomeController@index')->name('home');

    // Post Route
    Route::resource('/post', 'PostController');
    Route::get('/post/{post}', 'PostController@show')->name('post.show');

    // Comment Route
    Route::resource('/comment', 'CommentController');

    // Profile Route
    Route::get('/profile/{user}/edit', 'ProfileController@edit')->name('profile.edit');
    Route::get('/profile/{user}', 'ProfileController@index')->name('profile.index');
    Route::patch('/profile/{user}', 'ProfileController@update')->name('profile.update');

    // Like Route
    Route::post('like/{like}', 'LikeController@update')->name('like.create');

    // Follow Route
    Route::post('/follow/{user}', 'FollowController@store')->name('follow.store');

});