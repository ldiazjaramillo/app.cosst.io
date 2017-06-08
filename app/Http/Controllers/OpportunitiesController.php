<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OpportunitiesController extends Controller
{
    
    public function create(){
        $number_options = ['1-2'=>'1-2 Employees', '3-9'=>'3-9 Employees', '10+'=>'10+ Employees'];
        return view('opportunities.create', compact('number_options'));
    }

    public function store(Request $request){
        $errors = $this->validate($request, [
            'company_name'=>'required',
            'contact_name'=>'required',
            'decision_maker'=>'required',
            'contact_phone'=>'required',
            'contact_email'=>'required|email',
            'client_id'=>'required|unique:opportunities,client_id',
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

        $this->getRedirectPage($opportunity->employees_number, $opportunity->company_state);
    }

    private function stateHasCover($state){
        $covered_states = ["WA", "CO","CA","FL","TX","OH","MA","NY","NJ","IL","PA","GA",];

        return in_array($state, $covered_states);
    }

    private function getRedirectPage($employees_number, $state){
        $is_covered = $this->stateHasCover($state);
        if( $employees_number == '1-2' ){
            dd("Sales Proccess A (Sbiz OB)");
        }else if( $employees_number == '3-9' && !$is_covered ){
            dd("Sales Process A (Sbiz OB)");
        }else if($employees_number == '3-9' && $is_covered){
            dd("Sales Process B (MM FS)");
        }else if($employees_number == '10+' && !$is_covered){
            dd("Sales Process B (MMPR)");
        }else if($employees_number == '10+' && $is_covered){
            dd("Sales Process B (MM FS)");
        }
    }
}
