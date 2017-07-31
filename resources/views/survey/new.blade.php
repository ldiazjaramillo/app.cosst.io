@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <form class="form" action={{ route('survey.create') }}>
                <div class="form-group">
                    <select name="lead_id" id="lead_id" class="form-control input-lg">
                        <option value="">Search lead...</option>
                    </select>
                </div>
                <div>&nbsp;</div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary btn-lg">Go</button>
                </div>
            </form>
            <div class="form-group">
                
                
            </div>
        </div>
    </div>
</div>
@endsection

@section('bottom_script')
<script>
$(document).ready(function () {
    $("#lead_id").select2({
        ajax: {
            url: "{{ route('get.survey.leads') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;
                return {
                    results: data.items,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        //escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 3,
        //templateResult: formatRepo, // omitted for brevity, see the source of this page
        //templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });
});
</script>
@endsection
