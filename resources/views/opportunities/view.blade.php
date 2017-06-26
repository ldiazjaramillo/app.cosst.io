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
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>{{ $opportunity->contact_name }} at {{ $opportunity->company_name }}</h3>
        </div>
        <ul class="list-group">
            <li class="list-group-item"><strong>Is decision maker: </strong></strong>{{ $options[$opportunity->decision_maker] }}</li>
            <li class="list-group-item"><strong>Phone: </strong>{{ $opportunity->contact_phone }}</li>
            <li class="list-group-item"><strong>Email: </strong>{{ $opportunity->contact_email }}</li>
            <li class="list-group-item"><strong>Company State: </strong>{{ $opportunity->company_state }}</li>
            <li class="list-group-item"><strong>Employees: </strong>{{ $opportunity->employees_number }}</li>
            @if(isset($agents[$opportunity->type_id][$opportunity->agent_id]['name']))
            <li class="list-group-item"><strong>Gusto Agent: </strong>{{ $agents[$opportunity->type_id][$opportunity->agent_id]['name'] }}</li>
            @endif
            @if($opportunity->date)
            <li class="list-group-item"><strong>Invitation Date: </strong>{{ \Carbon\Carbon::parse($opportunity->date)->format('Y-m-d') }}</li>
            @endif
        </ul>
        <div class="panel-footer">
            &nbsp;
            <!--<a href="/summary" class="btn btn-default">Go back</a>-->
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3>Status: {{ $opportunity->status_options[$opportunity->status] }}</h3>
        </div>
        <ul class="list-group">
            <li class="list-group-item"><strong>Client ID: </strong>{{ $opportunity->client_id }}</li>
            @php $types = [null=>"N/A", 1=>"Cold Source", 2=>"Existing Source"] @endphp
            <li class="list-group-item"><strong>Lead Type: </strong>{{ $types[$opportunity->lead_type] }}</li>
        </ul>
        <div class="panel-footer">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-sm">Update Status</button>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="alert alert-warning alert-dismissible" role="alert" id="note_warning" style="display:none;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Warning!</strong> Notes can not be empty.
            </div>
            <div class="alert alert-danger alert-dismissible" role="alert" id="note_danger" style="display:none;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Error!</strong> An error occurred. Please, try again. If the error persists, please communicate with site's admin.
            </div>
            <div class="alert alert-success alert-dismissible" role="alert" id="note_success" style="display:none;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Warning!</strong> Notes were saved successfully.
            </div>
            <div class="form-group">
                <label>Notes:</label>
                <textarea name="comment" class="form-control" id="comments" cols="30" rows="10">{{ $opportunity->comment }}</textarea>
            </div>
        </div>
        <div class="panel-footer">
            <button type="button" class="btn btn-primary" id="save_notes">Save</button>
        </div>
    </div>

</div>
<!-- Small modal -->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
    <form action="{{ route('opportunity.status.store', [$opportunity->id]) }}" method="POST">
        {{ csrf_field() }}
        <div class="modal-body">
            <select name="status" id="status_id" class="form-control">
                @foreach($status_options as $value => $name)
                    <option value="{{ $value }}" @if($value == $status_id) selected @endif>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary">Update</button>
        </div>
    </form>
    </div>
  </div>
</div>
@endsection

@section('bottom_script')
<script>
$(document).ready(function () {
    $("#save_notes").on('click', function(){
        var comments = $("#comments").val();
        if(comments == ''){
            $("#note_danger, #note_success").hide();
            $("#note_warning").show();
            return;
        }
        $("#save_notes").addClass('disabled');
        $.ajax({
            type: "POST",
            url: "{{ route('opportunity.comments.store', [$opportunity->id]) }}",
            data: {
                'comments': comments
            },
        })
        .done(function() {
            $("#note_warning, #note_danger").hide();
            $("#note_success").show();
            $("#save_notes").removeClass('disabled');
        })
        .fail(function() {
            $("#note_warning, #note_success").hide();
            $("#note_danger").show();
        });
    });
});
</script>
@endsection
