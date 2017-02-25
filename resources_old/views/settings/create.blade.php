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

    <!-- User Register Email Settings Form-->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">User Registeration Config</h3>
            </div>
              {!! Form::open(['route' => 'designations.store', 'files'=>true]) !!}
                @include('settings._new_user_email_form')
              <div class="box-footer">
                {!! Form::submit('Save Settings', ['class' => 'btn btn-primary']) !!}
              </div>
              {!! Form::close() !!}
          </div>
        </div>
      </div>
    <!-- User Register Email Settings Form End-->

    </section>
  </div>
@endsection
