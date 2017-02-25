@extends('layouts.main')

@section('content')
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Create Visual Query
        <small>Visual
        </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('departments')}}">Visual Charts</a></li>
        <li class="active">Create Visual Query</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Create Query</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['route' => 'store.visual.query', 'files'=>true]) !!}
                @include('visual.queries._form')
              <div class="box-footer">
                {!! Form::submit('Save Query', ['class' => 'btn btn-primary']) !!}
                {!! Form::button('Cancel', ['class' => 'btn btn-primary','onclick'=>'window.location.reload()']) !!}
              </div>
              {!! Form::close() !!}
          </div>
          <!-- /.box -->

               

        </div>
        <!--/.col (left) -->
        
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
@endsection