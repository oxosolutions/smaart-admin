<div class="box-body">
  <div class="form-group {{ $errors->has('dataset_id') ? ' has-error' : '' }}">
    {!!Form::label('dataset_id','Select Dataset') !!}
    {!!Form::select('dataset_id',App\DatasetsList::datasetList(),null, ['class'=>'form-control','placeholder'=>'Select Dataset']) !!}
    @if($errors->has('dataset_id'))
      <span class="help-block">
            {{ $errors->first('dataset_id') }}
      </span>
    @endif
  </div>

  <div class="form-group {{ $errors->has('visual_name') ? ' has-error' : '' }}">
    {!!Form::label('visual_name','Visualisation Name') !!}
    {!!Form::text('visual_name',null, ['class'=>'form-control','placeholder'=>'Enter Visualisation Name']) !!}
    @if($errors->has('visual_name'))
      <span class="help-block">
            {{ $errors->first('visual_name') }}
      </span>
    @endif
  </div>
  
  <div class="form-group {{ $errors->has('settings') ? ' has-error' : '' }} " style="margin-top: 2%;">
    {!!Form::label('settings','Enter Settings JSON') !!}
    {!!Form::textarea('settings',null,['class'=>'form-control','placeholder'=>'Enter Settings JSON','id'=>'settings']) !!}
    @if($errors->has('settings'))
      <span class="help-block">
            {{ $errors->first('settings') }}
      </span>
    @endif
  </div>

  <div class="form-group {{ $errors->has('options') ? ' has-error' : '' }} " style="margin-top: 2%;">
    {!!Form::label('options','Enter Options JSON') !!}
    {!!Form::textarea('options',null,['class'=>'form-control','placeholder'=>'Enter Options JSON','id'=>'options']) !!}
    @if($errors->has('options'))
      <span class="help-block">
            {{ $errors->first('options') }}
      </span>
    @endif
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

  </style>