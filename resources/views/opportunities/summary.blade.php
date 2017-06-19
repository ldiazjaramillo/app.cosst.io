@extends('layouts.app')

@section('content')
@php $options = [''=>'',0=>"No", 1=>"Yes"] @endphp
<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#all" aria-controls="home" role="tab" data-toggle="tab">All</a></li>
    <li role="presentation"><a href="#sbiz" aria-controls="profile" role="tab" data-toggle="tab">SBIZ</a></li>
    <li role="presentation"><a href="#mmfs" aria-controls="messages" role="tab" data-toggle="tab">MMFS</a></li>
    <li role="presentation"><a href="#mmpr" aria-controls="settings" role="tab" data-toggle="tab">MMPR</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="all">
        <div>&nbsp;</div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>client_id</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>State</th>
                        <th>Employees</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @php $i=1; @endphp
                @foreach($opportunities as $opportunity)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $opportunity->client_id }}</td>
                        <td>{{ $opportunity->contact_name }}</td>
                        <td>{{ $opportunity->company_name }}</td>
                        <td>{{ $opportunity->contact_email }}</td>
                        <td>{{ $opportunity->company_state }}</td>
                        <td>{{ $opportunity->employees_number }}</td>
                        <td>{{ $opportunity->created_at }}</td>
                        <td><a href="/opportunity/view/{{$opportunity->id}}" class="btn btn-default">View</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="sbiz">
        <div>&nbsp;</div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>client_id</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>State</th>
                        <th>Employees</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @php $i=1; @endphp
                @foreach($opportunities->where('type_id', 1) as $opportunity)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $opportunity->client_id }}</td>
                        <td>{{ $opportunity->contact_name }}</td>
                        <td>{{ $opportunity->company_name }}</td>
                        <td>{{ $opportunity->contact_email }}</td>
                        <td>{{ $opportunity->company_state }}</td>
                        <td>{{ $opportunity->employees_number }}</td>
                        <td>{{ $opportunity->created_at }}</td>
                        <td><a href="/opportunity/view/{{$opportunity->id}}" class="btn btn-default">View</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="mmfs">
        <div>&nbsp;</div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>client_id</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>State</th>
                        <th>Employees</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @php $i=1; @endphp
                @foreach($opportunities->where('type_id', 2) as $opportunity)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $opportunity->client_id }}</td>
                        <td>{{ $opportunity->contact_name }}</td>
                        <td>{{ $opportunity->company_name }}</td>
                        <td>{{ $opportunity->contact_email }}</td>
                        <td>{{ $opportunity->company_state }}</td>
                        <td>{{ $opportunity->employees_number }}</td>
                        <td>{{ $opportunity->created_at }}</td>
                        <td><a href="/opportunity/view/{{$opportunity->id}}" class="btn btn-default">View</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="mmpr">
        <div>&nbsp;</div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>client_id</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>State</th>
                        <th>Employees</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @php $i=1; @endphp
                @foreach($opportunities->where('type_id', 3) as $opportunity)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $opportunity->client_id }}</td>
                        <td>{{ $opportunity->contact_name }}</td>
                        <td>{{ $opportunity->company_name }}</td>
                        <td>{{ $opportunity->contact_email }}</td>
                        <td>{{ $opportunity->company_state }}</td>
                        <td>{{ $opportunity->employees_number }}</td>
                        <td>{{ $opportunity->created_at }}</td>
                        <td><a href="/opportunity/view/{{$opportunity->id}}" class="btn btn-default">View</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
  </div>

</div>
@endsection
