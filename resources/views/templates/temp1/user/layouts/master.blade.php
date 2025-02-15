<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- PAGE TITLE HERE -->
	<title>{{ $general->sitename($page_title ?? '') }}</title>

	<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/png" href="{{ getImage(imagePath()['logoIcon']['path'] . '/favicon.png') }}">

	<link href="{{ asset($activeTemplateTrue) }}/dashboard/vendor/wow-master/css/libs/animate.css" rel="stylesheet">
	<link href="{{ asset($activeTemplateTrue) }}/dashboard/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset($activeTemplateTrue) }}/dashboard/vendor/bootstrap-select-country/css/bootstrap-select-country.min.css">
	<link rel="stylesheet" href="{{ asset($activeTemplateTrue) }}/dashboard/vendor/jquery-nice-select/css/nice-select.css">
	<link href="{{ asset($activeTemplateTrue) }}/dashboard/vendor/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">

	<!--swiper-slider-->
	<link rel="stylesheet" href="{{ asset($activeTemplateTrue) }}/dashboard/vendor/swiper/css/swiper-bundle.min.css">

    @stack('style-lib')
    <!-- Style css -->
	<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset($activeTemplateTrue) }}/dashboard/css/style.css" rel="stylesheet">

    @stack('style')

    @stack('css')
	
    <link href="{{ asset($activeTemplateTrue) }}/dashboard/{{ asset($activeTemplateTrue) }}/dashboard/css/style.css" rel="stylesheet" />
</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
	<div id="preloader">
	  <div class="loader"></div>
	</div>
    <!--*******************
        Preloader end
    ********************-->

    @yield('content')

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{ asset($activeTemplateTrue) }}/dashboard/vendor/global/global.min.js"></script>
	<script src="{{ asset($activeTemplateTrue) }}/dashboard/vendor/chart.js/Chart.bundle.min.js"></script>
	<script src="{{ asset($activeTemplateTrue) }}/dashboard/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<!-- Apex Chart -->
	<script src="{{ asset($activeTemplateTrue) }}/dashboard/vendor/apexchart/apexchart.js"></script>
	<!-- Chart piety plugin files -->
    <script src="{{ asset($activeTemplateTrue) }}/dashboard/vendor/peity/jquery.peity.min.js"></script>
	<script src="{{ asset($activeTemplateTrue) }}/dashboard/vendor/jquery-nice-select/js/jquery.nice-select.min.js"></script>
    <script src="{{ asset($activeTemplateTrue) }}/dashboard/js/plugins-init/piety-init.js"></script>
	<!--swiper-slider-->
	<script src="{{ asset($activeTemplateTrue) }}/dashboard/vendor/swiper/js/swiper-bundle.min.js"></script>
	<!-- Dashboard 1 -->
	<script src="{{ asset($activeTemplateTrue) }}/dashboard/js/dashboard/dashboard-1.js"></script>
	<script src="{{ asset($activeTemplateTrue) }}/dashboard/vendor/wow-master/dist/wow.min.js"></script>
	<script src="{{ asset($activeTemplateTrue) }}/dashboard/vendor/bootstrap-datetimepicker/js/moment.js"></script>
	<script src="{{ asset($activeTemplateTrue) }}/dashboard/vendor/datepicker/js/bootstrap-datepicker.min.js"></script>
	<script src="{{ asset($activeTemplateTrue) }}/dashboard/vendor/bootstrap-select-country/js/bootstrap-select-country.min.js"></script>
	
	<script src="{{ asset($activeTemplateTrue) }}/dashboard/js/dlabnav-init.js"></script>
    <script src="{{ asset($activeTemplateTrue) }}/dashboard/js/custom.min.js"></script>
	<script src="{{ asset($activeTemplateTrue) }}/dashboard/js/demo.js"></script>
    
	<script>
		
	$(function () {
			$("#datepicker").datepicker({ 
				autoclose: true, 
				todayHighlight: true
			}).datepicker('update', new Date());
	
	});

    $(document).ready(function(){
        $(".booking-calender .fa.fa-clock-o").removeClass(this);
        $(".booking-calender .fa.fa-clock-o").addClass('fa-clock');
		body.attr('data-theme-version', 'dark');
		body.attr('data-primary', 'color_10');
		$('form').on('submit', function () {
			$('.submit-btn').attr('disabled', 'true'); 
		});
    });
	jQuery(document).ready(function(){
		setTimeout(function(){
			dlabSettingsOptions.version = 'dark';
			dlabSettingsOptions.primary = 'color_10';
			new dlabSettings(dlabSettingsOptions);
		},1500)
	});
	$('.my-select').selectpicker();
	</script>

    @include('partials.notify')

    @stack('script-lib')

    @stack('script')

    @include('partials.plugins')
</body>

</html>
