@extends('layouts.main')

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Maps
        <small>View Map</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">View Map</li>
      </ol>
    </section>
    
    <!-- Main content -->
    <section class="content">
      
      <div class="row">
        <div class="col-xs-12">
          <div class="box-header">
              <a  href="{{url('maps')}}" class="btn btn-primary" >Back to map list</a>
          </div>
          <div class="box">

			<div class="map-info">
				<h4 class="map-info-title">{{$svg->title}}</h4>
				<ul>
					<li><span class="map-info-label">Code</span><span class="map-info-value">{{$svg->code}}</span></li>
					<li><span class="map-info-label">Code Albha 2</span><span class="map-info-value">{{$svg->code_albha_2}}</span></li>
					<li><span class="map-info-label">Code Albha 3</span><span class="map-info-value">{{$svg->code_albha_3}}</span></li>
				</ul>
			</div> 
           
            <!-- /.box-header -->
            <div class="box-body">

              <?php echo $svg->map_data; ?>
              
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
  
  <!-- /.content-wrapper -->
@endsection