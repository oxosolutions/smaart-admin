@extends('layouts.main')

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Maps
        <small>list of Map</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="javascript:;">Map</a></li>
        <li class="active">List Map</li>
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
       @if ($message = Session::get('error'))
        <div class="alert alert-danger bg-red-active alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fa fa-ban"></i> {{$message}}
              </div>
      @endif
      <div class="row">
        <div class="col-xs-12">
          <div class="box-header">
              <button class="btn btn-primary" onclick="window.location='{{route('map.create')}}'">Create New Map</button>
          </div>
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="map" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Title</th>
                  <th>Code</th>
                  <th>Parent</th>
                  <th>Code Albha 2</th>
                  <th>Code Albha 3</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
                <tfoot>
                <tr>
                   <th>ID</th>
                  <th>Title</th>
                  <th>Code</th>
                  <th>Parent</th>
                  <th>Code Albha 2</th>
                  <th>Code Albha 3</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <div class="overlay"></div>
  <div id="mapEditor">
    
  </div>
  <input type="hidden" name="csrf" value="{{csrf_token()}}">
  <style type="text/css">
    #mapEditor{
        width: 100%;
        position: absolute;
        top: 4%;
        left: 0%;
        height: 900px;
        z-index: 6666;
        background-color: #FFF;
        display: none;
    }
    .overlay{
        width: 100%;
        height: 1000px;
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0.5;
        background-color: #000;
        overflow-y: hidden;
        z-index: 5555;
        display: none;
    }
  </style>
  <!-- /.content-wrapper -->
@endsection