@extends('layouts.app')

@section('content')
<h2>{{ $event->name }}</h2>
{{ $event->description }}

<h3>Attendees</h3>
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
<form class="form-inline" method="POST" action="{{ route('calendar.invite.add_attendee', ['event_id'=>$event->id ]) }}">
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
  <button type="submit" class="btn btn-default">Add Attendee</button>
</form>
</div>
@endsection