<div class="box-body">
  
   <div class="form-group {{ $errors->has('send_register_email') ? ' has-error' : '' }}">
      {!!Form::checkbox('send_register_email','true',null,['class'=>'minimal','id'=>'send_register_email'])!!}
      @if($errors->has('send_register_email'))
      <span class="help-block">
      	{{ $errors->first('subject') }}
      </span>
      @endif
      &nbsp;
      {!!Form::label('send_register_email','Enable Registration Email') !!}
   </div>
   <div class="form-group {{ $errors->has('register_subject') ? ' has-error' : '' }}">
      {!!Form::label('register_subject','New User Register Subject') !!}
      {!!Form::text('register_subject',null, ['class'=>'form-control','placeholder'=>'Enter Subject']) !!}
      @if($errors->has('register_subject'))
      <span class="help-block">
      	{{ $errors->first('register_subject') }}
      </span>
      @endif
   </div>

   <div class="form-group {{ $errors->has('register_subject') ? ' has-error' : '' }}">
      {!!Form::label('register_description','Register Email Description') !!}
      {!!Form::textarea('register_description',null, ['class'=>'form-control','placeholder'=>'Enter Description']) !!}
      @if($errors->has('register_description'))
      <span class="help-block">
      	{{ $errors->first('register_description') }}
      </span>
      @endif
   </div>

</div>

