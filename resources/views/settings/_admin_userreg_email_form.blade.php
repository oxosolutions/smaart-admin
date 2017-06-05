<div class="box-body">
  
   <div class="form-group {{ $errors->has('send_adminReg_email') ? ' has-error' : '' }}">
      {!!Form::checkbox('send_adminReg_email','true',null,['class'=>'minimal','id'=>'send_adminReg_email'])!!}
      @if($errors->has('send_adminReg_email'))
      <span class="help-block">
      	{{ $errors->first('subject') }}
      </span>
      @endif
      &nbsp;
      {!!Form::label('send_adminReg_email','Enable Registration Email') !!}
   </div>
   <div class="form-group {{ $errors->has('adminreg_subject') ? ' has-error' : '' }}">
      {!!Form::label('adminreg_subject','Enter Subject') !!}
      {!!Form::text('adminreg_subject',null, ['class'=>'form-control','placeholder'=>'Enter Subject']) !!}
      @if($errors->has('adminreg_subject'))
      <span class="help-block">
         {{ $errors->first('adminreg_subject') }}
      </span>
      @endif
   </div>

   <div class="form-group {{ $errors->has('adminreg_email') ? ' has-error' : '' }}">
      {!!Form::label('adminreg_email','Enter Admin Email') !!}
      {!!Form::text('adminreg_email',null, ['class'=>'form-control','placeholder'=>'Enter Admin Email']) !!}
      @if($errors->has('adminreg_email'))
      <span class="help-block">
      	{{ $errors->first('adminreg_email') }}
      </span>
      @endif
   </div>

   <div class="form-group {{ $errors->has('adminreg_description') ? ' has-error' : '' }}">
      {!!Form::label('adminreg_description','Enter Email Description') !!}
      {!!Form::textarea('adminreg_description',null, ['class'=>'form-control','placeholder'=>'Enter Description']) !!}
      @if($errors->has('adminreg_description'))
      <span class="help-block">
      	{{ $errors->first('adminreg_description') }}
      </span>
      @endif
   </div>

</div>

