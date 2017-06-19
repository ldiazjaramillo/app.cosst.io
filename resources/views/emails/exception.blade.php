<h2>Error message from: {{ \Auth::user()->email }} ({{ \Auth::user()->name }})</h2>
<h3>Error Message: {{ $error }}</h3>
<ol>
@foreach($trace as $value)
<li>@if(isset($value["file"]))File: {{ $value["file"] }} on line {{ $value["line"] }}.@endif Function: {{ $value["function"] }}.@if(isset($value["class"])) Class: {{ $value["class"] }} {{ $value["type"] }}.@endif</li>
@endforeach
</ol>
