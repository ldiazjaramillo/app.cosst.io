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
use Codegis\GoogleCalendar\Event;
Route::get('test/calendar', function() {
    //create a new event
    $event = new Event;

    $event->name = 'A new event';
    $event->startDateTime = Carbon\Carbon::now();
    $event->endDateTime = Carbon\Carbon::now()->addHour();
    $event->addAttendee(['email' => 'ldiazjaramillo@gmail.com']);
    $event->addAttendee(['email' => 'luis@vitalfew.io']);

    $event->save();

});

Route::get('test/google-api', function() {
    $scopes = implode(' ', array( Google_Service_Calendar::CALENDAR ) );
    $client = new Google_Client();
    $client->setApplicationName( env('GOOGLE_CLIENT_APP_NAME') );
    $client->setClientId( env('GOOGLE_CLIENT_ID') );
    $client->setClientSecret( env('GOOGLE_CLIENT_SECRET') );
    $client->setRedirectUri('postmessage');
    //$client = new Google_Client();
    //$client->setAuthConfig('client_secrets.json');
    $client->setAccessType("offline");        // offline access
    //$client->setIncludeGrantedScopes(true);   // incremental auth
    $client->setScopes($scopes);
    //$client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);
    $client->setRedirectUri('http://localhost/oauth2');
    $auth_url = $client->createAuthUrl();

    return view('test_google_api', compact('auth_url'));
});

Route::get('oauth2', function(){
    //dd( request()->all() );
    $code = request()->get('code');
    //return redirect( route('settings.setCalendarToken') );
    $scopes = implode(' ', array( Google_Service_Calendar::CALENDAR ) );
    
    $client = new Google_Client();
    $client->setApplicationName( env('GOOGLE_CLIENT_APP_NAME') );
    $client->setClientId( env('GOOGLE_CLIENT_ID') );
    $client->setClientSecret( env('GOOGLE_CLIENT_SECRET') );
    $app_url = env('APP_URL');
    $client->setRedirectUri( "$app_url/oauth2" );
    $client->setAccessType( 'offline' );
    $client->setScopes($scopes);
    //dd($code);
    $google_auth = $client->authenticate($code);
    //dd($google_auth);
    //dd($client);
    $token = $client->getAccessToken();
    $user = \App\User::find( \Auth::user()->id );
    $user->gc_token = $token['access_token'];
    $user->gc_expires_in = $token['expires_in'];
    $user->gc_created = $token['created'];
    if(array_key_exists('refresh_token', $token)) $user->gc_refresh_token = $token['refresh_token'];
    $user->save();

    flash('Google Calendar access granted successfuly')->success();
    return redirect('/settings/calendar');
})->middleware('auth');

Route::get('test/excel', function(){
    $name = 'Opportunities';
    $extension = 'xls';
    $filename = $name.".".$extension;
    Excel::create($name, function($excel) {
        $opportunities = \App\Opportunity::all();
        $sbiz = $opportunities->where('type_id', 1)->where('status', '>', 1);
        // SBIZ sheet
        $excel->sheet('NEW', function($sheet) use($sbiz) {
            $sheet->fromModel($sbiz);
        });

    })->store($extension, '/tmp/');
    $storage_path = "/tmp/$filename";
    foreach(Storage::disk('google')->files() as $file) Storage::disk('google')->delete($file);
    Storage::disk('google')->put($filename, file_get_contents($storage_path));
    dd("done");
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
    Route::get('new/{client_id}', 'OpportunitiesController@new_client')->name('new_client');
    Route::post('check/{client_id}', 'OpportunitiesController@check_agent')->name('opportunity.check');
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
    Route::post('agent/update/{opportunity_id}', 'OpportunitiesController@agent_update')->name('opportunity.agent.store');
});

Route::group(['prefix' => 'settings', 'middleware' => 'auth'], function () {
    Route::get('calendar', 'SettingsController@calendar')->name('settings.calendar');
    Route::get('calendar', 'SettingsController@calendar')->name('settings.calendar');
});

Route::group(['prefix' => 'calendar', 'middleware' => 'auth'], function () {
    Route::get('/', 'CalendarController@index')->name('calendar.index');
    Route::get('/delete/{event_id}', 'CalendarController@delete')->name('calendar.delete');
    Route::get('/update/{event_id}', 'CalendarController@update')->name('calendar.update');
    Route::get('/create', 'CalendarController@create')->name('calendar.create');
    Route::get('/event/{event_id}', 'CalendarController@view_invite')->name('calendar.event_view');
    Route::post('/event/add_attendee/{event_id}', 'CalendarController@invite_add_attendee')->name('calendar.invite.add_attendee');
    Route::delete('/event/delete/{event_id}', 'CalendarController@invite_delete')->name('calendar.invite.delete');
    Route::put('/event/update/dates/{event_id}', 'CalendarController@invite_update_dates')->name('calendar.invite.update.dates');
});

Route::get('summary', 'OpportunitiesController@summary')->name('summary')->middleware('auth');

Auth::routes();

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::group(['prefix' => 'client'], function () {
    Route::get('select/', 'ClientsController@select')->name('client.select');
    Route::post('select/', 'ClientsController@select_store')->name('client.select.store');
});

Route::get('/outbound/login', 'HomeController@ob_login')->name('outbound.login')->middleware('auth');
