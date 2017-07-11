<?php

namespace App;

use Google_Client;
use Google_Service_Calendar;

class GoogleCalendarFactory
{
    public static function createForCalendarId($calendarId): GoogleCalendar
    {
        $config = config('laravel-google-calendar');

        $scopes = implode(' ', array( Google_Service_Calendar::CALENDAR ) );
        $credentialsPath = storage_path('app/laravel-google-calendar/');
        $application_name = "app.cosst.io Google Calendar";

        $client = new Google_Client();

        $client->setApplicationName($application_name);
        $client->setScopes($scopes);
        $client->setAuthConfig($config['client_secret_json']);
        $client->setAccessType('offline');

        if (!file_exists($config['client_secret_json'])) {
            $accessToken = json_decode(file_get_contents($config['client_secret_json']), true);
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

            // Store the credentials to disk.
            if(!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
            }
            file_put_contents($credentialsPath, json_encode($accessToken));
            printf("Credentials saved to %s\n", $credentialsPath);
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
        }
        //$client->setAssertionCredentials($credentials);

        $service = new Google_Service_Calendar($client);

        return new GoogleCalendar($service, $calendarId);
    }
}
