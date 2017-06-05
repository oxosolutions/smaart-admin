@extends('layouts.main')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

       
       
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{App\DatasetsList::countDataset()}}</h3>

              <p>Datasets</p>
            </div>
            <div class="icon">
              <i class="fa fa-life-ring"></i>
            </div>
            <a href="{{url('/dataset')}}" class="small-box-footer">All Datasets <i class="fa fa-arrow-circle-right"></i></a>
              <a href="{{url('/dataset/create')}}" class="small-box-footer">Add Dataset <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{App\Visualisation::visualisationCount()}}</h3>

              <p>Visualisations</p>
            </div>
            <div class="icon">
              <i class="fa fa-arrows-h"></i>
            </div>
            <a href="{{url('/visualisation')}}" class="small-box-footer">All Visualisations <i class="fa fa-arrow-circle-right"></i></a>
            <a href="{{url('/visualisation/create')}}" class="small-box-footer">Add Visualisation <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{App\Page::countPage()}}</h3>
              <p>Pages</p>
            </div>
            <div class="icon">
              <i class="fa fa-file-o"></i>
            </div>
            <a href="{{url('/pages')}}" class="small-box-footer">All Pages <i class="fa fa-arrow-circle-right"></i></a>
              <a href="{{url('/pages/create')}}" class="small-box-footer">Add Page<i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{App\User::countUser()}}</h3>

              <p>Users</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="{{url('/api_users')}}" class="small-box-footer">All Users <i class="fa fa-arrow-circle-right"></i></a>
              <a href="{{url('/api_users/create')}}" class="small-box-footer">Add User <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
     
      <!-- /.row -->
      <!-- Main row -->
     <!--  -->
          <!-- /.box -->

        

    </section>
    <!-- /.content -->
  </div>




 
@endsection
