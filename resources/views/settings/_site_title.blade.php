<div class="box-body">
   <div class="form-group {{ $errors->has('site_title') ? ' has-error' : '' }}">
      {!!Form::label('site_title','Site Title') !!}
            {!!Form::hidden('meta_type','site_title', ['class'=>'form-control','placeholder'=>'Enter Number Of Row']) !!}

      {!!Form::text('value',null, ['class'=>'form-control','placeholder'=>'Enter Site Title']) !!}
      @if($errors->has('site_title'))
      <span class="help-block">
      	{{ $errors->first('site_title') }}
      </span>
      @endif
   </div>
</div>

