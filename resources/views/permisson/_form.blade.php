
<div class="box-body">
  <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    {!!Form::label('name','name') !!}
    {!!Form::text('name',null, ['class'=>'form-control','placeholder'=>'Enter Permisson Name']) !!}
    @if($errors->has('name'))
      <span class="help-block">
            {{ $errors->first('name') }}
      </span>
    @endif
  </div>
   <div class="form-group {{ $errors->has('route') ? ' has-error' : '' }} ">
    {!!Form::label('icon','Icon') !!}
    {!!Form::text('icon',null, ['class'=>'form-control','placeholder'=>'Insert Icon']) !!}
    @if($errors->has('icon'))
      <span class="help-block">
            {{ $errors->first('icon') }}
      </span>
    @endif
  </div>

<div class="input_fields_wrap">
  <div class=" form-group {{ $errors->has('route') ? ' has-error' : '' }} floating-select-div">
    {!!Form::label('route','Route') !!}
    {!!Form::select('route[]',App\Permisson::getRouteListArray(),null, ['class'=>'form-control','placeholder'=>'url ']) !!}
    @if($errors->has('route'))
      <span class="help-block">
            {{ $errors->first('route') }}
      </span>
    @endif
  </div>

  <div class="form-group {{ $errors->has('route') ? ' has-error' : '' }} floating-select-div">
    {!!Form::label('route','Route For') !!}
    {!!Form::select('routeFor[]',App\Permisson::getRouteFor(),null, ['class'=>'form-control','placeholder'=>'Route For']) !!}
    @if($errors->has('route'))
      <span class="help-block">
            {{ $errors->first('route') }}
      </span>
    @endif
  </div>

   <div class="form-group {{ $errors->has('route_name') ? ' has-error' : '' }} floating-select-div">
    {!!Form::label('route_name','Route Name') !!}
    {!!Form::text('route_name[]',null, ['class'=>'form-control','placeholder'=>'Enter Route Name']) !!}
    @if($errors->has('route_name'))
      <span class="help-block">
            {{ $errors->first('route_name') }}
      </span>
    @endif
  </div>
</div>  
   <div id="append" class="form-group {{ $errors->has('route') ? ' has-error' : '' }}">
   </div>
   <button class=" add_field_create_form btn btn-success "><i class="fa fa-plus"></i></button>

 <!--  <div class="form-group {{ $errors->has('goal_title') ? ' has-error' : '' }}">
    {!!Form::label('display_name','Display Name') !!}
    {!!Form::text('display_name',null, ['class'=>'form-control','placeholder'=>'Optional Display Name']) !!}
    @if($errors->has('display_name'))
      <span class="help-block">
            {{ $errors->first('display_name') }}
      </span>
    @endif
  </div>
 -->

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
  .floating-select-div{
      float: left;
      width: 32%;
      margin-right: 5px ;

  }
</style>

<!-- /.box-body -->
