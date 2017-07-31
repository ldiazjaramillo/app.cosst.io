<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ClientsController extends Controller
{
    //

    public function select(){
        $clients = \App\Client::all();
        return view('clients.select', compact('clients'));
    }

    public function select_store(Request $request){
        $this->validate($request, [
            'client_id'=>'required'
        ]);
        $client_id = $request->get('client_id');
        $client = \App\Client::find($client_id);
        if(!is_null($client->custom_url) && $client->custom_url != "survey.new"){
            $user = \Auth::user();
            $custom_url = $client->custom_url;
            $name = explode(' ', trim($user->name));
            $first_name = $name[0];
            $password = $first_name;
            $email = $user->email;
            if($user->email == "ldiazjaramillo@gmail.com") $password = "mveecCeD";
            return view('custom_login', compact('custom_url', 'email', 'password'));
        }
        if($client->custom_url == "survey.new") $url = route($client->custom_url);
        else $url = "/";
        flash("Client set successfully. Now working with client: $client->name")->success();
        $request->session()->put('working_client.id', $client_id);
        $request->session()->put('working_client.name', $client->name);
        $request->session()->put('working_client.client_domain', $client->client_domain);
        $request->session()->put('working_client.slack_url', $client->slack_url);
        $request->session()->put('working_client.form1_url', $client->form1_url);
        $request->session()->put('working_client.form2_url', $client->form2_url);
        $request->session()->put('working_client.google_drive_folder', $client->google_drive_folder);
        return redirect($url);
    }

    public function loginCustom($url){
        try{
            $client = new Client();
            $user = \Auth::user();
            $name = explode(' ', trim($user->name));
            $first_name = $name[0];
            $password = $first_name;
            if($user->email == "ldiazjaramillo@gmail.com") $password = "mveecCeD";
            $response = $client->request( "GET", "http://$url/login",
            [
                'connect_timeout' => 1.5,
                'timeout' => 1.5,
                'http_errors' => false,
                'exceptions' => false,
                'verify' => false,
                'form_params' => [
                    '_token' => csrf_token(),
                    'email' => $user->email,
                    'password' => $first_name
                ],
            ]);
            dd($response);
            if($response->getStatusCode() == 200) return redirect("http://$url");
        }catch (\Exception $e){
            dd($e);
            flash("Error trying to login into $url")->warning();
            return redirect('/client/select');
        }
    }
}
