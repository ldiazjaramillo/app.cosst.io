<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Excel;
use GuzzleHttp\Client;
use Mail;

class OpportunitiesController extends Controller
{
    
    public function create(Request $request){
        if($request->has('new_id')) $new_lead = \App\Lead::where('zoom_id', $request->get('new_id'))->first();
        else if($request->has('existing_id')) $new_lead = \App\Lead::where('zoom_id', $request->get('existing_id'))->first();
        else $new_lead = false;
        $number_options = ['1-2'=>'1-2 Employees', '3-9'=>'3-9 Employees', '10+'=>'10+ Employees'];
        return view('opportunities.create', compact('number_options', 'new_lead'));
    }

    public function store(Request $request){
        $errors = $this->validate($request, [
            'company_name'=>'required',
            'contact_name'=>'required',
            'decision_maker'=>'required',
            'contact_phone'=>'required',
            'contact_email'=>'required|email',
            //'client_id'=>'required|unique:opportunities,client_id',
            'company_state'=>'required',
            'company_states'=>'required',
            'external_account'=>'required',
            'employees_number'=>'required',
        ]);
        //dd($request->all());
        if($request->has('company_states')){
            $request['company_states'] = implode($request->get('company_states'), ',');
        }
        $request['user_id'] = \Auth::user()->id;
        $request['type_id'] = $this->getOpportunityType($request->get('employees_number'), $request->get('company_state'), $request->get('client_id'));
        $opportunity = \App\Opportunity::create($request->all());

        $url = $this->getRedirectPage($opportunity->employees_number, $opportunity->company_state, $opportunity->id);

        return redirect($url);
    }

    private function stateHasCover($state){
        $covered_states = ["WA", "CO","CA","FL","TX","OH","MA","NY","NJ","IL","PA","GA",];

        return in_array($state, $covered_states);
    }

    private function getOpportunityType($employees_number, $state, $client_id){
        $is_covered = $this->stateHasCover($state);
        if( $employees_number == '1-2' ){
            return 1;
        }else if( $employees_number == '3-9' && !$is_covered ){
            return 1;
        }else if($employees_number == '3-9' && $is_covered){
            return 2;
        }else if($employees_number == '10+' && !$is_covered){
            return 3;
        }else if($employees_number == '10+' && $is_covered){
            return 2;
        }
    }

    private function getRedirectPage($employees_number, $state, $client_id){
        $is_covered = $this->stateHasCover($state);
        if( $employees_number == '1-2' ){
            return "opportunity/spa_sbiz/$client_id";
        }else if( $employees_number == '3-9' && !$is_covered ){
            return "opportunity/spa_sbiz/$client_id";
        }else if($employees_number == '3-9' && $is_covered){
            return "opportunity/spb_mmfs/$client_id";
        }else if($employees_number == '10+' && !$is_covered){
            return "opportunity/spb_mmpr/$client_id";
        }else if($employees_number == '10+' && $is_covered){
            return "opportunity/spb_mmfs/$client_id";
        }
    }

    public function spa_sbiz($client_id){
        $opportunity = \App\Opportunity::find($client_id);
        return view('opportunities.spa_sbiz', compact('opportunity'));
    }

    public function updateManagerMMFS($agent){
        //dd($agent);
        if($agent->agent_id == 4) $agent->agent_id = 0;
        else $agent->agent_id = $agent->agent_id + 1;
        $agent->save();
    }

    public function updateManagerMMPR($agent){
        //dd($agent);
        if($agent->agent_id == 2) $agent->agent_id = 0;
        else $agent->agent_id = $agent->agent_id + 1;
        $agent->save();
    }

