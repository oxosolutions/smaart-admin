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
             {!! Form::open(['route' => 'log.search', 'files'=>true,  'method' => 'get']) !!}

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
       

         
          <!-- /.box -->
        </div>

        <section class="content">

      <!-- row -->
      <div class="row">
       <div class="pull-right">{{ $log->links() }} </div>
        <div class="col-md-12">
             

          <!-- The time line -->
          <ul class="timeline">
            <!-- timeline time label -->
             <?php $index=1; $date ='';  ?>
             @if(count($log)>0)
                 @foreach($log as $value)
                   @if(Carbon\Carbon::parse($value->created_at)->format('d M Y')!=$date)
                      <?php $date = Carbon\Carbon::parse($value->created_at)->format('d M Y'); ?>
                      <li class="time-label">
                        <span class="bg-red">{{Carbon\Carbon::parse($value->created_at)->format('d M, Y') }}</span>
                      </li>
                    @endif
                    <li>
                      <?php $text = json_decode($value->text, true); ?>
                       @if(array_has($text,'query'))
                          <i class="fa fa-space-shuttle bg-aqua"></i>
                          @else
                           <i class="fa  fa-television bg-yellow"></i>
                        @endif
                        <div class="timeline-item">
                          <span class="time"><i class="fa fa-clock-o"></i> 
                            <?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($value->created_at))->diffForHumans() ?>
                          </span>

                      <h3 class="timeline-header no-border"><a href="#">{{@$text['name']}} <span style="color:#999;" > ({{@$text['email']}})</span></a>
                       @if(array_has($text,'query'))

                           
                              @if(starts_with($text['query'], 'select'))
                              <span class="text-green"> Query Run SELECT
                                 <?php $arra = explode(' ',$text['query']);
                                       // print_r($arra);
                                        $key = array_search('from',$arra )+1;
                                        echo strtoupper(str_replace(['`',"_"]," ",$arra[$key]));
                                 ?>  
                                 </span>                                
                              @elseif(starts_with($text['query'], 'update'))
                               <div class="text-orange">Query Run  UPDATE 
                              <?php $arra = explode(' ',$text['query']);
                                       // print_r($arra);
                                        
                                        echo strtoupper(str_replace(['`',"_"]," ",$arra[1]));
                                 ?>
                                 @if(count($text['value']) >0)
                                   With value {{  implode(", ",$text['value']) }}
                                 @endif      
                               </div>
                              @endif
                                                  
                          @else 
                          <span class="text-light-blue">                       
                              @if($text['route']=="/")
                                  View  Dashboard
                              @else
                                has Access this route {{url($text['route'])}} 
                              @endif
                          </span>  
                        @endif
                    </h3>
                        </div>
                    </li>
                 

                   <?php $index++;?>
                @endforeach
              @else
              <li>
              <i class="fa fa-comments bg-yellow"></i>

              <div class="timeline-item">
                <h3 class="timeline-header"><a href="#">No Result Found Try Again.</a> </h3>
              </div>
            </li>
             
              @endif
           
          </ul>
        </div>
        <!-- /.col -->
      </div>


      <!-- /.row -->

     
      <!-- /.row -->

    </section>
      </div>
         
    </section>

  </div>
@endsection
