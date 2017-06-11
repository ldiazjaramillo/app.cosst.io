@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <form class="form-inline">
                <div class="form-group">
                    <a href="{{ route('opportunity.create') }}" class="btn btn-default">New Opportunity</a>
                </div>
                - Or -
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Existing Opportunity ID">
                </div>
                <button type="submit" class="btn btn-primary">Go</button>
            </form>
            <div class="form-group">
                
                
            </div>
        </div>
    </div>
</div>
@endsection
