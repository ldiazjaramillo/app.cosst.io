<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Service_Calendar;
use Google_Client;

class SettingsController extends Controller
{

    public function calendar(){
        $user = \Auth::user();
        if( !$user->gc_token ){
            $has_calendar_token = false;
            $scopes = implode(' ', array( \Google_Service_Calendar::CALENDAR ) );
            $client = new Google_Client();
            $client->setApplicationName( env('GOOGLE_CLIENT_APP_NAME') );
            $client->setClientId( env('GOOGLE_CLIENT_ID') );
            $client->setClientSecret( env('GOOGLE_CLIENT_SECRET') );
            $client->setAccessType("offline");        // offline access
            $client->setScopes($scopes);
            $app_url = env('APP_URL');
            $client->setRedirectUri("$app_url/oauth2");
            $auth_url = $client->createAuthUrl();
        }else{
            $auth_url = null;
            $has_calendar_token = true;
        }
        return view('settings.calendar', compact('has_calendar_token', 'auth_url') );
    }
}
