@extends('layouts.app')

@section('content')
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('client.select.store') }}" method="POST">
{{ csrf_field() }}
<div class="col-md-4 col-md-offset-4">
    <div class="form-group">
        <label for="">Select client to work with</label>
        <select name="client_id" id="client_id" class="form-control">
            <option value="">Select Client</option>
        @foreach($clients as $client)
            <option value="{{$client->id}}">{{$client->name}}</option>
        @endforeach
        </select>
    </div>
    <div class="form-group text-center">
        <button class="btn btn-primary">Save</button>
    </div>
</div>
</form>
@endsection