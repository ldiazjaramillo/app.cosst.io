<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Excel;
use GuzzleHttp\Client;
use Mail;
use \Carbon\Carbon;
use Google_Service_Calendar;
use \App\GoogleEvent;

class OpportunitiesController extends Controller
{

    protected $working_client_id=null;

    public function __construct()
    {
        $this->working_client_id = session()->get('working_client.id');
    }

    public function isClientSet(){
        return  session()->has('working_client');
    }
    
    public function create(Request $request){
        if( !$this->isClientSet() ){
            flash('Please choose a client to work with.')->warning();
            return redirect("/client/select");
        }
        if($request->has('new_id')) $new_lead = \App\Lead::find($request->get('new_id'));
        else if($request->has('existing_id')) $new_lead = \App\Lead::find($request->get('existing_id'));
        else if($request->has('new_partner')) $new_lead = \App\Lead::find($request->get('new_partner'));
        else $new_lead = false;
        $number_options = ['1-2'=>'1-2 Employees', '3-9'=>'3-9 Employees', '10+'=>'10+ Employees'];
        return view('opportunities.create', compact('number_options', 'new_lead'));
    }

    public function store(Request $request){
        $errors = $this->validate($request, [
            'company_name'=>'required',
            'contact_name'=>'required',
            'contact_position'=>'required',
            'contact_phone'=>'required',
            'contact_email'=>'required|email',
            'contact_street'=>'required',
            'contact_state'=>'required',
            'contact_city'=>'required',
            'employees_number'=>'required',
            'open_positions'=>'required',
            'decision_maker'=>'required',
        ]);
        
        //dd($request->all());
        $open_positions = $request->get('open_positions');
        if($open_positions < 5 ) $request['status'] = 9;//set status of not qualify
        $request['user_id'] = \Auth::user()->id;
        $request['type_id'] = 1;
        $opportunity = \App\Opportunity::create( $request->all() );
        if($open_positions < 5 ) return view('opportunities.not_qualify');
        $url ="opportunity/new/$opportunity->id";

        return redirect($url);
    }

    private function stateHasCover($state){
        $covered_states = ["WA", "CO","CA","FL","TX","OH","MA","NY","NJ","IL","PA","GA",];

        return in_array($state, $covered_states);
    }

    public function getCurrentAgent($opportunity){
        $agents = $opportunity->getAgentsByType();
        $currentAgent = \App\ManagerAgent::where('type_id', $opportunity->type_id)->where('client_id', $opportunity->clients_id)->first();
        if(is_null($currentAgent)){
            $currentAgent = \App\ManagerAgent::create([
                'type_id' => $opportunity->type_id,
                'client_id' => $opportunity->clients_id,
                'agent_id' => $agents->first()->id
            ]);
        }else{
            $next_agent = null;
            $is_past = false;
            $temp_id = $currentAgent->agent_id; 
            foreach($agents as $agent){
                if($is_past){
                    if( is_null($agent->gc_token) ) continue;
                    $currentAgent->agent_id = $agent->id;
                    $currentAgent->save();
                    break;
                }
                if($agent->id == $currentAgent->agent_id) $is_past = true;
            }
            //dd($currentAgent->agent_id);
            if($temp_id == $currentAgent->agent_id){
                $currentAgent->agent_id = $agents->first()->id;
                $currentAgent->save();
            }
        }

        return $currentAgent->agent_id;
    }

    public function new_client($opportunity_id){
        $opportunity = \App\Opportunity::find($opportunity_id);
        $agents = $opportunity->getAgentsByType();
        //dd($agents);
        $agent_id = $this->getCurrentAgent($opportunity);
        return view('opportunities.new_client', compact('opportunity', 'agent_id'));
    }

    public function check_agent(Request $request, $opportunity_id){
        //dd($request->all());
        $opportunity = \App\Opportunity::find($opportunity_id);
        $this->validate($request, [
            'agent_id'=>'required',
            'date'=>'required',
        ]);
        $agent_id = $request->get('agent_id');
        $agent = \App\User::find($agent_id);
        $date = $request->get('date');
        $date_start = Carbon::parse($date)->startOfDay();
        $google = new \App\GoogleClient($agent_id);
        $service = new Google_Service_Calendar( $google->getClient() );
        $timezone = $service->calendars->get('primary')->getTimeZone();
        //dd( $freeBusy->getBusy() );
        $Calendar = new \App\GoogleCalendar($service);
        $freeHours = $Calendar->getFreeHours($date_start->copy()->startOfDay(), $date_start->copy()->endOfDay() );
        return view( 'opportunities.check_agent', compact('freeHours', 'opportunity', 'agent', 'date', 'timezone') );
        //dd($freeHours);
    }

