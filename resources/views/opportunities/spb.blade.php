@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        Full Name: {{ $opportunity->contact_name }}
        @php
            $name = explode(' ', trim($opportunity->contact_name));
            $first_name = $name[0];
            $last_name = ( array_key_exists( 1, $name ) ) ? $name[1] : "";
        @endphp
        <div class="input-group">
            <input type="text" class="form-control" value="{{ $first_name }}" id="first_name" readonly/>
            <span class="input-group-btn">
                <button class="btn btn-default clipboard" type="button" data-clipboard-target="#first_name">Copy</button>
            </span>
        </div><!-- /input-group -->
        <div class="input-group">
            <input type="text" class="form-control" value="{{ $last_name }}" id="last_name" readonly/>
            <span class="input-group-btn">
                <button class="btn btn-default clipboard" type="button" data-clipboard-target="#last_name">Copy</button>
            </span>
        </div><!-- /input-group -->
    </div>
    <div class="col-md-4">
        Company Name:
        <div class="input-group">
            <input type="text" class="form-control" value="{{ $opportunity->company_name }}" id="company_name" readonly/>
            <span class="input-group-btn">
                <button class="btn btn-default clipboard" type="button" data-clipboard-target="#company_name">Copy</button>
            </span>
        </div><!-- /input-group -->
    </div>
    <div class="col-md-4">
        Work Email:
        <div class="input-group">
            <input type="text" class="form-control" value="{{ $opportunity->contact_email }}" id="contact_email" readonly/>
            <span class="input-group-btn">
                <button class="btn btn-default clipboard" type="button" data-clipboard-target="#contact_email">Copy</button>
            </span>
        </div><!-- /input-group -->
    </div>
</div>
<div>&nbsp;</div>
<div class="row">
    <div class="col-md-4">
        Phone Number:
        <div class="input-group">
            <input type="text" class="form-control" value="{{ $opportunity->contact_phone }}" id="contact_phone" readonly/>
            <span class="input-group-btn">
                <button class="btn btn-default clipboard" type="button" data-clipboard-target="#contact_phone">Copy</button>
            </span>
        </div><!-- /input-group -->
    </div>
    <div class="col-md-4">
        # of Employees:
        <div class="input-group">
            <input type="text" class="form-control" value="{{ $opportunity->employees_number }}" id="employees_number" readonly/>
            <span class="input-group-btn">
                <button class="btn btn-default clipboard" type="button" data-clipboard-target="#employees_number">Copy</button>
            </span>
        </div><!-- /input-group -->
    </div>
    <div class="col-md-4">
        State(s):
        <div class="input-group">
            <input type="text" class="form-control" value="{{ $opportunity->company_state }}, {{ $opportunity->company_states }}" id="company_state" readonly/>
            <span class="input-group-btn">
                <button class="btn btn-default clipboard" type="button" data-clipboard-target="#company_state">Copy</button>
            </span>
        </div><!-- /input-group -->
    </div>
</div>
<div>&nbsp;</div>
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form method="post" action="{{ route('opportunity.notify', [ $opportunity->id ]) }}">
{{ csrf_field() }}
 <div class="row">
    <div class='col-sm-4'>
        <div class="form-group">
            <input type="hidden" name="agent_id" value="{{ $agent_id }}">
            <select name="agent_id2" class="form-control" disabled>
            @foreach($agents as $index => $value)
                <option value="{{ $index }}"@if($index == $agent_id) selected @endif>{{ $value['name'] }}</option>
            @endforeach
            </select>
            @php
                //$tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                $tzlist = array();
                $tzlist['Pacific/Honolulu']     = 'Hawaii-Aleutian Standard Time (HAST)';
                $tzlist['US/Aleutian']          = 'Hawaii-Aleutian with Daylight Savings Time (HADT)';
                $tzlist['Etc/GMT+9']            = 'Alaska Standard Time (AKST)';
                $tzlist['America/Anchorage']    = 'Alaska with Daylight Savings Time (AKDT)';
                $tzlist['America/Dawson_Creek'] = 'Pacific Standard Time (PST)';
                $tzlist['PST8PDT']              = 'Pacific with Daylight Savings Time (PDT)';
                $tzlist['MST']                  = 'Mountain Standard Time (MST)';
                $tzlist['MST7MDT']              = 'Mountain with Daylight Savings Time (MDT)';
                $tzlist['Canada/Saskatchewan']  = 'Central Standard Time (CST)';
                $tzlist['CST6CDT']              = 'Central with Daylight Savings Time (CDT)';
                $tzlist['America/New_York']     = 'Eastern Standard Time (EST)';
                $tzlist['EST5EDT']              = 'Eastern with Daylight Savings Time (EDT)';
                $tzlist['America/Puerto_Rico']  = 'Atlantic Standard Time (AST)';
                $tzlist['America/Halifax']      = 'Atlantic with Daylight Savings Time (ADT)';
                //dd($tzlist);
            @endphp
            <select name="timezone" id="timezone" class="form-control select2" required>
                <option value="">Select a timezone</option>
            @foreach($tzlist as $index => $value)
                <option value="{{ $index }}" @if($index == "America/Puerto_Rico") selected @endif>{{ $value }}</option>
            @endforeach
            </select>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class="form-group">
            <div class='input-group date' id='date'>
                <input type='text' class="form-control" name="date" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class="form-group">
            <textarea class="form-control" name="comment"></textarea>
        </div>
    </div>
</div>
<div class="text-right">
    <a href="./{{ $opportunity->id }}" class="btn btn-primary">Change Agent</a>
    <button class="btn btn-default">Continue</button>
</div>
</form>
<div>&nbsp;</div>
<!-- Calendly inline widget begin -->
<div class="calendly-inline-widget" data-url="https://{{ $agent['calendar'] }}" style="min-width:320px;height:580px;"></div>
<script type="text/javascript" src="https://calendly.com/assets/external/widget.js"></script>
<!-- Calendly inline widget end -->
@endsection

@section('bottom_script')
<script>
$(document).ready(function(){
    $("#btn_spb").on('click', function(e){
        if(confirm('Did you sent calendly form?')) return;
        else e.preventDefault();
    });
    $('#date').datetimepicker({
        daysOfWeekDisabled: [0, 6],
        //inline: true,
        sideBySide: true,
        stepping: 15,
        minDate: moment()
    });
});
</script>
@endsection
