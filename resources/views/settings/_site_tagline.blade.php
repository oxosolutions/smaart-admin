<div class="box-body">
  
   <div class="form-group {{ $errors->has('site_tagline') ? ' has-error' : '' }}">
      {!!Form::label('site_tagline','Site Tagline') !!}
            {!!Form::hidden('meta_type','site_tagline', ['class'=>'form-control','placeholder'=>'Enter Number Of Row']) !!}

      {!!Form::text('value',null, ['class'=>'form-control','placeholder'=>'Enter Site Tagline']) !!}
      @if($errors->has('site_tagline'))
      <span class="help-block">
         {{ $errors->first('site_tagline') }}
      </span>
      @endif
   </div>
</div>

