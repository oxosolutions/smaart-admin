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
		<!-- Font Awesome -->
  		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
		{{-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">

      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});
	  // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Mushrooms', 4],
          ['Onions', 1],
          ['Olives', 1],
          ['Zucchini', 1],
          ['Apple', 2]
        ]);

        // Set chart options
        var options = {'title':'How Much Pizza I Ate Last Night',
                       'width':800,
                       'height':600};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        //var chart = new google.visualization.BarChart(document.getElementById('chart_div'));

        chart.draw(data, options);
      }
    </script> --}}
			
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

		<link rel="stylesheet" type="text/css" href="{{asset('js/bower/bower_components/seiyria-bootstrap-slider/dist/css/bootstrap-slider.css')}}">
		<script type="text/javascript" src="{{asset('js/bower/bower_components/seiyria-bootstrap-slider/dist/bootstrap-slider.js')}}"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				var mySlider = $("input.slider").bootstrapSlider();
				
				$(document).on('click','.chart-row > h4 > span > a > img',function(){
					
					if(!$(this).parents('.chart-row').find('.chart-wrapperr').hasClass('none') ){
						$(this).css({'transform':'rotate(0deg)','transition':'transform 0.5s'});
						$(this).parents('.chart-row').find('.chart-wrapperr').addClass('none');
						$(this).parents('.chart-row').find('h4').css({'border':'0px'});
						$(this).parents('.chart-row').find('.chart-wrapperr').slideUp();
					}else{
						$(this).parents('.chart-row').find('.chart-wrapperr').removeClass('none');
						$(this).css({'transform':'rotate(180deg)','transition':'transform 0.5s'});
						$(this).parents('.chart-row').find('h4').css({'border-bottom': '1px solid #e8e8e8'});
						$(this).parents('.chart-row').find('.chart-wrapperr').slideDown();
					}
					
				});
				
			});
		</script>
		<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.chart-wrapper-left').sortable({
		            handle: '.chart-sort-arrow'
		        });
		        $('.filter-title img').css({'transform':'rotate(180deg)','transation':'transform 0.5s'});
		        $('.filter-title img').click(function(){
		        	if($('.survey-chart-filters').hasClass('hideDiv')){
		        		$(this).css({'transform':'rotate(0deg)','transation':'transform 0.5s'});
			        	$('.survey-chart-filters').removeClass('hideDiv');
			        	$('.survey-chart-filters').slideUp();
		        	}else{
		        		$(this).css({'transform':'rotate(180deg)','transation':'transform 0.5s'});
			        	$('.survey-chart-filters').addClass('hideDiv');
			        	$('.survey-chart-filters').slideDown();
		        	}
			        	
		        });
			});
		</script>
	</body>
</html>
