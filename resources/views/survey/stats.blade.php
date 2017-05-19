<!DOCTYPE html>
<html>
<head>
    <title></title>
     <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.1/css/materialize.min.css">
     <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.1/js/materialize.min.js"></script>

    <style type="text/css">
        body{
            padding: 15px;
        }
        p{
            margin: 0px;
            padding: 0px !important;
        }
        body{
            background-color: #F2F2F2;
        }
        .card{
            margin: 0px !important;
        }
    </style>
          
</head>
<body>

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
                                    $ansInfo .= "<li>Option Value <----------> $value['options']['value']</li>";
                                    $ansInfo .= "<li>Option condition <----------> $value['options']['condition']</li>";
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
