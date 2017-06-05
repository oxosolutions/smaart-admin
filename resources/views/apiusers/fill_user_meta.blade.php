@extends('layouts.main')

@section('content')
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Create User Meta
        <small>Api User Meta</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('api_users')}}">Api User Meta</a></li>
        <li class="active">Create New User Meta</li>
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
              <h3 class="box-title">Create New User Meta</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['route' => 'api.store_users_meta', 'files'=>true]) !!}
              @include('apiusers._form_user_meta')
              <div class="box-footer">
                {!! Form::submit('Save User', ['class' => 'btn btn-primary']) !!}
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