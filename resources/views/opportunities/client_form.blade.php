@extends('layouts.app')

@section('content')
<div class="col-md-4">
    Lead Name:
    <div class="input-group">
        <input type="text" class="form-control" value="{{ $opportunity->contact_name }}" id="contact_name" readonly/>
        <span class="input-group-btn">
            <button class="btn btn-default clipboard" type="button" data-clipboard-target="#contact_name">Copy</button>
        </span>
    </div><!-- /input-group -->
</div>
<div class="col-md-4">
    Lead Email:
    <div class="input-group">
        <input type="text" class="form-control" value="{{ $opportunity->contact_email }}" id="contact_email" readonly/>
        <span class="input-group-btn">
            <button class="btn btn-default clipboard" type="button" data-clipboard-target="#contact_email">Copy</button>
        </span>
    </div><!-- /input-group -->
</div>
<div class="col-md-4">
    Lead Owner:
    <div class="input-group">
        <input type="text" class="form-control" value="{{ $opportunity->client_agent }}" id="client_agent" readonly/>
        <span class="input-group-btn">
            <button class="btn btn-default clipboard" type="button" data-clipboard-target="#client_agent">Copy</button>
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
    Phone:
    <div class="input-group">
        <input type="text" class="form-control" value="{{ $opportunity->contact_phone }}" id="contact_phone" readonly/>
        <span class="input-group-btn">
            <button class="btn btn-default clipboard" type="button" data-clipboard-target="#contact_phone">Copy</button>
        </span>
    </div><!-- /input-group -->
</div>
<div class="col-md-4 text-right">
    <br/>
    <a href="/opportunity/notify/{{$opportunity->id}}" id="finish" class="btn btn-primary">Finish</a>
</div>
<div class="row">&nbsp;</div>
<div>&nbsp;</div>
<iframe src="{{ session()->get('working_client.form1_url') }}" frameborder="0" width="100%" height="600px"></iframe>

@endsection

@section('bottom_script')
<script>
$(document).ready(function(){
    $("#finish").on('click', function(e){
        if(confirm('Did you sent GUSTO form?')) return;
        else e.preventDefault();
    });
});
</script>
@endsection
