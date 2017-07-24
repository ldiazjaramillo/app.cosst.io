<?php
namespace App;

use DateTime;
use Carbon\Carbon;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

class GoogleCalendar
{
    /** @var \Google_Service_Calendar */
    protected $calendarService;

    /** @var string */
    protected $calendarId;

    public function __construct(Google_Service_Calendar $calendarService, $calendarId="primary")
    {
        $this->calendarService = $calendarService;

        $this->calendarId = $calendarId;
    }

    public function getCalendarId(): string
    {
        return $this->calendarId;
    }

    /**
     * @param \Carbon\Carbon $startDateTime
     * @param \Carbon\Carbon $endDateTime
     * @param array          $queryParameters
     *
     * @link https://developers.google.com/google-apps/calendar/v3/reference/events/list
     *
     * @return array
     */
    public function listEvents(
        Carbon $startDateTime = null,
        Carbon $endDateTime = null,
        array $queryParameters = []
    ): array {
        $parameters = ['singleEvents' => true];

        if (is_null($startDateTime)) {
            $startDateTime = Carbon::now();
        }

        $parameters['timeMin'] = $startDateTime->format(DateTime::RFC3339);

        if (is_null($endDateTime)) {
            $endDateTime = Carbon::now()->endOfDay();
        }
       $parameters['timeMax'] = $endDateTime->format(DateTime::RFC3339);
        $parameters['orderBy'] = 'startTime';
        $parameters = array_merge($parameters, $queryParameters);

        return $this
            ->calendarService
            ->events
            ->listEvents($this->calendarId, $parameters)
            ->getItems();
    }

    public function getFreeHours($date_start=null, $date_end=null){
        if(!$date_start->isToday() ){
            $fh_start = Carbon::now()->startOfDay();
            $fh_end = Carbon::now()->endOfDay();
            $fh_start->hour = 8;
        }else{
            $fh_start = $date_start;
            $date_start->addHours(2);
            $fh_end = $date_start->copy()->endOfDay();
        }
        $fh_end->hour = 17;
        $free_hours = [];
        //dd($free_hours);
        //dd($free_hours_time);
        $busy_hours = [];
        $events = $this->listEvents($date_start, $date_end);
        foreach($events as $event){
            if($event->start->dateTime){
                $startTime = \Carbon\Carbon::parse($event->start->dateTime);
                $endTime = \Carbon\Carbon::parse($event->end->dateTime);
                $difference = $startTime->diffInMinutes( $endTime );
                $count = intval( round($difference / 15, 0) );
                $new_time = $startTime->copy();
                $sum = 0;
                while($startTime <= $endTime){
                    $busy_index = $startTime->hour.$startTime->minute;
                    $busy_hours[$busy_index] = [ 'hour'=> $startTime->hour, 'minute'=>$startTime->minute ];
                    $startTime->addMinutes(15);
                }
            }
        }
        //dd($busy_hours);
        while($fh_start <= $fh_end){
            $free_index = $fh_start->hour.$fh_start->minute;
            if( !array_key_exists( $free_index, $busy_hours ) ) $free_hours[$free_index] = [ 'hour'=>$fh_start->hour, 'minute'=>$fh_start->minute ];
            $fh_start->addMinutes(15);
        }
        //dd( $free_hours );
        return $free_hours;
    }

    /**
     * Get a single event.
     *
     * @param string $eventId
     *
     * @return \Google_Service_Calendar_Event
     */
    public function getEvent(string $eventId): Google_Service_Calendar_Event
    {
        return $this->calendarService->events->get($this->calendarId, $eventId);
    }

    /**
     * Insert an event.
     *
     * @param \Codegis\GoogleCalendar\Event|Google_Service_Calendar_Event $event
     *
     * @link https://developers.google.com/google-apps/calendar/v3/reference/events/insert
     *
     * @return \Google_Service_Calendar_Event
     */
    public function insertEvent($event): Google_Service_Calendar_Event
    {
        if ($event instanceof GoogleEvent) {
            $event = $event->googleEvent;
        }
        $optionaArguments = array("sendNotifications"=>true);
        return $this->calendarService->events->insert($this->calendarId, $event, $optionaArguments);
    }

    /**
     * @param \Codegis\GoogleCalendar\Event|Google_Service_Calendar_Event $event
     *
     * @return \Google_Service_Calendar_Event
     */
    public function updateEvent($event): Google_Service_Calendar_Event
    {
        if ($event instanceof GoogleEvent) {
            $event = $event->googleEvent;
        }
        $optionaArguments = array("sendNotifications"=>true);
        return $this->calendarService->events->update($this->calendarId, $event->id, $event, $optionaArguments);
    }

    /**
     * @param string|\Codegis\GoogleCalendar\Event $eventId
     */
    public function deleteEvent($eventId)
    {
        if ($eventId instanceof Event) {
            $eventId = $eventId->id;
        }

        $this->calendarService->events->delete($this->calendarId, $eventId);
    }

    public function getService(): Google_Service_Calendar
    {
        return $this->calendarService;
    }

    public function getTimeZone(){
        return $this->calendarService->getTimeZone();
    }
}
