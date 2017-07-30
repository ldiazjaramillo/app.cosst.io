@extends('layouts.app')

@section('content')
<div class="col-md-6">
    <div class="panel panel-info">
        <!-- Default panel contents -->
        <div class="panel-heading"><h3>{{ $event->name }}</h3></div>
        <div class="panel-body">
            <p>{!! $event->description !!}</p>
        </div>

        <!-- List group -->
        <ul class="list-group">
            <li class="list-group-item"><label for="">From:</label> {{ $event->startDateTime }}</li>
            <li class="list-group-item"><label>To:</label> {{ $event->endDateTime }}</li>
            <li class="list-group-item"><label>Timezone:</label> {{ $event->timeZone }}</li>
        </ul>
        <div class="panel-footer text-right">
            <form action="{{ route('calendar.invite.delete', [ $event->id ]) }}" method="POST">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <a href="#" class="btn btn-info" data-toggle="modal" data-target="#date_modal">Change Date</a>
                <button class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>
<div class="col-md-6">
<div class="panel panel-success">
    <div class="panel-heading">
        <h3>Attendees</h3>
    </div>
    <div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
    @foreach($event->attendees as $attendee)
        <tr>
            <td>{{ $attendee->displayName }}</td>
            <td>{{ $attendee->email }}</td>
            <td>{{ $attendee->responseStatus }}</td>
        </tr>
    @endforeach
    </table>
    </div>
</div>
<form class="form" method="POST" action="{{ route('calendar.invite.add_attendee', ['event_id'=>$event->id ]) }}">
    <div class="panel panel-default">
        <div class="panel-heading"><h4>Add an attendee</h4></div>
        <div class="panel-body">
                    {{ csrf_field() }}
                <div class="form-group">
                    <label for="exampleInputName2">Name</label>
                    <input type="text" name="name" class="form-control" id="attendee_name" placeholder="Attendee Name">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail2">Email</label>   
                    <input type="email" name="email" class="form-control" id="attendee_email" placeholder="attendee@example.com">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail2">Status</label>
                    <select name="status" class="form-control" id="attendee_status">
                        <option value="needsAction">needsAction</option>
                        <option value="declined">declined</option>
                        <option value="tentative">tentative</option>
                        <option value="accepted">accepted</option>
                    </select>
                </div>
        </div>
        <div class="panel-footer text-right">
            <button type="submit" class="btn btn-primary">Add Attendee</button>
        </div>
    </div>
</form>
</div>

<!-- Date modal -->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="date_modal">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
    <form action="{{ route('calendar.invite.update.dates', [$event->id]) }}" method="POST">
        {{ method_field('PUT') }}
        {{ csrf_field() }}
        <div class="modal-body">
            <div class="form-group">
                <label for="">Agent</label>
                <select name="agent_id" id="agent_id" class="form-control">
                @foreach($agents as $agent)
                    <option value="{{ $agent->id }}" @if($event->getAgentId() == $agent->id) selected @endif>{{ $agent->name }}</option>
                @endforeach
                </select>

            </div>
            <div class="form-group">
                <label for="">Date and Time</label>
                <div class='input-group date' id='update_date'>
                    <input type='text' class="form-control" name="date" @if($opportunity->date) value="{{ \Carbon\Carbon::parse($opportunity->date, $opportunity->timezone)->format('m/d/Y h:i A') }}" @endif />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
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
    $('#update_date').datetimepicker({
        daysOfWeekDisabled: [0, 6],
        //inline: true,
        sideBySide: true,
        stepping: 15,
        minDate: moment(),
        useCurrent: false,
    });
});
</script>
@endsection