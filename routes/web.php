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

Route::get('/', 'HomeController@index')->name('home')->middleware('auth');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::group(['prefix' => 'opportunity', 'middleware' => 'auth'], function () {
    Route::get('create', 'OpportunitiesController@create')->name('opportunity.create');
    Route::post('create', 'OpportunitiesController@store')->name('opportunity.store');
});

Auth::routes();
