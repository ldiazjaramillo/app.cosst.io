<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class Google {

    protected $client;

    protected $service;

    protected $calendarId;

    protected $optParams;

    function __construct() {
        /* Get config variables */
        $client_id = Config::get('google.client_id');
        $client_secret = Config::get('google.client_secret');
        $service_account_name = Config::get('google.service_account_name');
        $key = Config::get('google.api_key');//you can use later

        $this->client = new \Google_Client();
        // service account creds
        $credentials_file = storage_path('cosst-8fcd38cd36b9.json');
        // set the location manually
        $this->client->setAuthConfig($credentials_file);
        //$this->client->setClientId($client_id);
        //$this->client->setClientSecret($client_secret);
        $this->client->setApplicationName("gusto.cosst.io");

        //$this->client->addScope('profile');
        $this->client->addScope(\Google_Service_Calendar::CALENDAR);
        $this->service = new \Google_Service_Calendar($this->client);
        $this->calendarId = 'gusto@vitalfew.io';
        $this->optParams = array(
            'maxResults' => 20,
            'orderBy' => 'startTime',
            'singleEvents' => TRUE,
            'timeMin' => date('c'),
        );
    }

    public function getEvents(){
        $results = $this->service->events->listEvents($this->calendarId, $this->optParams);
        //$results = $this->service->volumes->listVolumes('Henry David Thoreau', $optParams);
        return $results;
    }

    public function createEvent(){
        $event = new \Google_Service_Calendar_Event(array(
            'summary' => 'Luis Test',
            'location' => '800 Howard St., San Francisco, CA 94103',
            'description' => 'A chance to hear more about Google\'s developer products.',
            'start' => array(
                'dateTime' => '2017-06-27T09:00:00-07:00',
                'timeZone' => 'America/New_York',
            ),
            'end' => array(
                'dateTime' => '2017-06-27T17:00:00-07:00',
                'timeZone' => 'America/New_York',
            ),
            'recurrence' => array(
                'RRULE:FREQ=DAILY;COUNT=2'
            ),
            'attendees' => array(
                array('email' => 'gusto@vitalfew.io'),
            ),
            'reminders' => array(
                'useDefault' => FALSE,
                'overrides' => array(
                array('method' => 'email', 'minutes' => 24 * 60),
                array('method' => 'popup', 'minutes' => 10),
                ),
            ),
        ));

        $event = $this->service->events->insert($this->calendarId, $event);
        return $event;
    }
}
