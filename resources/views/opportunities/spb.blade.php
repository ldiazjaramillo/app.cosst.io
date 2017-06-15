@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        Full Name: {{ $opportunity->contact_name }}
        <div class="input-group">
            <input type="text" class="form-control" value="{{ explode(' ', trim($opportunity->contact_name))[0] }}" id="first_name" readonly/>
            <span class="input-group-btn">
                <button class="btn btn-default clipboard" type="button" data-clipboard-target="#first_name">Copy</button>
            </span>
        </div><!-- /input-group -->
        <div class="input-group">
            <input type="text" class="form-control" value="{{ explode(' ', trim($opportunity->contact_name))[1] }}" id="last_name" readonly/>
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
<div class="text-right">
    <a href="./{{ $opportunity->client_id }}" class="btn btn-primary">Change Agent</a>
    <a href="/opportunity/notify/{{ $opportunity->id }}" class="btn btn-default" id="btn_spb">Continue</a>
</div>
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
});
</script>
@endsection
