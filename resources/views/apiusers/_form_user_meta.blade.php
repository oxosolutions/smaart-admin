<div class="box-body">
  
    
<!-- <div class="form-group {{ $errors->has('user_list') ? ' has-error' : '' }}">
    {!!Form::label('user_list','User List') !!}
    {!!Form::select('user_list', App\User::userList(),'select', ['placeholder' => 'Select User','class'=>'form-control']) !!}
    @if($errors->has('department'))
      <span class="help-block">
            {{ $errors->first('user_list') }}
      </span>
    @endif
  </div> -->


   <input type="hidden" name="user_list" value="{{$user_id}}">
   
 

  <div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
    {!!Form::label('address','Address') !!}
    {!!Form::textarea('address',null, ['class'=>'form-control','placeholder'=>'Enter address']) !!}
    @if($errors->has('address'))
      <span class="help-block">
            {{ $errors->first('address') }}
      </span>
    @endif
  </div>

  <div class="form-group {{ $errors->has('ministry') ? ' has-error' : '' }}"><!-- fOR MULTIPLE SELECT  select2-department -->
    {!!Form::label('ministry','Ministry') !!}
    {!!Form::select('ministry[]',App\Ministrie::ministryList(),null, ['class'=>'form-control select2', 'multiple']) !!}
    @if($errors->has('ministry'))
      <span class="help-block">
            {{ $errors->first('ministry') }}
      </span>
    @endif
  </div>

  <div class="form-group {{ $errors->has('department') ? ' has-error' : '' }}">
    {!!Form::label('department','Department') !!}
    {!!Form::select('department[]',App\Department::departmentList(),null, ['class'=>'form-control select2','multiple']) !!}
    @if($errors->has('department'))
      <span class="help-block">
            {{ $errors->first('department') }}
      </span>
    @endif
  </div>

  <div class="form-group {{ $errors->has('designation') ? ' has-error' : '' }}">
    {!!Form::label('designation','Designation') !!}
    {!!Form::select('designation',App\Designation::designationList(),null, ['class'=>'form-control select2','multiple']) !!}
    @if($errors->has('designation'))
      <span class="help-block">
            {{ $errors->first('designation') }}
      </span>
    @endif
  </div>

  <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
    {!!Form::label('phone','Phone') !!}
    {!!Form::number('phone',null, ['class'=>'form-control','placeholder'=>'Enter phone']) !!}
    @if($errors->has('phone'))
      <span class="help-block">
            {{ $errors->first('phone') }}
      </span>
    @endif
  </div>

  




  <div class="col-md-4 form-group {{ $errors->has('profile_pic') ? ' has-error' : '' }}">
    {!!Form::label('file','Upload Pic') !!}
    {!!Form::file('profile_pic', ['class'=>'form-control','placeholder'=>'','id'=>'file-3']) !!}
    @if($errors->has('profile_pic'))
      <span class="help-block">
            {{ $errors->first('profile_pic') }}
      </span>
    @endif
  </div>
  
  <div style="clear: both"></div>


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
</style>