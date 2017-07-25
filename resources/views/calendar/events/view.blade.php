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
            <li class="list-group-item"><label>Porta ac consectetur ac</label></li>
            <li class="list-group-item"><label>Vestibulum at eros</label></li>
        </ul>
        <div class="panel-footer text-right">
            actions
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
<div class="panel panel-default">
    <div class="panel-heading"><h4>Add an attendee</h4></div>
    <div class="panel-body">
        <form class="form" method="POST" action="{{ route('calendar.invite.add_attendee', ['event_id'=>$event->id ]) }}">
                {{ csrf_field() }}
            <div class="form-group">
                <label for="exampleInputName2">Name</label>
                <input type="text" name="name" class="form-control" id="attendee_name" placeholder="Jane Doe">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail2">Email</label>   
                <input type="email" name="email" class="form-control" id="attendee_email" placeholder="jane.doe@example.com">
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
    </form>
</div>
</div>
@endsection