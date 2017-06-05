<div class="box-body">

   <div class="form-group {{ $errors->has('send_forget_email') ? ' has-error' : '' }}">
      {!!Form::checkbox('send_forget_email','true',null,['class'=>'minimal','id'=>'send_forget_email'])!!}
      @if($errors->has('send_forget_email'))
      <span class="help-block">
      	{{ $errors->first('send_forget_email') }}
      </span>
      @endif
      &nbsp;
      {!!Form::label('send_forget_email','Enable Forget Email') !!}
   </div>
   <div class="form-group {{ $errors->has('forget_subject') ? ' has-error' : '' }}">
      {!!Form::label('forget_subject','Forget Password Email Subject') !!}
      {!!Form::text('forget_subject',null, ['class'=>'form-control','placeholder'=>'Enter Subject']) !!}
      @if($errors->has('forget_subject'))
      <span class="help-block">
      	{{ $errors->first('forget_subject') }}
      </span>
      @endif
   </div>

   <div class="form-group {{ $errors->has('forget_description') ? ' has-error' : '' }}">
      {!!Form::label('forget_description','Forget Email Description') !!}
      {!!Form::textarea('forget_description',null, ['class'=>'form-control','placeholder'=>'Enter Description']) !!}
      @if($errors->has('forget_description'))
      <span class="help-block">
      	{{ $errors->first('forget_description') }}
      </span>
      @endif
   </div>

</div>

