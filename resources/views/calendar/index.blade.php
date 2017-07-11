@extends('layouts.app')

@section('content')
<h2>Calendar List</h2>
<a href="/calendar/create" class="btn btn-primary">Create Event</a> ( tomorrow at this same hour )
<div>&nbsp;</div>
<div class="table-responsive">
<table class="table table-bordered">
<thead>
    <tr>
        <th>Event Name</th>
        <th>Description</th>
        <th>Date</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
@foreach($events as $event)
    <tr>
        <td>{{ $event->getSummary() }}</td>
        <td>{{ $event->getDescription() }}</td>
        <td>{{ ($event->start->dateTime) ? $event->start->dateTime : $event->start->date }}</td>
        <td>{{ $event->getStatus() }}</td>
        <td>
            <a href="/calendar/delete/{{ $event->getId() }}" class="btn btn-danger">Delete</a>
            <a href="/calendar/update/{{ $event->getId() }}" class="btn btn-info">Update</a>
        </td>
    </tr>
@endforeach
</tbody>
</table>
</div>
@endsection
