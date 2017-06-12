<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $new_opportunities = \App\Lead::select(
            \DB::raw("CONCAT(first_name,' ',last_name, ' (', company_name ,' ) | ', zoom_id) AS name"), 'zoom_id')
            ->where(['type'=>1, 'status'=>1])
            ->pluck('name', 'zoom_id');
        $existing_opportunities = \App\Lead::select(
            \DB::raw("CONCAT(first_name,' ',last_name, ' (', company_name ,' ) | ', zoom_id) AS name"), 'zoom_id')
            ->where('status', 2)
            ->whereIn('type', [2, 3])
            ->pluck('name', 'zoom_id');
        //dd($existing_opportunities);
        //dd($zoom_leads);
        return view('home', compact('new_opportunities', 'existing_opportunities'));
    }
}
