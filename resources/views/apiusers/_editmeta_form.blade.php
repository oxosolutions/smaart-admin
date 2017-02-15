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
 
   

  <div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
    {!!Form::label('address','Address') !!}
    {!!Form::textarea('address',$address, ['class'=>'form-control','placeholder'=>'Enter address']) !!}
    @if($errors->has('address'))
      <span class="help-block">
            {{ $errors->first('address') }}
      </span>
    @endif
  </div>

  <div class="form-group {{ $errors->has('ministry') ? ' has-error' : '' }}">
    {!!Form::label('ministry','Ministry') !!}
    {!!Form::select('ministry[]',App\Ministrie::ministryList(),$minData, ['class'=>'form-control select2','multiple']) !!}
    @if($errors->has('ministry'))
      <span class="help-block">
            {{ $errors->first('ministry') }}
      </span>
    @endif
  </div>

  <div class="form-group {{ $errors->has('department') ? ' has-error' : '' }}">
    {!!Form::label('department','Department') !!}
    {!!Form::select('department[]',App\Department::departmentList(),$department, ['class'=>'form-control select2','multiple']) !!}
    @if($errors->has('department'))
      <span class="help-block">
            {{ $errors->first('department') }}
      </span>
    @endif
  </div>

  <div class="form-group {{ $errors->has('designation') ? ' has-error' : '' }}">
    {!!Form::label('designation','Designation') !!}
    {!!Form::select('designation',App\Designation::designationList(),$designation, ['class'=>'form-control select2','multiple']) !!}
    @if($errors->has('designation'))
      <span class="help-block">
            {{ $errors->first('designation') }}
      </span>
    @endif
  </div>

  <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
    {!!Form::label('phone','Phone') !!}
    {!!Form::number('phone',$phone, ['class'=>'form-control','placeholder'=>'Enter phone']) !!}
    @if($errors->has('phone'))
      <span class="help-block">
            {{ $errors->first('phone') }}
      </span>
    @endif
  </div>

    @if(!empty(@$profile_pic))
    <div class="input-group input-group-sm">
      {!!Form::label('min_images','Current Image') !!}<br/>
      @if(file_exists('profile_pic/'.$profile_pic))
       <img src="{{asset('profile_pic/').'/'.$profile_pic}}" width="160px" />
      @else
       <img src="http://www.freeiconspng.com/uploads/no-image-icon-1.jpg" width="160px" />
      @endif
    </div><br/>
  @endif

  <div class="col-md-4 form-group {{ $errors->has('profile_pic') ? ' has-error' : '' }}">
    {!!Form::label('file','Upload Pic') !!}
    <input type="hidden" name="current_pic" value="{{$profile_pic}}">
    {!!Form::file('profile_pic', ['class'=>'form-control','placeholder'=>'','id'=>'file-3']) !!}
    @if($errors->has('profile_pic'))
      <span class="help-block">
            {{$errors->first('profile_pic') }}
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