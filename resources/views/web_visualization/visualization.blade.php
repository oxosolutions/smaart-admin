@extends('layouts.visualization')
@section('content')

<?php

$visualization_id = $visualizations['visualization_id'];
$visualization_name = $visualizations['visualization_name'];

$meta = $visualizations['visualization_meta'];
$charts = $visualizations['visualizations'];

$visualization_theme = 'minimal';

if(isset($meta['theme']) && $meta['theme'] != ''){
	$visualization_theme = $meta['theme'];
}
$sidebar_class="no-sidebar";
if(
	isset($meta['filters_position']) 
	&& ($meta['filters_position'] == 'left' || $meta['filters_position'] == 'right') 
){
	$sidebar_class = $meta['filters_position']."-sidebar";
}


/*
echo "<pre>";
print_r($visualizations);
echo "</pre>";
*/
?>

<div id="theme_{{$visualization_theme}}" class="wrapper theme-{{$visualization_theme}}">
	<div id="visualization_{{$visualization_id}}" class="main visualization visualization-{{$visualization_id}}">

		@if(isset($meta['show_topbar']) && $meta['show_topbar'] == 1)
			<!--==============================-->
			<div id="aione_topbar_{{$visualization_id}}" class="aione-box aione-topbar aione-options">
				<div class="wrapper-row">
					<div class="aione-section-header aione-topbar-header">
						<div class="aione-section-header-title">
							<div class="aione-section-title">
								Select Charts to Display
							</div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="aione-section-content aione-topbar-content">
						<div class="widget-toggles">
							@foreach($titles as $chart_id => $title)
								<div class="widget-toggle">
							      <input type="checkbox" class="filled-in show-hide-charts" id="{{$chart_id}}_checkbox" data-hide="{{$chart_id}}" checked="checked" value="{{$chart_id}}"/>
							      <label for="{{$chart_id}}_checkbox">{{ucwords($title)}}</label>
							    </div>
						    @endforeach
					    </div>
					</div>
				</div> <!-- wrapper-row -->
			</div> <!-- aione_topbar -->
		@endif

		@if(isset($meta['show_header']) && $meta['show_header'] == 1)
			<!--==============================-->
			<div id="aione_header_{{$visualization_id}}" class="aione-box aione-header">
				<div class="wrapper-row ">
					<h1 class="aione-header-title">{!! $visualization_name !!} </h1>
					@if(isset($meta['visualization_description']) && $meta['visualization_description'] != '')
					<h3 class="aione-header-description">{!! $meta['visualization_description'] !!}</h3>
					@endif
				</div> <!-- wrapper-row -->
			</div> <!-- show_header -->
		@endif

		<!--==============================-->
		<div id="aione_content_{{$visualization_id}}" class="aione-box aione-content aione-content-{{$sidebar_class}}">
			<div class="wrapper-row padding-0">

				@if(isset($meta['filters_position']) && $meta['filters_position'] != 'bottom')
					@include('web_visualization.filters')
				@endif
				<!--==============================-->
				<div id="aione_content_main_{{$visualization_id}}" class="aione-box aione-content-main">
					<div class="wrapper-row padding-0">
						
						<!--==============================-->
						<div id="aione_selected_filters_{{$visualization_id}}" class="aione-box aione-selected-filters">
							<div class="wrapper-row padding-0">


							</div> <!-- wrapper-row -->
						</div> <!-- aione_selected_filters -->

						<!--==============================-->
						<div id="aione_charts_{{$visualization_id}}" class="aione-box aione-charts">
							<div class="wrapper-row  padding-0">

							<!--==============================-->
							<!--==============================-->

							@foreach($charts as $chart_key => $chart)

								<?php
								$chart_id = $chart_key;
								$chart_type = $chart['chart_type'];
								$chart_title = $chart['title'];
								$chart_enabled = $chart['enableDisable'];
								?>

								@if(1)
									<div id="chart_wrapper_{{$chart_id}}" class="aione-chart aione-chart-{{$chart_type}}">
										@if(isset($meta['show_chart_title']) && $meta['show_chart_title'] == 1)
										<div class="aione-section-header aione-topbar-header">
											<div class="aione-section-header-title">
												@if(isset($meta['sortable_chart_widgets']) && $meta['sortable_chart_widgets'] == 1)
												<div class="aione-section-handle"></div>
												@endif

												<div class="aione-section-title">{{$chart_title}}</div>
											</div>
											<div class="aione-section-header-actions">
												@if(isset($meta['collapsable_chart_widgets']) && $meta['collapsable_chart_widgets'] == 1)
												<span class="aione-section-header-action aione-widget-toggle aione-widget-collapse"></span>
												@endif
												@if(isset($meta['show_topbar']) && $meta['show_topbar'] == 1)
												<span class="aione-section-header-action aione-widget-toggle aione-widget-close"></span>
												@endif
											</div>
											<div class="clear"></div>
										</div>
										@endif
										<div id="" class="aione-chart-content">
											

											@if($chart_type == 'CustomMap')
												<div id="{{$chart_id}}" class="map-wrapper">
												{!! $details[$chart_key]['map'] !!}
												</div>
											@else
												<div id="{{$chart_id}}" class="chart-wrapperr"></div>
												{!! lava::render($chart_type,$chart_key,$chart_id) !!}

											@endif
										</div>
									</div>
								@endif




								







							@endforeach

					
								

							</div> <!-- wrapper-row -->
						</div> <!-- aione_charts -->

					</div> <!-- wrapper-row -->
				</div> <!-- aione_content_main -->
				<div class="clear"></div>

				@if(isset($meta['filters_position']) && $meta['filters_position'] == 'bottom')
					@include('web_visualization.filters')
				@endif

			</div> <!-- wrapper-row -->
		</div> <!-- aione_topbar -->


		@if(isset($meta['show_footer']) && $meta['show_footer'] == 1)
			@if(isset($meta['footer_content']) && $meta['footer_content'] != '')
				<!--==============================-->
				<div id="aione_footer_{{$visualization_id}}" class="aione-box aione-footer">
					<div class="wrapper-row ">
						{!!  $meta['footer_content'] !!} 
					</div> <!-- wrapper-row -->
				</div> <!-- aione_footer -->
			@endif
		@endif

		@if(isset($meta['show_copyright']) && $meta['show_copyright'] == 1)
			<!--==============================-->
			<div id="aione_copyright_{{$visualization_id}}" class="aione-box aione-copyright">
				<div class="wrapper-row ">
					©2017 <a href="http://smaartframework.com/" target="_blank">SMAART™ Framework</a>
				</div> <!-- wrapper-row -->
			</div> <!-- aione_copyright -->
		@endif

		@if(isset($meta['show_loader']) && $meta['show_loader'] == 1)
			<!--==============================-->
			<div id="aione_loader_{{$visualization_id}}" class="aione-loader">
				<div class="loading-animation">
					<div class="loading-bar">
						<div class="blue-bar"></div> 
					</div>
				</div>
			</div> <!-- aione_loader -->
		@endif


	</div>	
