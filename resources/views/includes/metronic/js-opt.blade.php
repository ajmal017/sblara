<!--[if lt IE 9]>
<script src="{{ asset('metronic/assets/global/plugins/respond.min.js') }}"></script>
<script src="{{ asset('metronic/assets/global/plugins/excanvas.min.js') }}"></script>
<script src="{{ asset('metronic/assets/global/plugins/ie8.fix.min.js') }}"></script>
<![endif]-->
{{--<script src="{{ mix('metronic_home.js') }}"></script>--}}

<script src="{{ asset('metronic/assets/global/plugins/jquery.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.0.5/sweetalert2.all.js"></script>
<script src="{{ asset('metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('metronic/assets/global/plugins/js.cookie.min.js') }}"></script>
<script src="{{ asset('metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('metronic/assets/global/plugins/jquery.blockui.min.js') }}"></script>
<script src="{{ asset('metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<script src="{{ asset('metronic/assets/global/plugins/counterup/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('metronic/assets/global/plugins/counterup/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('metronic/assets/global/plugins/horizontal-timeline/horizontal-timeline.js') }}"></script>
<script src="{{ asset('metronic/assets/global/plugins/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('metronic/assets/global/scripts/app.min.js') }}"></script>
<script src="{{ asset('metronic/assets/layouts/layout5/scripts/layout.min.js') }}"></script>
<script src="{{ asset('metronic/assets/layouts/global/scripts/quick-sidebar.min.js') }}"></script>
<script src="{{ asset('metronic/assets/layouts/global/scripts/quick-nav.min.js') }}"></script>
{{--<script src="{{ asset('') }}"></script>--}}


<script src="{{ asset('metronic_custom/custom.js') }}"></script>
<script src="{{ asset('js/application.js') }}"></script>
<script src="{{ asset('metronic_custom/highstock/code/js/highstock.js') }}"></script>

<!-- Bootstrap Select -->
<script src="{{ asset('metronic/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('metronic/assets/pages/scripts/components-bootstrap-select.min.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('metronic/assets/global/plugins/select2/js/select2.full.min.js') }}"></script>

<!-- Datepicker -->
<script src="{{ asset('metronic/assets/global/plugins/moment.min.js') }}"></script>
<script src="{{ asset('metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('metronic/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('metronic/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('metronic/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('metronic/assets/global/plugins/clockface/js/clockface.js') }}"></script>


<!-- UI Button Confirmation -->
<script src="{{ asset('metronic/assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js') }}"></script>
<script src="{{ asset('metronic/assets/pages/scripts/ui-confirmations.min.js') }}"></script>

<!-- Datatables -->
<script src="{{ asset('metronic/assets/global/scripts/datatable.js') }}"></script>
<script src="{{ asset('metronic/assets/global/plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('metronic/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}"></script>
<script src="{{ asset('metronic/assets/pages/scripts/table-datatables-buttons.min.js') }}"></script>
<script src="{{ asset('metronic/assets/global/plugins/horizontal-timeline/horizontal-timeline.js') }}"></script>
<script src="{{ asset('metronic/assets/global/plugins/horizontal-timeline/horizontal-timeline.js') }}"></script>

<!-- Toastr -->
<script src="{{ asset('metronic/assets/global/plugins/bootstrap-toastr/toastr.min.js') }}"></script>
@include('includes.flash.toastr')
<!-- END PAGE LEVEL SCRIPTS -->

<script src="{{ asset('maxazan-jquery-treegrid/js/jquery.treegrid.min.js') }}"></script>


<script src="{{ asset('jquery-easy-ticker-master/test/jquery.easing.min.js') }}"></script>
<script src="{{ asset('jquery-easy-ticker-master/jquery.easy-ticker.js') }}"></script>

<!--<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
  <script>tinymce.init({ selector:'textarea' });</script>-->
  
<script>
	$(document).ready(function ()
	{
	    $('#clickmewow').click(function ()
	    {
	        $('#radio1003').attr('checked', 'checked');
	    });
	})
</script>

<script src="{{ asset('js/slimscroll.min.js') }}"></script>
<script src="{{ asset('js/search.js') }}"></script>

<script src="{{asset('vendor/laravel-filemanager/js/lfm.js')}}"></script>
<script src="{{ asset('js/se.js') }}?v={{uniqid()}}"></script>
    <script src="{{ asset('js/filter.js') }}" type="text/javascript"></script>
@stack('scripts')