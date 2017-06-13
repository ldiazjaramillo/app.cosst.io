<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------store
|store
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('test', function() {
    Storage::disk('google')->put('test.txt', 'Hello World');
});
Route::get('test/delete', function() {
    //$files = Storage::disk('google')->files();
    foreach(Storage::disk('google')->files() as $file) Storage::disk('google')->delete($file);
    dd(Storage::disk('google')->files());
});

Route::get('/', 'HomeController@index')->name('home')->middleware('auth');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::group(['prefix' => 'opportunity', 'middleware' => 'auth'], function () {
    Route::get('create', 'OpportunitiesController@create')->name('opportunity.create');
    Route::post('create', 'OpportunitiesController@store')->name('opportunity.store');

    Route::get('spa_sbiz/{client_id}', 'OpportunitiesController@spa_sbiz')->name('spa_sbiz');
    Route::get('spb_mmfs/{client_id}', 'OpportunitiesController@spb_mmfs')->name('spb_mmfs');
    Route::get('spb_mmpr/{client_id}', 'OpportunitiesController@spb_mmpr')->name('spb_mmpr');
    Route::get('notify/{client_id}', 'OpportunitiesController@notify')->name('opportunity.notify');
});

Auth::routes();
