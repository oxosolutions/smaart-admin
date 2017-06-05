@extends('layouts.main')

@section('content')
 <div class="content-wrapper">
    <section class="content-header">
    
      <h1>
        Edit Page Form
        <small><a href="{{url('pages')}}">All Pages</a>
        </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('pages')}}">Pages</a></li>
        <li class="active">Edit Pages</li>
      </ol>
    </section>

    <section class="content">

     @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
         <i class="icon fa fa-check"></i>
          {{$message}}
        </div>
      @endif

      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Edit Page</h3>
            </div>
              {!! Form::model($model, ['method' => 'PATCH','route'=>['pages.update', $model->id], 'files'=>true]) !!}
                @include('pages._form')
              <div class="box-footer">
                {!! Form::submit('Save Page', ['class' => 'btn btn-primary']) !!}
                <a href="{{url('pages')}}" class="btn btn-primary pull-right">Back to List</a>
              </div>
              {!! Form::close() !!}
             

          </div>
        </div>
      </div>
    </section>
  </div>
@endsection
