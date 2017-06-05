@extends('layouts.main')
<style type="text/css">
  .box_org{
    display: none
  }
</style>

@section('content')
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Create Organization
        <small>Organization</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('api_users')}}">Organization</a></li>
        <li class="active">Create New Organization</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        @if ($message = Session::get('success'))
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
           <i class="icon fa fa-check"></i> 
            {{$message}}
          </div>
        @endif
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Create New Organization</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['route' => 'organization.store', 'files'=>true]) !!}
                @include('organization._form')
              <div class="box-footer">
                {!! Form::submit('Save Organization', ['class' => 'btn btn-primary']) !!}
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
<script src="http://192.168.0.101/smaartframework.com/smaart-admin/public/bower_components/admin-lte/plugins/jQuery/jquery-2.2.3.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('.org_list').click(function(){
    $('.box_org').slideToggle();
  });
  });
</script>