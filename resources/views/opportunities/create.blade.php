@extends('layouts.app')

@section('content')
<div class="stepwizard">
    <div class="stepwizard-row setup-panel">
        <div class="stepwizard-step">
            <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
            <p>Step 1</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
            <p>Step 2</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
            <p>Step 3</p>
        </div>
    </div>
</div>
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form role="form" method="POST" action="{{ route('opportunity.store') }}">
    {{ csrf_field() }}
    <input name="lead_id" type="hidden" required="required" class="form-control" @if($new_lead) value="{{$new_lead->status}}" @endif />
    <div class="row setup-content" id="step-1">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3> Step 1</h3>
                <div class="form-group{{ $errors->has('client_id') ? ' has-error' : '' }}">
                    <label class="control-label">Client ID:</label>
                    @php
                        $cliend_id = "";
                        if($new_lead){
                            if($new_lead->zoom_id) $cliend_id = trim($new_lead->zoom_id);
                            else $cliend_id = trim($new_lead->zoom_company_id);
                        }
                    @endphp
                    <input name="client_id" maxlength="100" type="text" required="required" class="form-control" placeholder="Client ID" value="{{ $cliend_id }}" />
                    @if ($errors->has('client_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('client_id') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
                    <label class="control-label">Client Business Name:</label>
                    <input name="company_name" maxlength="100" type="text" required="required" class="form-control" placeholder="Business Name" @if($new_lead) value="{{$new_lead->company_name}}" @else value="{{old('company_name')}}" @endif />
                    @if ($errors->has('company_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('company_name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('contact_name') ? ' has-error' : '' }}">
                    <label class="control-label">Client Contact Name:</label>
                    <input name="contact_name" maxlength="100" type="text" required="required" class="form-control" placeholder="First, Last" @if($new_lead) value="{{$new_lead->full_name}}" @else value="{{old('contact_name')}}" @endif />
                    @if ($errors->has('contact_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('contact_name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('contact_position') ? ' has-error' : '' }}">
                    <label class="control-label">Client Job Title:</label>
                    <input name="contact_position" maxlength="100" type="text" required="required" class="form-control" placeholder="Position" @if($new_lead) value="{{$new_lead->contact_position}}" @else value="{{old('contact_position')}}" @endif />
                    @if ($errors->has('contact_position'))
                        <span class="help-block">
                            <strong>{{ $errors->first('contact_position') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('contact_phone') ? ' has-error' : '' }}">
                    <label class="control-label">Client Contact Phone:</label>
                    <input name="contact_phone" maxlength="20" type="text" required="required" class="form-control" placeholder="Contact Phone" @if($new_lead) value="{{$new_lead->phone}}" @else value="{{old('contact_phone')}}" @endif />
                    @if ($errors->has('contact_phone'))
                        <span class="help-block">
                            <strong>{{ $errors->first('contact_phone') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('contact_email') ? ' has-error' : '' }}">
                    <label class="control-label">Client Contact Email:</label>
                    <input name="contact_email" type="email" required="required" class="form-control" @if($new_lead) value="{{$new_lead->email}}" @else value="{{ old('contact_email') }}" @endif />
                    @if ($errors->has('contact_email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('contact_email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('contact_street') ? ' has-error' : '' }}">
                    <label class="control-label">Client Address Street:</label>
                    <input name="contact_street" type="text" required="required" class="form-control" @if($new_lead) value="{{$new_lead->contact_street}}" @else value="{{ old('contact_street') }}" @endif />
                    @if ($errors->has('contact_street'))
                        <span class="help-block">
                            <strong>{{ $errors->first('contact_street') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('contact_city') ? ' has-error' : '' }}">
                    <label class="control-label">Client Address City:</label>
                    <input name="contact_city" type="text" required="required" class="form-control" @if($new_lead) value="{{$new_lead->contact_city}}" @else value="{{ old('contact_city') }}" @endif />
                    @if ($errors->has('contact_city'))
                        <span class="help-block">
                            <strong>{{ $errors->first('contact_city') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('contact_state') ? ' has-error' : '' }}">
                    <label class="control-label">Client Address State:</label>
                    <select name="contact_state" id="contact_state" required="required" class="form-control select2">
                        <option value="">Select</option>
                    @foreach(config('app.us_states') as $index=>$value)
                        <option value="{{ $index }}" @if($new_lead) @if($new_lead->lead_state == $value) selected @endif @endif>{{ $value }}</option>
                    @endforeach
                    </select>
                    @if ($errors->has('contact_state'))
                        <span class="help-block">
                            <strong>{{ $errors->first('contact_state') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('employees_number') ? ' has-error' : '' }}">
                    <label class="control-label">How many employees does the client have?</label>
                    <input type="number" name="employees_number" id="employees_number" class="form-control"  required="required" @if($new_lead) value="{{ $new_lead->employees }}" @else value="{{ old('employees_number') }}" @endif />
                    @if ($errors->has('employees_number'))
                        <span class="help-block">
                            <strong>{{ $errors->first('employees_number') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('decision_maker') ? ' has-error' : '' }}">
                    <label class="control-label">Is this the decision Maker?</label>
                    <label class="radio-inline">
                        <input type="radio" name="decision_maker" id="decision_maker1" value="1" required="required" @if(old('decision_maker') == 1) checked @endif>Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="decision_maker" id="decision_maker0" value="0" required="required" @if(old('decision_maker') === 0) checked @endif>No
                    </label>
                    @if ($errors->has('decision_maker'))
                        <span class="help-block">
                            <strong>{{ $errors->first('decision_maker') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('open_positions') ? ' has-error' : '' }}">
                    <label class="control-label">How many open positions do you currently have?</label>
                    <input type="number" name="open_positions" id="open_positions" class="form-control"  required="required" value="{{ old('open_positions') }}">
                    @if ($errors->has('open_positions'))
                        <span class="help-block">
                            <strong>{{ $errors->first('open_positions') }}</strong>
                        </span>
                    @endif
                </div>
                <button class="btn btn-success btn-lg pull-right nextBtn" type="submit">Finish!</button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('bottom_script')
<script>
$(document).ready(function () {
    $('.select2').select2();
    var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn');

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
                $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-primary').addClass('btn-default');
            $item.addClass('btn-primary');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    allNextBtn.click(function(e){
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url'],input[type='email'],input[type='number'],input[type='radio'],select"),
            isValid = true;

        $(".form-group").removeClass("has-error");
        for(var i=0; i<curInputs.length; i++){
            if (!curInputs[i].validity.valid){
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }
        if(!isValid && $(this).attr('type') == "submit") e.preventDefault();
        else if(isValid && $(this).attr('type') == "submit") return;
        if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');
    });

    $('div.setup-panel div a.btn-primary').trigger('click');
});
</script>
@endsection
