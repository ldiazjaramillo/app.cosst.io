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
@php
$search_options = [
    ""=>"Search By",
    1=>"Event Date",
    2=>"Creation Date"
];
@endphp
<div class="row">
<form method="post" action="{{ route('opportunity.reports') }}">
{{ csrf_field() }}
     <div class='col-sm-4'>
        <div class="form-group">
            <select name="search_type" id="search_type" class="form-control" required>
            @foreach($search_options as $index => $value)
                <option value="{{ $index }}" @if($index == $search_type) selected @endif>{{ $value }}</option>
            @endforeach
            </select>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class="form-group">
            <input type="text" id="date" name="date" class="form-control" value="{{$search_date}}">
        </div>
    </div>
    <div class='col-sm-4'>
        <div class="form-group">
            <button class="btn btn-primary">Search</button>
        </div>
    </div>
</form>
</div>

@if(isset($opportunities))
@include('opportunities.list')
@endif

@endsection

@section('bottom_script')
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css">
<script>
$(document).ready(function(){
    $("#btn_spb").on('click', function(e){
        if(confirm('Did you sent calendly form?')) return;
        else e.preventDefault();
    });
    $('#date').datetimepicker({
        daysOfWeekDisabled: [0, 6],
        //inline: true,
        format: 'YYYY-MM-DD',
        sideBySide: false,
        useCurrent: false,  
    });
    $('#table').DataTable();
});
</script>
@endsection
