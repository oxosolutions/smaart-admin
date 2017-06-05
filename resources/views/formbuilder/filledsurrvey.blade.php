@extends('layouts.main')

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Filled Surrvey
        <small>Filled Surrvey</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="javascript:;"> Surrvey</a></li>
        <li class="active">Filled Surrvey</li>
      </ol>
    </section>
    
    <!-- Main content -->
    <section class="content">
     
      <div class="row">
        <div class="col-xs-12">
          
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="surrvey" class="table table-bordered table-striped">
                
                <tbody>
                
                @foreach($model as $key => $val)
                <tr> <td>{{str_replace('_', ' ', $key)}}</td> <td>{{$val}} </td></tr> 
                @endforeach
                </tbody>
                
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
  <!-- /.content-wrapper -->
@endsection