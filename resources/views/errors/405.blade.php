@extends('layouts.app')

@section('content')
<h1>We're sorry but...</h1>
<h2>The client id was not found. We could not continue the notification proccess.</h2>
{{ url()->current() }}
@endsection
