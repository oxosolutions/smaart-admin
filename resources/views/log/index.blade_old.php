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
        Log System
        <small> Log System</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"> Log System</li>
      </ol>
    </section>
    <input type="hidden" value="{{Auth::user()->api_token}}" name="token_user" />
    <section class="content">
      <div class="col-xs-12">
        <div class="box-header">
          <div class="row">
             {!! Form::open(['route' => 'log.search', 'files'=>true]) !!}

                    <div class="col-xs-2">
                      <div class=" form-group {{ $errors->has('user_name') ? ' has-error' : '' }} floating-select-div">
                    
                        {!!Form::select('user_name',App\User::userList(),null, ['class'=>'form-control','placeholder'=>'User Name']) !!}
                        @if($errors->has('user_name'))
                          <span class="help-block">
                                {{ $errors->first('user_name') }}
                          </span>
                        @endif
                      </div>

                    </div>
                    <div class="col-xs-2">
                      <div class="form-group">              
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input name="from" type="text" class="form-control pull-right" id="datepickerFrom"  placeholder="From Date">
                        </div>
                      </div>                             
                    </div>
                    <div class="col-xs-2">
                      <div class="form-group">
                       <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input name="to" type="text" class="form-control pull-right" id="datepickerTo" placeholder="To Date">
                        </div>
                      </div>
                    </div>
                  <div class="col-xs-2">
                      {!! Form::submit('Search Log', ['class' => 'btn btn-primary']) !!}
                   </div>
                {!! Form::close() !!}
           </div>
        </div>
       

          <div class="box">
            
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                
                <?php $index=1; $date ='';  ?>
                 @foreach($log as $value)
                   @if(Carbon\Carbon::parse($value->created_at)->format('d M Y')!=$date)
                      <?php $date = Carbon\Carbon::parse($value->created_at)->format('d M Y'); ?>
                     <tr> <td></td><td><h3>{{Carbon\Carbon::parse($value->created_at)->format('d M Y') }}</h3></td></tr>
                    @endif
                  <tr>
                 <td>{{Carbon\Carbon::parse($value->created_at)->format('g:i:s A') }}</td>
                    
                    <?php $text = json_decode($value->text, true); ?>


                    
                    <td>{{@$text['email']}}
                    @if(array_has($text,'query'))
                   
                          Query Run {{$text['query']}} with values 
                        @else
                          
                              @if($text['route']=="/")

                              View  Dashboard
                              @else

                              has Access this route {{url($text['route'])}} At {{$value->created_at}}
                              @endif
                              </td>

                      @endif


                   </tr>
                   <?php $index++;?>
                    @endforeach
                    
                 
                 
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
         
    </section>

  </div>
@endsection
