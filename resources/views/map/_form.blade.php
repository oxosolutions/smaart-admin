<?php
      $parent = App\Map::getParent();
      if(count($parent)>0)
      {
        $parent = App\Map::getParent();
      }else{
         $parent = array(0=>'world'); 

      }

?>


<div class="box-body">

 <div class="form-group {{ $errors->has('parent') ? ' has-error' : '' }}"><!-- fOR MULTIPLE SELECT  select2-department -->
    {!!Form::label('parent','Parent') !!}
    {!!Form::select('parent',$parent,null, ['class'=>'form-control select2','placeholder'=>'Select Parent']) !!}
    @if($errors->has('parent'))
      <span class="help-block">
            {{ $errors->first('parent') }}
      </span>
    @endif
  </div>
  <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
    {!!Form::label('title','Title') !!}
    {!!Form::text('title',null, ['class'=>'form-control','placeholder'=>'Enter Title']) !!}
    @if($errors->has('title'))
      <span class="help-block">
            {{ $errors->first('title') }}
      </span>
    @endif
  </div>

  <div class="form-group {{ $errors->has('code') ? ' has-error' : '' }} " style="margin-top: 2%;">
    {!!Form::label('code',' Code') !!}
    {!!Form::text('code',null,['class'=>'form-control','placeholder'=>'Enter Map Code','id'=>'code']) !!}
    @if($errors->has('code'))
      <span class="help-block">
            {{ $errors->first('code') }}
      </span>
    @endif
  </div>

  <div class="form-group {{ $errors->has('code_albha_2') ? ' has-error' : '' }}">
    {!!Form::label('code_albha_2','Code Albha 2') !!}
    {!!Form::text('code_albha_2',null, ['class'=>'form-control','placeholder'=>'Enter Code Albha 2']) !!}
    @if($errors->has('code_albha_2'))
      <span class="help-block">
            {{ $errors->first('code_albha_2') }}
      </span>
    @endif
  </div>
   <div class="form-group {{ $errors->has('code_albha_3') ? ' has-error' : '' }}">
    {!!Form::label('code_albha_3','Code Albha 3') !!}
    {!!Form::text('code_albha_3',null, ['class'=>'form-control','placeholder'=>'Enter Code Albha 3']) !!}
    @if($errors->has('code_albha_3'))
      <span class="help-block">
            {{ $errors->first('code_albha_3') }}
      </span>
    @endif
  </div>
   <div class="form-group {{ $errors->has('code_numeric') ? ' has-error' : '' }}">
    {!!Form::label('code_numeric','Code Numeric') !!}
    {!!Form::text('code_numeric',null, ['class'=>'form-control','placeholder'=>'Enter Code Numeric']) !!}
    @if($errors->has('code_numeric'))
      <span class="help-block">
            {{ $errors->first('code_numeric') }}
      </span>
    @endif
  </div>

  
<div class="form-group {{ $errors->has('map_data') ? ' has-error' : '' }} " style="margin-top: 2%;">
    {!!Form::label('map_data','Map Data') !!}
    {!!Form::textarea('map_data',null,['class'=>'form-control','placeholder'=>'Enter Map Data','id'=>'map_data']) !!}
    @if($errors->has('map_data'))
      <span class="help-block">
            {{ $errors->first('map_data') }}
      </span>
    @endif
  </div>
   
  
  <div class="form-group {{ $errors->has('map_desc') ? ' has-error' : '' }} " style="margin-top: 2%;">
    {!!Form::label('map_desc','Map Description') !!}
    {!!Form::textarea('description',null,['class'=>'form-control','placeholder'=>'Enter Map Description','id'=>'map_desc']) !!}
    @if($errors->has('map_desc'))
      <span class="help-block">
            {{ $errors->first('map_desc') }}
      </span>
    @endif
  </div>

  


</div>
<!-- /.box-body -->
