<!DOCTYPE html>
<html>
<head>
    <title></title>
     <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.1/css/materialize.min.css">
     <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
     <script src="{{asset('/bower_components/admin-lte/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
  <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.1/js/materialize.min.js"></script>

    <style type="text/css">
        body{
            background-color: #F2F2F2;
        }
        .card{
            margin: 0px !important; 
        }
        .header-top{
            border-bottom: 1px solid #ddd;
            box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
            padding:5px;
            background-color: #fff;
        }
        .header-top h5{
            padding-left: 10px;


        }
        .card-section{
            min-height: 90px;
            background: #fff;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
            border-radius: 2px;
            margin-top: 32px;
            margin-bottom: 16px;
        }
        .card-content{
            background-color: #fff;
            text-align: center;
            float: left;
            width: 70%;
            
        }
        .card-inner{
            display: inline;
            float: right;
            width: 30%;
            min-height: 90px;
            text-align: center;
            font-size: 1.6em;
            color: #fff;
            padding-top: 28px;
        }
        .card-part1{
            font-size: 15px;
            padding-top: 17px;
            display: block;
        }
        .card-part2{
            font-size: 20px;
            padding-top: 5px;
            display: block;
        }
        .clear{
            clear: both;
        }
        .aione-survey-settings .aione-survey-setting {
            padding: 4px 10px;
            border-bottom: 1px solid #e8e8e8;
        }
        .aione-survey-settings .aione-survey-setting:last-child{
            border-bottom: none;
        }
        .aione-survey-settings .aione-survey-setting .aione-survey-setting-value {
            float: right;
        }
        .aione-survey-settings .aione-survey-setting .aione-survey-subsetting{
        	padding:5px 0px;
            border-bottom: 1px solid #e8e8e8;
        }
        .aione-survey-settings .aione-survey-setting .aione-survey-subsetting:last-child{
            border-bottom: none;
        }
        .aione-survey-settings .aione-survey-setting .aione-survey-subsetting .aione-survey-subsetting-key{
        	display:block;
            padding-bottom: 5px;
        }
        .aione-survey-settings .aione-survey-setting .aione-survey-subsetting .aione-survey-subsetting-value{
        	display:block;
            text-align: right;
            color: #777;
            font-size: 14px;
        } 
        .card-title-bar{
            font-size: 24px;
            padding: 5px 10px;
            display: block;
        }
        .thead{
            border: 1px solid #eee;
            padding: 5px;
            font-size: 12px;
            text-align: center;
            color: #777;
        }
        .tbody{
            border: 1px solid #eee;
            padding: 5px;
            font-size: 13px;
        }
        .z-depth-1, nav, .card-panel, .card, .toast, .btn, .btn-large, .btn-floating, .dropdown-content, .collapsible, .side-nav{
            box-shadow: none;
        }
        .collapsible-body{
            padding: 0px;
        }
        .collapsible{
            border-left: 0px;
            border-right: 0px;
            margin:0px;
        }

    </style>
          
</head>
<body>

<div>

<div class="header-top">
    <h5>URBAN SLUM STUDY</h5>
</div>

<div class="row">
        <div class="col m2">     
            <div class="card-section">

                <div class="card-content">

                    <span class="card-part1" >
                        Total
                    </span>

                    <span class="card-part2" >
                        Groups
                    </span>

                </div>

                <div class="card-inner" style="background-color: #da542e;">
                    <span>
                      5
                    </span>
                </div>

                <div class="clear"></div>
              
            </div>
        </div> 

        <div class="col m2">     
            <div class="card-section">

                <div class="card-content">

                    <span class="card-part1" >
                        Total
                    </span>

                    <span class="card-part2" >
                        Questions
                    </span>
                
                </div>

                <div class="card-inner" style="background-color: #08c;">
                    <span>
                      100
                    </span>
                </div>

                <div class="clear"></div>
              
            </div>
        </div> 

        <div class="col m2">     
            <div class="card-section">

                <div class="card-content">

                    <span class="card-part1" >
                        Complete
                    </span>

                    <span class="card-part2" >
                        Survey
                    </span>
                    
                </div>

                <div class="card-inner" style="background-color: #00a65a;">
                    <span>
                      60
                    </span>
                </div>

                <div class="clear"></div>
              
            </div>
        </div> 

        <div class="col m2">     
            <div class="card-section">

                <div class="card-content">

                    <span class="card-part1" >
                        Incomplete
                    </span>

                    <span class="card-part2" >
                        Survey
                    </span>
                
                </div>

                <div class="card-inner" style="background-color: #dd4b39;">
                    <span>
                      40
                    </span>
                </div>

                <div class="clear"></div>
              
            </div>
        </div> 

        <div class="col m2">     
            <div class="card-section">

                <div class="card-content">

                    <span class="card-part1" >
                        Total
                    </span>

                    <span class="card-part2" >
                        Errors
                    </span>

                </div>

                <div class="card-inner" style="background-color: #f74d4d;">
                    <span>
                      10
                    </span>
                </div>

                <div class="clear"></div>
              
            </div>
        </div> 

        <div class="col m2">     
            <div class="card-section">

                <div class="card-content">

                    <span class="card-part1" >
                        Total
                    </span>

                    <span class="card-part2" >
                        Warnings
                    </span>
                
                </div>

                <div class="card-inner" style="background-color: #ffb848;">
                    <span>
                      50
                    </span>
                </div>

                <div class="clear"></div>
              
            </div>
        </div>

