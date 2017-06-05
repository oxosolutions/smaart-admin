<div class="box-body">
  <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    {!!Form::label('name','name') !!}
    {!!Form::text('name',null, ['class'=>'form-control','placeholder'=>'Enter Role Name']) !!}
    @if($errors->has('name'))
      <span class="help-block">
            {{ $errors->first('name') }}
      </span>
    @endif
  </div>

  <div class="form-group {{ $errors->has('role_description') ? ' has-error' : '' }}">
    {!!Form::label('role_description','Description') !!}
    {!!Form::textarea('description',null,['class'=>'form-control','placeholder'=>'Optional Description','id'=>'role_description']) !!}
    @if($errors->has('role_description'))
      <span class="help-block">
            {{ $errors->first('role_description') }}
      </span>
    @endif
  </div>


</div>

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
</style>

<!-- /.box-body -->
