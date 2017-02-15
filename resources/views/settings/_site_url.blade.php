<div class="box-body">
  
   <div class="form-group {{ $errors->has('site_url') ? ' has-error' : '' }}">
      {!!Form::label('site_url','Site Url') !!}
            {!!Form::hidden('meta_type','site_url', ['class'=>'form-control','placeholder'=>'Enter Number Of Row']) !!}

      {!!Form::text('value',null, ['class'=>'form-control','placeholder'=>'Enter Site Url']) !!}
      @if($errors->has('site_tagline'))
      <span class="help-block">
         {{ $errors->first('site_url') }}
      </span>
      @endif
   </div>
</div>

