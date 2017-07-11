@extends('layouts.app')

@section('content')
    @if($has_calendar_token)
        <h2>You are already connected to your Google Calendar</h2>
    @else
        <a href="{{ $auth_url }}" class="btn btn-default">Connect to google calendar</a>
    @endif
@endsection
