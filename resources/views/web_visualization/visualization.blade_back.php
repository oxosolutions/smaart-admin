@extends('layouts.visualization')
@section('content')
	@foreach($details as $key => $value)
		<div id="{{$value['id']}}"></div>
		{!! lava::render($value['type'],$key,$value['id']) !!}
	@endforeach
 
	<form method="POST" action="">
		{!! Form::token() !!}
		@php
			$multidrop = 0;
		@endphp
		@foreach($filters as $key => $value)
			@if($value['column_type'] == 'mdropdown')
				<div class="row" style="margin-top: 5%;">
					<div class="col-md-2">
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
					<div class="col-md-2">
						<label>{{ucfirst($value['column_name'])}}</label>
						<select name='singledrop[{{$multidrop}}][{{$key}}][]'>
							@foreach($value['column_data'] as $option)
								<option value="{{$option}}">{{$option}}</option>
							@endforeach
						</select>
					</div>
				</div>
			@endif
			@if($value['column_type'] == 'range')
				<div class="row" style="margin-top: 5%;">
					<div class="col-md-2">
						<label>{{ucfirst($value['column_name'])}}</label>
						<b>{{$value['column_min']}}<b/><input type="range" value="" name="range" data-slider-min="{{$value['column_min']}}" data-slider-max="{{$value['column_max']}}" data-slider-step="1" data-slider-value="[{{$value['column_min']}},{{$value['column_max']}}]" class="slider" /><b>{{$value['column_max']}}<b/>
					</div>
				</div>
			@endif
		@endforeach
		<input type="submit" name="applyFilter" value="Apply Filters" />
	</form>

@endsection

