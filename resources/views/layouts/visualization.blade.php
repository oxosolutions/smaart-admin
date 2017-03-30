<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Visualizations | SMAART&trade; Framework</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="{{asset('/bower_components/admin-lte/bootstrap/css/bootstrap.min.css')}}">
		<link rel="stylesheet" href="{{asset('/css/visualization-style.css')}}">

		<style>
		{{@$custom_code['custom_css']}}
		</style>
	</head> 
	<body>
		<div id="theme_{{@$theme}}" class="wrapper theme-{{@$theme}}">
			<div class="main">
				@yield('content')
			</div>	
		</div>
		<script src="{{asset('/bower_components/admin-lte/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
		<script src="{{asset('/js/visualization.js')}}" type="text/javascript">  </script>
		<script type="text/javascript">  
				<?php echo @$custom_code['custom_js']; ?>
		</script>



	</body>
</html>
