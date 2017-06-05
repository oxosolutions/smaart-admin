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
        .slider-container{
            margin-top: 20px;
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
        .text_style{
            background-color: #606CA8!important;
        }
        .radio_style {
            background-color: #26a69a!important;
        }
        .dropdown_style{
            background-color: #214469!important;
        }
        .text_only_style{
            background-color: #9E9E9E!important;
        }
        .clear{
            clear: both;
        }
        .aione-survey-settings .aione-survey-setting {
            padding:10px;
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
            
        }
        .aione-survey-settings .aione-survey-setting .aione-survey-subsetting .aione-survey-subsetting-value{
        	display:block;
            text-align: right;
            color: #777;
            font-size: 14px;
        } 
        .aione-survey-subsetting-value-enable{
            display:block;
            text-align: right;
            color: #777;
            font-size: 14px;
            padding-top: 10px;
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
<?php 
    
    $user = SurveyHelper::user_detail_by_id($survey_data->created_by);
    $survey_data->created_by;
   $group_question = $ansInfo= $ansVal = $concate_data ="";
    if(count($survey_data->group)>0)
    {
        foreach ($survey_data->group as $group_key => $group_value){ 
            $countQues =  count($group_value->question);

          $group_question  .="<li><div class='collapsible-header'><i class='material-icons' style='margin:0px;font-size:17px;text-align:left;'>send</i>
                                $group_value->title <span>($countQues )</span></div>
                            <div class='collapsible-body'>
                                <table>                                   
                                    <tr>
                                        <th class='thead'>ID</th>
                                        <th class='thead'>Key</th>
                                        <th class='thead'>Question</th>
                                        <th class='thead'>Type</th>
                                        <th class='thead'>Required</th>
                                        <th class='thead'>Conditions</th>
                                        <th class='thead'>Validations</th>
                                        <th class='thead'>Options</th>
                                    </tr>";
            foreach ($group_value->question as $que_key => $que_value){

                        $group_question  .="<tr>
                                        <td class='tbody'>$que_value->question_id</td>";
                        $decode_answer = json_decode($que_value->answer,true);

                        foreach ($decode_answer as $akey => $avalue) {
                            $question_type = $decode_answer['question_type'];
                           
                            if($akey=='extraOptions')
                            {
                                 if(is_array($decode_answer['extraOptions'])) 
                                 {  
                                    $ansInfo .="<ul>";
                                    $optVal = "";
                                    foreach ($decode_answer['extraOptions'] as $key => $value) {
                                       $optVal .=  $value['options']['value'].", ";
                                        $ansInfo .= "<li>Option condition <----------> ".$value['options']['condition']."</li>";
                                    }
                                    $ansInfo .="</ul>";
                                 } 

                            }else if($akey=='question_key')
                                {
                                    $group_question .="<td class='tbody'>$avalue</td>";
                                }
                                 if($akey=='question_type')
                                {
                                    $types = $avalue;
                                }

                                if($akey=='required')
                                {
                                    $requireds = $avalue;
                                }

                                if($akey=='pattern')
                                {
                                    $patterns = $avalue;
                                }
                            }

                        $group_question  .="<td class='tbody'>$que_value->question </td>
                                        <td class='tbody'>".@$types."</td>
                                        <td class='tbody'>".@$requireds."</td>
                                        <td class='tbody'>no</td>
                                        <td class='tbody'>".@$patterns."</td>
                                        <td class='tbody'>".@$optVal."</td>
                                    </tr>";
                    $type[] = $question_type;
                }

                $group_question  .="</table>                             
                            </div></li>";
        }
    }                
    $type_value = array_count_values($type);
?>

<div>

<div class="header-top">
    <h5>{{strtoupper( $survey_data->name)}}</h5>
</div>

<div class="slider-container">
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
                          {{$survey_data->count_group()}}
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
                          {{$survey_data->count_ques()}}
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
                          {{$survey_data->completed_survey}}
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
                            {{$survey_data->pending_survey}}        
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

    <div class="row">
        @foreach($type_value as  $tkey => $tval)
            <div class="col m2">     
                <div class="card-section">

                    <div class="card-content">

                        <span class="card-part1" >
                            Total
                        </span>
                    @if($tkey=='text_only') 
                        <span class="card-part2 {{$tkey}}" >
                            Textarea
                        </span>
                    @else 
                        <span class="card-part2" >
                            {{ucwords($tkey)}}
                        </span>
                    @endif
                    </div>

                    <div class="card-inner {{$tkey}}_style " style="background-color: #da542e;">
                        <span>
                            {{$tval}}
                        </span>
                    </div>

                    <div class="clear"></div>
                  
                </div>
            </div>
        @endforeach
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
                        {!!$group_question!!}                
                    </ul>
                </div>

            </div>
        </div>

<script type="text/javascript">


    $(document).ready(function(){

    $('.collapsible').collapsible({

    accordion: false, // A setting that changes the collapsible behavior to expandable instead of the default accordion style
    onOpen: function(el) { 
    // alert('Open'); 
    }, // Callback for Collapsible open
    onClose: function(el) {
     // alert('Closed'); } // Callback for Collapsible close
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
                            @if( $survey_setting_value->key == 'authentication_type' || $survey_setting_value->key == 'authorized_users' || $survey_setting_value->key == 'authorized_roles' || $survey_setting_value->key == 'survey_start_date' || $survey_setting_value->key == 'survey_expiry_date' || $survey_setting_value->key =='survey_timer_type' || $survey_setting_value->key =='survey_duration' || $survey_setting_value->key == 'survey_response_limit_value' || $survey_setting_value->key =='survey_response_limit_type' || $survey_setting_value->key =='surveyViewType' || $survey_setting_value->key =='labelPlacement'|| $survey_setting_value->key =='questionPlacement')

                                @if($survey_setting_value->key == 'authorized_users')
                                    <span class="aione-survey-subsetting-value-enable"> 
                                    @if(is_array($user = json_decode($survey_setting_value->value, true)))
                                        @foreach($user as $uKey => $uVal)
                                            {{SurveyHelper::user_name_by_id($uVal)}},
                                        @endforeach
                                    @else
                                         {{$survey_setting_value->value}}
                                    @endif
                                    </span>
                                @elseif($survey_setting_value->key == 'authorized_roles')
                                    <span class="aione-survey-subsetting-value-enable"> 
                                    @if(is_array($role = json_decode($survey_setting_value->value, true)))
                                        @foreach($role as $rKey => $rVal)
                                            {{SurveyHelper::role_name_by_id($rVal)}},
                                        @endforeach
                                    @else
                                    {{$survey_setting_value->value}}
                                                             
                                    @endif
                                </span>
                                @else
                                 {{$survey_setting_value->value}}
                                @endif
                            @elseif( $survey_setting_value->value == 0)
                                <span class="aione-switch-disabled">
                                    <!--<svg height="10" width="10">
                                      <circle cx="5" cy="5" r="2" stroke="red" stroke-width="5" fill="#fff" />
                                    </svg>-->
                                    <i class="material-icons dp48" style="color: #dd4b39;font-size: 17px;">info</i>
                                </span>
                            @else
                                <span class="aione-switch-enabled">
                                    <!--<svg height="10" width="10">
                                      <circle cx="5" cy="5" r="2" stroke="green" stroke-width="5" fill="#fff" />
                                    </svg>-->
                                    <i class="material-icons dp48" style="color: green;font-size: 17px;">info</i>
                                </span>
                            @endif
                            {{-- {{$survey_setting_value->value}} --}}
                            </span>
						@endif
						
					</div>
					@endforeach
				</div>

					
            </div>
        </div>
    </div>
</div>

</body>
</html>
