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
        API Config
        <small>Config panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">API Config</li>
      </ol>
    </section>
    <input type="hidden" value="{{Auth::user()->api_token}}" name="token_user" />
    <section class="content">
      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> User Register</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/register
                  </code>
                </p>
                <p>
                  <code>
                    Method: POST
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">name </spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">email </spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">password </spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">departments </spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">ministries </spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">designation </spam> <span class="label label-danger">Required</span></li>
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Dataset Import API</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/dataset/import?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: POST
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">file </spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">format </spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">add_replace </spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">with_dataset </spam> <span class="label label-success">Optional</span></li>
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> List Dataset API</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/dataset/list?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  none
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Single Dataset API</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/dataset/view/{id}?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2">id </spam> <span class="label label-danger">Required</span></li>
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> All Departments API</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/department/list?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  none
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Single Department API</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/department/{id}?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2">id </spam> <span class="label label-danger">Required</span></li>
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Ministry List API</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/ministry/list?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  none
                </code>
              </div>
            </div>
          </div>
      </div>


      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Single Ministry API</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/ministry/{id}?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2">id </spam> <span class="label label-danger">Required</span></li>
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Goals List API</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/goals/list?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  none
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Single Goal API</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/goals/{id}?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2">id </spam> <span class="label label-danger">Required</span></li>
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> All Users List API</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/users?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  none
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Export Single Dataset API</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/dataset/export/{id}?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2">id </spam> <span class="label label-danger">Required</span></li>
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Download Exported File API</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/dataset/download/{fileName}?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2">filename </spam> <span class="label label-danger">Required</span></li>
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Get Goal By <code>goal_number</code> API</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/goalData/{goal_number}?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2">goal_number </spam> <span class="label label-danger">Required</span></li>
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Get All Schems API</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/schema?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  none
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Get All Visual list API</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/visual/list?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  none
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Get Visual Data By ID API</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/visual/{id}?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">id </spam> <span class="label label-danger">Required</span></li>
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Store Visual</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/store/visual?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: POST
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">dataset (id) </spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">visual_name </spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">options </spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">settings </spam> <span class="label label-danger">Required</span></li>
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>
      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> All Indicators</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/indicators?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  none
                </code>
              </div>
            </div>
          </div>
      </div>
      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> All Pages</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/pages?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  none
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Page by Slug</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/pages/{page_slug}?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  page_slug
                </code>
              </div>
            </div>
          </div>
      </div>
      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> dataset chartdata by id</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    api: {{url('/')}}/api/v1/dataset/chartdata/{id}?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">put token</a>
                </p>
                <p>
                  <code>
                    method: get
                  </code>
                </p>
                params:
                <code>
                  id
                </code>
              </div>
            </div>
          </div>
      </div>
      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Dataset Validate Columns by id</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    api: {{url('/')}}/api/v1/dataset/validate/columns/{id}?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    method: get
                  </code>
                </p>
                params:
                <code>
                  id
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Visual Settings </h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/visual/settings?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: POST
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">options</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">settings</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">id</spam> <span class="label label-danger">Required</span></li>
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>
      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Save Validate Column </h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/dataset/savevalidatecolumns?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: POST
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">id</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">columns</spam> <span class="label label-danger">Required</span></li>
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>
      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Dataset delete by id</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    api: {{url('/')}}/api/v1/dataset/delete/{id}?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    method: get
                  </code>
                </p>
                params:
                <code>
                  id
                </code>
              </div>
            </div>
          </div>
      </div>
      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Visual delete by id</h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    api: {{url('/')}}/api/v1/visual/delete/{id}?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    method: get
                  </code>
                </p>
                params:
                <code>
                  id
                </code>
              </div>
            </div>
          </div>
      </div>
      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Profile </h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    api: {{url('/')}}/api/v1/profile?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    method: get
                  </code>
                </p>
                params:
                <code>
                  null
                </code>
              </div>
            </div>
          </div>
      </div>
      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> User Change Password </h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/profile/changepass?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: POST
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">old_pass</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">new_pass</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">conf_pass</spam> <span class="label label-danger">Required</span></li>
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Dataset Save Edited Datset </h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/dataset/saveEditedDatset?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: POST
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">dataset_id</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">records</spam> <span class="label label-danger">Required</span></li>
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Dataset Save Subset </h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/dataset/saveSubset?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: POST
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">subset_name</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">subset_columns</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">dataset_id</spam> <span class="label label-danger">Required</span></li>
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>
      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Update Profile </h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/update/profile?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: POST
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">name</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">phone</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">email</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">designation</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">address</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">department</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">ministry</spam> <span class="label label-danger">Required</span></li>
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> User List </h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/userlists?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">none</spam></li>
                    
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Edit </h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/editUser/{id}?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">id</spam><span class="label label-danger">Required</span></li>
                    
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>

       <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Update User </h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/user/update?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: POST
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">id</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">name</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">phone</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">email</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">designation</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">address</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">department</spam> <span class="label label-danger">Required</span></li>
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">ministry</spam> <span class="label label-danger">Required</span></li>
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>

       <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> Un Approve User </h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/user/unapprove/{id}?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">id</spam><span class="label label-danger">Required</span></li>
                    
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i>  Approve User </h3>
              </div>
              <div class="box-body">
                <p>
                  <code>
                    API: {{url('/')}}/api/v1/user/approve/{id}?api_token=YOUR_UNIQUE_USER_TOKEN
                  </code>
                  &nbsp;<a href="javascript:;" style="font-size: 11px;" class="put-token">Put Token</a>
                </p>
                <p>
                  <code>
                    Method: GET
                  </code>
                </p>
                Params:
                <code>
                  <ul class="api_param well ">
                    <li><spam class="col-md-2 col-sm-4 col-xs-6">id</spam><span class="label label-danger">Required</span></li>
                    
                  </ul>
                </code>
              </div>
            </div>
          </div>
      </div>

      
    </section>
  </div>
@endsection
