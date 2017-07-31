@extends('layouts.app')

@section('content')
@php
$products_category = [
    0=>"None",
    1=>"ATS",
    2=>"Job Boards/Postings/Advertising",
    3=>"Ad Agency",
    4=>"Recruitment Agency",
    5=>"Other Advertising",
    6=>"Other Technology",
];
$future_purchase = [
    0=>"None",
    1=>"ATS",
    2=>"Ad Agency",
    3=>"Niche Job Postings",
    4=>"General Job boards",
    5=>"Regional Job boards",
    6=>"Aggregators",
    7=>"Distirbution Solutions",
    8=>"Programmatic Advertising Solutions",
    9=>"Pay per applicant",
    10=>"Recruitment Services/Agency/Exec Search",
    11=>"OFCCP Compliance",
    12=>"Social Recruiting",
    13=>"Mobile Recruiting",
    14=>"Other",
];
$future_purchase_type = [
    0=>"None",
    1=>"HealthCare",
    2=>"Finance",
    3=>"Sales",
    4=>"Tech",
    5=>"Other",
];

$product_interest = [
    1=>"PPC Advertising",
    2=>"PPA Advertising",
    3=>"Programmatic ad buying",
    4=>"Mobile Solutions",
];

$contact_by = [
    1=>"Phone call",
    2=>"Email",
    3=>"SMS",
    4=>"Social message/connection",
    5=>"Other",
];
@endphp
<div class="stepwizard">
    <div class="stepwizard-row setup-panel">
        <div class="stepwizard-step">
            <a href="#lead_step" type="button" class="btn btn-primary btn-circle">lead</a>
            <p>Lead Info</p>
        </div>
        <div class="stepwizard-step">
            <a href="#q-1" type="button" class="btn btn-default btn-circle" disabled="disabled">1</a>
            <p>Question #1</p>
        </div>
        <div class="stepwizard-step">
            <a href="#q-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
            <p>Question #2</p>
        </div>
        <div class="stepwizard-step">
            <a href="#q-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
            <p>Question #3</p>
        </div>
        <div class="stepwizard-step">
            <a href="#q-4" type="button" class="btn btn-default btn-circle" disabled="disabled">4</a>
            <p>Question #4</p>
        </div>
        <div class="stepwizard-step">
            <a href="#q-5" type="button" class="btn btn-default btn-circle" disabled="disabled">5</a>
            <p>Question #5</p>
        </div>
        <div class="stepwizard-step">
            <a href="#q-6" type="button" class="btn btn-default btn-circle" disabled="disabled">6</a>
            <p>Question #6</p>
        </div>
        <div class="stepwizard-step">
            <a href="#q-7" type="button" class="btn btn-default btn-circle" disabled="disabled">7</a>
            <p>Question #7</p>
        </div>
        <div class="stepwizard-step">
            <a href="#q-8" type="button" class="btn btn-default btn-circle" disabled="disabled">8</a>
            <p>Question #8</p>
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
<form role="form" method="POST" action="{{ route('survey.store') }}">
    {{ csrf_field() }}
    <input name="lead[id]" type="hidden" @if($lead) value="{{$lead->id}}" @endif />
    <input name="survey[lead_id]" type="hidden" @if($lead) value="{{$lead->id}}" @endif />
    <input name="survey[client_id]" type="hidden" value="{{ session()->get('working_client.id') }}" />