    public function spb_mmfs($client_id){
        $opportunity = \App\Opportunity::find($client_id);
        $mmfs = \App\MMFS::all()->first();
        $agent_id = $mmfs_id = $mmfs->agent_id;
        $agents = [
            0=>['name'=>'Brandon Boyle', 'email'=>'brandon.boyle@gusto.com', 'calendar'=>'calendly.com/brandon_gusto'],
            1=>['name'=>'Michael Reddish', 'email'=>'michael.reddish@gusto.com', 'calendar'=>'calendly.com/michael-reddish'],
            2=>['name'=>'Chad Benoit', 'email'=>'chad@gusto.com', 'calendar'=>'calendly.com/chad_zp'],
            3=>['name'=>'Johnny Wells', 'email'=>'johnny.wells@gusto.com', 'calendar'=>'calendly.com/johnnywells'],
            4=>['name'=>'Kabir Chopra', 'email'=>'kabir.chopra@gusto.com', 'calendar'=>'calendly.com/kabirchopra'],
        ];
        $agent = $agents[$mmfs_id];
        $this->updateManagerMMFS($mmfs);
        return view('opportunities.spb', compact('opportunity', 'agent', 'agents', 'agent_id'));
    }

    public function spb_mmpr($client_id){
        $opportunity = \App\Opportunity::find($client_id);
        $mmpr = \App\MMPR::all()->first();
        $agent_id = $mmpr_id = $mmpr->agent_id;
        $agents = [
            0=>['name'=>'Yekta Tehrani', 'email'=>'yekta.tehrani@gusto.com', 'calendar'=>'calendly.com/yekta-tehrani'],
            1=>['name'=>'Matt Worden', 'email'=>'matt.worden@gusto.com', 'calendar'=>'calendly.com/matt-worden'],
            2=>['name'=>'Matthew Baker', 'email'=>'matthew.baker@gusto.com', 'calendar'=>'calendly.com/matthewbaker'],
        ];
        $agent = $agents[$mmpr_id];
        $this->updateManagerMMPR($mmpr);
        return view('opportunities.spb', compact('opportunity', 'agent', 'agents', 'agent_id'));
    }

