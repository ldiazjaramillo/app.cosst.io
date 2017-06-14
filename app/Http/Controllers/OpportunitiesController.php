<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Excel;
use GuzzleHttp\Client;
use Cache;
use \Carbon\Carbon;
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

        $url = $this->getRedirectPage($opportunity->employees_number, $opportunity->company_state, $opportunity->client_id);

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
        $opportunity = \App\Opportunity::where('client_id', $client_id)->first();
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
        $opportunity = \App\Opportunity::where('client_id', $client_id)->first();
        $mmfs = \App\MMFS::all()->first();
        $mmfs_id = $mmfs->agent_id;
        $agents = [
            0=>['name'=>'Brandon Boyle', 'email'=>'brandon.boyle@gusto.com', 'calendar'=>'calendly.com/brandon_gusto'],
            1=>['name'=>'Michael Reddish', 'email'=>'michael.reddish@gusto.com', 'calendar'=>'calendly.com/michael-reddish'],
            2=>['name'=>'Chad Benoit', 'email'=>'chad@gusto.com', 'calendar'=>'calendly.com/chad_zp'],
            3=>['name'=>'Johnny Wells', 'email'=>'johnny.wells@gusto.com', 'calendar'=>'calendly.com/johnnywells'],
            4=>['name'=>'Kabir Chopra', 'email'=>'kabir.chopra@gusto.com', 'calendar'=>'calendly.com/kabirchopra'],
        ];
        $agent = $agents[$mmfs_id];
        $this->updateManagerMMFS($mmfs);
        return view('opportunities.spb', compact('opportunity', 'agent'));
    }

    public function spb_mmpr($client_id){
        $opportunity = \App\Opportunity::where('client_id', $client_id)->first();
        $mmpr = \App\MMPR::all()->first();
        $mmpr_id = $mmpr->agent_id;
        $agents = [
            0=>['name'=>'Yekta Tehrani', 'email'=>'yekta.tehrani@gusto.com', 'calendar'=>'calendly.com/yekta-tehrani'],
            1=>['name'=>'Matt Worden', 'email'=>'matt.worden@gusto.com', 'calendar'=>'calendly.com/matt-worden'],
            2=>['name'=>'Matthew Baker', 'email'=>'matthew.baker@gusto.com', 'calendar'=>'calendly.com/matthewbaker'],
        ];
        $agent = $agents[$mmpr_id];
        $this->updateManagerMMPR($mmpr);
        return view('opportunities.spb', compact('opportunity', 'agent'));
    }

    public function notify($client_id){
        $opportunity = \App\Opportunity::where('client_id', $client_id)->first();
        if(!$opportunity) return abort('405');
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

        })->store($extension);
        $storage_path = storage_path("exports/$filename");
        //dd(Storage::disk('google')->exists("0B8d-d_nnDKn8V0hlbDAtZlEtQ0U"));
        //dd(Storage::disk('google')->files());
        //if(Storage::disk('google')->exists("0B8d-d_nnDKn8V0hlbDAtZlEtQ0U")) Storage::disk('google')->append($filename, 'Appended Text,asdas,asdas,asdasd,adsda');
        foreach(Storage::disk('google')->files() as $file) Storage::disk('google')->delete($file);
        Storage::disk('google')->put($filename, file_get_contents($storage_path));
        if(!$opportunity->type_id) $opportunity->type_id = 0;
        $channels = [
            0 => ['channel'=>'#gusto', 'url'=>'https://hooks.slack.com/services/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'],
            1 => ['channel'=>'#vitalfew_sbiz_pass', 'url'=>'https://hooks.slack.com/services/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'],
            2 => ['channel'=>'#vitalfew_mmfs_pass', 'url'=>'https://hooks.slack.com/services/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'],
            3 => ['channel'=>'#vitalfew_mmpr_pass', 'url'=>'https://hooks.slack.com/services/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']
        ];
        try{
            $client = new Client(['base_uri' => 'https://hooks.slack.com/services/']);
            $url = $channels[$opportunity->type_id]['url'];
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
                    'text' => $message
                ]
            ]);

        }catch (\Exception $e){
            return false;
        }
        return view('opportunities.notify');
    }

    public function getExistingLeads(Request $request){
        $query = $request->get('q');
        $existing_opportunities = \App\Lead::select(
        \DB::raw("CONCAT(first_name,' ',last_name, ' (', company_name ,' ) | ', zoom_id) AS text"), 'zoom_id AS id')
        ->whereIn('type', [2, 3])
        ->where('status', 2)
        ->orWhere('first_name', 'like', "%$query%")
        ->orWhere('last_name', 'like', "%$query%")
        ->orWhere('company_name', 'like', "%$query%")
        ->orWhere('zoom_id', 'like', "%$query%");
        //->pluck('name', 'zoom_id');
        $existing_opportunities = $existing_opportunities->get();
        return response()->json(['items'=>$existing_opportunities]);
    }
}
