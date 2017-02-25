
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
<!-- "id" => 32
        "permisson_id" => 12
        "route" => "api_users"
        "route_for" => "read"
        "route_name" => "List User" -->

<!-- {{dump($model->routeMapping)}} -->
@foreach($model->routeMapping as $route)

<div class="data-div">
  <div class=" form-group {{ $errors->has('route') ? ' has-error' : '' }} floating-select-div">
        <input type="hidden" name="route_map_id[]" value="{{$route->id}}" >

    {!!Form::label('route','Route') !!}
    {!!Form::select('route[]',App\Permisson::getRouteListArray(),$route->route, ['class'=>'form-control','placeholder'=>'url ']) !!}
    @if($errors->has('route'))
      <span class="help-block">
            {{ $errors->first('route') }}
      </span>
    @endif
  </div>
  <div class="form-group {{ $errors->has('route') ? ' has-error' : '' }} floating-select-div">


    {!!Form::label('route','Route For') !!}
    {!!Form::select('routeFor[]',App\Permisson::getRouteFor(),$route->route_for, ['class'=>'form-control','placeholder'=>'Route For']) !!}
    @if($errors->has('route'))
      <span class="help-block">
            {{ $errors->first('route') }}
      </span>
    @endif
  </div>
   <div class="form-group {{ $errors->has('route_name') ? ' has-error' : '' }} floating-select-div">
        {!!Form::label('route_name','Route Name') !!}
        {!!Form::text('route_name[]',$route->route_name, ['class'=>'form-control','placeholder'=>'Enter Route Name']) !!}
        @if($errors->has('route_name'))
          <span class="help-block">
                {{ $errors->first('route_name') }}
          </span>
        @endif
  </div>
  <a href="{{route('permisson.delete_route',$route->id)}}" type="button" style="margin-top: 26px;" class="remove_field btn btn-danger"><i class="fa fa-minus"></i></a>

</div>

@endforeach
<div class="append-data" style="display:none">
    <div class="input_fields_wrap" >
      <div class=" form-group {{ $errors->has('route') ? ' has-error' : '' }} floating-select-div">


        {!!Form::label('route','Route') !!}
        {!!Form::select('route_new[]',App\Permisson::getRouteListArray(),null, ['class'=>'form-control','placeholder'=>'url ']) !!}
        @if($errors->has('route'))
          <span class="help-block">
                {{ $errors->first('route') }}
          </span>
        @endif
      </div>
      <div class="form-group {{ $errors->has('route') ? ' has-error' : '' }} floating-select-div">


        {!!Form::label('route','Route For') !!}
        {!!Form::select('routeFor_new[]',App\Permisson::getRouteFor(),null, ['class'=>'form-control','placeholder'=>'Route For']) !!}
        @if($errors->has('route'))
          <span class="help-block">
                {{ $errors->first('route') }}
          </span>
        @endif
      </div>
       <div class="form-group {{ $errors->has('route_name') ? ' has-error' : '' }} floating-select-div">
        {!!Form::label('route_name','Route Name') !!}
        {!!Form::text('route_name_new[]',null, ['class'=>'form-control','placeholder'=>'Enter Route Name']) !!}
        @if($errors->has('route_name'))
          <span class="help-block">
                {{ $errors->first('route_name') }}
          </span>
        @endif
      </div>
      <button type="button" style="margin-top: 26px;" class="remove_field btn btn-danger"><i class="fa fa-minus"></i></button>

    </div>
</div>
<!-- <div class="input_fields_wrap">
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



</div> -->
   <div id="append" class="form-group {{ $errors->has('route') ? ' has-error' : '' }}">
   </div>
   <button style="float: left;display: block;clear: both;" class="add_field_button btn btn-success"><i class="fa fa-plus"></i></button>

 <!--  <div class="form-group {{ $errors->has('goal_title') ? ' has-error' : '' }}">
    {!!Form::label('display_name','Display Name') !!}
    {!!Form::text('display_name',null, ['class'=>'form-control','placeholder'=>'Optional Display Name']) !!}
    @if($errors->has('display_name'))
      <span class="help-blocnnk">
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
      width: 30% !important;
      margin-right: 5px ;

  }
</style>

<!-- /.box-body -->
