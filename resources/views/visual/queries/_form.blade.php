<div class="box-body">
	{!! Form::hidden('db_table',@$db_table, ['class' => 'btn btn-primary']) !!}
	{!! Form::hidden('visual_id',@$visual_id, ['class' => 'btn btn-primary']) !!}
  	@if(@$visual_id == null)
		  <div class="form-group {{ $errors->has('visual_id') ? ' has-error' : '' }}">
		    {!!Form::label('visual_id','Select Visual') !!}
		    {!!Form::select('visual_id',App\GeneratedVisual::visualList(),null, ['class'=>'form-control select2 visual','placeholder'=>'Select Visual']) !!}
		    @if($errors->has('visual_id'))
		      <span class="help-block">
		            {{ $errors->first('visual_id') }}
		      </span>
		    @endif
		  </div>
  	@else
		  <div class="form-group {{ $errors->has('filter_col') ? ' has-error' : '' }}">
		    {!!Form::label('filter_col','Select Columns For Filter') !!}
		    {!!Form::select('filter_col[]',$columns,null, ['class'=>'form-control select2 filterCol','multiple']) !!}
		    @if($errors->has('filter_col'))
		      <span class="help-block">
		            {{ $errors->first('filter_col') }}
		      </span>
		    @endif
		  </div>
		  {!! Form::button('Generate Data', ['class' => 'btn btn-primary','id'=>'genData','style'=>'margin-bottom:2%;']) !!}
			<span style="color:red;display: none;" id="mesg"></span>
			<div id="floatingBarsG" style="display: none;">
				<div class="blockG" id="rotateG_01"></div>
				<div class="blockG" id="rotateG_02"></div>
				<div class="blockG" id="rotateG_03"></div>
				<div class="blockG" id="rotateG_04"></div>
				<div class="blockG" id="rotateG_05"></div>
				<div class="blockG" id="rotateG_06"></div>
				<div class="blockG" id="rotateG_07"></div>
				<div class="blockG" id="rotateG_08"></div>
			</div>
			<div class="columns_data">
				
			</div>
  	@endif
</div>
<!-- /.box-body -->
<style type="text/css">
  .file-actions{
      float: right;
  }
  .file-upload-indicator{
     display: none;
  }
  .select2-selection__choice{

      background-color: #3c8dbc !important;
  }
  .select2-selection__choice__remove{

      color: #FFF !important;
  }

  .select2-container--default .select2-selection--single{
  		border-radius: 0 !important;
  		height: 31px !important;
  		padding: 5px 1px !important;
  }

</style>

<link rel="stylesheet" type="text/css" href="{{asset('css/visual-create.css')}}">