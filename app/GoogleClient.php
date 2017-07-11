<?php

namespace App;

use Google_Client;
use User;
use Google_Service_Calendar;

class GoogleClient
{

    protected $client;

    /**
    * Returns an authorized API client.
    * @return Google_Client the authorized client object
    */
    function __construct($user_id = null) {
        $scopes = implode(' ', array( Google_Service_Calendar::CALENDAR ) );
        $this->user_id = $user_id;
        $client = new Google_Client();
        $client->setApplicationName( env('GOOGLE_CLIENT_APP_NAME') );
        $client->setClientId( env('GOOGLE_CLIENT_ID') );
        $client->setClientSecret( env('GOOGLE_CLIENT_SECRET') );
        $client->setAccessType( 'offline' );
        $client->setScopes($scopes);
        $app_url = env('APP_URL');
        $client->setRedirectUri("$app_url/oauth2");
        
        $client->setAccessToken( $this->getToken() );
        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken( $client->getRefreshToken() );
            $token = $client->getAccessToken();
            $this->setToken($token);
        }
        $this->client = $client;
    }

    protected function getToken(){
        if(is_null($this->user_id)) $user = \Auth::user();
        else $user = \App\User::find($this->user_id);
        $token=[];
        $token['access_token'] = $user->gc_token;
        $token['expires_in'] = $user->gc_expires_in;
        $token['refresh_token'] = $user->gc_refresh_token;
        $token['created'] = $user->gc_created;
        return $token;
    }

    protected function setToken($token){
        if(is_null($this->user_id)) $user = \Auth::user();
        else $user = \App\User::find($this->user_id);
        $user->gc_token = $token['access_token'];
        $user->gc_expires_in = $token['expires_in'];
        $user->gc_refresh_token = $token['refresh_token'];
        $user->gc_created = $token['created'];
        $user->save();
    }

    public function getClient(){
        return $this->client;
    }


}
