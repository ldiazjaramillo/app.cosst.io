<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Service_Calendar;
use \App\GoogleEvent;
use \Carbon\Carbon;
class CalendarController extends Controller
{
    
    public function index(){
        if(!\Auth::user()->gc_token) return redirect( route('settings.calendar') );
        $google = new \App\GoogleClient();
        $service = new Google_Service_Calendar( $google->getClient() );
        $Calendar = new \App\GoogleCalendar($service);
        $events = $Calendar->listEvents();

        return view('calendar.index', compact('events'));
    }

    public function delete($event_id){
        $google = new \App\GoogleClient();
        $service = new Google_Service_Calendar( $google->getClient() );
        $Calendar = new \App\GoogleCalendar($service);
        $Calendar->deleteEvent($event_id);
        flash('Event deleted successfuly')->success();
        return redirect('/calendar');
        //dd($event_id);
    }

    public function update($event_id){
        $event = GoogleEvent::find($event_id);
        $event->name = "New Event updated";
        $event->startDateTime = \Carbon\Carbon::now('EST')->addDays(3);
        $event->endDateTime = \Carbon\Carbon::now('EST')->addDays(3)->addMinutes(30);
        $event->save();
        flash("Event dates updated at: $event->startDateTime - $event->endDateTime")->success();
        return redirect('/calendar');

    }

    public function create(){
        $event = new GoogleEvent;

        $event->name = 'A new event TEST';
        $event->startDateTime = Carbon::now('EST')->addDay();
        $event->endDateTime = Carbon::now('EST')->addDay()->addHour();
        $event->addAttendee(['email' => \Auth::user()->email]);
        $event->addAttendee(['email' => 'ldiazjaramillo@gmail.com', 'responseStatus'=>'needsAction']);
        $event->save();

        flash("Event created")->success();
        return redirect('/calendar');
    }
}