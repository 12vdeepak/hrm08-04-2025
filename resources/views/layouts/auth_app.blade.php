<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>

		<!-- META DATA -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta content="DayOne - It is one of the Major Dashboard Template which includes - HR, Employee and Job Dashboard. This template has multipurpose HTML template and also deals with Task, Project, Client and Support System Dashboard." name="description">
		<meta content="Spruko Technologies Private Limited" name="author">
		<meta name="keywords" content="admin dashboard, dashboard ui, backend, admin panel, admin template, dashboard template, admin, bootstrap, laravel, laravel admin panel, php admin panel, php admin dashboard, laravel admin template, laravel dashboard, laravel admin panel"/>

		<!-- TITLE -->
		<title>HRM</title>

        @include('layouts.components.custom-styles')

	</head>

	<body class="login-img">

        @yield('switcher-icon')
					<!-- SWITCHER ICON CODE -->

					<!-- <div class="dropdown custom-layout">
						<div class="demo-icon nav-link  icon  float-end mt-5 me-5">
							<i class="fe fe-settings fa-spin text_primary"></i>
						</div>
					</div> -->

					<!-- END SWITCHER ICON CODE -->
					@yield('content')

					<div class = "mybadge-container">
						@if(Session::has('error'))
							<span class="mybadge badge-red">{{ session('error') }}</span>
						@endif

						@if(Session::has('success'))
							<span class="mybadge badge-green">{{ session('success') }}</span>
						@endif
					</div>


					@include('layouts.components.custom-scripts')

	</body>
</html>
