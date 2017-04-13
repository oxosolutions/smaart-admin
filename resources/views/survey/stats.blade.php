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
dump($survey_data->group());
?>

    <div id="card-stats">
        <div class="row" style="margin-bottom: 24px">
            <div class="col s12 m6 l3">
                <div class="card" >
                    <div class="card-content  green white-text" style="padding: 10px 14px;">
                        <p class="card-stats-title"><i class="mdi-social-group-add"></i>{{$survey_data->name}}</p>
                        <h5 class="card-stats-number">{{$survey_data->created_on}}</h5>
                        <p class="card-stats-compare"><i class="mdi-hardware-keyboard-arrow-up"></i>

<span class="green-text text-lighten-5">{{$survey_data->description}}</span>
                        </p>
                    </div>
                    <div class="card-action  green darken-2">
                        <div id="clients-bar" class="center-align"><canvas width="227" height="25" style="display: inline-block; width: 227px; height: 25px; vertical-align: top;"></canvas></div>
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
                        <p class="card-stats-title"><i class="mdi-editor-attach-money"></i>Users</p>
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
    <div class="row">
        <div class="col s12 m8 l8">
            <div class="card" style="padding:10px;margin: 0px">
                <div>
                    <div class="row" style="margin-bottom: 0px">
                        <i class="material-icons dp48 col" style="padding: 0px">play_arrow</i>
                        <p class="col ">title of group</p>
                    </div>
                    <div style="padding-left: 50px">
                        <p>-questions inside a group</p>
                        <p>-questions inside a group</p>
                        <p>-questions inside a group</p>
                        <p>-questions inside a group</p>
                        <p>-questions inside a group</p>
                        <p>-questions inside a group</p>
                    </div>
                </div>
                <div>
                    <div class="row" style="margin-bottom: 0px">
                        <i class="material-icons dp48 col" style="padding: 0px">play_arrow</i>
                        <p class="col ">title of group</p>
                    </div>
                    <div style="padding-left: 50px">
                        <p>-questions inside a group</p>
                        <p>-questions inside a group</p>
                        <p>-questions inside a group</p>
                        <p>-questions inside a group</p>
                        <p>-questions inside a group</p>
                        <p>-questions inside a group</p>
                    </div>
                </div>
                <div>
                    <div class="row" style="margin-bottom: 0px">
                        <i class="material-icons dp48 col" style="padding: 0px">play_arrow</i>
                        <p class="col ">title of group</p>
                    </div>
                    <div style="padding-left: 50px">
                        <p>-questions inside a group</p>
                        <p>-questions inside a group</p>
                        <p>-questions inside a group</p>
                        <p>-questions inside a group</p>
                        <p>-questions inside a group</p>
                        <p>-questions inside a group</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col s12 m4 l4">
            <div class="card" style="margin: 0px">
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
                        <tr>
                            <td>Enable survey</td>
                            <td>Yes</td>
                            
                        </tr>
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
