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

Route::group(['prefix' => '/'],function (){

    Route::get('', "IndexController@index")->name("index");

    Route::group(['prefix' => 'auth'],function (){

        Route::post('register', "AuthController@register");

        Route::post('login', "AuthController@login");

        Route::post('isauth/{stat?}', "AuthController@isauth");

    });

    Route::group(['prefix' => 'contacts'],function (){

            Route::post('show', "ContactController@show");

            Route::post('create', "ContactController@create");

            Route::post('get/update/{id}', "ContactController@getupdate");

            Route::post('set/update/{id}', "ContactController@setupdate");

            Route::post('remove/{id}', "ContactController@remove");

            Route::post('search', "ContactController@search");

    });

    Route::group(['prefix' => 'logs','middleware' => ['auth']],function (){

            Route::post('show', "LogController@show");

            Route::post('export', "LogController@exportToCsv");

    });

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
