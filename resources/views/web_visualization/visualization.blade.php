@extends('layouts.visualization')
@section('content')

<div class="row main-chart-row">
		<div class="row" style="background-color: #fff;margin: 15px">
			<div style="padding:10px" class="row">
				<h5 class="col s11">Select graphs to display</h5>
				
			</div>
			<div class="divider"></div>
			<div style="padding:10px">
				@foreach($titles as $chart_id => $title)
					<p class="col s2">
				      <input type="checkbox" class="filled-in show-hide-charts" id="{{$chart_id}}_checkbox" data-hide="{{$chart_id}}" checked="checked" />
				      <label for="{{$chart_id}}_checkbox">{{ucwords($title)}}</label>
				    </p>
			    @endforeach
			</div>
		</div>
	<div class="chart-wrapper-left {{(!empty($filters))?'col-md-8':'col-md-12'}}">
		

		@foreach($details as $key => $value)

			@if($value['type'] != 'CustomMap' && $value['type'] != 'TableChart')
				<div class="chart-row">
					<a href="" class="chart-sort-arrow"><i class="fa fa-arrows" aria-hidden="true"></i></a>
					{{-- <h4>{{$titles[$key]}}<span><a href="javascript:;"><img src="{{asset('arrow-down.png')}}" alt=""></a></span></h4> --}}
					<div class="row valign-wrapper">
						<div class="col s10 left-align" style="padding-left: 60px"><h5>{{$titles[$key]}}</h5></div>
	  					<div class="col s1 center-align valign">
	  						  <div id="" class="fixed-action-btn horizontal click-to-toggle">
							    <a class="btn-floating  red" >
							      <i class="fa fa-eye" aria-hidden="true"></i>
							    </a>
							    <ul>
							      <li><a class="btn-floating btn-small red"><i class="fa fa-line-chart fa-1g" aria-hidden="true"></i></a></li>
							      <li><a class="btn-floating btn-small yellow darken-1"><i class="fa fa-pie-chart fa-1g" aria-hidden="true"></i></a></li>
							      <li><a class="btn-floating btn-small green"><i class="fa fa-area-chart fa-2x" aria-hidden="true"></i></a></li>
							      <li><a class="btn-floating btn-small blue"><i class="fa fa-bar-chart fa-2x" aria-hidden="true"></i></a></li>
							      <li><a class="btn-floating btn-small blue"><i class="fa fa-arrows fa-2x" aria-hidden="true"></i></a></li>
							    </ul>
							  </div>
	  					</div>
	  					<div class="col s1 center-align"><span class="accordion-arrow"><img src="{{asset('arrow-down.png')}}" alt="" style="width: 20px"></span></div>
						
					</div>

					<div id="{{$value['id']}}" class="chart-wrapperr" style="width: {{$value['chartWidth']}}%;"></div>

					{!! lava::render($value['type'],$key,$value['id']) !!}
					
				</div>
			@elseif($value['type'] == 'TableChart')
				<div class="chart-row">
					<a href="" class="chart-sort-arrow"><i class="fa fa-arrows" aria-hidden="true"></i></a>
					{{-- <h4>{{$titles[$key]}}<span><a href="javascript:;"><img src="{{asset('arrow-down.png')}}" alt=""></a></span></h4> --}}
					<div class="row valign-wrapper">
						<div class="col s10 left-align" style="padding-left: 60px"><h5>{{$titles[$key]}}</h5></div>
	  					<div class="col s1 center-align valign">
	  						  <div id="" class="fixed-action-btn horizontal click-to-toggle">
							    <a class="btn-floating  red" >
							      <i class="fa fa-eye" aria-hidden="true"></i>
							    </a>
							    <ul>
							      <li><a class="btn-floating btn-small red"><i class="fa fa-line-chart fa-1g" aria-hidden="true"></i></a></li>
							      <li><a class="btn-floating btn-small yellow darken-1"><i class="fa fa-pie-chart fa-1g" aria-hidden="true"></i></a></li>
							      <li><a class="btn-floating btn-small green"><i class="fa fa-area-chart fa-2x" aria-hidden="true"></i></a></li>
							      <li><a class="btn-floating btn-small blue"><i class="fa fa-bar-chart fa-2x" aria-hidden="true"></i></a></li>
							      <li><a class="btn-floating btn-small blue"><i class="fa fa-arrows fa-2x" aria-hidden="true"></i></a></li>
							    </ul>
							  </div>
	  					</div>
	  					<div class="col s1 center-align"><span class="accordion-arrow"><img src="{{asset('arrow-down.png')}}" alt="" style="width: 20px"></span></div>
						
					</div>
					<div id="{{$value['id']}}" class="chart-wrapperr" style="width: {{$value['chartWidth']}}%;">
						{!! lava::render($value['type'],$key,$value['id']) !!}
					</div>

				</div>
			@elseif($value['type'] == 'CustomMap')
				<div class="chart-row">
					<a href="" class="chart-sort-arrow"><i class="fa fa-arrows" aria-hidden="true"></i></a>
					{{-- <h4>{{$titles[$key]}}<span><a href="javascript:;"><img src="{{asset('arrow-down.png')}}" alt=""></a></span></h4> --}}
					<div class="row valign-wrapper">
						<div class="col s10 left-align" style="padding-left: 60px"><h5>{{$titles[$key]}}</h5></div>
	  					<div class="col s1 center-align valign">
	  						  <div id="" class="fixed-action-btn horizontal click-to-toggle">
							    <a class="btn-floating  red" >
							      <i class="fa fa-eye" aria-hidden="true"></i>
							    </a>
							    <ul>
							      <li><a class="btn-floating btn-small red"><i class="fa fa-line-chart fa-1g" aria-hidden="true"></i></a></li>
							      <li><a class="btn-floating btn-small yellow darken-1"><i class="fa fa-pie-chart fa-1g" aria-hidden="true"></i></a></li>
							      <li><a class="btn-floating btn-small green"><i class="fa fa-area-chart fa-2x" aria-hidden="true"></i></a></li>
							      <li><a class="btn-floating btn-small blue"><i class="fa fa-bar-chart fa-2x" aria-hidden="true"></i></a></li>
							      <li><a class="btn-floating btn-small blue"><i class="fa fa-arrows fa-2x" aria-hidden="true"></i></a></li>
							    </ul>
							  </div>
	  					</div>
	  					<div class="col s1 center-align"><span class="accordion-arrow"><img src="{{asset('arrow-down.png')}}" alt="" style="width: 20px"></span></div>
						
					</div>
					<div id="{{$value['id']}}" class="chart-wrapperr" style="width: {{$value['chartWidth']}}%;">
						{!! $value['map'] !!}
					</div>

				</div>
			@endif
		@endforeach
	</div>
	<div id="toolbar_div"></div>
	@if(!empty($filters))
		<div class="chart-filters col-md-4" style="padding: 0px">
			<div class="filter-title">
				<center>
					<h5>Filters <span><a href="javascript:;"><img src="{{asset('arrow-down.png')}}" alt=""></a></span></h5>
				</center>
			</div>
			<div class="survey-chart-filters hideDiv">
				<form method="POST" action="">
					{!! Form::token() !!}
					@php
						$multidrop = 0;
						$singledrop = 0;
						$range = 0;
					@endphp
					@foreach($filters as $key => $value)
						@if($value['column_type'] == 'mdropdown')
							<div class="row" style="margin-top: 5%;">
								<div class=" col-md-12">
									<label>{{ucfirst($value['column_name'])}}</label>
									<select name='multipledrop[{{$multidrop}}][{{$key}}][]' multiple>
										@foreach($value['column_data'] as $option)
											<option value="{{$option}}">{{$option}}</option>
										@endforeach
									</select>
								</div>
							</div>
							@php
								$multidrop++;
							@endphp
						@endif
						@if($value['column_type'] == 'dropdown')
							<div class="row" style="margin-top: 5%;">
								<div class="col-md-12">
									<label>{{ucfirst($value['column_name'])}}</label>
									<select name='singledrop[{{$singledrop}}][{{$key}}][]'>
										@foreach($value['column_data'] as $option)
											<option value="{{$option}}">{{$option}}</option>
										@endforeach
									</select>
								</div>
							</div>
							@php
								$singledrop++;
							@endphp
						@endif
						@if($value['column_type'] == 'range')
							<div class="row" style="margin-top: 5%;">
								<div class="col-md-12">
									<label style="width: 100%">{{ucfirst($value['column_name'])}}</label>
									<b>{{$value['column_min']}}<b/><input type="range[]"  name="range[{{$range}}][{{$key}}]" data-slider-min="{{$value['column_min']}}" data-slider-max="{{$value['column_max']}}" data-slider-step="1" data-slider-value="[{{$value['column_min']}},{{$value['column_max']}}]" class="slider" /><b>{{$value['column_max']}}<b/>
								</div>
							</div>
							@php
								$range++;
							@endphp
						@endif
					@endforeach
					<div class="chats-filter-button">
						<button name="downloadData" type="submit" value="downloadData" class="waves-effect waves-light btn" style="margin-left: 5px">Download Data</button>
						<input type="submit" name="applyFilter" class="btn btn-default pull-right" value="Apply Filters" />
					</div>
					
				</form>
			</div>
		</div>
	@endif
