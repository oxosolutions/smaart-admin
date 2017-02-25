@extends('layouts.main')

@section('content')
 <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Create Page Form
        <small>Page
        </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('pages')}}">Pages</a></li>
        <li class="active">Create Page</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Create Page</h3>
            </div>
              {!! Form::open(['route' => 'pages.store', 'files'=>true]) !!}
                @include('pages._form')
              <div class="box-footer">
                {!! Form::submit('Save Page', ['class' => 'btn btn-primary']) !!}
              </div>
              {!! Form::close() !!}

          </div>
        </div>
      </div>
    </section>
  </div>
@endsection
