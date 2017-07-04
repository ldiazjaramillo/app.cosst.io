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

Route::get('test/excel', function(){
    $name = 'Opportunities';
    $extension = 'xls';
    $filename = $name.".".$extension;
    $result = Excel::create($name, function($excel) {
        $opportunities = \App\Opportunity::all();
        $sbiz = $opportunities->where('type_id', 1);
        // SBIZ sheet
        $excel->sheet('SBIZ', function($sheet) use($sbiz) {
            $sheet->fromModel($sbiz);
        });
        $mmfs = $opportunities->where('type_id', 2);
        // MMFS sheet
        $excel->sheet('MMFS', function($sheet) use($mmfs) {
            $sheet->fromModel($mmfs);
        });
        $mmpr = $opportunities->where('type_id', 3);
        // MMPR sheet
        $excel->sheet('MMPR', function($sheet) use($mmpr) {
            $sheet->fromModel($mmpr);
        });

    })->store($extension, '/tmp/', true);
    dd($result);
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
    Route::get('new_partners/{client_id}', 'OpportunitiesController@new_partners')->name('new_partners');
    Route::get('notify/{client_id}', 'OpportunitiesController@notify')->name('opportunity.notify');
    Route::post('notify/{client_id}', 'OpportunitiesController@notify2')->name('opportunity.notify2');
    Route::get('view/{id}', 'OpportunitiesController@view')->name('opportunity.view');
    Route::get('get/new/leads', 'OpportunitiesController@getNewLeads')->name('get.new.leads');
    Route::get('get/existing/leads', 'OpportunitiesController@getExistingLeads')->name('get.existing.leads');
    Route::get('get/partners/leads', 'OpportunitiesController@getPartnersLeads')->name('get.partners.leads');
    Route::get('reports', 'OpportunitiesController@reports')->name('opportunity.reports.page');
    Route::get('reports/today', 'OpportunitiesController@reports_today')->name('opportunity.reports.today');
    Route::get('reports/search', 'OpportunitiesController@get_reports')->name('opportunity.reports');
    Route::get('status/update/{opportunity_id}', 'OpportunitiesController@get_status_update')->name('opportunity.status.update');
    Route::post('status/update/{opportunity_id}', 'OpportunitiesController@status_update')->name('opportunity.status.store');
    Route::post('comments/update/{opportunity_id}', 'OpportunitiesController@comments_update')->name('opportunity.comments.store');
    Route::post('invite/update/{opportunity_id}', 'OpportunitiesController@invite_update')->name('opportunity.invite.update');
});

Route::get('summary', 'OpportunitiesController@summary')->name('summary')->middleware('auth');

Auth::routes();


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
