<div class="box-body">
  
   <div class="form-group {{ $errors->has('survey_themes') ? ' has-error' : '' }}">
      {!!Form::label('survey_themes','Default Settimg Parameter') !!}
            {!!Form::hidden('meta_type','survey_themes', ['class'=>'form-control','placeholder'=>'Enter Number Of Row']) !!}

      {!!Form::textarea('value',null, ['class'=>'form-control','placeholder'=>'Enter themes JSON']) !!}
      @if($errors->has('survey_themes'))
      <span class="help-block">
         {{ $errors->first('survey_themes') }}
      </span>
      @endif
   </div>
</div>