<div class="setup-content" id="lead_step">
    <h3>Lead Info</h3>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[first_name]') ? ' has-error' : '' }}">
                <label class="control-label">First Name:</label>
                <input name="lead[first_name]" maxlength="100" type="text" class="form-control" placeholder="Lead First Name" @if($lead) value="{{$lead->first_name}}" @else value="{{old('lead[first_name]')}}" @endif />
                @if ($errors->has('lead[first_name]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[first_name]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[last_name]') ? ' has-error' : '' }}">
                <label class="control-label">Last Name:</label>
                <input name="lead[last_name]" maxlength="100" type="text" class="form-control" placeholder="Lead Last Name" @if($lead) value="{{$lead->last_name}}" @else value="{{old('lead[last_name]')}}" @endif />
                @if ($errors->has('lead[last_name]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[last_name]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[middle_name]') ? ' has-error' : '' }}">
                <label class="control-label">Middle name:</label>
                <input name="lead[middle_name]" maxlength="100" type="text" class="form-control" placeholder="Lead Middle Name" @if($lead) value="{{$lead->middle_name}}" @else value="{{old('lead[middle_name]')}}" @endif />
                @if ($errors->has('lead[middle_name]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[middle_name]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[salutation]') ? ' has-error' : '' }}">
                <label class="control-label">Salutation:</label>
                <input name="lead[salutation]" maxlength="100" type="text" class="form-control" placeholder="Lead Salutation" @if($lead) value="{{$lead->salutation}}" @else value="{{old('lead[salutation]')}}" @endif />
                @if ($errors->has('lead[salutation]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[salutation]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[suffix]') ? ' has-error' : '' }}">
                <label class="control-label">Suffix:</label>
                <input name="lead[suffix]" maxlength="100" type="text" class="form-control" placeholder="Lead suffix" @if($lead) value="{{$lead->suffix}}" @else value="{{old('lead[suffix]')}}" @endif />
                @if ($errors->has('lead[suffix]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[suffix]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[job_title]') ? ' has-error' : '' }}">
                <label class="control-label">Job title:</label>
                <input name="lead[job_title]" maxlength="100" type="text" class="form-control" placeholder="Lead job_title" @if($lead) value="{{$lead->job_title}}" @else value="{{old('lead[job_title]')}}" @endif />
                @if ($errors->has('lead[job_title]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[job_title]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[job_title_hierarchy_level]') ? ' has-error' : '' }}">
                <label class="control-label">Job title hierarchy level:</label>
                <input name="lead[job_title_hierarchy_level]" maxlength="100" type="text" class="form-control" placeholder="Job title hierarchy level" @if($lead) value="{{$lead->job_title_hierarchy_level}}" @else value="{{old('lead[job_title_hierarchy_level]')}}" @endif />
                @if ($errors->has('lead[job_title_hierarchy_level]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[job_title_hierarchy_level]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[job_function]') ? ' has-error' : '' }}">
                <label class="control-label">Job Function:</label>
                <input name="lead[job_function]" maxlength="100" type="text" class="form-control" placeholder="Job title hierarchy level" @if($lead) value="{{$lead->job_function}}" @else value="{{old('lead[job_function]')}}" @endif />
                @if ($errors->has('lead[job_function]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[job_function]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[management_level]') ? ' has-error' : '' }}">
                <label class="control-label">Management Level:</label>
                <input name="lead[management_level]" maxlength="100" type="text" class="form-control" placeholder="Job title hierarchy level" @if($lead) value="{{$lead->management_level}}" @else value="{{old('lead[management_level]')}}" @endif />
                @if ($errors->has('lead[management_level]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[management_level]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[company_division_name]') ? ' has-error' : '' }}">
                <label class="control-label">Company division name:</label>
                <input name="lead[company_division_name]" maxlength="100" type="text" class="form-control" placeholder="Job title hierarchy level" @if($lead) value="{{$lead->company_division_name}}" @else value="{{old('lead[company_division_name]')}}" @endif />
                @if ($errors->has('lead[company_division_name]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[company_division_name]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[direct_phone_number]') ? ' has-error' : '' }}">
                <label class="control-label">Direct Phone Number:</label>
                <input name="lead[direct_phone_number]" maxlength="100" type="text" class="form-control" placeholder="Job title hierarchy level" @if($lead) value="{{$lead->direct_phone_number}}" @else value="{{old('lead[direct_phone_number]')}}" @endif />
                @if ($errors->has('lead[direct_phone_number]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[direct_phone_number]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[email_address]') ? ' has-error' : '' }}">
                <label class="control-label">Email address:</label>
                <input name="lead[email_address]" maxlength="100" type="text" class="form-control" placeholder="Job title hierarchy level" @if($lead) value="{{$lead->email_address}}" @else value="{{old('lead[email_address]')}}" @endif required />
                @if ($errors->has('lead[email_address]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[email_address]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[person_street]') ? ' has-error' : '' }}">
                <label class="control-label">Person Street:</label>
                <input name="lead[person_street]" maxlength="100" type="text" class="form-control" placeholder="Job title hierarchy level" @if($lead) value="{{$lead->person_street}}" @else value="{{old('lead[person_street]')}}" @endif />
                @if ($errors->has('lead[person_street]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[person_street]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[person_city]') ? ' has-error' : '' }}">
                <label class="control-label">Person City:</label>
                <input name="lead[person_city]" maxlength="100" type="text" class="form-control" placeholder="Job title hierarchy level" @if($lead) value="{{$lead->person_city}}" @else value="{{old('lead[person_city]')}}" @endif />
                @if ($errors->has('lead[person_city]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[person_city]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[person_state]') ? ' has-error' : '' }}">
                <label class="control-label">Person State:</label>
                <select name="lead[person_state]" id="person_state" class="form-control select2">
                    <option value="">Select</option>
                @foreach(config('app.us_states') as $index=>$value)
                    <option value="{{ $index }}" @if($lead) @if($lead->person_state == $value) selected @endif @endif>{{ $value }}</option>
                @endforeach
                </select>
                @if ($errors->has('lead[person_state]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[person_state]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[person_zip]') ? ' has-error' : '' }}">
                <label class="control-label">Person Zip:</label>
                <input name="lead[person_zip]" maxlength="100" type="text" class="form-control" placeholder="Person Zip" @if($lead) value="{{$lead->person_zip}}" @else value="{{old('lead[person_zip]')}}" @endif />
                @if ($errors->has('lead[person_zip]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[person_zip]') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[country]') ? ' has-error' : '' }}">
                <label class="control-label">Country:</label>
                <input name="lead[country]" maxlength="100" type="text" class="form-control" placeholder="Country" @if($lead) value="{{$lead->country}}" @else value="{{old('lead[country]')}}" @endif />
                @if ($errors->has('lead[country]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[country]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>
    <h3>Company Info</h3>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[company_name]') ? ' has-error' : '' }}">
                <label class="control-label">Company name:</label>
                <input name="lead[company_name]" maxlength="100" type="text" class="form-control" placeholder="Company name" @if($lead) value="{{$lead->company_name}}" @else value="{{old('lead[company_name]')}}" @endif />
                @if ($errors->has('lead[company_name]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[company_name]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[company_domain_name]') ? ' has-error' : '' }}">
                <label class="control-label">Company domain name:</label>
                <input name="lead[company_domain_name]" maxlength="100" type="text" class="form-control" placeholder="Company domain name" @if($lead) value="{{$lead->company_domain_name}}" @else value="{{old('lead[company_domain_name]')}}" @endif />
                @if ($errors->has('lead[company_domain_name]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[company_domain_name]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[company_phone_number]') ? ' has-error' : '' }}">
                <label class="control-label">Company phone number:</label>
                <input name="lead[company_phone_number]" maxlength="100" type="text" class="form-control" placeholder="Company phone number" @if($lead) value="{{$lead->company_phone_number}}" @else value="{{old('lead[company_phone_number]')}}" @endif />
                @if ($errors->has('lead[company_phone_number]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[company_phone_number]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[company_street_address]') ? ' has-error' : '' }}">
                <label class="control-label">Company Street address:</label>
                <input name="lead[company_street_address]" maxlength="100" type="text" class="form-control" placeholder="Company Street address" @if($lead) value="{{$lead->company_street_address}}" @else value="{{old('lead[company_street_address]')}}" @endif />
                @if ($errors->has('lead[company_street_address]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[company_street_address]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[company_city]') ? ' has-error' : '' }}">
                <label class="control-label">Company City:</label>
                <input name="lead[company_city]" maxlength="100" type="text" class="form-control" placeholder="Company City" @if($lead) value="{{$lead->company_city}}" @else value="{{old('lead[company_city]')}}" @endif />
                @if ($errors->has('lead[company_city]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[company_city]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[company_state]') ? ' has-error' : '' }}">
                <label class="control-label">Company State:</label>
                <select name="lead[company_state]" id="company_state" class="form-control select2">
                    <option value="">Select</option>
                @foreach(config('app.us_states') as $index=>$value)
                    <option value="{{ $index }}" @if($lead) @if($lead->company_state == $value) selected @endif @endif>{{ $value }}</option>
                @endforeach
                </select>
                @if ($errors->has('lead[company_state]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[company_state]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[company_zip_postal_code]') ? ' has-error' : '' }}">
                <label class="control-label">Company ZIP/Postal code:</label>
                <input name="lead[company_zip_postal_code]" maxlength="100" type="text" class="form-control" placeholder="Company ZIP/Postal code" @if($lead) value="{{$lead->company_zip_postal_code}}" @else value="{{old('lead[company_zip_postal_code]')}}" @endif />
                @if ($errors->has('lead[company_zip_postal_code]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[company_zip_postal_code]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[company_country]') ? ' has-error' : '' }}">
                <label class="control-label">Company Country:</label>
                <input name="lead[company_country]" maxlength="100" type="text" class="form-control" placeholder="Company Country" @if($lead) value="{{$lead->company_country}}" @else value="{{old('lead[company_country]')}}" @endif />
                @if ($errors->has('lead[company_country]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[company_country]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[industry_label]') ? ' has-error' : '' }}">
                <label class="control-label">Industry label:</label>
                <input name="lead[industry_label]" maxlength="100" type="text" class="form-control" placeholder="Industry label" @if($lead) value="{{$lead->industry_label}}" @else value="{{old('lead[industry_label]')}}" @endif />
                @if ($errors->has('lead[industry_label]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[industry_label]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[industry_hierarchical_category]') ? ' has-error' : '' }}">
                <label class="control-label">Industry hierarchical category:</label>
                <input name="lead[industry_hierarchical_category]" maxlength="100" type="text" class="form-control" placeholder="Industry hierarchical category" @if($lead) value="{{$lead->industry_hierarchical_category}}" @else value="{{old('lead[industry_hierarchical_category]')}}" @endif />
                @if ($errors->has('lead[industry_hierarchical_category]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[industry_hierarchical_category]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[secondary_industry_label]') ? ' has-error' : '' }}">
                <label class="control-label">Secondary industry label:</label>
                <input name="lead[secondary_industry_label]" maxlength="100" type="text" class="form-control" placeholder="Secondary industry label" @if($lead) value="{{$lead->secondary_industry_label}}" @else value="{{old('lead[secondary_industry_label]')}}" @endif />
                @if ($errors->has('lead[secondary_industry_label]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[secondary_industry_label]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[revenue_in_000s]') ? ' has-error' : '' }}">
                <label class="control-label">Revenue (in 000s):</label>
                <input name="lead[revenue_in_000s]" maxlength="100" type="text" class="form-control" placeholder="Revenue (in 000s)" @if($lead) value="{{$lead->revenue_in_000s}}" @else value="{{old('lead[revenue_in_000s]')}}" @endif />
                @if ($errors->has('lead[revenue_in_000s]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[revenue_in_000s]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[revenue_range]') ? ' has-error' : '' }}">
                <label class="control-label">Revenue Range:</label>
                <input name="lead[revenue_range]" maxlength="100" type="text" class="form-control" placeholder="Revenue Range" @if($lead) value="{{$lead->revenue_range}}" @else value="{{old('lead[revenue_range]')}}" @endif />
                @if ($errors->has('lead[revenue_range]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[revenue_range]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[employees]') ? ' has-error' : '' }}">
                <label class="control-label">Employees:</label>
                <input name="lead[employees]" maxlength="100" type="text" class="form-control" placeholder="Employees" @if($lead) value="{{$lead->employees}}" @else value="{{old('lead[employees]')}}" @endif />
                @if ($errors->has('lead[employees]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[employees]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('lead[employees_range]') ? ' has-error' : '' }}">
                <label class="control-label">Employees range:</label>
                <input name="lead[employees_range]" maxlength="100" type="text" class="form-control" placeholder="Employees range" @if($lead) value="{{$lead->employees_range}}" @else value="{{old('lead[employees_range]')}}" @endif />
                @if ($errors->has('lead[employees_range]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lead[employees_range]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>
    <a class="btn btn-success btn-lg pull-right nextBtn">Next</a>
</div>

<div class="setup-content" id="q-1">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group{{ $errors->has('survey[products_category]') ? ' has-error' : '' }}">
                <label class="control-label">1. Which of the following products are you responsible for making decisions about purchasing? </label>
                @foreach($products_category as $value => $name)
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="survey[products_category][]" class="checkbox_products_category" value="{{ $value }}"> {{ $name }}
                        </label>
                    </div>
                @endforeach
                Other: <input type="text" name="survey[products_category_other]" class="form-control" disabled />
                @if ($errors->has('survey[products_category]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('survey[products_category]') }}</strong>
                    </span>
                @endif
            </div>
            <a class="btn btn-success btn-lg pull-right nextBtn">Next</a>
        </div>
    </div>
</div>
<div class="setup-content" id="q-2">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('survey[future_purchase]') ? ' has-error' : '' }}">
                <label class="control-label">2. Which of the following do you and your company plan on purchasing in the next 3 months?</label>
                @foreach($future_purchase as $value => $name)
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="survey[future_purchase][]" class="checkbox_future_purchase" value="{{ $value }}"> {{ $name }}
                        </label>
                    </div>
                @endforeach
                Other: <input type="text" name="survey[future_purchase_other]" class="form-control" disabled />
                @if ($errors->has('survey[future_purchase]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('survey[future_purchase]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('survey[future_purchase_type]') ? ' has-error' : '' }}">
                <label class="control-label">Type</label>
                @foreach($future_purchase_type as $value => $name)
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="survey[future_purchase_type][]" class="checkbox_future_purchase_type" value="{{ $value }}"> {{ $name }}
                        </label>
                    </div>
                @endforeach
                Other: <input type="text" name="survey[future_purchase_type_other]" class="form-control" disabled />
                @if ($errors->has('survey[future_purchase_type]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('survey[future_purchase_type]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <a class="btn btn-success btn-lg pull-right nextBtn">Next</a>
        </div>
    </div>
</div>
<div class="setup-content" id="q-3">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group{{ $errors->has('survey[product_interest]') ? ' has-error' : '' }}">
                <label class="control-label">3. Are you interested in learning about any of the following product/services?</label>
                @foreach($product_interest as $value => $name)
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="survey[product_interest][]" class="checkbox_product_interest" value="{{ $value }}"> {{ $name }}
                        </label>
                    </div>
                @endforeach
                @if ($errors->has('survey[product_interest]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('survey[product_interest]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <a class="btn btn-success btn-lg pull-right nextBtn">Next</a>
        </div>
    </div>
</div>
<div class="setup-content" id="q-4">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group{{ $errors->has('survey[client_needs]') ? ' has-error' : '' }}">
                <label class="control-label">4. Do you have specific needs at this time that a member might be able to help you with:</label>
                <textarea name="survey[client_needs]" class="form-control" id="survey[client_needs]" cols="30" rows="5"></textarea>
                @if ($errors->has('survey[client_needs]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('survey[client_needs]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <a class="btn btn-success btn-lg pull-right nextBtn">Next</a>
        </div>
    </div>
</div>
<div class="setup-content" id="q-5">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group{{ $errors->has('survey[client_product_introduce]') ? ' has-error' : '' }}">
                <label class="control-label">5. Is there anything we can tell members about the best way to introduce their products to you?</label>
                <textarea name="survey[client_product_introduce]" class="form-control" id="survey[client_product_introduce]" cols="30" rows="5"></textarea>
                @if ($errors->has('survey[client_product_introduce]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('survey[client_product_introduce]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <a class="btn btn-success btn-lg pull-right nextBtn">Next</a>
        </div>
    </div>
</div>

<div class="setup-content" id="q-6">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group{{ $errors->has('survey[contact_by]') ? ' has-error' : '' }}">
                <label class="control-label">6. How do you like people to contact you?</label>
                @foreach($contact_by as $value => $name)
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="survey[contact_by][]" class="checkbox_contact_by" value="{{ $value }}"> {{ $name }}
                        </label>
                    </div>
                @endforeach
                Other: <input type="text" name="survey[contact_by_other]" class="form-control" disabled />
                @if ($errors->has('survey[contact_by]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('survey[contact_by]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <a class="btn btn-success btn-lg pull-right nextBtn">Next</a>
        </div>
    </div>
</div>

<div class="setup-content" id="q-7">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group{{ $errors->has('survey[season]') ? ' has-error' : '' }}">
                <label class="control-label">7. Is there a "season" that you are most likely to consider a new vendor?</label>
                <textarea name="survey[season]" class="form-control" id="survey[season]" cols="30" rows="5"></textarea>
                @if ($errors->has('survey[season]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('survey[season]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <a class="btn btn-success btn-lg pull-right nextBtn">Next</a>
        </div>
    </div>
</div>
<div class="setup-content" id="q-8">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group{{ $errors->has('survey[favorite_vendors]') ? ' has-error' : '' }}">
                <label class="control-label">8. Do you have some favorite vendors in TAtech?</label>
                <textarea name="survey[favorite_vendors]" class="form-control" id="survey[favorite_vendors]" cols="30" rows="5"></textarea>
                @if ($errors->has('survey[favorite_vendors]'))
                    <span class="help-block">
                        <strong>{{ $errors->first('survey[favorite_vendors]') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-12 text-right">
            <button class="btn btn-primary">Save</button>
        </div>
    </div>
</div>
</form>
@endsection

@section('bottom_script')
<script>
$(document).ready(function () {
    $('input[name="survey[products_category][]"]').change(function(){
        if(this.value == 0) {
            if(this.checked){
                $('input[name="survey[products_category_other]"]').prop('disabled', true);
                $('input[name="survey[products_category][]"]').prop('checked', false);
                $('input[name="survey[products_category][]"]').prop('disabled', true);
                this.checked = true;
                this.disabled = false;
            }else{
                $('input[name="survey[products_category_other]"]').prop('disabled', true);
                $('input[name="survey[products_category][]"]').prop('checked', false);
                $('input[name="survey[products_category][]"]').prop('disabled', false);
            }
        }
        
        if( this.value == 6 || this.value == 5 ) $('input[name="survey[products_category_other]"]').prop('disabled', !this.checked);
    });

    $('input[name="survey[future_purchase][]"]').change(function(){
        if(this.value == 0) {
            if(this.checked){
                $('input[name="survey[future_purchase_other]"]').prop('disabled', true);
                $('input[name="survey[future_purchase][]"]').prop('checked', false);
                $('input[name="survey[future_purchase][]"]').prop('disabled', true);
                this.checked = true;
                this.disabled = false;
            }else{
                $('input[name="survey[future_purchase_other]"]').prop('disabled', true);
                $('input[name="survey[future_purchase][]"]').prop('checked', false);
                $('input[name="survey[future_purchase][]"]').prop('disabled', false);
            }
        }
        
        if( this.value == 14 ) $('input[name="survey[future_purchase_other]"]').prop('disabled', !this.checked);
    });

    $('input[name="survey[future_purchase_type][]"]').change(function(){
        if(this.value == 0) {
            if(this.checked){
                $('input[name="survey[future_purchase_type_other]"]').prop('disabled', true);
                $('input[name="survey[future_purchase_type][]"]').prop('checked', false);
                $('input[name="survey[future_purchase_type][]"]').prop('disabled', true);
                this.checked = true;
                this.disabled = false;
            }else{
                $('input[name="survey[future_purchase_type_other]"]').prop('disabled', true);
                $('input[name="survey[future_purchase_type][]"]').prop('checked', false);
                $('input[name="survey[future_purchase_type][]"]').prop('disabled', false);
            }
        }
        
        if( this.value == 5 ) $('input[name="survey[future_purchase_type_other]"]').prop('disabled', !this.checked);
    });

    $('input[name="survey[contact_by][]"]').change(function(){        
        if( this.value == 5 ) $('input[name="survey[contact_by_other]"]').prop('disabled', !this.checked);
    });


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