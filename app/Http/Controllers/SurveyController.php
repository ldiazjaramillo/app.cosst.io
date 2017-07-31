<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SurveyController extends Controller
{

    public function isClientSet(){
        return  session()->has('working_client');
    }
    
    public function new(){
        if( !$this->isClientSet() ){
            flash('Please choose a client to work with.')->warning();
            return redirect("/client/select");
        }
        return view('survey.new');
    }

    public function getSurveyLeads(Request $request){
        $query = $request->get('q');
        $new_opportunities = \DB::select("
            SELECT CONCAT(COALESCE(`first_name`, ''),' ', COALESCE(`last_name`, ''), ' (', COALESCE(`company_name`, '') ,' ) | ', COALESCE(`zoom_individual_id`, '') ) AS text, id
            FROM master_leads
            WHERE first_name like '%$query%' OR last_name LIKE '%$query%' OR company_name LIKE '%$query%' OR zoom_individual_id LIKE '%$query%' OR zoom_company_id LIKE '%$query%' OR email_address LIKE '%$query%'
        ");
        //dd($new_opportunities);
        //$new_opportunities = $new_opportunities->get();
        return response()->json(['items'=>$new_opportunities]);
    }

    public function create(Request $request, $lead_id=null){
        if( is_null($lead_id) ) $lead_id = $request->get('lead_id');
        $lead = \App\MasterLead::find($lead_id);
        return view('survey.create', compact('lead') );
    }

    public function store(Request $request){
        $survey_array = $request->get('survey');
        $lead_array = $request->get('lead');
        $survey_array['products_category'] = implode(",", $survey_array['products_category']);
        $survey_array['future_purchase'] = implode(",", $survey_array['future_purchase']);
        $survey_array['future_purchase_type'] = implode(",", $survey_array['future_purchase_type']);
        $survey_array['product_interest'] = implode(",", $survey_array['product_interest']);
        $survey_array['contact_by'] = implode(",", $survey_array['contact_by']);
        //dd( $survey_array );
        $survey = \App\Survey::updateOrCreate($survey_array);
        $lead = \App\MasterLead::find( $lead_array['id'] );
        $lead->update($lead_array);
        //dd($lead, $survey);
        flash("Survey was successfully!")->success();
        return redirect(route('survey.new'));
    }
}