    public function notify2(Request $request, $opportunity_id){
        $this->validate($request, [
            'agent_id'=>'required',
            'date'=>'date|required',
            'time'=>'required',
            'duration'=>'required',
            'timezone'=>'required',
        ]);
        $opportunity = \App\Opportunity::find($opportunity_id);
        //dd($request->all());
        $timezone = $request->get('timezone');
        $date = $request->get('date');
        $time = $request->get('time');
        $duration = $request->get('duration');
        $notes = $request->get('notes');
        $agent_id = $request->get('agent_id');
        $agent = \App\User::find($agent_id);

        $startDateTime = Carbon::parse($date." ".$time, $timezone);

        $event = new GoogleEvent;
        $event->name = 'Jobtarget >> Programmatic Free trial Meet UP';
        $event->setCalendarId = "primary";
        $event->startDateTime = $startDateTime;
        $event->endDateTime = $startDateTime->copy()->addMinutes($duration);
        $event->addAttendee(['email' => \Auth::user()->email]);
        $event->addAttendee(['email' => $agent->email, 'name'=>$agent->name,'responseStatus'=>'needsAction']);
        $event->save($agent_id);
        $opportunity->notes = $notes;
        $opportunity->date = $startDateTime;
        $opportunity->timezone = $timezone;
        $opportunity->status = 2;
        $opportunity->agent_id = $agent_id;
        $opportunity->save();
        return $this->notify($opportunity->id);

        $Date = \Carbon\Carbon::parse($request->get('date'), $timezone)->timezone('UTC');
        $DateTimezone = \Carbon\Carbon::parse($request->get('date'), $timezone);
       //echo $Date;
        //dd($DateTimezone);
        //$Date = \Carbon\Carbon::parse($request->get('date'), 'PST');
        $opportunity = \App\Opportunity::find($id);
        $opportunity->agent_id = $request->get('agent_id');
        $opportunity->date = $Date->toDateTimeString();
        $opportunity->notes = $request->get('notes');
        $opportunity->timezone = $request->get('timezone');
        $opportunity->status = 2;
        $opportunity->save();
        $current_agent = $agents[$opportunity->type_id][$opportunity->agent_id];
        $data['start_date'] = $DateTimezone->timestamp;
        $data['end_date'] = $DateTimezone->copy()->addMinutes(15)->timestamp;
        $data['timezone'] = $DateTimezone->tzName;
        $data['dtstart'] = $DateTimezone->format('Ymd\THis\Z');
        $data['dtend'] = $DateTimezone->copy()->addMinutes(15)->format('Ymd\THis\Z');
        $data['title'] = "Jobtarget >> Programmatic Free trial Meet UP (".$current_agent['name'].") to Call ".$opportunity->contact_name;
        $data['organizer_name'] = $current_agent['name'];
        $data['organizer_email'] = $current_agent['email'];
        $data['client_email'] = $opportunity->contact_email;
        $data['company_name'] = $opportunity->company_name;
        $data['contact_name'] = $opportunity->contact_name;
        $data['contact_phone'] = $opportunity->contact_phone;
        $data['employees_number'] = $opportunity->employees_number;
        $name = explode(' ', trim($opportunity->contact_name));
        $first_name = $name[0];
        $data['description'] = "$opportunity->company_name \n
            Contact Name: $opportunity->contact_name \n
            Phone: $opportunity->contact_phone \n
            Number of Employees: $opportunity->employees_number \n
            Note: $first_name please click accept so ".$current_agent['name']." knows that you will be available at the agreed upon time. Thank you!";
        
        try{
            $client = new Client();
            $url = "https://hooks.zapier.com/hooks/catch/2314747/5u1q39/";
            if(env('APP_ENV') == "local"){
                $data['client_email'] = "luis@vitalfew.io";
                $data['organizer_email'] = "ethan@vitalfew.io";
            }
            $response = $client->post($url, [
                'json' => [
                    "summary" => $data['title'],
                    "description" => $data['description'],
                    "location" => "Will call ".$opportunity->contact_name." at ".$opportunity->contact_phone." ".$opportunity->contact_email,
                    "start_date" => $DateTimezone->toIso8601String(),
                    "end_date" => $DateTimezone->copy()->addMinutes(15)->toIso8601String(),
                    "email1" => $data['client_email'],
                    "email2" => $data['organizer_email'],
                    "email3" => "a.cassio@jobtarget.com"
                ]
            ]);

        }catch (\Exception $e){
            return $this->notify($opportunity->id);
        }

        return $this->notify($opportunity->id);

        Mail::send('emails.calendar_invite', ['data'=>$data], function ($message) use ($data, $current_agent) {
            $time = time();
            $filename = "/tmp/invite_$time.ics";
            $meeting_duration = (1800); // half hour
            $meetingstamp = $data['start_date'];
            $dtstart = $data['dtstart'];
            $dtend = $data['dtend'];
            $todaystamp = date('Ymd\THis\Z');
            $uid = date('Ymd').'T'.date('His').'-'.rand().'@gusto.com';
            $description = strip_tags($data['description']);
            $location = "gusto.com";
            $title_invite = $data['title'];
            //$organizer = "CN=".$current_agent['name'].":mailto:".$current_agent['email'];
            $organizer = "CN=Ethan Bloomfield:mailto:ethan@mygusto.com";

            // ICS
            $mail[0] = "BEGIN:VCALENDAR";
            $mail[1] = "PRODID:-//Google Inc//Google Calendar 70.9054//EN";
            $mail[2] = "VERSION:2.0";
            $mail[3] = "CALSCALE:GREGORIAN";
            $mail[4] = "METHOD:REQUEST";
            $mail[5] = "BEGIN:VEVENT";
            $mail[6] = "DTSTART;TZID=".$data['timezone'].":" . $dtstart;
            $mail[7] = "DTEND;TZID=".$data['timezone'].":" . $dtend;
            $mail[8] = "DTSTAMP;TZID=".$data['timezone'].":" . $todaystamp;
            $mail[9] = "UID:" . $uid;
            $mail[10] = "ORGANIZER;" . $organizer;
            $agent_mail = $current_agent['email'];
            $mail[11] = "ATTENDEE;CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=ACCEPTED;RSVP=TRUE;CN=$agent_mail;X-NUM-GUESTS=0:mailto:$agent_mail";
            $mail[12] = "ATTENDEE;CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN=".$data['organizer_name'].";X-NUM-GUESTS=1:mailto:".$data['organizer_email'];
            $mail[13] = "CREATED:" . $todaystamp;
            $mail[14] = "DESCRIPTION:" . $description;
            $mail[15] = "LAST-MODIFIED:" . $todaystamp;
            $mail[16] = "LOCATION:" . $location;
            $mail[17] = "SEQUENCE:0";
            $mail[18] = "STATUS:CONFIRMED";
            $mail[19] = "SUMMARY:" . $title_invite;
            $mail[20] = "TRANSP:OPAQUE";
            $mail[21] = "END:VEVENT";
            $mail[22] = "END:VCALENDAR";

            $mail = implode("\r\n", $mail);
            header("text/calendar");
            file_put_contents($filename, $mail);
            $username = \Auth::user()->username;
            $message->subject("Invitation");
            $message->from("$username@mygusto.com");
            if(env('APP_ENV') == "local"){
                $message->to(['luis@vitalfew.io', 'ethan@mygusto.com']);
            }else{
                $message->to([ $current_agent['email'], $data['client_email'] ]);
                $message->cc('ethan@mygusto.com', 'Ethan');
                $message->bcc("$username@mygusto.com", \Auth::user()->name);
            }
            
            $message->attach($filename, array('mime' => "text/calendar"));
        });
        return $this->notify($opportunity->id);
    }

