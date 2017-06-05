<div class="box-body">

   <div class="form-group {{ $errors->has('send_approveuser_email') ? ' has-error' : '' }}">
      {!!Form::checkbox('send_approveuser_email','true',null,['class'=>'minimal','id'=>'send_approveuser_email'])!!}
      @if($errors->has('send_approveuser_email'))
      <span class="help-block">
      	{{ $errors->first('send_approveuser_email') }}
      </span>
      @endif
      &nbsp;
      {!!Form::label('send_approveuser_email','Enable User Approvel Email') !!}
   </div>
   <div class="form-group {{ $errors->has('approvel_subject') ? ' has-error' : '' }}">
      {!!Form::label('approvel_subject','Approved Email Subject') !!}
      {!!Form::text('approvel_subject',null, ['class'=>'form-control','placeholder'=>'Enter Subject']) !!}
      @if($errors->has('approvel_subject'))
      <span class="help-block">
      	{{ $errors->first('approvel_subject') }}
      </span>
      @endif
   </div>

   <div class="form-group {{ $errors->has('aprroved_description') ? ' has-error' : '' }}">
      {!!Form::label('aprroved_description','Approved Email Description') !!}
      {!!Form::textarea('aprroved_description',null, ['class'=>'form-control','placeholder'=>'Enter Description']) !!}
      @if($errors->has('aprroved_description'))
      <span class="help-block">
      	{{ $errors->first('aprroved_description') }}
      </span>
      @endif
   </div>

</div>

