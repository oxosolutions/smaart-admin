
<div class="box-body">

 
  <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
    {!!Form::label('name','Surrvey Name') !!}
    {!!Form::text('name',null, ['class'=>'form-control','placeholder'=>'Surrvey Name']) !!}
    @if($errors->has('name'))
      <span class="help-block">
            {{ $errors->first('name') }}
      </span>
    @endif
  </div>

  
   
  
  <div class="form-group {{ $errors->has('description') ? ' has-error' : '' }} " style="margin-top: 2%;">
    {!!Form::label('description','Surrvey Description') !!}
    {!!Form::textarea('description',null,['class'=>'form-control','placeholder'=>'Enter Surrvey Description','id'=>'description']) !!}
    @if($errors->has('description'))
      <span class="help-block">
            {{ $errors->first('description') }}
      </span>
    @endif
  </div>

  


</div>
<!-- /.box-body -->
