<select name="status" id="status_id" class"form-control">
    @foreach($status_options as $value => $name)
        <option value="{{ $value }}" @if($value == $status_id) selected @endif>{{ $name }}</option>
    @endforeach
</select>
