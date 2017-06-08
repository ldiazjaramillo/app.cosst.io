<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OpportunitiesController extends Controller
{
    
    public function create(){
        $states = ['NY'=>'NY', 'FL'=>'FL'];
        return view('opportunities.create', compact('states'));
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

        $this->getRedirectPage($opportunity->employees_number);
    }

    private function getRedirectPage($employees_number, $states=true){
        if($employees_number < 3){
            dd("Sales Proccess A (Sbiz OB)");
        }else if($employees_number >= 3 && $employees_number <= 9 && $states){
            dd("Sales Process A (Sbiz OB)");
        }else if($employees_number >= 3 && $employees_number <= 9 && !$states){
            dd("Sales Process B (MM FS)");
        }else if($employees_number >= 10 && !$states){
            dd("Sales Process B (MMPR)");
        }else if($employees_number >= 10 && $states){
            dd("Sales Process B (MM FS)");
        }
    }
}
