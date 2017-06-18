(function ()
{
    'use strict';

    angular
        .module('app.visualizations.edit')
        .controller('EditVisualController', EditVisualController)
        .filter('propsFilter', propsFilter);

    function propsFilter(){

        return function(items, props){
            var out = [1,2,3,4];
            return out;
        }
    }

    /** @ngInject */
    function EditVisualController($scope, $state, api, $mdToast, $compile , $mdDialog){
        var vm = this;
        $scope.disableColumns = true;
        $scope.disableButton = false;
        $scope.disableDataset = true;
        $scope.message = false;
        $scope.chartType = {};
        $scope.chartXAxis = {};
        $scope.chartYAxis = {};
        $scope.filter_columns = {};       
        $scope.filter_type = {};
        $scope.settings = {};
        $scope.chartsData = {};
        $scope.chartsData.visual_settings = {};
        var visualSettings = {"animation":{"startup":true,"duration":250,"easing":"inAndOut"},"legend":"top","curveType":"function","pointSize":"8","width":"80%","height":"480","chartArea":{"left":"10%","top":"10%","bottom":"25%","height":"100%","width":"80%"},"bar":{"groupWidth":"80%"},"tooltip":{"isHtml":true}};
        $scope.chart_types = [{
                "value": "ColumnChart",
                "label": "Column Chart"
            },
            {
                "value": "BarChart",
                "label": "Bar Chart"
            },
            {
                "value": "AreaChart",
                "label": "Area Chart"
            },
            {
                "value": "PieChart",
                "label": "Pie Chart"
            },
            {
                "value": "LineChart",
                "label": "line Chart"
            },
            // {
            //     "value": "BubbleChart",
            //     "label": "Bubble Chart"
            // },
            {
                "value": "CustomMap",
                "label": "Custom Map"
            },
            {
                "value": "TableChart",
                "label": "Table chart"
            }
            ,
            {
                "value": "ListChart",
                "label": "List chart"
            }
        ];

        $scope.editName = function(){

            if($scope.visualName == undefined || $scope.visualName == false){
                $scope.visualName = true;
            }else{
                $scope.visualName = false;
            }
        }
        
        api.visual.visualDetails.get({id:$state.params.id},function(res){
            $scope.disableColumns = false;
            $scope.message = true;
            $scope.columns = res.data.dataset_columns;
            $scope.defaultMaps = res.data.maps.global_maps;
            $scope.userDefinedMaps = res.data.maps.organization_maps;
            $scope.chart_settings = res.data.chart_settings;
            $scope.charts = res.data.charts;
            if(res.data.charts[0] != undefined){
                var chart_index = 1;
                angular.forEach(res.data.charts, function(chart){
                    angular.forEach(chart, function(value, key){
                        if($scope.chartsData[key] == undefined){
                            $scope.chartsData[key] = {};
                        }
                        if(key == 'enableDisable'){
                            $scope.chartsData[key]['chart_'+chart_index] = (value == 1)?true:false;
                        }else if(key == 'visual_settings'){
                            $scope.chartsData[key]['chart_'+chart_index] = JSON.stringify(value);
                        }else{
                            $scope.chartsData[key]['chart_'+chart_index] = value;
                        }
                    });
                    $scope.chartType['chart_'+chart_index] = true;
                    chart_index++;
                });
            }
            if(res.data.visualization_meta.filters != undefined && res.data.visualization_meta.filters != ''){
                $scope.extraFilters = JSON.parse(res.data.visualization_meta.filters);
                angular.forEach($scope.extraFilters, function(value, key){
                    $scope.filter_columns[key] =  value.column;
                    $scope.filter_type[key] =  value.type;
                });
            }
            //fill settings
            $scope['visual_settings'] = {};
            angular.forEach(res.data.visualization_meta, function(value, key){
                if(value == 1 || value == 0){
                    $scope['visual_settings'][key] = (value == 1)?true:false;
                }else{
                    if(key != 'filters'){
                        $scope['visual_settings'][key] = value;
                    }
                }
            });
        });

        $scope.showCustom = function(event, chart, chartType) {
            if(chartType == undefined){
                $scope.chart_type_error = true;
                setTimeout(function(){
                    $scope.chart_type_error = false;
                },5000);
                return false;
            }else{
                $scope.chart_type_error = false;
            }
           $mdDialog.show({
              clickOutsideToClose: true,
              scope: $scope,        
              preserveScope: true,           
              templateUrl: 'app/main/visualizations/edit/dialogs/visual-setting.html',
              controller: function DialogController($scope, $mdDialog) {
                 $scope.closeDialog = function() {
                    $mdDialog.hide();
                 }
                 $scope.selectedChart = chartType;
                 
                 var chartSetting = JSON.parse($scope.chart_settings);
                 $scope.settings = chartSetting;
                 console.log(chartSetting);
                 setTimeout(function(){
                    if($scope.chartsData.visual_settings[chart] != undefined){
                        $scope.visualSettings.chart_settings = JSON.parse($scope.chartsData.visual_settings[chart]);
                    }
                 },3);
                 $scope.saveVisualSettings = function(){
                    $scope.chartsData.visual_settings[chart] = JSON.stringify($scope.visualSettings.chart_settings);
                    $mdDialog.hide();
                 }
              }
           });
        };

        $scope.AddEmbedCss = function(event) {
           $mdDialog.show({
                clickOutsideToClose: true,
                scope: $scope,        
                preserveScope: true,           
                templateUrl: 'app/main/visualizations/edit/dialogs/add-embed-css.html',
                controller: function DialogController($scope, $mdDialog) {
                    $scope.closeDialog = function() {
                        $mdDialog.hide();
                    }
                    
                    $scope.saveEmbedSettings = function(){
                        $mdDialog.hide();
                    }
                
                }
            });
        };
        $scope.AddEmbedJs = function(event) {
           $mdDialog.show({
                clickOutsideToClose: true,
                scope: $scope,        
                preserveScope: true,           
                templateUrl: 'app/main/visualizations/edit/dialogs/add-embed-js.html',
                controller: function DialogController($scope, $mdDialog) {
                    $scope.closeDialog = function() {
                        $mdDialog.hide();
                    }
                    
                    $scope.saveEmbedSettings = function(){
                        $mdDialog.hide();
                    }
                
                }
            });
        };

        $scope.getNumber = function(num) {
            return new Array(num);   
        }
        $scope.viewDataset = function(datasetId){
            $state.go('app.dataset_view',{'id':datasetId});
        }


        $scope.showChartType = function(text,chart){
            if(text.length > 0){
                $scope.chartType[chart] = true;
            }else{
                $scope.chartType[chart] = false;
            }
            $scope.chart_type_error = false;
        }
        $scope.showAxisesFields = function(chart){
            $scope.chartXAxis[chart] = true;
            $scope.chartYAxis[chart] = true;
        }
        $scope.showFormula = function(){
            $scope.formulaHS = true;
        }

        $scope.addMoreChart = function(){
            var cnt = parseInt($('.chartCount').length+1);

            $('.repeat').append($compile('<div class="frame" style="margin-top:2%; height:50px; overflow:hidden;"> <md-divider></md-divider> <md-button class="deleteFrame md-icon-button mt-5" aria-label="delete_chart" style="float: right;"> <md-icon flex="20" md-font-icon="icon-trash" class="s24 red-fg"></md-icon> </md-button> <div layout="row"> <div class="md-accent-bg ph-10 pv-5 md-headline chartCount mt-5">{{$index+1}}</div><div flex="70" layout="row" layout-align="center center" class="md-title">{{chartsData.title[\'chart_'+cnt+'\']}}</div><div flex="20"> <md-button class=" md-icon-button" ng-click="cloneChart(\'chart_'+cnt+'\')"> <md-tooltip md-direction="top" class="md-body-1">Clone this chart</md-tooltip> <md-icon md-font-icon="icon-content-copy" class="clone-chart"></md-icon> </md-button> </div><div style="float: right; margin-top: 2%; margin-right: 4%; font-size: 23px; cursor: pointer;" class="exp_col"> <md-tooltip md-direction="top" class="md-body-1 col-exp">Minimize / Maximize</md-tooltip> <img src="assets/images/arrow-down.png" style="width:16px;margin-top:5px;transform: rotate(179deg)"> </div></div><div> <md-list-item> <p class="font-size-16"> Enable Charts</p><span> <md-tooltip md-direction="top" class="md-body-1">Enable / Disable survey </md-tooltip> <md-switch ng-model="chartsData.enableDisable[\'chart_'+cnt+'\']" aria-label="Switch 1"></md-switch> </span> <md-divider></md-divider> </md-list-item> </div><md-input-container class="md-block mt-30" flex="95" style="margin-left: 2%;"> <label class="font-size-16 font-weight-300">Chart Title</label> <input type="text" name="chart_title" ng-model="chartsData.title[\'chart_'+cnt+'\']" value="" required ng-change="showChartType(chartsData.title[\'chart_'+cnt+'\'],\'chart_'+cnt+'\')"/></md-input-container> <md-input-container class="md-block mv-35" flex="95" style="margin-left: 2%; padding-bottom: 2%;" ng-show="chartType[\'chart_'+cnt+'\']"> <label class="font-size-18 font-weight-300">Chart Type</label> <md-select ng-model="chartsData.chartType[\'chart_'+cnt+'\']" ng-change="showAxisesFields(\'chart_'+cnt+'\')"> <md-option ng-repeat="value in chart_types" ng-value="value.value">{{value.label}}</md-option> </md-select> </md-input-container> <md-input-container class="md-block mv-35" flex="95" style="margin-left:2%;padding-bottom:2%" ng-show="chartsData.chartType[\'chart_'+cnt+'\']==\'CustomMap\'"> <label class="font-size-18 font-weight-300">Map Area</label><select ui-select2 ng-model="chartsData.mapArea[\'chart_'+cnt+'\']" style="width: 100%;" class="font-size-16"> <optgroup label="User Defined"> <option value="usermaps-{{maps.id}}" ng-repeat="maps in userDefinedMaps" class="font-size-16" >{{maps.title}}</option> </optgroup> <optgroup label="Default Maps"> <option value="globalmaps-{{maps.id}}" ng-repeat="maps in defaultMaps" class="font-size-16" >{{maps.title}}</option> </optgroup> </select> </md-input-container> <md-input-container class="md-block mt-10 mb-35" flex="95" style="margin-left: 2%;" ng-show="chartXAxis[\'chart_'+cnt+'\']"> <label class="font-size-18 font-weight-300" ng-if="chartsData.chartType[\'chart_'+cnt+'\'] !=\'CustomMap\'">Select Variable For X-Axis</label> <label class="font-size-18 font-weight-300" ng-if="chartsData.chartType[\'chart_'+cnt+'\']==\'CustomMap\'">Select Area Code of MAP</label> <md-select ng-model="chartsData.column_one[\'chart_'+cnt+'\']" ng-disabled="disableColumns"> <md-option ng-value="0">Select</md-option> <md-option ng-repeat="(key, column) in columns" ng-value="key">{{column}}</md-option> </md-select> </md-input-container> <md-input-container class="md-block mv-35" flex="95" style="margin-left: 2%;" ng-if="chartsData.chartType[\'chart_'+cnt+'\']==\'CustomMap\'"> <label class="font-size-18 font-weight-300">Select Data To Display on MAP</label> <md-select ng-model="chartsData.viewData[\'chart_'+cnt+'\']" ng-disabled="disableColumns"> <md-option ng-repeat="(key, column) in columns" ng-value="key">{{column}}</md-option> </md-input-container> <md-input-container class="md-block mv-35" flex="95" style="margin-left: 2%;" ng-show="chartYAxis[\'chart_'+cnt+'\']"> <label class="font-size-18 font-weight-300" ng-if="chartsData.chartType[\'chart_'+cnt+'\'] !=\'CustomMap\'">Select Variables For Y-Axis</label><label class="font-size-18 font-weight-300" ng-if="chartsData.chartType[\'chart_'+cnt+'\']==\'CustomMap\'">Values For Display on Tooltip</label> <md-select ng-model="chartsData.columns_two[\'chart_'+cnt+'\']" ng-disabled="disableColumns" multiple> <md-option ng-repeat="(key, column) in columns" ng-value="key">{{column}}</md-option> </md-select> </md-input-container> <md-input-container class="md-block mv-35" flex="95" style="margin-left: 2%;"> <label class="font-size-18 font-weight-300">Select Formula</label> <md-select ng-model="chartsData.formula[\'chart_'+cnt+'\']"> <md-option ng-repeat="(key, value) in {\'no\':\'No Formula\', \'count\':\'Count\',\'addition\':\'Addition\',\'percent\':\'Percent\'}" value="{{key}}" ng-selected="key==\'no\'">{{value}}</md-option> </md-select> </md-input-container> <md-input-container class="md-block mv-35" flex="95" style="margin-left: 2%;" > <span class="font-size-14 font-weight-300 grey-600-fg">Chart Width</span> <md-select ng-model="chartsData.chartWidth[\'chart_'+cnt+'\']" aria-label="chartt" > <md-option value="25"> 25% </md-option> <md-option value="50"> 50% </md-option> <md-option value="75"> 75% </md-option> <md-option value="100"> 100% </md-option> </md-select> </md-input-container> <md-input-container class="md-block mv-35" flex="95" style="margin-left: 2%;" ng-show="chartsData.chartType[\'chart_'+cnt+'\']==\'CustomMap\'"> <span class="font-size-14 font-weight-300 grey-600-fg" >Load custom data</span> <md-select ng-model="chartsData.customData[\'chart_'+cnt+'\']" aria-label="chartt" multiple > <md-option ng-repeat="(key, column) in columns" ng-value="key">{{column}}</md-option> </md-select> </md-input-container> <md-input-container class="md-block mv-35" flex="95" style="margin-left: 2%;margin-top: 2.5%; padding-bottom:2%; display:none;"> <label >Visual Setting</label> <textarea required ng-model="chartsData.visual_settings[\'chart_'+cnt+'\']"></textarea></md-input-container> <md-button class="md-raised md-primary ph-15" ng-click="showCustom($event,\'chart_'+cnt+'\',chartsData.chartType[\'chart_'+cnt+'\'])"> <md-tooltip md-direction="top" class="md-body-1">Add chart design settings to the dataset</md-tooltip> Chart Design Settings </md-button> </div>')($scope));

            $('.chartCount:last').html(parseInt($('.chartCount').length));
        }

        $scope.addFilter = function(){
            var totalFilters = parseInt($('.filterCount').length + 1);
            var appendData = '<div> <md-divider></md-divider><div layout="row" class="pl-5"> <span class="md-grey-bg md-headline mt-20 mh-10 mb-15 filterCount md-body-2 white-fg" style="border-radius:50%;height:28px;line-height:28px;width:28px;text-align:center;"> '+totalFilters+' </span> <div flex="40" layout="row"> <md-input-container class="md-block" flex="95" style="margin-left: 2%;" > <label class="font-size-18 font-weight-300">Filter Variable</label> <md-select ng-model="filter_columns[\'filter_'+totalFilters+'\']"> <md-option ng-repeat="(key, column) in columns" ng-value="key"> {{column}} </md-option> </md-select> </md-input-container> </div> <div flex="40" layout="row"> <md-input-container class="md-block" flex="95" style="margin-left: 2%;"> <label class="font-size-18 font-weight-300">Filter Type</label> <md-select ng-model="filter_type[\'filter_'+totalFilters+'\']"> <md-option value="range"> Range </md-option> <md-option value="dropdown"> Single Select </md-option> <md-option value="mdropdown"> Multi Select </md-option> </md-select> </md-input-container> </div> <div flex class="mr-30 mt-24"> <a href="" style="float:right;margin-right:1%;margin-top:1%;" class="deleteFilter" filter-ind="'+totalFilters+'"><md-tooltip md-direction="top" class="md-body-1"> Remove Filter </md-tooltip><md-icon flex="20" md-font-icon="icon-trash" class="s20 red-fg"> </md-icon></a> </div> </div> </div>'
            $('.filtersList').append($compile(appendData)($scope));
            if($scope.extraFilters == null){
                $scope.nofilterAvail = true;
            }
        }

        $scope.cloneChart = function(chartId){
            $scope.addMoreChart();
            angular.forEach($scope.chartsData, function(value, key){
                $scope.chartsData[key]['chart_'+$('.chartCount').length] = $scope.chartsData[key][chartId]
            });
        }

        $scope.update_visual = function(from_wizard_or_not){

            $scope.isLoading = true;
            $scope.error_message = '';
            var settings = $scope.visual_settings;
            var filters = {};
            angular.forEach($scope.filter_columns, function(value, key){
                filters[key] = {};
                filters[key]['type'] = $scope.filter_type[key];
                filters[key]['column'] = $scope.filter_columns[key];
            });
            var total_charts = $scope.chartsData.title;
            var charts = {};
            var index = 1;
            angular.forEach(total_charts, function(data_value, chart_key){
                charts['chart_'+index] = {};
                angular.forEach($scope.chartsData, function(value, key){
                    charts['chart_'+index][key] = value['chart_'+index];
                });
                index++;
            });
            var formData = new FormData;
            formData.append('charts',JSON.stringify(charts));
            formData.append('filters',JSON.stringify(filters));
            formData.append('settings',JSON.stringify(settings));
            formData.append('visualization_id',$state.params.id);
            api.postMethod.saveVisual(formData).then(function(res){
                if(res.data.status == 'success'){
                     $mdToast.show(
                         $mdToast.simple()
                            .textContent('Visualization Updated Successfully!')
                            .position('top right')
                            .hideDelay(5000)
                        );
                     $scope.isLoading = false;
                }else{
                    $scope.error_message = res.data.message;
                    $scope.message = true;
                }
            });
            
        }

        $scope.if_chart_in_array = function(chart, chartArray){
            
            if($.inArray(chart, chartArray) != -1){
                return true;
            }else{
                return false;
            }
        }


        $scope.enableDisable = true;

        
        $(document).on('click','.deleteFilter',function(){
            delete $scope.filter_columns['filter_'+$(this).attr('filter-ind')];
            delete $scope.filter_columns[0];
            delete $scope.filter_columns[1];
            $(this).parent('div').parent('div').remove();
            if($('.filterCount').length == 0){
                $scope.nofilterAvail = false;
            }
        });

        

        $(document).on('click','.deleteFrame', function(){
            var chart = parseInt($(this).closest('div').find('.chartCount').html());
            if($scope.chartsData != null){
                delete $scope.chartsData.title['chart_'+chart];
                delete $scope.chartsData.formula['chart_'+chart];
                delete $scope.chartsData.column_one['chart_'+chart];
                delete $scope.chartsData.columns_two['chart_'+chart];
                delete $scope.chartsData.visual_settings['chart_'+chart];
                delete $scope.chartsData.chartType['chart_'+chart];
                delete $scope.chartsData.chartWidth['chart_'+chart];
                try{
                    delete $scope.chartsData.customData['chart_'+chart];
                    delete $scope.chartsData.mapArea['chart_'+chart];
                    delete $scope.chartsData.viewData['chart_'+chart];
                }catch(e){

                }
                delete $scope.chartsData.enableDisable['chart_'+chart];
            }
            $(this).parent('div').remove(); 
        });
    	
    	$scope.changeDatset = function(){

    		$scope.message = false;
    		$scope.error_message = '';
    	}
        $scope.view_visualization = function(){
            $state.go('app.genvisual_view',{'id':$state.params.id});
        }

        window.$compile = $compile;
        window.$scope = $scope;
    }
    $(document).on('click','.clone-chart', function(){
        
    });
    $(document).on('click','.exp_col', function(){
        $(this).parent('div').parents('.frame').css('overflow','hidden');
        if($(this).parent('div').parents('.frame').hasClass('expanded')){
            var elm = $(this);
            $(this).parent('div').parents('.frame').animate({
                'height':'50px'
            },200, function(){
                elm.html($compile('<img src="assets/images/arrow-down.png" style="width:16px;margin-top:5px;transform: rotate(179deg)">')($scope));
            });
            $(this).parent('div').parents('.frame').removeClass('expanded');
        }else{
            var elm = $(this);
            $(this).parent('div').parents('.frame').addClass('expanded');
            $(this).parent('div').parents('.frame').animate({
                'height': $(this).get(0).scrollHeight + 17
            },200, function(){
                $(this).height('auto');
                elm.html('<img src="assets/images/arrow-down.png" style="width:16px;margin-top:5px;">');
            }); 
        }
    });

})();
