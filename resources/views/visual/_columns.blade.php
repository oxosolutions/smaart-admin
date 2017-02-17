

<div class="form-group {{ $errors->has('columns') ? ' has-error' : '' }}">
	{!!Form::label('filter_cols','Select Columns For Filter') !!}
	{!!Form::select('filter_cols[]',$columns,@$prefilledFilter, ['class'=>'form-control select2','multiple']) !!}
	@if($errors->has('filter_cols'))
	  <span class="help-block">
	        {{ $errors->first('filter_cols') }}
	  </span>
	@endif
</div>


<div id="visualCharts" class="panel panel-primary">
	<div class="panel-heading">Add Visual Columns</div>
	<div class="panel-body">
		<h4 style="text-align: center;">Chart <span class="chart_count">1</span></h4>
		<div class="repeat_div">
			<div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
		    	{!!Form::label('title','Title') !!}
		    	{!!Form::text('title[chart_1]',@$preFilled['title']['chart_1'], ['class'=>'form-control','placeholder'=>'Enter Title Name']) !!}
		    	@if($errors->has('title'))
		      		<span class="help-block">
		            	{{ $errors->first('title') }}
		      		</span>
		    	@endif
		  	</div>

			<div class="form-group {{ $errors->has('columns') ? ' has-error' : '' }}">
				{!!Form::label('columns_one','Select Column One') !!} <span style="font-size: 11px;"> (preferable column first should be string type)</span>
				{!!Form::select('columns_one[chart_1]',$columns,@$preFilled['column_one']['chart_1'], ['class'=>'form-control select2','placeholder'=>'Select Column One']) !!}
				@if($errors->has('columns'))
				  <span class="help-block">
				        {{ $errors->first('columns') }}
				  </span>
				@endif
			</div>
			<div class="form-group">
				<label>
	              <input type="checkbox" value="chart_1" name="count[]" class="minimal count-column" {{(@in_array('chart_1',@$preFilled['count']))?'checked="checked"':''}}>
	              Count Column
	            </label>
			</div>
			<div class="form-group {{ $errors->has('columns') ? ' has-error' : '' }} second_col">
				{!!Form::label('columns_two','Select Column Two') !!}
				{!!Form::select('columns_two[chart_1][]',$columns,@$preFilled['columns_two']['chart_1'], ['class'=>'form-control select2','multiple']) !!}
				@if($errors->has('columns'))
				  <span class="help-block">
				        {{ $errors->first('columns') }}
				  </span>
				@endif
			</div>

			<div class="form-group {{ $errors->has('visual_name') ? ' has-error' : '' }}" style="margin-top: 2%;">
			    {!!Form::label('visual_settings','Visual Settings') !!}
			    {!!Form::textarea('visual_settings[chart_1][]',@$preFilled['visual_settings']['chart_1'][0], ['class'=>'form-control','placeholder'=>'Enter Visual Settings']) !!}
			    @if($errors->has('visual_settings'))
			      <span class="help-block">
			            {{ $errors->first('visual_settings') }}
			      </span>
			    @endif
			</div>
			<div class="form-group {{ $errors->has('columns') ? ' has-error' : '' }} second_col">
				{!!Form::label('columns','Select Chart type') !!}
				{!!Form::select('chartType[chart_1]',App\GeneratedVisual::chartTypes(),@$chartTypes['chart_1'], ['class'=>'form-control select2']) !!}
				@if($errors->has('columns'))
				  <span class="help-block">
				        {{ $errors->first('columns') }}
				  </span>
				@endif
			</div>

			<hr/>
		@if(!empty(@$model))
			@php
				$index = 2;
				$chart =  'chart_'.$index;
				$loop = count($preFilled['column_one']);
			@endphp
			@for($i = 1; $i < $loop; $i++)
				@include('visual._clone')
			@endfor
			@php
				$index++;
			@endphp
		@endif
		</div>

		<button class="btn btn-primary add-more">Add More</button>
	</div>
</div>


<style type="text/css">
	.select2-selection__choice{

	      background-color: #3c8dbc !important;
	  }
  .select2-selection__choice__remove{

      color: #FFF !important;
  }
</style>