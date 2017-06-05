<?php 
    $source = array('dataset'=>'Dataset','surrvey_data'=> "Surrvey Data" , "google_spread_sheet"=> "Google Spread Sheet",'api'=> 'API');      
?>
<div class="box-body">
	<div class="form-group {{ $errors->has('visual_name') ? ' has-error' : '' }}">
    	{!!Form::label('visual_name','Visual Name') !!}
    	{!!Form::text('visual_name',null, ['class'=>'form-control','placeholder'=>'Enter Visual Name']) !!}
    	@if($errors->has('visual_name'))
      		<span class="help-block">
            	{{ $errors->first('visual_name') }}
      		</span>
    	@endif
  	</div>
      <div class="form-group {{ $errors->has('source') ? ' has-error' : '' }}">
      {!!Form::label('source','Select Source') !!}
      {!!Form::select('source',$source,null, ['id'=>'source' ,'class'=>'form-control select2','placeholder'=>'Select Source']) !!}
      @if($errors->has('source'))
          <span class="help-block">
              {{ $errors->first('source') }}
          </span>
      @endif
    </div>
    <div id="api" class="hid">
        <div class="form-group {{ $errors->has('spread_sheet') ? ' has-error' : '' }}">
          {!!Form::label('api','API') !!}
          {!!Form::text('value',null, ['class'=>'form-control','placeholder'=>'Enter Link']) !!}
          @if($errors->has('api'))
              <span class="help-block">
                  {{ $errors->first('api') }}
              </span>
          @endif
        </div>
  </div>
  <div id="google_spread_sheet" class="hid" >
    <div id="google_spread_sheet" class="form-group{{ $errors->has('spread_sheet') ? ' has-error' : '' }}">
      {!!Form::label('spread_sheet','Spread Sheet') !!}
      {!!Form::text('value',null, ['class'=>'form-control','placeholder'=>'Enter Link']) !!}
      @if($errors->has('visual_name'))
          <span class="help-block">
              {{ $errors->first('visual_name') }}
          </span>
      @endif
    </div>
  </div>
  <div id="surrvey_data" class="hid">
    <div class="form-group  {{ $errors->has('source') ? ' has-error' : '' }}">
      {!!Form::label('source','Select Surrvey') !!}
      {!!Form::select('value',App\Surrvey::getSurrvey(),null, ['id'=>'surrvey' ,'class'=>'form-control select2','placeholder'=>'Select Surrvey']) !!}
      @if($errors->has('source'))
          <span class="help-block">
              {{ $errors->first('source') }}
          </span>
      @endif
    </div>
  </div>
  <div id="dataset" class="hid">
  	<div  class="form-group {{ $errors->has('dataset_id') ? ' has-error' : '' }}">
    	{!!Form::label('dataset_id','Select Dataset') !!}
    	{!!Form::select('dataset_id',App\DatasetsList::datasetList(),null, ['class'=>'form-control select2','placeholder'=>'Select Dataset']) !!}
    	@if($errors->has('dataset_id'))
      		<span class="help-block">
            	{{ $errors->first('dataset_id') }}
      		</span>
    	@endif
  	</div>
  </div>
  	<div>
		{!! Form::button('Get Columns', ['class' => 'btn btn-primary','id'=>'getColumns']) !!}
		<!-- <span style="color:red;display: none;" id="mesg"></span> -->
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
	</div>

	<div id="visual" style="margin-top: 2%;">	  
	  <div class="datasetColumns">
	  	@if(!empty(@$model))
	  		{{Form::hidden('model_id',$model->id,['class'=>'model_id'])}}
	  		@include('visual._columns')
	  	@endif
	  </div>
	</div>


</div>
<!-- /.box-body -->
<style type="text/css">
  .file-actions{
      float: right;
  }
  .file-upload-indicator{
     display: none;
  }

  .select2-container--default .select2-selection--single{
  		border-radius: 0 !important;
  		height: 31px !important;
  		padding: 5px 1px !important;
  }

</style>

<link rel="stylesheet" type="text/css" href="{{asset('css/visual-create.css')}}">
