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
								
								@if(isset($chart['error']))
									@include('web_visualization.errors',['errors'=>[$chart['error']]])
								@else
									<?php
										$chart_id = $chart_key;
										$chart_type = $chart['chart_type'];
										$chart_title = $chart['title'];
										$chart_enabled = $chart['enableDisable'];
									?>

									@if($chart_enabled == 1)
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
													{!! $charts[$chart_key]['map'] !!}
													</div>
													<div id="map_data_{{$chart_id}}" class="map-data-wrapper">
														<div id="map_data_header_{{$chart_id}}" class="map-data-header">
															<span class="map-data-title"></span>
															<span class="map-data-close">+</span>
														</div>
														<div id="map_data_content_{{$chart_id}}" class="map-data-content">
														</div>
													</div>
													<div class="view_data" style="display: none;">
														{{json_encode($javascript[$chart_key]['arranged_data']['view_data'])}}
													</div>
													<div class="tooltip_data" style="display: none;">
														{{json_encode($javascript[$chart_key]['arranged_data']['tooltip_data'])}}
													</div>
													<div class="popup_data" style="display: none;">
														{{json_encode($javascript[$chart_key]['arranged_data']['popup_data'])}}
													</div>
												@elseif($chart_type == 'ListChart')
													<div id="{{$chart_id}}" style="width: 98%; border: 1px solid #CCC; height: 200px; overflow: scroll; padding-left: 2%; overflow-x: hidden;">
														@foreach($chart['list'] as $key => $values)
															@foreach($values as $k => $val)
																<b>{{ucwords(str_replace('_',' ',$k))}}</b> : {{$val}} <br/>
															@endforeach
															<hr/>
														@endforeach
													</div>
												@else
													<div id="{{$chart_id}}" class="chart-wrapperr"></div>
													{!! lava::render($chart_type,$chart_key,$chart_id) !!}
												@endif
											</div>
										</div>
									@endif
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
	
		
	<div class="inf">
   		 <span class="title">Texes</span>
         <span class="data">Year:2015</span>
         <span class="data">Year:2016</span>
         <span class="data">Year:2017</span>
    </div>
</div>
<script src="{{asset('/bower_components/admin-lte/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<script src="{{asset('/js/visualization.js')}}" type="text/javascript"></script>
<script src="{{asset('/js/ion.rangeSlider.js')}}" type="text/javascript"></script>
<script src="{{asset('/js/classybrew.js')}}" type="text/javascript"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
  $('select').select2();
</script>
<script type="text/javascript">
		$(document).ready(function(){
			$('.slider').each(function(){
				var elem = $(this);
				$(this).ionRangeSlider({
				    type: "double",
				    grid: true,
				    min: elem.attr('data-slider-min'),
				    max: elem.attr('data-slider-max'),
				    from: 0,
				    step: 1
				});
			});
		});
		
	</script>