</div>
<script src="{{asset('/bower_components/admin-lte/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<script src="{{asset('/js/visualization.js')}}" type="text/javascript"></script>

<script type="text/javascript">
	$(document).ready(function(){
			var chartsList = '{!! json_encode($javascript) !!}';

			//console.log("==111111");
			
			$.each(JSON.parse(chartsList), function(key,val){
				//console.log("==22222222--"+key);
				//console.log("==22222222--"+key);
				$.each(val.data, function(ikey, ival){
					//console.log("==333333--"+ikey);
					var index = 0;
					$.each(ival, function(dataKey, dataVal){
						
						var colorVal = index/val.data.length;
						var leagendWidth = (1/(val.data.length-1))*100;
						var colorCode = getColor(colorVal);
						
						var putId = val.headers[dataKey].replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, "_");
						var currentClass = $('#'+ival[0]).attr('class');
						//console.log("--"+currentClass);
						$('#'+key+' #'+ival[0]).attr(putId,dataVal);
						$('#'+key+' #'+ival[0]).css({'fill': colorCode }).attr('class','mapArea '+currentClass);
					});
					index++;
					//console.log(ival);
				});
			});

			$('.map-wrapper .mapArea').mouseover(function (e) {
                var elm = $(this);
                var title=$(this).attr('title');
                var html = '';
                html += '<div class="inf">';
                html += '<span class="title">'+title + '</span>';
                $.each(JSON.parse(chartsList), function(key, val){
                	$.each(val.headers, function(k_in, v){
	                    if(k_in > 0){
	                        var atr_id = v.replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, "_");
	                        html += '<span class="data">'+v+': '+ elm.attr(atr_id)+'</span>';
	                    }
	                });
                });
                html += '</div>';
                $(html).appendTo('body');
            })
            .mouseleave(function () {
                $('.inf').remove();
            }).mousemove(function(e) {
                var mouseX = e.pageX, //X coordinates of mouse
                    mouseY = e.pageY; //Y coordinates of mouse

                $('.inf').css({
                    'top': mouseY-($('.inf').height()+30),
                    'left': mouseX
                });
            });
            /*console.log(lava);
            lava.getChart('chart_2', function (googleChart, lavaChart) {

			    
			});*/
				/*setTimeout(function(){
					console.log(lava.charts.BarChart['chart_1'].data.Mf);
					data = new google.visualization.DataTable();
			        data.addColumn('string', 'Topping');
			        data.addColumn('number', 'Slices');
			        data.addRows([
			          ['Mushrooms', 3],
			          ['Onions', 1],
			          ['Olives', 1],
			          ['Zucchini', 1],
			          ['Pepperoni', 2]
			        ]);

			        // Set chart options
			        var options = {'title':'How Much Pizza I Ate Last Night',
			                       'width':400,
			                       'height':300};

			        // Instantiate and draw our chart, passing in some options.
			        chart = new google.visualization.PieChart(document.getElementById('chart_1'));
			        
			        chart.draw(data, options);
				},3000);*/
				
		      
		      

			function getColor(value){
	            var hue=((1-value)*50).toString(10);
	            return ["hsl(",hue,",100%,50%)"].join("");
	        }

	        $('.show-hide-charts').click(function(){
	        	if($(this).is(':checked')){
	        		$('#'+$(this).attr('data-hide')).parents('.chart-row').show();
	        	}else{
	        		$('#'+$(this).attr('data-hide')).parents('.chart-row').hide();
	        	}
	        });
	});