</div>
@endsection
<script type="text/javascript" src="{{asset('bower_components/admin-lte/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function(){
			var chartsList = '{!! json_encode($javascript) !!}';
			
			$.each(JSON.parse(chartsList), function(key,val){
				$.each(val.data, function(ikey, ival){
					var index = 0;
					$.each(ival, function(dataKey, dataVal){
						var colorVal = index/val.data.length;
						var leagendWidth = (1/(val.data.length-1))*100;
						var colorCode = getColor(colorVal);
						
						var putId = val.headers[dataKey].replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, "_");
						var currentClass = $('#'+ival[0]).attr('class');
						$('#'+key+' #'+ival[0]).attr(putId,dataVal);
						$('#'+key+' #'+ival[0]).css({'fill': colorCode }).attr('class','mapArea '+currentClass);
					});
					index++;
					//console.log(ival);
				});
			});
			$('.chart-wrapperr .mapArea').mouseover(function (e) {
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
				console.log(lava.events);
				lava.events.on('jsapi:ready', function (google) {
					console.log(google);
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
<style type="text/css">
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
.fixed-action-btn{
	position: static !important;
}
.fixed-action-btn.horizontal ul{
	right: 120px !important;
}
.main-chart-row .row{
	margin-bottom: 0px;
}
.fixed-action-btn.horizontal ul{
	top:48% !important;
}
select{
	display: inline-block !important;
}
table {
    border-collapse: collapse !important;
    width: 100% !important;
}

th, td {
    text-align: left !important;
    padding: 8px !important;

}


tr:nth-child(even){background-color: #f2f2f2 !important}

th {
    background-color: #26a69a !important;
    color: white !important;
    background-image: none !important;
}
</style>