<script type="text/javascript">
	$(document).ready(function(){
			var cb = new classyBrew();
			var quantile = new classyBrew();
			$('.aione-chart-content').each(function(e){
				
				var elem = $(this);
				var chart_view_data = $(this).find('.view_data').html();
				if(chart_view_data != undefined){
					var chart_data_array = $.map(JSON.parse(chart_view_data), function(value, index) {
					    return [value];
					});
					var colors = cb.getColorCodes();
					quantile.setSeries(chart_data_array);
					quantile.classify('quantile', 5);
					var index = 0;
					quantile.setColorCode(colors[3])
					$.each(JSON.parse(chart_view_data), function(key, value){
						elem.find('#'+key).css({fill:quantile.getColorInRange(chart_data_array[index])}).attr('class','mapArea');
						index++;
					});
				}
			});
			$('.map-wrapper .mapArea').mouseover(function (e) {
				var area_id = $(this).attr('id');
				var tooltip_data = $(this).parents('.aione-chart-content').find('.tooltip_data').html();
				if(tooltip_data != undefined){
					tooltip_data = JSON.parse(tooltip_data);
					var html = '<span class="title">'+area_id+'</span>';
					$.each(tooltip_data[area_id], function(key, value){
						$.each(value, function(k,v){
							html += '<span class="data">'+k+':'+v+'</span>';
						});
						html += '<hr/>';
					});
					$('.inf').html(html);
					
				}
			}).mousemove(function(e){
				var mouseX = e.pageX, //X coordinates of mouse
                    mouseY = e.pageY; //Y coordinates of mouse

                $('.inf').css({
                    'top': mouseY-($('.inf').height()+30),
                    'left': mouseX,
                    'display': 'block'
                });
			}).mouseleave(function(){
				$('.inf').css({
					'display':'none'
				});
			});
			

			/*$('.map-wrapper .mapArea').mouseover(function (e) {
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
            });*/
			
			
			$('.map-wrapper .mapArea').click(function (e) {
				var area_id = $(this).attr('id');
				var popup_data = JSON.parse($(this).parents('.aione-chart-content').find('.popup_data').html());
				var clicked_id_data = popup_data[area_id];
                e.preventDefault();
				$('.map-data-wrapper').addClass('active'); 
				var position = $(this).position();
				$('.map-data-wrapper').css({
                    'top': position.top,
                    'left':  position.left
                });
				var title = $(this).attr('title');
				$('.map-data-title').html(title);
				
				var html = '<div class="map-data-rows">';
				$.each(clicked_id_data, function(key, val){
					var row_status = 0;
					var row_html = '';
					html += '<div class="map-data-row">'; 
					$.each(val, function(k, v){
						html += '<span class="map-data-col '+k.replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, "_")+'">';
						html += k+' : '+v;
						html += '</span>';
					});

					html += '</div>';
                });
				html += '</div>';
				$(".map-data-content").html(html); 			
				
            });
			
			
			
			
			$('.map-data-close').click(function (e) {
                e.preventDefault();
				$('.map-data-wrapper').removeClass('active');
            });
			$('.reset-filters-button').click(function (e) {
                e.preventDefault();
				window.location.reload();
            });

		      

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


<style type="text/css">
.wrapper{
	position: relative;
}
.map-data-row {
    margin: 0 0 10px 0;
    padding: 0 0 10px 0;
    border-bottom: 1px solid #989898;
}

.map-data-row .map-data-col {
	display:block;
}





.aione-sidebar{
	background-color:#ffffff;
}
.filter-title {
        margin: 0px;
    padding: 10px 0;
    font-size: 18px;
    color: #168dc5;
}
.aione-button {
    margin: 20px 0 30px 0;
    padding: 10px 20px;
    color: #FFFFFF;
    background-color: #666666;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    line-height: 20px;
    cursor: pointer;
}
.map-data-wrapper{
	width:0px;
	height:0px;
	position:absolute;
	left:0;
	top:0;
	z-index: 10;
	ovrflow:hidden;
	opacity:0;
	border:1px solid #e8e8e8;
	background-color:rgba(255,255,255,1);
	-moz-transition: all 200ms ease-in-out;
	-webkit-transition: all 200ms ease-in-out;
	transition: all 200ms ease-in-out;
}
.map-data-wrapper.active{
	width:300px;
	height:400px;
	opacity:1;
	transform:translate(-300px,-200px)
}
/*
.map-data-wrapper.active{
	left:0;
}

.map-data-wrapper:before {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 11;
    background-color: rgba(255,255,255,0.6);
    -moz-transition: all 100ms ease-in-out;
    -webkit-transition: all 100ms ease-in-out;
    transition: all 100ms ease-in-out;
}
*/
.map-data-wrapper:hover:before{
	background-color:rgba(255,255,255,0.9);
}
.map-data-header {
    border-bottom: 1px solid #e8e8e8;
    z-index: 12;
    position: relative;
    margin: 0;
}
.map-data-title {
    display: block;
    margin-right: 40px;
    color: #333333;
    font-size: 16px;
    line-height: 40px;
    padding: 0 10px;
}
.map-data-close {
    position: absolute;
    top: 0;
    right: 0;
    font-size: 30px;
    line-height: 40px;
    text-align: center;
    transform: rotate(45deg);
    width: 40px;
    height: 40px;
    display: block;
    color: #cc0000;
	cursor: pointer
}
.map-data-content {
    z-index: 14;
    position: relative;
    padding: 10px;
	
	height: 90.7%;
	height: calc(100% - 61px);
    overflow-y: scroll;
}



.theme-clean_light .main{

}
.theme-clean_light .land{
	fill: #f2f2f2;
    stroke: #282828; 
    stroke-width: 0.3;
}
.theme-clean_light .land:hover{
	fill: #d2d2d2;
}
.theme-clean_light .mapArea{
	fill:#03a9f4;
} 
.theme-clean_light .mapArea:hover{
	fill:#0288d1;
} 


.theme-clean_dark .main{
	background-color:#333333; 
}
.theme-clean_dark .land{
	fill: #454545;
    stroke: #282828; 
    stroke-width: 0.3; 
}
.theme-clean_dark .land:hover{
	fill: #666666;
    stroke: #222222;
}
.theme-clean_dark .mapArea{
	fill:#FFB300;
}
.theme-clean_dark .mapArea:hover{
	fill:#ff9800;
}
 
 .map-wrapper{
	 text-align:center;
 }
 .map-wrapper svg {
    min-width:100%;
	max-width: 100%;
}
 .map-wrapper .land {
    -moz-transition: all 150ms ease-in;
    -webkit-transition: all 150ms ease-in;
    transition: all 150ms ease-in;
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

/*
.aione-sidebar.aione-sidebar-position-right:before{
	content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 11;
	background-color:#FFFFFF;
}
*/




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
    display: none;
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
table {
    border-collapse: collapse !important;
    width: 100% !important;
}
thead tr{
	background-color: #4584F0 !important
}
thead th{
	background: none !important;
	color: white; 
}
th, td {
    text-align: left !important;
    padding: 8px !important;
}

tr:nth-child(even){background-color: #f2f2f2 !important}
</style> 

<script type="text/javascript">  
	$(window).load(function() {
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
@endsection 