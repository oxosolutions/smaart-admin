<div class="box-body">
  <div class="form-group {{ $errors->has('intervention_id') ? ' has-error' : '' }}">
    {!!Form::label('fact_id','Fact ID') !!}
    {!!Form::text('fact_id',null, ['class'=>'form-control','placeholder'=>'Enter Fact ID']) !!}
    @if($errors->has('fact_id'))
      <span class="help-block">
            {{ $errors->first('intervention_id') }}
      </span>
    @endif
  </div>

  <div class="form-group {{ $errors->has('intervention_title') ? ' has-error' : '' }}">
    {!!Form::label('fact_title','Fact Title') !!}
    {!!Form::text('fact_title',null, ['class'=>'form-control','placeholder'=>'Enter Fact Title']) !!}
    @if($errors->has('fact_title'))
      <span class="help-block">
            {{ $errors->first('intervention_title') }}
      </span>
    @endif
  </div>

  @if(@$model)
    <div class="input-group input-group-sm">
      {!!Form::label('fact_image','Current Image') !!}<br/>
      @if(file_exists('fact_image/'.$model->ministry_image))
      <img src="{{asset('fact_image/').'/'.$model->fact_image}}" width="160px" />
      @else
      <img src="http://www.freeiconspng.com/uploads/no-image-icon-1.jpg" width="160px" />
      @endif
    </div><br/>
  @endif


  <div class="{{ $errors->has('fact_image') ? ' has-error' : '' }} input-group input-group-sm">
    {!!Form::label('fact_image','Fact Image') !!}
    {!!Form::file('fact_image',['class'=>'form-control','id'=>'file-3']) !!}
    @if($errors->has('fact_image'))
      <span class="help-block">
            {{ $errors->first('fact_image') }}
      </span>
    @endif
  </div>
  
  <div class="form-group {{ $errors->has('intervention_desc') ? ' has-error' : '' }} " style="margin-top: 2%;">
    {!!Form::label('fact_desc','Fact Description') !!}
    {!!Form::textarea('fact_desc',null,['class'=>'form-control','placeholder'=>'Eenter Description','id'=>'fact_desc']) !!}
    @if($errors->has('fact_desc'))
      <span class="help-block">
            {{ $errors->first('fact_desc') }}
      </span>
    @endif
  </div>

  


</div>
<!-- /.box-body -->
