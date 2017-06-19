@extends('layouts.app')

@section('content')
@php 
$options = [0=>'No', 1=>'Yes'];
$status = [1=>'No Appoinment', 2=>'Appoinment Scheduled'];
$agents = [
            1 => [
                0 =>['name'=>'Joey B', 'email'=>'joey.brown@gusto.com', 'calendar'=>'calendly.com/joey-brown'],
                1 =>['name'=>'Candace S', 'email'=>'candace.sake@gusto.com', 'calendar'=>'calendly.com/candace-sake'],
                2 =>['name'=>'Rene E', 'email'=>'rene.etter-garrette@gusto.com', 'calendar'=>'calendly.com/rene-gusto'],
                3 =>['name'=>'Donny T', 'email'=>'donny.tachis@gusto.com', 'calendar'=>'calendly.com/donny-tachis'],
            ],
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
@endphp
<h2>{{ $opportunity->contact_name }} at {{ $opportunity->company_name }}</h2>
<h3>Status: {{ $status[$opportunity->status] }}</h3>
<ul>
    <li><strong>Client ID: </strong>{{ $opportunity->client_id }}</li>
    <li><strong>Is decision maker: </strong></strong>{{ $options[$opportunity->decision_maker] }}</li>
    <li><strong>Phone: </strong>{{ $opportunity->contact_phone }}</li>
    <li><strong>Email: </strong>{{ $opportunity->contact_email }}</li>
    <li><strong>Company State: </strong>{{ $opportunity->company_state }}</li>
    <li><strong>Employees: </strong>{{ $opportunity->employees_number }}</li>
    @if(isset($agents[$opportunity->type_id][$opportunity->agent_id]['name']))
    <li><strong>Gusto Agent: </strong>{{ $agents[$opportunity->type_id][$opportunity->agent_id]['name'] }}</li>
    @endif
    @if($opportunity->date)
    <li><strong>Invitation Date: </strong>{{ \Carbon\Carbon::parse($opportunity->date)->format('Y-m-d') }}</li>
    @endif
</ul>
<a href="/summary" class="btn btn-default">Go back</a>
@endsection
