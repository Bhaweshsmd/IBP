<script src="{{ URL::asset('/assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/sweetalert.js') }}"></script>
<script src="{{ URL::asset('/asset/cdnjs/iziToast.min.js')}}"></script>
<script src="{{ URL::asset('/assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('/assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ URL::asset('/assets/plugins/owl-carousel/owl.carousel.min.js') }}"></script>
<script src="{{ URL::asset('/assets/plugins/aos/aos.js') }}"></script>
<script src="{{ URL::asset('/assets/js/jquery.waypoints.js') }}"></script>
<script src="{{ URL::asset('/assets/js/jquery.counterup.min.js') }}"></script>
<script src="{{ URL::asset('/assets/plugins/theia-sticky-sidebar/ResizeSensor.js') }}"></script>
<script src="{{ URL::asset('/assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js') }}"></script>
<script src="{{ URL::asset('/assets/plugins/simple-calendar/jquery.simple-calendar.js') }}"></script>
<script src="{{ URL::asset('/assets/js/calander.js') }}"></script>
<script src="{{ URL::asset('/assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ URL::asset('/assets/plugins/ion-rangeslider/js/custom-rangeslider.js') }}"></script>
<script src="{{ URL::asset('/assets/js/moment.min.js') }}"></script>
<script src="{{URL::asset('/assets/plugins/moment/moment.min.js')}}"></script>
<script src="{{ URL::asset('/assets/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ URL::asset('/assets/plugins/apexchart/apexcharts.min.js') }}"></script>
<script src="{{ URL::asset('/assets/plugins/apexchart/chart-data.js') }}"></script>
<script src="{{ URL::asset('/assets/plugins/fancybox/jquery.fancybox.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/backToTop.js') }}"></script>
<script src="{{ URL::asset('/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('/assets/plugins/datatables/datatables.min.js') }}"></script>
@if (Route::is(['coaches-map', 'coaches-map-sidebar', 'listing-map-sidebar', 'listing-map']))
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD6adZVdzTvBpE2yBRK8cDfsss8QXChK0I"></script>
    <script src="{{ URL::asset('/assets/js/map.js') }}"></script>
@endif

@if (Route::is(['user-wallet']))
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://checkout.stripe.com/checkout.js"></script>
	<script src="{{ URL::asset('/assets/js/addfund.js') }}"></script>
@endif
@if(Route::is(['otp-verification','otp-verify','forgot-otp-verification']))
	<script src="{{ URL::asset('/assets/js/otp.js') }}"></script>
@endif
<script src="{{ URL::asset('/assets/js/script.js') }}"></script>