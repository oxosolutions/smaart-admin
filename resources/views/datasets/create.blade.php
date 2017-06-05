@extends('layouts.main')

@section('content')
 <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Add Dataset Form
        <small>Dataset
        </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('dataset')}}">Datasets</a></li>
        <li class="active">Create Dataset</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Create Dataset</h3>
            </div>
              {!! Form::open(['route' => 'dataset.store', 'files'=>true]) !!}
                @include('datasets._form')
              <div class="box-footer">
                {!! Form::submit('Save Dataset', ['class' => 'btn btn-primary']) !!}
              </div>
              {!! Form::close() !!}

          </div>
        </div>
      </div>
    </section>
  </div>
@endsection