</script>


@if(isset($meta['custom_js_code']) && $meta['custom_js_code'] != '')
	<script type="text/javascript">  
		<?php echo @$meta['custom_js_code']; ?>
	</script>
@endif

@if(isset($meta['custom_css_code']) && $meta['custom_css_code'] != '')
	<style type="text/css"> 
		<?php echo @$meta['custom_css_code']; ?>
	</style>
@endif

<script type="text/javascript">  
	$( document ).ready(function() {
		$( '.aione-loader' ).hide();
	});
	$('.aione-widget-options .aione-options input').change(function(){
		var target_widget_id = $(this).val();
		var is_checked = $(this).prop('checked');
		if(is_checked){
			$("#"+target_widget_id).show();
		} else{
			$("#"+target_widget_id).hide();
		}
	});
	$('.aione-widget-collapse').click(function(){
		$(this).toggleClass('active');
		$(this).parent().parent().parent().find('.aione-chart-content').slideToggle(100);
	});
	$('.aione-widget-close').click(function(){
		var target_option_name = $(this).parent().parent().parent().attr('data-option');
		$(".widget-toggles .widget-toggle [name="+target_option_name+"]").prop('checked', false);
		$(this).parent().parent().parent().hide(); 
	});
	$('.aione-options-handle').click(function(){
		$(this).find('.fa').toggleClass('fa-rotate-180');
		$(this).parent().find('.aione-options').slideToggle(300);
	});
</script>

<style type="text/css">
.theme-clean_light .main{
	max-width:980px;
	margin-top: 20px;
	margin-bottom: 20px; 
}
.theme-clean_dark .main{
	background-color:#333333; 
}
.theme-clean_dark .land{
	fill: #454545;
    stroke: #282828; 
    stroke-width: 0.3; 
}
.theme-clean_dark .mapArea{
	fill:#ff9800;
}
 
 .map-wrapper{
	 text-align:center;
 }
 .map-wrapper svg {
    max-width: 100%;
}



