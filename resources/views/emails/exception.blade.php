<h2>Error message from: {{ \Auth::user()->email }} ({{ \Auth::user()->name }})</h2>
<h3>Error Message: {{ $error }}</h3>
<ol>
@foreach($traces as $trace)
<li>
@if(isset($trace["file"]) )File: {{ $trace["file"] }} on line {{ $trace["line"] }}.@endif 
@if( isset($trace["class"]) ) Class: {{ $trace["class"] }} {{ $trace["type"] }}@endif @if( isset($trace["function"]) ) {{ $trace["function"] }}.@endif
</li>
@endforeach
</ol>