</div>



</div>

<div>
    <div class="row">
        <div class="col m9">
            <div class="card">
                <div class="center-align">
                    <span class="card-title-bar">Structure of survey</span>
                </div>

                <div>
                    <ul class="collapsible" data-collapsible="accordion">
                        <li>
                            <div class="collapsible-header">Section 1: SOCIO-DEMOGRAPHIC PROFILE</div>
                            <div class="collapsible-body">
                                <table>                                   
                                    <tr>
                                        <th class="thead">ID</th>
                                        <th class="thead">Key</th>
                                        <th class="thead">Question</th>
                                        <th class="thead">Type</th>
                                        <th class="thead">Required</th>
                                        <th class="thead">Conditions</th>
                                        <th class="thead">Validations</th>
                                        <th class="thead">Options</th>
                                    </tr>
                                 
                                    <tr>
                                        <td class="tbody">SID1_GID1_QID1785</td>
                                        <td class="tbody">q1</td>
                                        <td class="tbody">1. What does CSS stand for?</td>
                                        <td class="tbody">Radio</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                    </tr>

                                    <tr>
                                        <td class="tbody">SID1_GID1_QID1786</td>
                                        <td class="tbody">q2</td>
                                        <td class="tbody">2. Which of the following selector matches a element based on its id?</td>
                                        <td class="tbody">Radio</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                    </tr>

                                    <tr>
                                        <td class="tbody">SID1_GID1_QID1787</td>
                                        <td class="tbody">q3</td>
                                        <td class="tbody">3. Which of the following property is used to change the face of a font?</td>
                                        <td class="tbody">Radio</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                    </tr>

                                    <tr>
                                        <td class="tbody">SID1_GID1_QID1788</td>
                                        <td class="tbody">q4</td>
                                        <td class="tbody">4. Where in an HTML document is the correct place to refer to an external style sheet?</td>
                                        <td class="tbody">Radio</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                    </tr>
                                </table>                             
                            </div>
                        </li>
                        <li>
                            <div class="collapsible-header">Section 2: BASIC TECHNOLOGY ACCESSIBILITY
                            </div>
                            <div class="collapsible-body">
                                <table>                                   
                                    <tr>
                                        <th class="thead">ID</th>
                                        <th class="thead">Key</th>
                                        <th class="thead">Question</th>
                                        <th class="thead">Type</th>
                                        <th class="thead">Required</th>
                                        <th class="thead">Conditions</th>
                                        <th class="thead">Validations</th>
                                        <th class="thead">Options</th>
                                    </tr>
                                 
                                    <tr>
                                        <td class="tbody">SID1_GID1_QID1785</td>
                                        <td class="tbody">q1</td>
                                        <td class="tbody">1. What does CSS stand for?</td>
                                        <td class="tbody">Radio</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                    </tr>

                                    <tr>
                                        <td class="tbody">SID1_GID1_QID1786</td>
                                        <td class="tbody">q2</td>
                                        <td class="tbody">2. Which of the following selector matches a element based on its id?</td>
                                        <td class="tbody">Radio</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                    </tr>

                                    <tr>
                                        <td class="tbody">SID1_GID1_QID1787</td>
                                        <td class="tbody">q3</td>
                                        <td class="tbody">3. Which of the following property is used to change the face of a font?</td>
                                        <td class="tbody">Radio</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                    </tr>

                                    <tr>
                                        <td class="tbody">SID1_GID1_QID1788</td>
                                        <td class="tbody">q4</td>
                                        <td class="tbody">4. Where in an HTML document is the correct place to refer to an external style sheet?</td>
                                        <td class="tbody">Radio</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                        <td class="tbody">No</td>
                                    </tr>
                                </table>    
                            </div>
                        </li>
                        <li>
                            <div class="collapsible-header">Section 3: BASIC NEEDS ASSESSMENT
                            </div>
                            <div class="collapsible-body">
                                
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

