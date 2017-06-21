<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cache;
use \Carbon\Carbon;
use App\Services\Google;

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
        //$result = $google->createEvent();
        //dd($result->htmlLink);
        return view('home');
    }
}
