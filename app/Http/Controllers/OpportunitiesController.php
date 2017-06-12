<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Excel;
use GuzzleHttp\Client;

class OpportunitiesController extends Controller
{
    
    public function create(Request $request){
        if($request->has('new_id')) $new_lead = \App\Lead::where('zoom_id', $request->get('new_id'))->first();
        else $new_lead = collect();
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
        $opportunity = \App\Opportunity::create($request->all());

        $url = $this->getRedirectPage($opportunity->employees_number, $opportunity->company_state, $opportunity->client_id);

        return redirect($url);
    }

    private function stateHasCover($state){
        $covered_states = ["WA", "CO","CA","FL","TX","OH","MA","NY","NJ","IL","PA","GA",];

        return in_array($state, $covered_states);
    }

    private function getRedirectPage($employees_number, $state, $client_id){
        $is_covered = $this->stateHasCover($state);
        if( $employees_number == '1-2' ){
            return "opportunity/spa_sbiz_ob/$client_id";
        }else if( $employees_number == '3-9' && !$is_covered ){
            return "opportunity/spa_sbiz_ob/$client_id";
        }else if($employees_number == '3-9' && $is_covered){
            return "opportunity/spb_mmfs/$client_id";
        }else if($employees_number == '10+' && !$is_covered){
            return "opportunity/spb_mmpr/$client_id";
        }else if($employees_number == '10+' && $is_covered){
            return "opportunity/spb_mmfs/$client_id";
        }
    }

    public function spa_sbiz_ob($client_id){
        $opportunity = \App\Opportunity::where('client_id', $client_id)->first();
        return view('opportunities.spa_sbiz_ob', compact('opportunity'));
    }

    public function spb_mmfs($client_id){
        $opportunity = \App\Opportunity::where('client_id', $client_id)->first();
        $mmfs_agents = [
            0=>['name'=>'Brandon Boyle', 'email'=>'brandon.boyle@gusto.com', 'calendar'=>'calendly.com/brandon_gusto'],
            1=>['name'=>'Michael Reddish', 'email'=>'michael.reddish@gusto.com', 'calendar'=>'calendly.com/michael-reddish'],
            2=>['name'=>'Chad Benoit', 'email'=>'chad@gusto.com', 'calendar'=>'calendly.com/chad_zp'],
            3=>['name'=>'Johnny Wells', 'email'=>'johnny.wells@gusto.com', 'calendar'=>'calendly.com/johnnywells'],
            4=>['name'=>'Kabir Chopra', 'email'=>'kabir.chopra@gusto.com', 'calendar'=>'calendly.com/kabirchopra'],
        ];
        return view('opportunities.spb', compact('opportunity', 'agents'));
    }

    public function spb_mmpr($client_id){
        $opportunity = \App\Opportunity::where('client_id', $client_id)->first();
        $mmfs_agents = [
            0=>['name'=>'Yekta Tehrani', 'email'=>'yekta.tehrani@gusto.com', 'calendar'=>'calendly.com/yekta-tehrani'],
            1=>['name'=>'Matt Worden', 'email'=>'matt.worden@gusto.com', 'calendar'=>'calendly.com/matt-worden'],
            2=>['name'=>'Matthew Baker', 'email'=>'matthew.baker@gusto.com', 'calendar'=>'calendly.com/matthewbaker'],
        ];
        return view('opportunities.spb', compact('opportunity', 'agents'));
    }

    public function notify($client_id){
        foreach(Storage::disk('google')->files() as $file) Storage::disk('google')->delete($file);
        $opportunity = \App\Opportunity::where('client_id', $client_id)->first();
        $name = 'Opportunities';
        $extension = 'xls';
        $filename = $name.".".$extension;
        Excel::create($name, function($excel) {
            $opportunities = \App\Opportunity::all();
            // Set sheets
            $excel->sheet('Sheetname', function($sheet) use($opportunities) {
                $sheet->fromModel($opportunities);
            });
        })->store($extension);
        $storage_path = storage_path("exports/$filename");
        //dd(Storage::disk('google')->exists("0B8d-d_nnDKn8V0hlbDAtZlEtQ0U"));
        //dd(Storage::disk('google')->files());
        //if(Storage::disk('google')->exists("0B8d-d_nnDKn8V0hlbDAtZlEtQ0U")) Storage::disk('google')->append($filename, 'Appended Text,asdas,asdas,asdasd,adsda');
        Storage::disk('google')->put($filename, file_get_contents($storage_path));

        try{
            $client = new Client(['base_uri' => 'https://hooks.slack.com/services/']);
            $url = 'https://hooks.slack.com/services/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';
            //$url = env('SLACK_URL', false);
            $company = $opportunity->company_name;
            $message = "A new lead has completed the process and is ready for follow up: The lead is $company, the Lead ID is $client_id";

            $response = $client->request('POST', $url, [
                'connect_timeout' => 1.5,
                'timeout' => 1.5,
                'http_errors' => false,
                'exceptions' => false,
                'verify' => false,
                'json' => [
                    'text' => $message,
                    'channel' => '#gusto',
                    'username' => '@bot'
                ]
            ]);

        }catch (\Exception $e){
            return false;
        }
         return response()->json(['status' => 'Success']);
    }
}
