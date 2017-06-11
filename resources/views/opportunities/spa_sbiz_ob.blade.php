@extends('layouts.app')

@section('content')

<!-- Calendly inline widget begin 
<div class="calendly-inline-widget" data-url="https://calendly.com/misterdiaz" style="min-width:320px;height:580px;"></div>
<script type="text/javascript" src="https://calendly.com/assets/external/widget.js"></script>
<!-- Calendly inline widget end -->
<div class="row">
    <div class="col-md-4">
        Full Name: {{ $opportunity->contact_name }}
    </div>
    <div class="col-md-4">
        Work Email Address: {{ $opportunity->contact_email }}
    </div>
    <div class="col-md-4">
        Company Name: {{ $opportunity->company_name }}
    </div>
</div>
<div>&nbsp;</div>
<div class="row">
    <div class="col-md-4">
        State: {{ $opportunity->company_state }}, {{ $opportunity->company_states }}
    </div>
    <div class="col-md-4">
        Company Phone:  {{ $opportunity->contact_phone }}
    </div>
    <div class="col-md-4">
        Number of Employees:  {{ $opportunity->employees_number }}
    </div>
</div>
<div class="text-right">
<a href="/opportunity/notify" class="btn btn-default">Continue</a>
</div>
<div>&nbsp;</div>
<iframe id="myFrame" src="https://gusto.com/invite/company?utm_source=vitalfew&utm_campaign=chivan" width="100%" height="500px" frameborder="0"></iframe>
@endsection

@section('bottom_script')
<script>
$(document).ready(function() {
})
</script>
@endsection
