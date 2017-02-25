<div class="box-body">
  <div class="form-group {{ $errors->has('page_title') ? ' has-error' : '' }}">
    {!!Form::label('page_title','Page Title') !!}
    {!!Form::text('page_title',null, ['class'=>'form-control','placeholder'=>'Enter Page title']) !!}
    @if($errors->has('page_title'))
      <span class="help-block">
            {{ $errors->first('page_title') }}
      </span>
    @endif
  </div>
  <div class="form-group {{ $errors->has('page_subtitle') ? ' has-error' : '' }}">
    {!!Form::label('page_subtitle','Page Sub-Title') !!}
    {!!Form::text('page_subtitle',null, ['class'=>'form-control','placeholder'=>'Enter Page Sub-title']) !!}
    @if($errors->has('page_subtitle'))
      <span class="help-block">
            {{ $errors->first('page_subtitle') }}
      </span>
    @endif
  </div>
  <div class="form-group {{ $errors->has('page_slug') ? ' has-error' : '' }}">
    {!!Form::label('page_slug','Page Slug') !!}
    {!!Form::text('page_slug',null, ['class'=>'form-control','placeholder'=>'Enter Page Slug']) !!}
    @if($errors->has('page_slug'))
      <span class="help-block">
            {{ $errors->first('page_slug') }}
      </span>
    @endif
  </div>
  <div class="form-group {{ $errors->has('content') ? ' has-error' : '' }}">
    {!!Form::label('content','Page Content') !!}
    {!!Form::textarea('content',null, ['class'=>'form-control','placeholder'=>'Enter Content','id'=>'editor1']) !!}
    @if($errors->has('content'))
      <span class="help-block">
            {{ $errors->first('content') }}
      </span>
    @endif
  </div>

  @if(!empty(@$model->page_image))
    <div class="input-group input-group-sm">
      {!!Form::label('page_image','Current Image') !!}<br/>
      <img src="{{asset('pages_data/').'/'.$model->page_image}}" width="160px" />
    </div><br/>
    @else
     <div class="input-group input-group-sm">
     
      <img src="{{asset('/No_Image_Available.png')}}" width="100px" />
    </div><br/>
   
  @endif
  <div class="{{ $errors->has('page_image') ? ' has-error' : '' }} input-group input-group-sm">
    {!!Form::label('page_image','Image') !!}
    {!!Form::file('page_image',['class'=>'form-control','id'=>'file-3']) !!}
    @if($errors->has('page_image'))
      <span class="help-block">
            {{ $errors->first('page_image') }}
      </span>
    @endif
  </div>

  <div class="{{ $errors->has('status') ? ' has-error' : '' }} form-group" style="margin-top: 2%;">
    {!!Form::label('status','Status') !!}
    {!!Form::select('status',\App\Page::statusList(),null, ['class'=>'form-control','placeholder'=>'Select Status']) !!}
    @if($errors->has('status'))
      <span class="help-block">
            {{ $errors->first('status') }}
      </span>
    @endif
  </div>


</div>
<!-- /.box-body -->
<style type="text/css">
  .file-actions{
      float: right;
  }
  .file-upload-indicator{
     display: none;
  }

  </style>
