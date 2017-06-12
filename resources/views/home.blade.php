@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <form class="form" action={{ route('opportunity.create') }}>
                <div class="form-group">
                    <select name="new_id" id="new_id" class="form-control input-lg select2">
                        <option value="">New Opportunity ID</option>
                    @foreach($new_opportunities as $value => $name)
                        <option value="{{ $value }}">{{ $name }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group text-center">
                - Or -
                </div>
                <div class="form-group">
                    <select name="existing_id" id="new_id" class="form-control input-lg select2">
                        <option value="">Existing Opportunity ID</option>
                    @foreach($existing_opportunities as $value => $name)
                        <option value="{{ $value }}">{{ $name }}</option>
                    @endforeach
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
    $('.select2').select2();
});
</script>
@endsection
