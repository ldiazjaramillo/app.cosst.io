<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cache;
use \Carbon\Carbon;
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

        if (!Cache::has('existing_opportunities')) {
            $expiresAt = Carbon::now()->addMinutes(180);
            $existing_opportunities = \App\Lead::select(
            \DB::raw("CONCAT(first_name,' ',last_name, ' (', company_name ,' ) | ', zoom_id) AS name"), 'zoom_id')
            ->whereIn('type', [2, 3])
            ->where('status', 2)
            ->pluck('name', 'zoom_id');
            Cache::put('existing_opportunities', $existing_opportunities, $expiresAt);
        }else{
            $existing_opportunities = Cache::get('existing_opportunities');
        }

        return view('home', compact('new_opportunities', 'existing_opportunities'));
    }
}