<script type="text/javascript">


    $(document).ready(function(){

    $('.collapsible').collapsible({

    accordion: false, // A setting that changes the collapsible behavior to expandable instead of the default accordion style
    onOpen: function(el) { alert('Open'); }, // Callback for Collapsible open
    onClose: function(el) { alert('Closed'); } // Callback for Collapsible close
    });

  });

</script>

        <div class="col m3">
            <div class="card">
                <div class="center-align">
                    <span class="card-title-bar">Settings</span>
                </div>
                <div class="divider"></div>
				<div class="aione-survey-settings">
					@foreach($survey_data->setting  as $survey_setting_key => $survey_setting_value)
					<div class="aione-survey-setting">
						<?php
						//$survey_setting_value_array = json_decode($survey_setting_value->value);
						//json_last_error() === 0
						//echo "<pre>";
						//print_r(json_decode($survey_setting_value->value));
						//echo "</pre>";
						//echo "</pre>";
						//echo $survey_setting_value->key;
						
						
						?>

						@if( $survey_setting_value->key == 'survey_custom_error_messages_list')
							@foreach(json_decode($survey_setting_value->value) as $survey_setting_subkey => $survey_setting_subvalue)
								<div class="aione-survey-subsetting">
									<span class="aione-survey-subsetting-key">{{ucwords(str_replace('_', ' ', $survey_setting_subkey))}}</span>
									<span class="aione-survey-subsetting-value">{{$survey_setting_subvalue}}</span>
								</div>
							@endforeach
						@else
							<span class="aione-survey-setting-key">{{ucwords(str_replace('_', ' ', $survey_setting_value->key))}}</span>
							<span class="aione-survey-setting-value">
                            @if( $survey_setting_value->value == 0)
                                <span class="aione-switch-disabled">
                                    <svg height="10" width="10">
                                      <circle cx="5" cy="5" r="2" stroke="red" stroke-width="5" fill="#fff" />
                                    </svg>
                                </span>
                            @else
                                <span class="aione-switch-enabled">
                                    <svg height="10" width="10">
                                      <circle cx="5" cy="5" r="2" stroke="green" stroke-width="5" fill="#fff" />
                                    </svg>
                                </span>
                            @endif
                            <!--{{$survey_setting_value->value}}-->
                            </span>
						@endif
						
					</div>
					@endforeach
				</div>

					
            </div>
        </div>
    </div>
</div>


<br>
<br>
<br>

<?php 

    $user = SurveyHelper::user_detail_by_id($survey_data->created_by);
    $survey_data->created_by;
    $ansInfo= $ansVal = $concate_data ="";
    if(count($survey_data->group)>0)
    {
        foreach ($survey_data->group as $group_key => $group_value){ 
            $countQues =  count($group_value->question);
            $concate_data .="<div>
                                <div class='row' style='margin-bottom: 0px'>
                                <i class='material-icons dp48 col' style='padding: 0px'>play_arrow</i>
                                <p class='col'>$group_value->title <span>($countQues)</span></p>
                                </div>
                            <div style='padding-left: 50px'>";
            foreach ($group_value->question as $que_key => $que_value){
                $decode_answer = json_decode($que_value->answer,true);
              //      dump($decode_answer);
                $ansInfo="";
                 $ansInfo .="<ul>";
                    foreach ($decode_answer as $akey => $avalue) {
                        if($akey=='extraOptions')
                        {
                             if(is_array($decode_answer['extraOptions'])) 
                             {  
                                $ansInfo .="<ul>";
                                foreach ($decode_answer['extraOptions'] as $key => $value) {
                                   $ansInfo .= "<li>Option Value <---------->". $value['options']['value']."</li>";
                                    $ansInfo .= "<li>Option condition <----------> ".$value['options']['condition']."</li>";
                                }
                                $ansInfo .="</ul>";
                             }  //$ansInfo .= " <li>$akey <----------> $avalue </li>";

                        }else{
                        $ansInfo .= " <li>$akey <----------> $avalue </li>";
                        }
                    }
                $ansInfo .="</ul>";

                $types = $decode_answer['question_type'];
                $extraOptions = $decode_answer['extraOptions'];
                 $ansCondition = $ansVal ="";
    //Option Value
                if(!empty($data = $decode_answer['extraOptions']) && ($types=='checkbox' ||$types=='radio'))
                {
                   foreach ($data as $key => $value) {
                    $ansVal .= $value['options']['value'].', ';
                    $ansCondition .= $value['options']['condition'].', ';
                    }
                }
    //User Filled By 
                 $concate_data .="<p>$que_value->question Type ---> $types Options -- $ansVal -->condition $ansCondition  $ansInfo</p>";
                 $type[] = $types;
            }
             $concate_data .="</div></div>";
        }
    }                
    $type_value = array_count_values($type);
