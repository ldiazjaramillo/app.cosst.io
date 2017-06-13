@extends('layouts.app')

@section('content')
<h1>Congrats!!</h1>
<h2>Calendar invitations and Slack notification has been sent</h2>
<h2>Also, Google sheet file has been update it. Take a look at it, <a href="https://drive.google.com/drive/folders/{{ ENV('GOOGLE_DRIVE_FOLDER_ID') }}" target="_blank">Here</a></h2>
@endsection
