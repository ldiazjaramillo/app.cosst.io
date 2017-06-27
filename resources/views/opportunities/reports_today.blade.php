@extends('layouts.app')

@section('content')
@php
$search_options = [
    ""=>"Search By",
    1=>"Event Date",
    2=>"Creation Date",
    3=>''
];
@endphp
<div class="col-md-4 col-md-offset-2">
    <canvas id="myChart1"></canvas>
</div>
<div class="col-md-4">
    <canvas id="myChart2"></canvas>
</div>
<div class="row">&nbsp;<br/></div>
<div class="row">&nbsp;<br/></div>
<table class="table table-bordered" id="table">
<thead>
    <tr>
        <th>Client ID</th>
        <th>Contact Name</th>
        <th>Company Name</th>
        <th>Email</th>
        <th>{{ ($search_type==1) ? "Time" : "Invite" }}</th>
        <th>Gusto Agent</th>
        <th>Status</th>
        <th></th>
    </tr>
</thead>
<tbody>
    @forelse($opportunities as $opportunity)
    <tr>
        <td>{{ $opportunity->client_id }}</td>
        <td>{{ $opportunity->contact_name }}</td>
        <td>{{ $opportunity->company_name }}</td>
        <td>{{ $opportunity->contact_email }}</td>
        <td>{{ $opportunity->today_time }}</td>
        <td>{{ $opportunity->gusto_agent }}</td>
        <td>{{ $opportunity->status_name }}</td>
        <td><a href="{{ route('opportunity.view', [$opportunity->id]) }}" class="btn btn-default">View</a></td>
    </tr>
    @empty
        <tr>
            <td colspan="8">No data available for this date</td>
        </tr>
    @endforelse
</tbody>
</table>

@php
$data = [];
foreach( $opportunities->sortBy('status')->groupBy('status') as $index => $value):
    $data[$value->first()->status_options[$index]] = $value->count();
endforeach;
$data2 = array( "Total" => $opportunities->count() ) + $data;
@endphp
@endsection

@section('bottom_script')
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"</script>
<script src="//cdn.datatables.net/buttons/1.3.1/js/buttons.flash.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
<script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
<script src="//cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.3.1/js/buttons.print.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
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
.dt-buttons{
    display: inline;
}
a.dt-button{
    margin-left: 5px;
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
    $('#table').DataTable({
        dom: 'lBfrtip',
        buttons: [
            'csv', 'excel', 'pdf', 'print'
        ],
        "aoColumnDefs": [
            { "bSearchable": false, "aTargets": [ 7 ] },
            { "bSortable": false, "aTargets": [ 7 ] }
        ],
    });
    $(".dt-button").addClass("btn btn-default");
    var ctx1 = document.getElementById('myChart1').getContext('2d');
    var ctx2 = document.getElementById('myChart2').getContext('2d');
    var chart1 = new Chart(ctx1, {
        // The type of chart we want to create
        type: 'doughnut',
        // The data for our dataset
        data: {
            labels: {!! json_encode( array_keys($data), true ) !!},
            //["DB registered","Event scheduled","Not interested","Meeting confirmed","Meeting held","Meeting cancelled","Meeting rescheduled","No show"],
            datasets: [{
                label: "My First dataset",
                backgroundColor: [
                    "rgb(54, 162, 235)",
                    "rgb(75, 192, 192)",
                    "rgb(201, 203, 207)",
                    "rgb(255, 159, 64)",
                    "rgb(153, 102, 255)",
                    "rgb(255, 99, 132)",
                    "rgb(255, 205, 86)",
                    "rgb(255, 205, 250)"
                ],
                //borderColor: 'rgb(255, 99, 132)',
                data: {!! json_encode( array_values($data) ) !!},
            }]
        },

        // Configuration options go here
        options: {
            responsive: true,
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Today Stats'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });
    var chart2 = new Chart(ctx2, {
        // The type of chart we want to create
        type: 'horizontalBar',
        // The data for our dataset
        data: {
            labels: {!! json_encode( array_keys($data2), true ) !!},
            //["DB registered","Event scheduled","Not interested","Meeting confirmed","Meeting held","Meeting cancelled","Meeting rescheduled","No show"],
            datasets: [{
                label: "Total",
                backgroundColor: [
                    "rgb(255, 105, 250)",
                    "rgb(54, 162, 235)",
                    "rgb(75, 192, 192)",
                    "rgb(201, 203, 207)",
                    "rgb(255, 159, 64)",
                    "rgb(153, 102, 255)",
                    "rgb(255, 99, 132)",
                    "rgb(255, 205, 86)",
                    "rgb(255, 205, 250)"
                ],
                //borderColor: 'rgb(255, 99, 132)',
                data: {!! json_encode( array_values($data2) ) !!},
            }]
        },

        // Configuration options go here
        options: {
            responsive: true,
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Today Stats'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            },
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
});
</script>
@endsection
