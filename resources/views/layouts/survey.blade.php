<!DOCTYPE html>
<html>
<style type="text/css">
	.footer{
		width: 100%;
	    max-width: 980px;
	    margin: 0 auto !important;
	}
</style>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Survey | SMAART&trade; Framework</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="{{asset('/css/survey-style.css')}}">
		<link rel="stylesheet" href="{{asset('/bower_components/admin-lte/bootstrap/css/bootstrap.min.css')}}">
	</head>
	<body>
		<div class="wrapper">
			<div class="main">
				@yield('content')
			</div>
			<div class="survey-footer footer" style="background: grey;color: white;">
				<div class="wrapper-row ">
				
					&copy; copyright 2017 
					
				</div> <!-- wrapper-row -->
			</div> <!-- survey-footer -->		
		</div>
		<script src="{{asset('/bower_components/admin-lte/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>

		<script src="{{asset('/js/survey.js')}}" type="text/javascript">  </script>



	</body>
</html>
