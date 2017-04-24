@extends('layouts.default')

@section('content')
{{-- @include('block.market_summary') --}}
<script src="{{ url('/js/jquery-2.2.4.js')}}"></script>

<link rel="stylesheet" href="{{ url('/bootstrap-select/css/bootstrap-select.min.css') }}">
<script src="{{ url('/bootstrap-select/js/bootstrap-select.min.js')}}"></script>
<script src="{{ url('/bootstrap-select/js/i18n/defaults-*.min.js')}}"></script>
<script src="{{ url('/js/html2canvas.js')}}"></script>
<script type="text/javascript">
	function setCookie(cname, cvalue, exdays) {
	    var d = new Date();
	    d.setTime(d.getTime() + (exdays*24*60*60*1000));
	    var expires = "expires="+ d.toUTCString();
	    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}
	function getCookie(cname) {
	    var name = cname + "=";
	    var decodedCookie = decodeURIComponent(document.cookie);
	    var ca = decodedCookie.split(';');
	    for(var i = 0; i <ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0) == ' ') {
	            c = c.substring(1);
	        }
	        if (c.indexOf(name) == 0) {
	            return c.substring(name.length, c.length);
	        }
	    }
	    return "-1";
	}

</script>
<style>
    .tab-content > .tab-pane,
    .pill-content > .pill-pane {
        display: block;     /* undo display:none          */
        height: 0;          /* height:0 is also invisible */
        overflow-y: hidden; /* no-overflow                */
    }
    .tab-content > .active,
    .pill-content > .active {
        height: auto;       /* let the content decide it  */
    } /* bootstrap hack end */

</style>
@push('scripts')
<script src="{{ URL::asset('metronic_custom/highstock/code/js/highstock.js') }}"></script>
@endpush

<div class="row">
	<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" style="padding: 1px;">
	<form name="form1" action="/monitor/save_data" method="POST">
	{{ Form::open(array('action' => array('AjaxController@saveData', 'name'=>'form1'))) }}
		<input type="HIDDEN" name="symbols" id="symbols">
		<input type="HIDDEN" name="periods" id="periods">
        <button type="button" id="saveBtn" class="btn btn-primary" style="width: 100%" >Save My Watch List</button>
    {{ Form::close() }}
    </div>
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" style="padding: 1px;">
		<button type="button" id="shotBtn" class="btn btn-primary" style="width: 100%" >Screen Shot</button>
    </div>
</div>
<div class="row">
	@for ($id = 0; $id < 3; $id++)
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="padding: 5px; !important">
	     	@include('block.monitor_chart')
	    </div>
	@endfor
</div>
<div class="row">
     @for ($id = 3; $id < 6; $id++)
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="padding: 5px; !important">
	     	@include('block.monitor_chart')
	    </div>
	@endfor
</div>
<div class="row">
	@for ($id = 6; $id < 9; $id++)
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="padding: 5px; !important">
	     	@include('block.monitor_chart')
	    </div>
	@endfor
</div>

<script type="text/javascript">

$(document).ready(function(){

    $("#saveBtn").click(function(){
    	document.getElementById('symbols').value = '';
		for(i=0; i< 9 ; i++){
            var sel = 'symbol' + i;
            var per = 'period' + i;
            document.getElementById('symbols').value += document.getElementById(sel).value + ',';
            document.getElementById('periods').value += document.getElementById(per).value + ',';
        }       
        form1.submit();
    });
    $("#shotBtn").click(function(){
		html2canvas(document.body, {
		  onrendered: function(canvas) {
		    //document.body.appendChild(canvas);
		    var myImage = canvas.toDataURL("image/png");
            var printWindow = window.open(myImage);                        
            printWindow.document.close();
            printWindow.focus();
		  }
		});
	});
});	
</script>
{{-- @include('block.advance_chart')
@include('block.market_summary')
@include('block.market_summary') --}}
@endsection
