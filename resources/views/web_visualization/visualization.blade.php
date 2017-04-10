<style type="text/css">
.chart-row{
    border: 1px solid #e8e8e8 !important;
    margin-bottom: 15px;
}
.chart-row h4{
	padding-left: 40px;
	border-bottom: 1px solid #e8e8e8;
	padding-bottom: 10px;
	margin-bottom: 0px;
	margin-left: 15px;
}
.chart-row h4 span img{
	width: 20px;
	float: right;
	margin-right: 25px;
}
.none{
	display: none;
}
.main{
	max-width: 100% !important;
	background:none !important;
	border: none !important
}
.chart-wrapper-left{
	width: 100%;
	float: left;
}
.chart-filters{
	border: 1px solid #e8e8e8;
	width: 32.33333% !important;
}
#theme_{
	min-height: 100%
}
.chart-wrapper-left .chart-row{
	background:white;
}
.chart-filters{
	background:white !important;
}
.chart-row > h4 > span > a > img{
	transform: rotate(180deg);
}
.main-chart-row{
	margin-left: 0px !important;
	margin-right: 0px !important
}
.slider.slider-horizontal{
	margin-left: 18px;
	margin-right: 18px;
	width: 80% !important;
}
.chart-row .chart-sort-arrow{
	padding: 10px;
    background: #888;
    color: white;
    position: absolute;
    font-size: 20px;
    cursor: move;
}
.chats-filter-button{
	padding:10px 0px 30px 10px;
}
.filter-title{
	border-bottom: 1px solid #e8e8e8;
}
.filter-title img{
	width: 20px;
	float: right;
	margin-right: 10px;
}
</style>
@extends('layouts.visualization')
@section('content')
<div class="row main-chart-row">
	<div class="chart-wrapper-left {{(!empty($filters))?'col-md-8':'col-md-12'}}">
		@foreach($details as $key => $value)
			@if($value['type'] != 'CustomMap')
				<div class="chart-row">
					<a href="" class="chart-sort-arrow"><i class="fa fa-arrows" aria-hidden="true"></i></a>
					<h4>{{$titles[$key]}}<span><a href="javascript:;"><img src="{{asset('arrow-down.png')}}" alt=""></a></span></h4>

					<div id="{{$value['id']}}" class="chart-wrapperr"></div>

					{!! lava::render($value['type'],$key,$value['id']) !!}
				</div>
			@else
				<div class="chart-row">
					<a href="" class="chart-sort-arrow"><i class="fa fa-arrows" aria-hidden="true"></i></a>
					<h4>{{$titles[$key]}}<span><a href="javascript:;"><img src="{{asset('arrow-down.png')}}" alt=""></a></span></h4>

					<div id="{{$value['id']}}" class="chart-wrapperr">
						{!! $value['map'] !!}
					</div>

				</div>
			@endif
		@endforeach
	</div>
	@if(!empty($filters))
		<div class="chart-filters col-md-4">
			<div class="filter-title">
				<center>
					<h4>Filters <span><a href="javascript:;"><img src="{{asset('arrow-down.png')}}" alt=""></a></span></h4>
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
								<div class="col-md-12">
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

			function getColor(value){
	            var hue=((1-value)*50).toString(10);
	            return ["hsl(",hue,",100%,50%)"].join("");
	        }
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
</style>