    public function notify2(Request $request, $id){
        $agents = [
            2 => [
                0=>['name'=>'Brandon Boyle', 'email'=>'brandon.boyle@gusto.com', 'calendar'=>'calendly.com/brandon_gusto'],
                1=>['name'=>'Michael Reddish', 'email'=>'michael.reddish@gusto.com', 'calendar'=>'calendly.com/michael-reddish'],
                2=>['name'=>'Chad Benoit', 'email'=>'chad@gusto.com', 'calendar'=>'calendly.com/chad_zp'],
                3=>['name'=>'Johnny Wells', 'email'=>'johnny.wells@gusto.com', 'calendar'=>'calendly.com/johnnywells'],
                4=>['name'=>'Kabir Chopra', 'email'=>'kabir.chopra@gusto.com', 'calendar'=>'calendly.com/kabirchopra'],
            ],
            3 => [
                0=>['name'=>'Yekta Tehrani', 'email'=>'yekta.tehrani@gusto.com', 'calendar'=>'calendly.com/yekta-tehrani'],
                1=>['name'=>'Matt Worden', 'email'=>'matt.worden@gusto.com', 'calendar'=>'calendly.com/matt-worden'],
                2=>['name'=>'Matthew Baker', 'email'=>'matthew.baker@gusto.com', 'calendar'=>'calendly.com/matthewbaker'],
            ]
        ];
        $this->validate($request, [
            'agent_id'=>'required',
            'date'=>'date|required',
            //'comment'=>'required',
        ]);
        $Date = \Carbon\Carbon::parse($request->get('date'));
        $opportunity = \App\Opportunity::find($id);
        $opportunity->agent_id = $request->get('agent_id');
        $opportunity->date = $Date->toDateTimeString();
        $opportunity->comment = $request->get('comment');
        $opportunity->status = 2;
        $opportunity->save();
        $current_agent = $agents[$opportunity->type_id][$opportunity->agent_id];
        $data['start_date'] = $Date->timestamp;
        $data['end_date'] = $Date->copy()->addMinutes(30)->timestamp;
        $data['title'] = "GUSTO >> PayRoll Review (".$current_agent['name'].") to Call ".$opportunity->contact_name;
        $data['organizer_name'] = $current_agent['name'];
        $data['organizer_email'] = $current_agent['email'];
        $data['client_email'] = $opportunity->contact_email;
        $data['company_name'] = $opportunity->company_name;
        $data['contact_name'] = $opportunity->contact_name;
        $data['contact_phone'] = $opportunity->contact_phone;
        $data['employees_number'] = $opportunity->employees_number;
        $data['description'] = "$opportunity->company_name \n
            Contact Name: $opportunity->contact_name \n
            Phone: $opportunity->phone \n
            Number of Employees: $opportunity->employees_number \n
            Note:  $opportunity->contact_name please click accept so ".$current_agent['name']." knows that you will be available at the agreed upon time. Thank you!";
        Mail::send('emails.calendar_invite', ['data'=>$data], function ($message) use ($data, $current_agent) {
            $time = time();
            $filename = "/tmp/invite_$time.ics";
            $meeting_duration = (1800); // half hour
            $meetingstamp = $data['start_date'];
            $dtstart = date( 'Ymd\THis\Z', ($data['start_date']) );
            //dd($dtstart);
            $dtend =  date('Ymd\THis\Z', ($data['end_date']) );
            $todaystamp = date('Ymd\THis\Z');
            $uid = date('Ymd').'T'.date('His').'-'.rand().'@gusto.com';
            $description = strip_tags($data['description']);
            $location = "gusto.com";
            $title_invite = $data['title'];
            $organizer = "CN=".$current_agent['name'].":mailto:".$current_agent['email'];

            // ICS
            $mail[0] = "BEGIN:VCALENDAR";
            $mail[1] = "PRODID:-//Google Inc//Google Calendar 70.9054//EN";
            $mail[2] = "VERSION:2.0";
            $mail[3] = "CALSCALE:GREGORIAN";
            $mail[4] = "METHOD:REQUEST";
            $mail[5] = "BEGIN:VEVENT";
            $mail[6] = "DTSTART;TZID=US-Eastern:" . $dtstart;
            $mail[7] = "DTEND;TZID=US-Eastern:" . $dtend;
            $mail[8] = "DTSTAMP;TZID=US-Eastern:" . $todaystamp;
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

            $message->subject("Invitation");
            $message->from('invites@gusto.cosst.io');
            if(env('APP_ENV') == "local"){
                $message->to(['ldiazjaramillo@gmail.com']);
            }else{
                $message->to([ $current_agent['email'], $data['client_email'] ]);
                $message->bbc('ethan@mygusto.com', 'Ethan');
            }
            
            $message->attach($filename, array('mime' => "text/calendar"));
        });
        //$this->sendInvite();
        return $this->notify($opportunity->id);
        //return view('opportunities.notify');
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

        })->store($extension, '/tmp/');
        $storage_path = "/tmp/$filename";
        //dd(Storage::disk('google')->exists("0B8d-d_nnDKn8V0hlbDAtZlEtQ0U"));
        //dd(Storage::disk('google')->files());
        //if(Storage::disk('google')->exists("0B8d-d_nnDKn8V0hlbDAtZlEtQ0U")) Storage::disk('google')->append($filename, 'Appended Text,asdas,asdas,asdasd,adsda');
        foreach(Storage::disk('google')->files() as $file) Storage::disk('google')->delete($file);
        Storage::disk('google')->put($filename, file_get_contents($storage_path));
        //return view('opportunities.notify');
        if(env('APP_ENV') == "local") $opportunity->type_id = 0;
        //if(env('APP_ENV') == "local") return view('opportunities.notify'); 
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
            $url = $channels[$opportunity->type_id]['url'];
            //$url = $channels[0]['url'];
            //$url = env('SLACK_URL', false);
            $company = $opportunity->company_name;
            $message = "A new lead has completed the process and is ready for follow up: The lead is $company, the Lead ID is $client_id";
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
        $opportunities = \App\Opportunity::all();
        return view('opportunities.summary', compact('opportunities'));
    }
}