?>

    <div id="card-stats">
        <div class="row" style="margin-bottom: 24px">
            <div class="col s12 m6 l3">
                <div class="card" >
                    <div class="card-content  green white-text" style="padding: 10px 14px;">
                        <p class="card-stats-title"><i class="mdi-social-group-add"></i>{{$survey_data->name}}</p>
                        <h5 class="card-stats-number">{{$survey_data->created_on}}</h5>
                        <p class="card-stats-compare"><i class="mdi-hardware-keyboard-arrow-up"></i>

<span class="green-text text-lighten-5 truncate">{{$survey_data->description}}</span>
                        </p>
                    </div>
                    <div class="card-action  green darken-2">
                        <div id="clients-bar" class="center-align"><canvas width="227" height="25" style="display: inline-block; width: 227px; height: 25px; vertical-align: top;"></canvas>Created BY-->{{@$user->name}} <br>organization name -->{{@$user->organization->organization_name}}
 </div>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 l3">
                <div class="card">
                    <div class="card-content pink lighten-1 white-text" style="padding: 10px 14px;">
                        <p class="card-stats-title"><i class="mdi-editor-insert-drive-file"></i>Total questions</p>
                        <h5 class="card-stats-number">{{$survey_data->count_ques()}}</h5>
                        <p class="card-stats-compare"><i class="mdi-hardware-keyboard-arrow-down"></i> 3% <span class="deep-purple-text text-lighten-5">from last month</span>
                        </p>
                    </div>
                    <div class="card-action  pink darken-2">
                        <div id="invoice-line" class="center-align"><canvas width="268" height="25" style="display: inline-block; width: 268px; height: 25px; vertical-align: top;"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 l3">
                <div class="card">
                    <div class="card-content blue-grey white-text" style="padding: 10px 14px;">
                        <p class="card-stats-title"><i class="mdi-action-trending-up"></i>Groups</p>
                        <h5 class="card-stats-number">{{$survey_data->count_group()}}</h5>
                        <p class="card-stats-compare"><i class="mdi-hardware-keyboard-arrow-up"></i> 80% <span class="blue-grey-text text-lighten-5">from yesterday</span>
                        </p>
                    </div>
                    <div class="card-action blue-grey darken-2">
                        <div id="profit-tristate" class="center-align"><canvas width="227" height="25" style="display: inline-block; width: 227px; height: 25px; vertical-align: top;"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 l3">
                <div class="card">
                    <div class="card-content purple white-text" style="padding: 10px 14px;">
                        <p class="card-stats-title"><i class="mdi-editor-attach-money"></i>Filled Survey</p>
                        <h5 class="card-stats-number">{{$survey_data->total_filled}}</h5>
                        <p class="card-stats-compare"><i class="mdi-hardware-keyboard-arrow-up"></i> 70% <span class="purple-text text-lighten-5">last month</span>
                        </p>
                    </div>
                    <div class="card-action purple darken-2">
                        <div id="sales-compositebar" class="center-align"><canvas width="214" height="25" style="display: inline-block; width: 214px; height: 25px; vertical-align: top;"></canvas></div>
                    </div>
                </div>
            </div>

            

        </div>
    </div>

    <div id="card-stats">
        <div class="row" style="margin-bottom: 24px">
                 
                <div class="col s12 m6 l3">
                    <div class="card">
                        <div class="card-content purple white-text" style="padding: 10px 14px;">
                            <p class="card-stats-title"><i class="mdi-editor-attach-money"></i>Answer Type</p>
                            @foreach($type_value as  $tkey => $tval)

                                @if($tkey=='text_only') 
                                    <h5 class="card-stats-number">Textarea : {{$tval}}</h5>
                                @else   
                                    <h5 class="card-stats-number">{{ucwords($tkey)}} : {{$tval}}</h5>
                                @endif
                            @endforeach
                           
                        </div>
                        <div class="card-action purple darken-2">
                            <div id="sales-compositebar" class="center-align"><canvas width="214" height="25" style="display: inline-block; width: 214px; height: 25px; vertical-align: top;"></canvas></div>
                        </div>
                    </div>
                </div>
                     <div class="card">
                        <div class="card-content purple white-text" style="padding: 10px 14px;">
                            <p class="card-stats-title"><i class="mdi-editor-attach-money"></i>Complete Filled Survey</p>
                            
                                    <h5 class="card-stats-number">{{$survey_data->completed_survey}}</h5>
                                
                           
                        </div>
                        <div class="card-action purple darken-2">
                            <div id="sales-compositebar" class="center-align"><canvas width="214" height="25" style="display: inline-block; width: 214px; height: 25px; vertical-align: top;"></canvas></div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-content purple white-text" style="padding: 10px 14px;">
                            <p class="card-stats-title"><i class="mdi-editor-attach-money"></i>Pending Filled Survey</p>
                            
                                    <h5 class="card-stats-number">{{$survey_data->pending_survey}}</h5>
                                
                           
                        </div>
                        <div class="card-action purple darken-2">
                            <div id="sales-compositebar" class="center-align"><canvas width="214" height="25" style="display: inline-block; width: 214px; height: 25px; vertical-align: top;"></canvas></div>
                        </div>
                    </div>
                </div>
                 
        </div>
    </div>    
   

    <div class="row">
        <div class="col s12 m8 l8">
            <div class="card" style="padding:10px;margin: 0px">
                <div>
                   <p style="font-size:24px">Structure of survey</p>
                </div>
                  <div class="divider"></div>

                  {{-- SURVEY DATA  --}}
                {!!$concate_data!!}
                
             
            
            </div>
        </div>
         <div class="col s12 m4 l4">
            <div class="card" style="padding:10px;margin: 0px">
                <div class="center-align">
                   <p style="font-size:24px">Survey Filled by Users</p>
                </div>
                <div class="divider"></div>
                <table class="striped">
                    <thead>
                        <tr>
                            <th>User Id</th>
                            <th>Number</th>
                            
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($survey_data['user_filled_count'] as $uKey => $uVal)
                        <tr>
                            <td>{{$uKey}}</td>
                            <td>{{$uVal}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col s12 m4 l4">
            <div class="card" style="padding:10px;margin: 0px">
                <div class="center-align">
                   <p style="font-size:24px">Settings</p>
                </div>
                <div class="divider"></div>
                <table class="striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($survey_data->setting as $seKey => $seVal)
                        <tr>
                            <td >{{ucwords(str_replace('_', ' ', $seVal->key))}}</td>
                            @if($seVal->key == "survey_custom_error_messages_list") 
                                <td> 
                                    <ul>
                                    @foreach(json_decode($seVal->value,true) as $mesKey => $mesVal) 
                                        <li>{{$mesKey}} ---> {{$mesVal}}</li>
                                    @endforeach
                                    </ul>
                            </td>
                            @else
                            <td>{{$seVal->value}}</td>

                            @endif
                        </tr>
                    @endforeach

                        <tr>
                            <td>Survey start date</td>
                            <td>2017-04-13 15:35:00</td>
                            
                        </tr>
                        <tr>
                            <td>Survey expire date</td>
                            <td>2018-04-13 15:35:00</td>
                            
                        </tr>
                        <tr>
                            <td>Survey timer</td>
                            <td>Yes</td>
                            
                        </tr>
                        <tr>
                            <td>Survey timer type</td>
                            <td>Yes</td>
                            
                        </tr>
                        <tr>
                            <td>Survey timer type</td>
                            <td>Yes</td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</body>
</html>