.aione-topbar {
    border: 1px solid #e8e8e8;
    margin-bottom: 20px;
    position: relative;
}
.widget-toggles {
    padding: 10px 0;
}
.widget-toggles .widget-toggle{
	display: inline-block;
	margin: 0 20px 0 0;
}
.widget-toggles .widget-toggle label{
	cursor: pointer;
}
.aione-header {
    border: 1px solid #e8e8e8;
    margin-bottom: 20px;
    position: relative;
}
.aione-header-title{
	color: #666666;
	text-align: center;
	font-size: 24px;
	line-height: 30px;
	font-weight: 400;
	padding: 10px 0 10px 0;
	margin: 0;
	position: relative;
	font-family: "Open Sans", Arial, Helvetica, sans-serif;
}
/*
.aione-header-title:before {
    content: "";
    width: 160px;
    height: 0;
    background: #ffffff;
    border-bottom: 1px solid #a8a8a8;
    display: block;
    position: absolute;
    top: 55px;
    left: 50%;
    margin: 0 0 0 -80px;
}


.aione-header-title:after {
    content: "";
    width: 16px;
    height: 16px;
    background: #ffffff;
    border: 2px solid #747474;
    display: block;
    position: absolute;
    top: 47px;
    border-radius: 50%;
    left: 50%;
    margin: 0 0 0 -8px;
    box-shadow: 0px 0px 0px 4px #FFFFFF;
}
*/
.aione-header-description{
	color: #747474;
    text-align: center;
    font-size: 18px;
    line-height: 24px;
    font-weight: 400;
    padding: 10px 0 10px 0;
    margin: 0;
    font-family: "Open Sans", Arial, Helvetica, sans-serif;
}

.aione-section-header{
	border-bottom: 1px solid #e8e8e8;
}
.aione-section-header .aione-section-header-title{
	float:left;
	padding: 0 0 0 15px;
}
.aione-section-header .aione-section-header-actions{
	float: right;
    width: 100px;
    text-align: right;
    position: relative;
}

.aione-section-header .aione-section-header-title .aione-section-handle{
	display: inline-block;
    padding: 15px 0 0 0;
    width: 20px;
    margin-right: 10px;
    position: relative;
    cursor:move;
}
.aione-section-header:hover .aione-section-header-title .aione-section-handle:before,
.aione-section-header:hover .aione-section-header-title .aione-section-handle:after{
	border-color:#999999; 
}
.aione-section-header .aione-section-header-title .aione-section-handle:before{
	content: "";
    width: 20px;
    height: 4px;
    background: #ffffff;
    border-top: 3px solid #d2d2d2;
    border-bottom: 3px solid #d2d2d2;
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    margin: 0;
}
.aione-section-header .aione-section-header-title .aione-section-handle:after{
	content: "";
    width: 20px;
    height: 0;
    background: #ffffff;
    border-top: 3px solid #d2d2d2;
    display: block;
    position: absolute;
    top: 14px;
    left: 0;
    margin: 0;
}
.aione-section-header .aione-section-header-title .aione-section-title{
	display:inline-block;
	color: #747474;
    text-align: left;
    font-size: 18px;
    line-height: 24px;
    font-weight: 400;
    padding: 10px 0 10px 0;
    margin: 0;
    font-family: "Open Sans", Arial, Helvetica, sans-serif;
}
.aione-section-header .aione-section-header-actions .aione-section-header-action{
	display: inline-block;
    width: 20px;
    height: 20px;
    position: relative;
    cursor: pointer;
    margin: 10px 10px 10px 0;
    transition: all 200ms ease-in-out;
}
.aione-section-header .aione-section-header-actions .aione-widget-close{
	transform: rotate(45deg);
}
.aione-section-header .aione-section-header-actions .aione-widget-close:hover{
	transform: rotate(135deg);
}
.aione-section-header .aione-section-header-actions .aione-widget-close:before{
	content: "";
    width: 20px;
    height: 0;
    border-top: 1px solid #d2d2d2;
    display: block;
    position: absolute;
    top: 9.5px;
    left: 0;
    margin: 0;
}
.aione-section-header .aione-section-header-actions .aione-widget-close:after{
	content: "";
    width: 0;
    height: 20px;
    border-left: 1px solid #d2d2d2;
    display: block;
    position: absolute;
    top: 0;
    left: 9.5px;
    margin: 0;
}
.aione-section-header:hover .aione-section-header-actions .aione-widget-close:before,
.aione-section-header:hover .aione-section-header-actions .aione-widget-close:after{
    border-color: #cc0000;
}

.aione-section-header .aione-section-header-actions .aione-widget-collapse:before{
	content: "";
    width: 0;
    height: 0;
    border-top: 6px solid #d2d2d2;
    border-right: 6px solid transparent;
    border-left: 6px solid transparent;
    display: block;
    position: absolute;
    top: 8px;
    left: 4px;
}
.aione-section-header .aione-section-header-actions .aione-widget-collapse.active:before{
    border-top: none;
    border-bottom: 6px solid #d2d2d2;
}
.aione-section-header:hover .aione-section-header-actions .aione-widget-collapse:before{
    border-top-color: #999999;
}
.aione-section-header:hover .aione-section-header-actions .aione-widget-collapse.active:before{
    border-bottom-color: #999999;
}

