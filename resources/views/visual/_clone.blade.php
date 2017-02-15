<div class="clone">
	<div class="row">
		<div class="col-md-11">
			<h4 style="text-align: center; margin-left: 10%;">Chart <span class="chart_count">{{@$index}}</span></h4>
		</div>
		<div class="col-md-1">
			<button class="btn btn-danger pull-right delete-clone">X</button>
		</div>
	</div>
	<div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
		    	{!!Form::label('title','Title') !!}
		    	{!!Form::text('title['.$chart.']',@$preFilled['title'][$chart], ['class'=>'form-control','placeholder'=>'Enter Title Name']) !!}
		    	@if($errors->has('title'))
		      		<span class="help-block">
		            	{{ $errors->first('title') }}
		      		</span>
		    	@endif
		  	</div>

	<div class="form-group {{ $errors->has('columns') ? ' has-error' : '' }}">
		{!!Form::label('columns_one','Select Column One') !!}<span style="font-size: 11px;"> (preferable*: column first should be string type)</span>
		{!!Form::select('columns_one['.$chart.']',$columns,@$preFilled['column_one'][$chart], ['class'=>'form-control select2','placeholder'=>'Select Column One']) !!}
		@if($errors->has('columns'))
		  <span class="help-block">
		        {{ $errors->first('columns') }}
		  </span>
		@endif
	</div>
	<div class="form-group">
		<label>
	      <input type="checkbox" value="{{$chart}}" name="count[]" class="minimal count-column" {{(@in_array($chart,@$preFilled['count']))?'checked="checked"':''}}>
	      Count Column
	    </label>
	</div>
	<div class="form-group {{ $errors->has('columns') ? ' has-error' : '' }} second_col">
		{!!Form::label('columns','Select Column Two') !!}
		{!!Form::select('columns_two['.$chart.'][]',$columns,@$preFilled['columns_two'][$chart], ['class'=>'form-control select2','multiple']) !!}
		@if($errors->has('columns'))
		  <span class="help-block">
		        {{ $errors->first('columns') }}
		  </span>
		@endif
	</div>

	<div class="form-group {{ $errors->has('visual_name') ? ' has-error' : '' }}" style="margin-top: 2%;">
	    {!!Form::label('visual_settings','Visual Settings') !!}
	    {!!Form::textarea('visual_settings['.$chart.'][]',@$preFilled['visual_settings'][$chart][0], ['class'=>'form-control','placeholder'=>'Enter Visual Settings']) !!}
	    @if($errors->has('visual_settings'))
	      <span class="help-block">
	            {{ $errors->first('visual_settings') }}
	      </span>
	    @endif
	</div>

	<div class="form-group {{ $errors->has('columns') ? ' has-error' : '' }} second_col">
		{!!Form::label('columns','Select Chart type') !!}
		{!!Form::select('chartType['.$chart.']',App\GeneratedVisual::chartTypes(),@$chartTypes[$chart], ['class'=>'form-control select2']) !!}
		@if($errors->has('columns'))
		  <span class="help-block">
		        {{ $errors->first('columns') }}
		  </span>
		@endif
	</div>
	<hr/>
</div>