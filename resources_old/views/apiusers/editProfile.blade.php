@extends('layouts.main')

@section('content')


 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit User
        <small>Api Users</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('api_users')}}">Api Users</a></li>
        <li class="active">Edit User</li>
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
                  <h3 class="box-title">Edit User</h3>
                </div>
                <form method="POST" action="{{route('updateProfile')}}" class="form-horizontal">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                  <div class="form-group">
                    <label class="control-label col-sm-2" for="name">Name:</label>
                    <div class="col-sm-8">
                    @foreach($user_detail as $value)
                      <input type="text" class="form-control" name="name" value="{{$value->name}}" id="name" placeholder="Enter name">
                    
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-sm-2" for="email">Email:</label>
                    <div class="col-sm-8">
                      <input type="email" class="form-control" disabled="" name="email" value="{{$value->email}}" id="email" placeholder="Enter email">
                    </div>
                  </div> 
                  <div class="form-group">
                    <label class="control-label col-sm-2" for="email">Password:</label>
                    <div class="col-sm-8">
                      <input type="password" class="form-control" name="password" value="" id="email" placeholder="click here for new password">
                      <span style="color:grey;font-size:12px">( <strong>Note: </strong>Leave Blank if you don't want to change the password)</span>
                    </div>
                  </div> 
                  @endforeach  
                  @foreach($user_meta as $value)
                  @if($value->key == "phone" || $value->key == "address")
                  <div class="form-group">
                      <label class="control-label col-sm-2" for="{{$value->key}}">{{ucwords($value->key)}}:</label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" name="{{$value->key}}" value="{{$value->value}}" id="{{$value->key}}" placeholder="Enter {{$value->key}}">
                      </div>
                  </div>
                  @endif
                  @endforeach
                  {{-- <div class="form-group">
                     <!--  @foreach(App\UserMeta::where(['key'=>'ministry' ,'user_id'=>Auth::user()->id])->get() as $value)
                          @foreach(App\Ministrie::select('ministry_title')->where('id',$value->value)->get() as $value)
                            {{$value->ministry_title}}
                          @endforeach
                      @endforeach -->
                    <label class="control-label col-sm-2" for="ministry">Ministry:</label>
                    <div class="col-sm-8">
                      <select class="form-control" id="ministry" name="ministry">
                        @foreach(App\Ministrie::all() as $value)
                            <option value="{{$value->id}}">{{$value->ministry_title}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>    --}}
                  {{-- <div class="form-group">
                    <label class="control-label col-sm-2" for="dep">Department:</label>
                    <div class="col-sm-8">
                      <select class="form-control" id="dep" name="department">
                        @foreach(App\Department::all() as $value)
                            <option value="{{$value->id}}">{{$value->dep_name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>   
                  <div class="form-group">
                    <label class="control-label col-sm-2" for="des">Designation:</label>
                    <div class="col-sm-8">
                      <select class="form-control" id="des" name="designation">
                        @foreach(App\Designation::all() as $value)
                            <option value="{{$value->id}}">{{$value->designation}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>   
                     --}}

                  <div class="form-group"> 
                    <div class="col-sm-offset-2 col-sm-8">
                      <button type="submit" class="btn btn-default">Submit</button>
                    </div>
                  </div>
                </form>
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