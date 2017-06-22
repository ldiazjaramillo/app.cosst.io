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
    </tr>
</thead>
<tbody>
    @forelse($opportunities as $opportunity)
    <tr>
        <td>{{ $opportunity->client_id }}</td>
        <td>{{ $opportunity->contact_name }}</td>
        <td>{{ $opportunity->company_name }}</td>
        <td>{{ $opportunity->contact_email }}</td>
        <td>{{ ($search_type==1) ? $opportunity->event_time : $opportunity->event_date }}</td>
        <td>{{ $opportunity->gusto_agent }}</td>
        <td>{{ $opportunity->status_name }}</td>
    </tr>
    @empty
        <tr>
            <td colspan="8">No data available for this date</td>
        </tr>
    @endforelse
</tbody>
</table>
