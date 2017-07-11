@extends('layouts.app')

@section('content')
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="row">
    <div class="col-md-4">
        Full Name: {{ $opportunity->contact_name }}
    </div>
    <div class="col-md-4">
        Company Name: {{ $opportunity->company_name }}
    </div>
    <div class="col-md-4">
        Work Email: {{ $opportunity->contact_email }}
    </div>
</div>
<div>&nbsp;</div>
<div class="row">
    <div class="col-md-4">
        Phone Number: {{ $opportunity->contact_phone }}
    </div>
    <div class="col-md-4">
        # of Employees: {{ $opportunity->employees_number }}
    </div>
    <div class="col-md-4">
        State(s): {{ $opportunity->contact_state }}
    </div>
</div>
<div>&nbsp;</div>

<h2>Setup Appointment</h2>

<h3>Step One <small>Choose an Agent and a Date to see available hours</small></h3>
<form method="post" action="{{ route('opportunity.check', [ $opportunity->id ]) }}">
{{ csrf_field() }}
 <div class="row">
    <div class='col-sm-4'>
        <div class="form-group">
            <label for="">Agent choose by the system is:</label>
            <select name="agent_id" class="form-control">
            @foreach($opportunity->getAgentsByType() as $agent)
                <option value="{{ $agent->id }}"@if($agent->id == $agent_id) selected @endif>{{ $agent->name }}</option>
            @endforeach
            </select>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class="form-group">
            <label for="">Date for the meeting:</label>
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
            <label for="">&nbsp;</label>
            <button class="btn btn-default">Check availability</button>
        </div>
    </div>
</div>
</form>
<div>&nbsp;</div>

@endsection

@section('bottom_script')
<script>
$(document).ready(function(){
    $('#date').datetimepicker({
        format: 'MM/DD/YYYY',
        daysOfWeekDisabled: [0, 6],
        //inline: true,
        sideBySide: false,
        //stepping: 15,
        minDate: moment()
    });
});
</script>
@endsection
