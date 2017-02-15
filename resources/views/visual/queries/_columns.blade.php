@foreach($model as $key => $column)
	@php
		$column = json_decode(json_encode($column), true);
	@endphp
	<div class="form-group {{ $errors->has('columns') ? ' has-error' : '' }}">
		{!!Form::label('columns','Select Value') !!}
		<select class="form-control select2" name="columns[{{key($column[0])}}]">
			@foreach($column as $key=>$value)
				@foreach($value as $iKey => $iVal)
					<option value="{{$iVal}}">{{$iVal}}</option>
				@endforeach
			@endforeach
		</select>
		@if($errors->has('columns'))
		  <span class="help-block">
		        {{ $errors->first('columns') }}
		  </span>
		@endif
	</div>
@endforeach
<style type="text/css">
	.select2-selection__choice{

	      background-color: #3c8dbc !important;
	  }
  .select2-selection__choice__remove{

      color: #FFF !important;
  }
</style>