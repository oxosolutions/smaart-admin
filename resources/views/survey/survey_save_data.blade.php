@extends('layouts.survey');
<style type="text/css">

.aione-section{
	padding-top: 46px!important;
	
}
.aione-container{
  	height: 100%;
}
.content{
	padding-left: 0px!important;
	padding-right: 0px!important; 
}
	.fix{
    position: fixed;
    top: 10%;
    /*top:79;*/

  }
.thead-fixed{
	/*position: fixed;
	top:79;
	left:0;*/
	background-color: #fff;
}
.table-bordered{
	border:none!important;
}
.main{
  max-width: 100%!important;
}

.inners{
  width: 150px;
  display: inline-block;
}

.outer{
  display: inline;
  width:150%;
}

</style>

@section('content')
<div class="aione-container">
  <div class="fade-background" >
  </div>


  <div id="aione_topbar_1" class="sione-topbar" style="top: 0px;z-index:9999;background-color:#fff;position: fixed;width: 100%;border-bottom: 1px solid #a8a8a8">
    {{-- <div style="padding:10px ; background-color: #2D323E;position: fixed;z-index: 999;width: 17%" class="w-3"> --}}
        {{--  --}}
    {{-- </div> --}}
    <div style="width: 5%;float: left;text-align: center;padding: 29px 0px;border-right: 1px solid #e8e8e8">
      <a class="menu-button" href="javascript:;" style="color: white;font-size: 20px"><i class="fa fa-bars"></i></a>
    </div>
    <div style="width: 80%;float: left;padding: 15px 0px;">
      <div style="margin:0px;float: left;width: 50%">
        <div style="width: 35%">
          <div style="width: 30%;float: left;text-align: center">
            <img src="{{asset('images/logo.png')}}" style="width: 40px">
          </div>
          <div style="color: rgb(3,155,229);width: 70%;float: right">
            <span style="font-size: 20px;line-height: 20px">SMAART&trade;</span><br>
            <span style="font-size: 20px">Framework</span>
          </div>
          <div style="clear: both">
            
          </div>
        </div>
        
      </div>
      <div style="float: right;width: 50%;text-align: right;padding: 14px 0px ">
            <a href="{{route('survey.export_survey_data',['sid'=>$sid])}}">Export</a>
      </div>
      
    </div>
    <div style="width: 15%;float: left;text-align: center;padding: 29px 0px">
      <div>
        Welcome: Abc Xyz
        <img src="">
      </div>
    </div>
    <div style="clear: both">
      
    </div>
    
  </div>
    <section class="content aione-section">
    
     	<!--<div class="row">-->
        	<!--<div class="col-xs-12">-->
          @php 
            $divs = $head = $ansText ="";
            foreach ($sdata[0] as $key => $value) {

              $head .="<th style='width:146px!important;' >$key </th>";
              $ansText .="<td> $value </td>";
            }
            unset($sdata[0]);
          @endphp
        
         {{--  <table class="table table-bordered table-striped">
            <thead>
                <tr class="thead-fixed fix">
                {!! $head !!}
                 </tr>
            </thead>
          </table> --}}
              <table id="pages" class="table table-bordered table-striped">
                <thead>
                <tr class="thead-fixed">
                
                 {!! $head !!}
                </tr>
                </thead>
                    <tbody>
                      <tr> 
                      
                        {!! $ansText !!}
                      </tr>
                      @foreach($sdata as $key => $val)
                      <tr>
                       
                        @foreach($val as $akey => $aval)
                          <td> {{$aval}}</td>
                        @endforeach
                      </tr>
                      @endforeach
                    </tbody>
                </table>
            <!--</div>-->

      	<!--</div>-->
    
    </section>
</div>
@endsection