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

<h3>Step Two. <small>Choose an Time for appointment with: <strong>{{ $agent->name }}</strong> </small></h3>
<h4>Date: {{$date}} <small>{{$timezone}}</small></h4>
<form method="post" action="{{ route('opportunity.notify2', [ $opportunity->id ]) }}">
{{ csrf_field() }}
<input type="hidden" name="agent_id" value="{{ $agent->id }}" />
<input type="hidden" name="date" value="{{ $date }}" />
<input type="hidden" name="timezone" value="{{ $timezone }}" />
 <div class="row">
    <div class="col-sm-4">
        <label for="">Available time:</label>
        <div class="form-group">
    @foreach($freeHours as $index => $free)
        @php
            $value = $free['hour'].":";
            $value .= ($free['minute'] == 0) ? "00" : $free['minute']; @endphp
        <label class="btn btn-primary">
            <input type="radio" id="{{ $index }}" value="{{$value}}" name="time"> {{ $free['hour'] }}:{{ ($free['minute'] == 0) ? "00" : $free['minute'] }}
        </label>
    @endforeach
        </div>
    </div>
    <div class="col-sm-4">
        <label for="">Type of meeting</label>
        <select name="duration" id="" class="form-control">
            <option value="15">15 min</option>
            <option value="30">30 min</option>
            <option value="60">1 hour</option>
        </select>
    </div>
    <div class="col-sm-4">
        <label for="">Notes</label>
        <textarea name="notes" id="" cols="30" rows="10" class="form-control"></textarea>
    </div>
</div>
<div>&nbsp;</div>
<div class='col-md-12'>
    <div class="form-group text-right">
        <label for="">&nbsp;</label>
        <button class="btn btn-default">Continue</button>
        <a href="/opportunity/new/{{ $opportunity->id }}" class="btn btn-primary">Change Agent</a>
    </div>
</div>
</form>
<div>&nbsp;</div>

@endsection

@section('bottom_script')
<script>
$(document).ready(function(){
    $('#date').datetimepicker({
        format: 'LT',
        daysOfWeekDisabled: [0, 6],
        inline: true,
        sideBySide: false,
        stepping: 15,
        minDate: moment()
    });
});
</script>
@endsection
