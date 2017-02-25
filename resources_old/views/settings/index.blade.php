@extends('layouts.main')

@section('content')
<style type="text/css">
  ul li{
    list-style: none
  }
</style>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        App Settings
        <small>Config panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Settings</li>
      </ol>
    </section>
    <input type="hidden" value="{{Auth::user()->api_token}}" name="token_user" />
    <section class="content">
      @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
         <i class="icon fa fa-check"></i> 
          {{$message}}
        </div>
      @endif
    <!-- User Register Email Settings Form-->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Register Email Config</h3>
            </div>
              {!! Form::model($Reg_model, ['method' => 'PATCH','route'=>['register.settings', $Reg_model->id], 'files'=>true]) !!}
              {{--{!! Form::open(['route' => 'register.settings', 'files'=>true]) !!}--}}
                @include('settings._new_user_email_form')
              <div class="box-footer">
                {!! Form::submit('Save Settings', ['class' => 'btn btn-primary']) !!}
              </div>
              {!! Form::close() !!}
          </div>
        </div>
      </div>
    <!-- User Register Email Settings Form End-->

    <!-- User Forget Email Settings Form-->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Forget Email Config</h3>
            </div>
              {!! Form::model($Forget_model, ['method' => 'PATCH','route'=>['forget.settings', $Forget_model->id], 'files'=>true]) !!}
              {{--{!! Form::open(['route' => 'designations.store', 'files'=>true]) !!}--}}
                @include('settings._forget_email_form')
              <div class="box-footer">
                {!! Form::submit('Save Settings', ['class' => 'btn btn-primary']) !!}
              </div>
              {!! Form::close() !!}
          </div>
        </div>
      </div>
    <!-- User Forget Email Settings Form End-->

    <!-- User Register Mail to Admin Email Settings Form -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Admin Register Config</h3>
            </div>
              {!! Form::model($adminReg_model, ['method' => 'PATCH','route'=>['adminreg.settings', $adminReg_model->id], 'files'=>true]) !!}
              {{--{!! Form::open(['route' => 'designations.store', 'files'=>true]) !!}--}}
                @include('settings._admin_userreg_email_form')
              <div class="box-footer">
                {!! Form::submit('Save Settings', ['class' => 'btn btn-primary']) !!}
              </div>
              {!! Form::close() !!}
          </div>
        </div>
      </div>
    <!-- User Register Mail to Admin Email Settings Form End-->

    <!-- On User Approve Email Settings Form -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">User Approvel Config</h3>
            </div>
              {!! Form::model($userApprov_model, ['method' => 'PATCH','route'=>['aprroveuser.settings', $userApprov_model->id], 'files'=>true]) !!}
              {{--{!! Form::open(['route' => 'designations.store', 'files'=>true]) !!}--}}
                @include('settings._after_approve_user')
              <div class="box-footer">
                {!! Form::submit('Save Settings', ['class' => 'btn btn-primary']) !!}
              </div>
              {!! Form::close() !!}
          </div>
        </div>
      </div>
    <!-- User Register Mail to Admin Email Settings Form End-->
     <!-- On Data set Settings Form -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Dataset Number Row Setting</h3>
            </div>
              {!! Form::model($dataset_model, ['method' => 'PATCH','route'=>['dataset.settings', $dataset_model->id], 'files'=>true]) !!}
              {{--{!! Form::open(['route' => 'designations.store', 'files'=>true]) !!}--}}
                @include('settings._dataset_setting')
              <div class="box-footer">
                {!! Form::submit('Save Settings', ['class' => 'btn btn-primary']) !!}
              </div>
              {!! Form::close() !!}
          </div>
        </div>
      </div>
    <!-- User Data set  Settings Form End-->
     <!-- On Data set Settings Form -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Site Title Setting</h3>
            </div>
              {!! Form::model(@$siteTitle_model, ['method' => 'PATCH','route'=>['sitevalue.settings', @$siteTitle_model->id], 'files'=>true]) !!}
              {{--{!! Form::open(['route' => 'designations.store', 'files'=>true]) !!}--}}
                @include('settings._site_title')
              <div class="box-footer">
                {!! Form::submit('Save Settings', ['class' => 'btn btn-primary']) !!}
              </div>
              {!! Form::close() !!}
          </div>
        </div>
      </div>
    <!-- User Data set  Settings Form End-->
     <!-- On Data set Settings Form -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Site Tagline Setting</h3>
            </div>
              {!! Form::model(@$siteTagline_model, ['method' => 'PATCH','route'=>['sitevalue.settings', @$siteTagline_model->id], 'files'=>true]) !!}
              {{--{!! Form::open(['route' => 'designations.store', 'files'=>true]) !!}--}}
                @include('settings._site_tagline')
              <div class="box-footer">
                {!! Form::submit('Save Settings', ['class' => 'btn btn-primary']) !!}
              </div>
              {!! Form::close() !!}
          </div>
        </div>
      </div>
    <!-- User Data set  Settings Form End-->
     <!-- On Data set Settings Form -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Site Url Setting</h3>
            </div>
              {!! Form::model(@$siteUrl_model, ['method' => 'PATCH','route'=>['sitevalue.settings', @$siteUrl_model->id], 'files'=>true]) !!}
              {{--{!! Form::open(['route' => 'designations.store', 'files'=>true]) !!}--}}
                @include('settings._site_url')
              <div class="box-footer">
                {!! Form::submit('Save Settings', ['class' => 'btn btn-primary']) !!}
              </div>
              {!! Form::close() !!}
          </div>
        </div>
      </div>


    <!-- User Data set  Settings Form End-->
    <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Visual Setting</h3>
            </div>
              {!! Form::model(@$visual_setting, ['method' => 'PATCH','route'=>['sitevalue.settings', @$visual_setting->id], 'files'=>true]) !!}
              {{--{!! Form::open(['route' => 'designations.store', 'files'=>true]) !!}--}}
                @include('settings._visual_setting')
              <div class="box-footer">
                {!! Form::submit('Save Settings', ['class' => 'btn btn-primary']) !!}
              </div>
              {!! Form::close() !!}
          </div>
        </div>
      </div>

    </section>
  </div>
@endsection