.aione-sidebar {
    margin-bottom:20px;
    border: 1px solid #e8e8e8;
}

.aione-content-left-sidebar .aione-content-main,
.aione-content-right-sidebar .aione-content-main{
	width:72%;
}
.aione-content-left-sidebar .aione-content-main{
	float:right;
}
.aione-content-right-sidebar .aione-content-main{
	float:left;
}


.aione-sidebar.aione-sidebar-position-left,
.aione-sidebar.aione-sidebar-position-right{
	width:25%;
}

.aione-sidebar.aione-sidebar-position-right{
	float:right;
}
.aione-sidebar.aione-sidebar-position-left{
	float:left;
}

.aione-charts .aione-chart{
	display: block;
	overflow: hidden;
}




.aione-footer {
    text-align: center;
    padding: 20px;
    margin-bottom:20px;
    border: 1px solid #e8e8e8;
}

.padding-0{
	padding: 0;
	padding-top: 0;
	padding-right: 0;
	padding-bottom: 0; 
	padding-left: 0;
}


.aione-loader{
	position: fixed;
	top:0;
	right: 0;
	bottom: 0;
	left: 0;
	z-index: 80;
	display: block;
	text-align: center;
	background-color: #ffffff;
} 

.loading-animation{
    position: absolute;
    top: 50%;
    left: 50%;
    width: 280px;
    margin: -2px 0 0 -140px;
    -o-transform: scale(1);
    -ms-transform: scale(1);
    -moz-transform: scale(1);
    -webkit-transform: scale(1);
    transform: scale(1);
    transition: -webkit-transform .5s ease;
    transition: transform .5s ease;
    transition: transform .5s ease, -webkit-transform .5s ease;
}
.loading-animation .loading-bar{
    width: 60%;
    height: 4px;
    margin: 0 auto;
    border-radius: 2px;
    background-color: #cfcfcf;
    position: relative;
    overflow: hidden;
    z-index: 1;
    transition: -webkit-transform .3s ease-in;
    transition: transform .3s ease-in;
    transition: transform .3s ease-in, -webkit-transform .3s ease-in;
}
.loading-animation .loading-bar .blue-bar{
    height: 100%;
    width: 50%;
    position: absolute;
    background-color: #3596d8;
    border-radius: 2px;
    -moz-animation: initial-loading 1.5s infinite ease;
    -webkit-animation: initial-loading 1.5s infinite ease;
    animation: initial-loading 1.5s infinite ease;
}

@-webkit-keyframes initial-loading{
    0%,100%{
        -webkit-transform:translate(-50%,0);
        transform:translate(-50%,0);
    }
    50%{
        -webkit-transform:translate(150%,0);
        transform:translate(150%,0);
    }
}
@keyframes initial-loading{
    0%,100%{
        -webkit-transform:translate(-50%,0);
        transform:translate(-50%,0);
    }
    50%{
        -webkit-transform:translate(150%,0);
        transform:translate(150%,0);
    }
}






.wrapper.theme-clean_light .aione-charts .aione-chart{
	margin-bottom:20px;
    border: 1px solid #e8e8e8;
}







.inf {
    position: absolute;
    background: #ffffff;
    border: 1px solid #e8e8e8;
    width: 250px;
    margin: 0 0 0 -125px;
    padding: 8px;
    z-index: 9999;
    border-radius: 4px;
    font-size: 15px;
    line-height: 18px;
}
.inf:after {
	content: "";
    position: absolute;
    display: block;
    width: 0;
    height: 0;
    bottom: -10px;
    left: 50%;
    margin: 0 0 0 -10px;
    border-top: 10px solid #ffffff;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    z-index: 9999;
}

.inf .title {
    font-size: 17px;
    line-height: 20px;
    border-bottom: 1px solid #666666;
    text-align: center;
    color: #168dc5;
    padding: 0px 0 8px 0;
    margin: 0;
    display: block;
}
.inf .data {
    display: block;
    border-bottom: 1px dotted #e8e8e8;
    padding: 4px 0;
    margin: 0;
}
.google-visualization-table{
	min-width: 100% !important;
}
.google-visualization-table .google-visualization-table-table{
	width: 100% !important;
}
</style> 

@endsection