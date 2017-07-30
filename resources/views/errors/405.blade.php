@extends('layouts.app')

@section('content')
<h1>We're sorry but...</h1>
<h2>The page you're trying ot access is not allowed.</h2>
{{ url()->current() }}
@endsection
