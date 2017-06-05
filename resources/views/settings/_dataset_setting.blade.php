<div class="box-body">
   <div class="form-group {{ $errors->has('send_register_email') ? ' has-error' : '' }}">
      {!!Form::checkbox('dataset_status','true',null,['class'=>'minimal','id'=>'send_register_email'])!!}
      @if($errors->has('send_register_email'))
      <span class="help-block">
      	{{ $errors->first('subject') }}
      </span>
      @endif
      &nbsp;
      {!!Form::label('send_register_email','Enable Dataset Setting') !!}
   </div>
   <div class="form-group {{ $errors->has('dataset_num_row') ? ' has-error' : '' }}">
      {!!Form::label('dataset_num_row','Number Of Row Dataset') !!}
      {!!Form::text('dataset_num_row',null, ['class'=>'form-control','placeholder'=>'Enter Number Of Row']) !!}
      @if($errors->has('dataset_num_row'))
      <span class="help-block">
      	{{ $errors->first('dataset_num_row') }}
      </span>
      @endif
   </div>
</div>

