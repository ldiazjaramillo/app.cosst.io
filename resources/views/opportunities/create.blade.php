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
    <input name="client_id" type="hidden" required="required" class="form-control" value="@if($new_lead) {{$new_lead->zoom_id}} @endif" />
    <input name="lead_type" type="hidden" required="required" class="form-control" value="@if($new_lead) {{$new_lead->type}} @endif" />
    <div class="row setup-content" id="step-1">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3> Step 1</h3>
                <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
                    <label class="control-label">Client Business Name:</label>
                    <input name="company_name" maxlength="100" type="text" required="required" class="form-control" placeholder="Business Name" value="@if($new_lead) {{$new_lead->company_name}} @else {{old('company_name')}} @endif"/>
                    @if ($errors->has('company_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('company_name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('contact_name') ? ' has-error' : '' }}">
                    <label class="control-label">Client Contact Name:</label>
                    <input name="contact_name" maxlength="100" type="text" required="required" class="form-control" placeholder="First, Last" value="@if($new_lead) {{$new_lead->full_name}} @else {{old('contact_name')}} @endif"/>
                    @if ($errors->has('contact_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('contact_name') }}</strong>
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
                <div class="form-group{{ $errors->has('contact_phone') ? ' has-error' : '' }}">
                    <label class="control-label">Client Contact Phone:</label>
                    <input name="contact_phone" maxlength="20" type="text" required="required" class="form-control" value="@if($new_lead) {{$new_lead->phone}} @else {{old('contact_phone')}} @endif" />
                    @if ($errors->has('contact_phone'))
                        <span class="help-block">
                            <strong>{{ $errors->first('contact_phone') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('contact_email') ? ' has-error' : '' }}">
                    <label class="control-label">Client Contact Email:</label>
                    <input name="contact_email" type="email" required="required" class="form-control" value="@if($new_lead) {{$new_lead->email}} @else {{ old('contact_email') }} @endif" />
                    @if ($errors->has('contact_email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('contact_email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('company_state') ? ' has-error' : '' }}">
                    <label class="control-label">Company State:</label>
                    <select name="company_state" id="company_state" required="required" class="form-control select2">
                        <option value="">Select</option>
                    @foreach(config('app.us_states') as $index=>$value)
                        <option value="{{ $index }}" @if($new_lead) @if($new_lead->lead_state == $value) selected @endif @endif>{{ $value }}</option>
                    @endforeach
                    </select>
                    @if ($errors->has('company_state'))
                        <span class="help-block">
                            <strong>{{ $errors->first('company_state') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('company_states') ? ' has-error' : '' }}">
                    <label class="control-label">States you have employees:</label>
                    <select multiple name="company_states[]" id="company_states" required="required" class="form-control select2">
                        <option value="">Select</option>
                    @php if(old('company_states')) $company_states_arr = old('company_states'); else $company_states_arr = []; @endphp
                    @foreach(config('app.us_states') as $index=>$value)
                        <option value="{{ $index }}" @if( in_array( $index, $company_states_arr ) ) selected @endif>{{ $value }}</option>
                    @endforeach
                    </select>
                    @if ($errors->has('company_states'))
                        <span class="help-block">
                            <strong>{{ $errors->first('company_states') }}</strong>
                        </span>
                    @endif
                </div>
            @if($new_lead && $new_lead->type == 3)
                <div class="form-group{{ $errors->has('provide_accounting') ? ' has-error' : '' }}">
                    <label class="control-label">Do you provide accounting bookeeping for clients?</label>
                    <label class="radio-inline">
                        <input type="radio" name="provide_accounting" id="provide_accounting1" value="1" required="required" @if(old('provide_accounting') == 1) checked @endif>Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="provide_accounting" id="provide_accounting0" value="0" required="required" @if(old('provide_accounting') === 0) checked @endif>No
                    </label>
                    @if ($errors->has('provide_accounting'))
                        <span class="help-block">
                            <strong>{{ $errors->first('provide_accounting') }}</strong>
                        </span>
                    @endif
                </div>
            @else
                <div class="form-group{{ $errors->has('external_account') ? ' has-error' : '' }}">
                    <label class="control-label">Do you have a an external account/bookeeper?</label>
                    <label class="radio-inline">
                        <input type="radio" name="external_account" id="external_account1" value="1" required="required" @if(old('external_account') == 1) checked @endif>Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="external_account" id="external_account0" value="0" required="required" @if(old('external_account') === 0) checked @endif>No
                    </label>
                    @if ($errors->has('external_account'))
                        <span class="help-block">
                            <strong>{{ $errors->first('external_account') }}</strong>
                        </span>
                    @endif
                </div>
            @endif
                <div class="form-group{{ $errors->has('employees_number') ? ' has-error' : '' }}">
                    <label class="control-label">How many employees does the client have?</label>
                    <select name="employees_number" required="required" class="form-control">
                        <option value="">Select</option>
                    @php 
                    if($new_lead){ 
                        if($new_lead->employees < 3) $employees = '1-2';
                        else if($new_lead->employees >= 3 && $new_lead->employees <=9 ) $employees = '3-9';
                        else $employees = '10+';
                    } 
                    @endphp
                    @foreach($number_options as $index=>$value)
                        <option value="{{ $index }}" @if(isset($employees)) @if($employees == $index) selected @endif @endif>{{ $value }}</option>
                    @endforeach
                    </select>
                    @if ($errors->has('employees_number'))
                        <span class="help-block">
                            <strong>{{ $errors->first('employees_number') }}</strong>
                        </span>
                    @endif
                </div>
            @if($new_lead && $new_lead->type == 3)
                <div class="form-group{{ $errors->has('clients_number') ? ' has-error' : '' }}">
                    <label class="control-label">How many clients do you have?</label>
                    <input type="number" name="clients_number" id="clients_number" class="form-control"  required="required" value="{{ old('clients_number') }}">
                    @if ($errors->has('clients_number'))
                        <span class="help-block">
                            <strong>{{ $errors->first('clients_number') }}</strong>
                        </span>
                    @endif
                </div>
            @endif
            @if($new_lead && $new_lead->type == 3)
                <button class="btn btn-success btn-lg pull-right nextBtn" type="submit">Finish!</button>
            @else
                <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Next</button>
            @endif
            </div>
        </div>
    </div>
    <div class="row setup-content" id="step-2">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3> Step 2</h3>
                <div class="form-group">
                    <label class="control-label">Do you require Certified payroll?</label>
                    <label class="radio-inline">
                        <input type="radio" name="certified_payroll" id="certified_payroll1" value="1" @if(old('certified_payroll') == 1) checked @endif/>Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="certified_payroll" id="certified_payroll0" value="0" checked/>No
                    </label>
                </div>

                <div class="form-group">
                    <label class="control-label">Do you require Job costing?</label>
                    <label class="radio-inline">
                        <input type="radio" name="job_costing" id="job_costing1" value="1" @if(old('job_costing') == 1) checked @endif />Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="job_costing" id="job_costing0" value="0" checked />No
                    </label>
                </div>

                <div class="form-group">
                    <label class="control-label">Do you require paper checks?</label>
                    <label class="radio-inline">
                        <input type="radio" name="paper_checks" id="paper_checks1" value="1" @if(old('paper_checks') == 1) checked @endif />Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="paper_checks" id="paper_checks0" value="0" checked />No
                    </label>
                </div>

                <div class="form-group">
                    <label class="control-label">Do you require Schedule H filing (househould, nannies)?</label>
                    <label class="radio-inline">
                        <input type="radio" name="schedule_filing" id="schedule_filing1" value="1" @if(old('schedule_filing') == 1) checked @endif />Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="schedule_filing" id="schedule_filing0" value="0" checked />No
                    </label>
                </div>

                <div class="form-group">
                    <label class="control-label">Do you require a 943 filing? (farms)</label>
                    <label class="radio-inline">
                        <input type="radio" name="farms_filing" id="farms_filing1" value="1" @if(old('farms_filing') == 1) checked @endif />Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="farms_filing" id="farms_filing0" value="0" checked />No
                    </label>
                </div>

                <div class="form-group">
                    <label class="control-label">Do you require international?</label>
                    <label class="radio-inline">
                        <input type="radio" name="require_international" id="require_international1" value="1" @if(old('require_international') == 1) checked @endif />Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="require_international" id="require_international0" value="0" checked />No
                    </label>
                </div>

                <div class="form-group">
                    <label class="control-label">Do you require FICA tip credit tracking?</label>
                    <label class="radio-inline">
                        <input type="radio" name="require_fica" id="require_fica1" value="1" @if(old('require_fica') == 1) checked @endif />Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="require_fica" id="require_fica0" value="0" checked />No
                    </label>
                </div>

                <div class="form-group">
                    <label class="control-label">Do you require garnishment payment remits?</label>
                    <label class="radio-inline">
                        <input type="radio" name="require_garnishment" id="require_garnishment1" value="1" @if(old('require_garnishment') == 1) checked @endif />Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="require_garnishment" id="require_garnishment0" value="0" checked />No
                    </label>
                </div>
                
                <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Next</button>
            </div>
        </div>
    </div>
    <div class="row setup-content" id="step-3">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3> Step 3</h3>
                <div class="form-group">
                    <label class="control-label">How do you currently process payroll (yourself OR enter company name)</label>
                    <input name="payroll_process" type="text" class="form-control" placeholder="" />
                </div>

                <div class="form-group">
                    <label class="control-label">Do you offer company-sponsored health benefits?</label>
                    <label class="radio-inline">
                        <input type="radio" name="health_benefits" id="health_benefits1" value="1" @if(old('health_benefits') == 1) checked @endif />Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="health_benefits" id="health_benefits0" value="0" @if(old('health_benefits') === 0) checked @endif />No
                    </label>
                </div>

                <div class="form-group">
                    <label class="control-label">Who is your broker?</label>
                    <input name="health_broker" type="text" class="form-control" placeholder="" />
                </div>

                <div class="form-group">
                    <label class="control-label">Would you like to consider Gusto?</label>
                    <label class="radio-inline">
                        <input type="radio" name="consider" id="consider1" value="1" @if(old('consider') == 1) checked @endif />Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="consider" id="consider0" value="0" @if(old('consider') === 0) checked @endif />No
                    </label>
                </div>
                <button class="btn btn-success btn-lg pull-right" type="submit">Finish!</button>
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
