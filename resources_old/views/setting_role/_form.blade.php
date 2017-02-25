<div class="box-body">

@if ($message = Session::get('error'))

        <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fa fa-ban"></i> {{$message}}
              </div>
       <!--  <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
         <i class="icon fa fa-check"></i> 
         
        </div> -->
      @endif

<div class="form-group {{ $errors->has('role_id') ? ' has-error' : '' }}">
    {!!Form::label('role_id','Role List') !!}
    {!!Form::select('role_id', App\Role::role_list(),'select', ['placeholder' => 'Select Role','class'=>'form-control']) !!}
    @if($errors->has('role_id'))
      <span class="help-block">
            {{ $errors->first('role_id') }}
      </span>
    @endif

     <table id="example2" class="table table-bordered table-hover">
    <tbody>

    <tr>
      <th>Module </th>
      <th>Read </th>
      <th>Write </th>
      <th>Delete </th>
</tr>
       
@foreach(App\Permisson::permisson_data() as $val)

 <tr>
            <td>{{$val->display_name}}</td>
            <td><input name ="permisson_id[{{$val->id}}][]" type="checkbox" value="read" >
            </td>
            <td><input name ="permisson_id[{{$val->id}}][]" type="checkbox" value="write" ></td>
            <td><input name ="permisson_id[{{$val->id}}][]" type="checkbox" value="delete" ></td>
        </tr>
@endforeach
</tbody>
</table>
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
