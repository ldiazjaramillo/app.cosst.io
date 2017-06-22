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
        <div class="form-group{{ $errors->has('search_type') ? ' has-error' : '' }}">
            <select name="search_type" id="search_type" class="form-control" required>
            @foreach($search_options as $index => $value)
                <option value="{{ $index }}" @if($index == $search_type) selected @endif>{{ $value }}</option>
            @endforeach
            </select>
        </div>
    </div>
    <div class='col-sm-2'>
        <div class="form-group{{ $errors->has('date_from') ? ' has-error' : '' }}">
            <input type="text" id="date_from" name="date_from" class="form-control" value="{{$date_from}}">
        </div>
    </div>
    <div class='col-sm-2'>
        <div class="form-group"{{ $errors->has('date_to') ? ' has-error' : '' }}>
            <input type="text" id="date_to" name="date_to" class="form-control" value="{{$date_to}}">
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
<style>
}
.dataTables_wrapper .dataTables_paginate .paginate_button:active {
    outline: none;
    background-color: none;
    background: -moz-linear-gradient(top, #2b2b2b 0%, #0c0c0c 100%);
    background: -ms-linear-gradient(top, #2b2b2b 0%, #0c0c0c 100%);
    background: -o-linear-gradient(top, #2b2b2b 0%, #0c0c0c 100%);
    box-shadow: none;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    color: white !important;
    border: none;
    background-color: none;
    background: none;
    background: -moz-linear-gradient(top, #585858 0%, #111 100%);
    background: -ms-linear-gradient(top, #585858 0%, #111 100%);
    background: -o-linear-gradient(top, #585858 0%, #111 100%);
}
}
</style>
<script>
$(document).ready(function(){
    $("#btn_spb").on('click', function(e){
        if(confirm('Did you sent calendly form?')) return;
        else e.preventDefault();
    });
    $('#date_from').datetimepicker({
        daysOfWeekDisabled: [0, 6],
        //inline: true,
        format: 'YYYY-MM-DD',
        sideBySide: false,
        useCurrent: false,  
    });
    $('#date_to').datetimepicker({
        daysOfWeekDisabled: [0, 6],
        format: 'YYYY-MM-DD',
        useCurrent: false //Important! See issue #1075
    });
    $("#date_from").on("dp.change", function (e) {
        $('#date_to').data("DateTimePicker").minDate(e.date);
    });
    $("#date_to").on("dp.change", function (e) {
        $('#date_from').data("DateTimePicker").maxDate(e.date);
    });
    $('#table').DataTable();
});
</script>
@endsection