    public function notify($id){
        $opportunity = \App\Opportunity::find($id);
        if(!$opportunity) return abort('405');
        $client_id = $opportunity->client_id;
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
            $mmfs = $opportunities->where('type_id', 2)->where('status', '>', 1);
            // MMFS sheet
            $excel->sheet('MMFS', function($sheet) use($mmfs) {
                $sheet->fromModel($mmfs);
            });
            $mmpr = $opportunities->where('type_id', 3)->where('status', '>', 1);
            // MMPR sheet
            $excel->sheet('MMPR', function($sheet) use($mmpr) {
                $sheet->fromModel($mmpr);
            });

            $partners = $opportunities->where('type_id', 4)->where('status', '>', 1);
            // partners sheet
            $excel->sheet('partners', function($sheet) use($partners) {
                $sheet->fromModel($partners);
            });

        })->store($extension, '/tmp/');
        $storage_path = "/tmp/$filename";
        foreach(Storage::disk('google')->files() as $file) Storage::disk('google')->delete($file);
        Storage::disk('google')->put($filename, file_get_contents($storage_path));
        //return view('opportunities.notify');
        if(env('APP_ENV') == "local") $opportunity->type_id = 0;
        if(env('APP_ENV') == "local") return view('opportunities.notify'); 
        if(!$opportunity->type_id) $opportunity->type_id = 0;        
        //dd($opportunity);
        $channels = [
            0 => ['channel'=>'#gusto', 'url'=>'https://hooks.slack.com/services/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'],
            1 => ['channel'=>'#vitalfew_sbiz_pass', 'url'=>'https://hooks.slack.com/services/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'],
            2 => ['channel'=>'#vitalfew_mmfs_pass', 'url'=>'https://hooks.slack.com/services/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'],
            3 => ['channel'=>'#vitalfew_mmpr_pass', 'url'=>'https://hooks.slack.com/services/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']
        ];
        try{
            $client = new Client(['base_uri' => 'https://hooks.slack.com/services/']);
            $url = "https://hooks.slack.com/services/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";
            //$url = $channels[0]['url'];
            //$url = env('SLACK_URL', false);
            $company = $opportunity->company_name;
            $client_agent = $opportunity->gusto_agent;
            $event_date = $opportunity->event_date;
            $vf_agent = $opportunity->vf_agent;
            $message = "A new appointment has been set for $client_agent by $vf_agent with $company on $event_date. Congratulations!";
            //$message = "A new lead has completed the process and is ready for follow up: The lead is $company, the Lead ID is $client_id";
            if(env('APP_ENV') == "local") $message .= " THIS IS A TEST. PLEASE IGNORE THIS!";

            $response = $client->request('POST', $url, [
                'connect_timeout' => 1.5,
                'timeout' => 1.5,
                'http_errors' => false,
                'exceptions' => false,
                'verify' => false,
                'json' => [
                    'text' => $message
                ]
            ]);

        }catch (\Exception $e){
            return view('opportunities.notify', compact('message'));
        }
        return view('opportunities.notify');
    }

    public function summary(){
        $opportunities = \App\Opportunity::where('status', 2)->get()->sortByDesc('created_at');
        return view('opportunities.summary', compact('opportunities'));
    }

    public function view($id){
        $opportunity = \App\Opportunity::find($id);
        $status_options = $opportunity->status_options;
        $status_id = $opportunity->status;
        $lead = \App\Lead::where('zoom_id', $opportunity->client_id)->orWhere('zoom_company_id', $opportunity->client_id)->first();
        if($lead) $opportunity->lead_type = $lead->type;
        else $opportunity->lead_type = null;
        return view('opportunities.view', compact('opportunity', 'status_options', 'status_id'));
    }

    public function getNewLeads(Request $request){
        $query = $request->get('q');
        $client_id = $this->working_client_id;
        $new_opportunities = \DB::select("
            SELECT CONCAT(COALESCE(`first_name`, ''),' ', COALESCE(`last_name`, ''), ' (', COALESCE(`company_name`, '') ,' ) | ', COALESCE(`zoom_id`, '') ) AS text, id
            FROM leads
            WHERE type=1 and status = 1 AND client_id='$client_id' and (first_name like '%$query%' OR last_name LIKE '%$query%' OR company_name LIKE '%$query%'
            OR zoom_id LIKE '%$query%' OR zoom_company_id LIKE '%$query%' OR email LIKE '%$query%')
        ");
        //dd($new_opportunities);
        //$new_opportunities = $new_opportunities->get();
        return response()->json(['items'=>$new_opportunities]);
    }

    public function getExistingLeads(Request $request){
        $query = $request->get('q');
        $existing_opportunities = \DB::select("
            SELECT CONCAT(COALESCE(`first_name`, ''),' ', COALESCE(`last_name`, ''), ' (', COALESCE(`company_name`, '') ,' ) | ', COALESCE(`zoom_id`, '') ) AS text, id
            FROM leads
            WHERE type IN (2,3) and status = 2 and (first_name like '%$query%' OR last_name LIKE '%$query%' OR company_name LIKE '%$query%'
            OR zoom_id LIKE '%$query%' OR zoom_company_id LIKE '%$query%' OR email LIKE '%$query%')
        ");
        //dd($existing_opportunities);
        //$existing_opportunities = $existing_opportunities->get();
        return response()->json(['items'=>$existing_opportunities]);
    }

    public function getPartnersLeads(Request $request){
        $query = $request->get('q');
        $existing_opportunities = \DB::select("
            SELECT CONCAT(COALESCE(`first_name`, ''),' ', COALESCE(`last_name`, ''), ' (', COALESCE(`company_name`, '') ,' ) | ', COALESCE(`zoom_id`, '') ) AS text, id
            FROM leads
            WHERE type=3 and status = 1 and (first_name like '%$query%' OR last_name LIKE '%$query%' OR company_name LIKE '%$query%'
            OR zoom_id LIKE '%$query%' OR zoom_company_id LIKE '%$query%' OR email LIKE '%$query%')
        ");
        //dd($existing_opportunities);
        //$existing_opportunities = $existing_opportunities->get();
        return response()->json(['items'=>$existing_opportunities]);
    }

    public function reports(){
        $search_type = "";
        $date_from = \Carbon\Carbon::parse('yesterday')->format('Y-m-d');
        $date_to = date('Y-m-d');
        return view('opportunities.reports', compact('search_type', 'date_from', 'date_to'));
    }

    public function get_reports(Request $request){
        $this->validate($request, [
            'search_type' => 'required',
            'date_from' => 'required|date|date_format:Y-m-d|before_or_equal:date_to',
            'date_to' => 'required|date|date_format:Y-m-d',
        ]);
        $search_type = $request->get('search_type');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        switch($search_type):
            case 1:
                $opportunities = \App\Opportunity::where('date', '>=', $date_from." 00:00:00")->where('date', '<=', $date_to." 23:59:59")->get();
            break;
            case 2:
                $opportunities = \App\Opportunity::where('created_at', '>=', $date_from." 00:00:00")->where('created_at', '<=', $date_to." 23:59:59")->get();
            break;
        endswitch;
        return view('opportunities.reports', compact('opportunities', 'search_type', 'date_from', 'date_to'));
    }

    public function reports_today(){
        $opportunities = \App\Opportunity::where('date', '>=', date("Y-m-d")." 00:00:00")->where('date', '<=', date("Y-m-d")." 23:59:59")->get();
        $search_type = 3;
        return view('opportunities.reports_today', compact('opportunities', 'search_type'));
    }

    public function get_status_update($opportunity_id){
        $opportunity = \App\Opportunity::find($opportunity_id);
        $status_options = $opportunity->status_options;
        $status_id = $opportunity->status;
        return view('opportunities.status_update', compact('status_id', 'status_options'));
    }

    public function status_update(Request $request, $opportunity_id){
        $opportunity = \App\Opportunity::find($opportunity_id);
        $opportunity->status = $request->get('status');
        $opportunity->save();
        flash('Status updated successfully.')->success();
        return redirect( route('opportunity.view', [$opportunity_id]) );
    }

    public function comments_update(Request $request, $opportunity_id){
        $opportunity = \App\Opportunity::find($opportunity_id);
        $opportunity->notes = $request->get('comments');
        $opportunity->save();
        return response()->json(['message'=>'Comments saved']);
    }

    public function invite_update(Request $request, $opportunity_id){
        //dd($request->all());
        if($request->has('send_calendar_invite')) $send_invite = 1;
        else $send_invite = 0;
        $opportunity = \App\Opportunity::find($opportunity_id);
        $timezone = $request->get('timezone');
        $Date = \Carbon\Carbon::parse($request->get('date'), $timezone)->timezone('UTC');
        $opportunity->date = $Date->toDateTimeString();
        $opportunity->timezone = $timezone;
        $opportunity->agent_id = $request->get('agent_id');
        $opportunity->save();
        flash('Invite date updated successfully.')->success();
        if($send_invite) $this->send_invite($opportunity->id);
        return redirect( route('opportunity.view', [$opportunity_id]) );
    }

    public function send_invite($opportunity_id){
        $agents = [
            1 => [
                0 => [ "name" => "Mark Angeles", "calendar" => "calendly.com/m-angeles", "email" => "m.angeles@jobtarget.com", "phone" => "1 (860) 288-5439"],
                1 => [ "name" => "Ian Kukulka", "calendar" => "calendly.com/i-kukulka", "email" => "i.kukulka@jobtarget.com", "phone" => "1 (860) 288-5444"],
                2 => [ "name" => "Rob Prest", "calendar" => "calendly.com/r-prest", "email" => "r.prest@jobtarget.com", "phone" => "1 (860) 288-5433"],
                3 => [ "name" => "Jerry Vissers", "calendar" => "calendly.com/j-vissers", "email" => "j.vissers@jobtarget.com", "phone" => "1 (860) 288-5441"]
            ],
        ];
        $opportunity = \App\Opportunity::find($opportunity_id);
        $timezone = $opportunity->timezone;
        $DateTimezone = \Carbon\Carbon::parse($opportunity->date, "UTC")->tz($timezone);
       //echo $Date;
        //dd($DateTimezone);
        $current_agent = $agents[$opportunity->type_id][$opportunity->agent_id];
        $data['start_date'] = $DateTimezone->timestamp;
        $data['end_date'] = $DateTimezone->copy()->addMinutes(15)->timestamp;
        $data['timezone'] = $DateTimezone->tzName;
        $data['dtstart'] = $DateTimezone->format('Ymd\THis\Z');
        $data['dtend'] = $DateTimezone->copy()->addMinutes(15)->format('Ymd\THis\Z');
        $data['title'] = "Jobtarget >> Programmatic Free trial Meet UP (".$current_agent['name'].") to Call ".$opportunity->contact_name;
        $data['organizer_name'] = $current_agent['name'];
        $data['organizer_email'] = $current_agent['email'];
        $data['client_email'] = $opportunity->contact_email;
        $data['company_name'] = $opportunity->company_name;
        $data['contact_name'] = $opportunity->contact_name;
        $data['contact_phone'] = $opportunity->contact_phone;
        $data['employees_number'] = $opportunity->employees_number;
        $name = explode(' ', trim($opportunity->contact_name));
        $first_name = $name[0];
        $data['description'] = "$opportunity->company_name \n
            Contact Name: $opportunity->contact_name \n
            Phone: $opportunity->contact_phone \n
            Number of Employees: $opportunity->employees_number \n
            Note: $first_name please click accept so ".$current_agent['name']." knows that you will be available at the agreed upon time. Thank you!";
        
        try{
            $client = new Client();
            $url = "https://hooks.zapier.com/hooks/catch/2314747/5u1q39/";
            if(env('APP_ENV') == "local"){
                $data['client_email'] = "luis@vitalfew.io";
                $data['organizer_email'] = "ethan@vitalfew.io";
            }
            $response = $client->post($url, [
                'json' => [
                    "summary" => $data['title'],
                    "description" => $data['description'],
                    "location" => "Will call ".$opportunity->contact_name." at ".$opportunity->contact_phone." ".$opportunity->contact_email,
                    "start_date" => $DateTimezone->toIso8601String(),
                    "end_date" => $DateTimezone->copy()->addMinutes(15)->toIso8601String(),
                    "email1" => $data['client_email'],
                    "email2" => $data['organizer_email'],
                    "email3" => "a.cassio@jobtarget.com"
                ]
            ]);

        }catch (\Exception $e){
            $this->notify($opportunity->id);
        }

        $this->notify($opportunity->id);
    }
    
}
