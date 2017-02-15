<div class="box-body">
  
   <div class="form-group {{ $errors->has('visual_setting') ? ' has-error' : '' }}">
      {!!Form::label('visual_setting','Visual Settimg Parameter') !!}
            {!!Form::hidden('meta_type','visual_setting', ['class'=>'form-control','placeholder'=>'Enter Number Of Row']) !!}

      {!!Form::textarea('value',null, ['class'=>'form-control','placeholder'=>'Enter Site Url']) !!}
      @if($errors->has('visual_setting'))
      <span class="help-block">
         {{ $errors->first('visual_setting') }}
      </span>
      @endif
   </div>
</div>

