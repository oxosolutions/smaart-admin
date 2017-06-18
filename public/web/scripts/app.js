(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "msApiProvider"];
    angular
        .module('app.visualizations.view', ['svgMaps'])
        .config(config);

    /** @ngInject */
    function config($stateProvider, msApiProvider)
    {
        $stateProvider.state('app.genvisual_view', {
            url    : '/visual/view/:id',
            views  : {
                'content@app': {
                    templateUrl: 'app/main/visualizations/view/view.html',
                    controller : 'ViewVisualController as vm'
                }
            }

        });

    }

})();
(function() {
    'use strict';

    ViewVisualController.$inject = ["$scope", "$timeout", "$mdSidenav", "$state", "api", "$compile", "$mdDialog"];
    angular
        .module('app.visualizations.view')
        .directive('bindHtmlCompile', ['$compile', function($compile) {
            return {
                restrict: 'A',
                link: function(scope, element, attrs) {
                    scope.$watch(function() {
                        return scope.$eval(attrs.bindHtmlCompile);
                    }, function(value) {
                        element.html(value && value.toString());
                        var compileScope = scope;
                        if (attrs.bindHtmlScope) {
                            compileScope = scope.$eval(attrs.bindHtmlScope);
                        }
                        $compile(element.contents())(compileScope);
                    });
                }
            };
        }])
        .controller('ViewVisualController', ViewVisualController);

    /** @ngInject */
    function ViewVisualController($scope, $timeout, $mdSidenav, $state, api, $compile, $mdDialog) {
        var formdata = new FormData();
        formdata.append('visual_id',$state.params.id);
        api.postMethod.generateEmbed(formdata).then(function(res){
            api.visual.visualEmbedCode.get({visual_id:$state.params.id},function(res){
                console.log(api.surveyEmbed);
                $('.content').html('<iframe src="'+api.surveyEmbed+'v/'+res.token+'" width="100%" style="border:none;" height="480px"></iframe>');
            });
        });
        

        var formData = new FormData();
        formData.append('id',$state.params.id);
        console.log($state.params.id);

        var vm = this;
        window.chartWrapper = {};
        window.newTempSettings = {};
        $scope.showFirst = true;
        var chartAnimation;
        $scope.slider = {};
        google.charts.load('current'); // Don't need to specify chart libraries!
        //google.charts.setOnLoadCallback(drawVisualization);

        $scope.editName = function(event,datasetID, name) {

              $mdDialog.show({
                clickOutsideToClose: true,
                scope: $scope,        
                preserveScope: true,           
                templateUrl: 'app/main/visualization/dialogs/edit-visual.html',
                controller: ["$scope", "$mdDialog", "api", function DialogController($scope, $mdDialog, api) {  
                  $scope.newName = name;                
                  $scope.rename = function(){
                    var formdata = new FormData();
                    formdata.append('id',datasetID);
                    formdata.append('dataset_name',$scope.newName);
                    api.postMethod.renameDataset(formdata).then( function(res){
                        $mdDialog.hide();
                        $scope.dataset_name = $scope.newName;
                    })
                    
                  }
                  $scope.closeDialog = function() {
                    $mdDialog.hide();
                  }
                }]
              });
          };
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
            {
                "value": "BubbleChart",
                "label": "Bubble Chart"
            },
            {
                "value": "CustomMap",
                "label": "Custom Map"
            },
            {
                "value": "TableChart",
                "label": "Table chart"
            }
        ];
        $scope.drawVisual = function(action){
            
            if(vm.visFilters == undefined && jQuery.isEmptyObject($scope.slider) == true && vm.visFiltersMulti == undefined){
                return false;
            }
            $scope.procReq = true;
            $scope.pr = true;

            var formData = new FormData();
            formData.append('id',$state.params.id);
            if(action == 'filter'){
                formData.append('type','filter');
            }else{
                $scope.vm.visFilters = '';
                $scope.vm.visFiltersMulti = '';
                vm.visFilters = '';
                formData.append('type','non-filter');
            }
            if(jQuery.isEmptyObject($scope.slider) != true){
                var rangesList = {};
                angular.forEach($scope.slider, function(val, key){
                    var sliderRange = {};
                    if(val.min != undefined){
                        sliderRange['min'] = val.min;
                        sliderRange['max'] = val.max;
                        rangesList[key] = sliderRange;
                    }
                });                
                formData.append('range_filters',JSON.stringify(rangesList));
            }
            formData.append('filter_array',JSON.stringify(vm.visFilters));
            formData.append('filter_array_multi',JSON.stringify(vm.visFiltersMulti));
            api.postMethod.getVisual(formData).then(function(res){
                res = res.data;
                // console.log(res);
                $scope.filters = res.filters;
                $("#data_wrapper").text(JSON.stringify(res));
                $scope.charts = [];
                $scope.charts = res;
                
                setTimeout(function(){
                    var index = 1;
                    angular.forEach(res.chart_data, function(ind,val){
                        var chartTypes = JSON.parse(res.chart_types);
                        var options = res.settings;
                        if(chartTypes[val] != 'CustomMap' && chartTypes[val] != 'TableChart'){
                            chartWrapper['chart_'+index] = new google.visualization.ChartWrapper({
                                chartType: chartTypes[val],
                                dataTable: res.chart_data[val],
                                options: JSON.parse(options[val][0]),
                                containerId: 'chart_wrapper_'+index,
                            });
                            var chartSetoptions = JSON.parse(options[val][0]);
                            chartWrapper['chart_'+index].draw();
                        }else if(chartTypes[val] == 'TableChart'){
                            generateTable(res.chart_data[val], index);                            
                        }else{
                            var chrtData = res.chart_data['chart_'+index];
                            var settings = res.settings['chart_'+index];
                            var chartHeaderArray = chrtData[0];
                            settings = JSON.parse(settings[0]);
                            try{
                                var haxColor = settings['chartColor']['colors']
                            }catch(e){
                                var haxColor = '#ED6F1D';
                            }
                            
                            var hex = haxColor.replace('#','');
                            var r = parseInt(hex.substring(0,2), 16);
                            var g = parseInt(hex.substring(2,4), 16);
                            var b = parseInt(hex.substring(4,6), 16);

                            if(res.map_display_val != null){
                                var stateCode = res.map_display_val;
                                var columnHeaderForSort = res.map_display_val[0];
                                var stateCode_Loop = res.map_display_val;
                            }
                            
                            if($.inArray(columnHeaderForSort, chartHeaderArray) !== -1){
                                var HeaderIndex = chartHeaderArray.indexOf(columnHeaderForSort);
                                var sortedArray = chrtData.sort(function(a, b){
                                    return a[HeaderIndex] - b[HeaderIndex]; 
                                });
                            }else{
                                angular.forEach(res.map_display_val, function(v,k){
                                    if($.isArray(chrtData[k])){
                                        chrtData[k].push(v);
                                    }else{
                                        var tempArray = [];
                                        tempArray.push(chrtData[k]);
                                        tempArray.push(v);
                                        chrtData[k] = tempArray;
                                    }
                                });
                                if(res.map_display_val == null){
                                    var arrayDataForSort = chrtData;
                                    delete arrayDataForSort[0];
                                    var sortedArray = arrayDataForSort.sort(function(a, b){
                                        return a[1] - b[1];
                                    });
                                    var putHeader = [];
                                    putHeader.push('String');
                                    putHeader.push('Frequecy');
                                    sortedArray.unshift(putHeader);
                                }else{
                                    var HeaderIndex = chartHeaderArray.indexOf(columnHeaderForSort);
                                    var sortedArray = chrtData.sort(function(a, b){
                                        return a[HeaderIndex] - b[HeaderIndex]; 
                                    });
                                }
                            }
                            
                            var highest_value = Math.max.apply(Math, stateCode);
                            
                            
                            $('#chart_wrapper_'+index).html($compile(res.maps[val])($scope));
                            var stateInd = 0;
                            var leagend = "<div class='map-leagend'>";
                            angular.forEach(sortedArray, function(val,ind){

                                if(ind != 0){
                                    var currentClass = $('#'+val[0]).attr('class');
                                    
                                    /*$('#'+val[0])
                                    .css({'fill': 'rgba('+r+','+g+','+b+','+(stateCode_Loop[stateInd]/highest_value) +')'}).attr('class','mapArea '+currentClass);*/
                                    var colorVal = stateInd/sortedArray.length;
                                    var leagendWidth = (1/(sortedArray.length-1))*100;
                                    var colorCode = getColor(colorVal);

                                    $('#chart_wrapper_'+index+' #'+val[0])
                                    .css({'fill': colorCode }).attr('class','mapArea '+currentClass);
                                    angular.forEach(val, function(v,i_nd){
                                        if(i_nd > 0){
                                            $('#chart_wrapper_'+index+' #'+val[0]).attr(chrtData[0][i_nd].replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, "_"),v);
                                        }
                                    });
                                    if(res.map_display_val == null){
                                        leagend += '<div data-value="'+val[1]+'" style="width:'+leagendWidth+'%; background-color:'+colorCode+'"></div>';
                                    }else{
                                        leagend += '<div data-value="'+val[HeaderIndex]+'" style="width:'+leagendWidth+'%; background-color:'+colorCode+'"></div>';
                                    }
                                    stateInd++;
                                }
                            });
                            leagend += "</div>";
							leagend += "<div class='smaart-watermark'>Created with <a href='http://smaartframework.com' target='_blank'>SMAART™ Framework</a></div>";
                            $('#cchart_wrapper_'+index).append(leagend);
                            //$(leagend).appendTo('body');
                            
                            $('#chart_wrapper_'+index+' .mapArea').mouseover(function (e) {
                                var elm = $(this);
                                var title=$(this).attr('title');
                                var html = '';
                                html += '<div class="inf">';
                                html += '<span class="title">'+title + '</span>';
                                angular.forEach(chrtData[0], function(v, k_in){
                                    if(k_in > 0){
                                        var atr_id = v.replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, "_");
                                        html += '<span class="data">'+v+': '+ elm.attr(atr_id)+'</span>';
                                    }
                                });
                                html += '</div>';
                                $(html).appendTo('body');
                            })
                            .mouseleave(function () {
                                $('.inf').remove();
                            }).mousemove(function(e) {
                                var mouseX = e.pageX, //X coordinates of mouse
                                    mouseY = e.pageY; //Y coordinates of mouse

                                $('.inf').css({
                                    'top': mouseY-($('.inf').height()+30),
                                    'left': mouseX
                                });
                            });
                        }
                        index++;
                    });
                },1);
                /*setTimeout(function(){
                    var index = 1;
                    angular.forEach(res.chart_data, function(ind,val){
                        var settingButton = '<div layout="row" layout-align="end start" style="margin-top:-2%" class="seting"> <md-button class="md-fab md-mini md-primary" aria-label="settings" ng-click="showCustom($event,\'chart_'+index+'\','+index+')"> <md-tooltip md-direction="top" class="md-body-1"><span class="p-10"> Design setting for this chart</span></md-tooltip><md-icon md-font-icon="icon-cog"><md-tooltip md-direction="top" class="md-body-1"><span class="p-10"> Design setting for this chart</span></md-tooltip></md-icon> </md-button> </div>';
                        
                        $('#chart_wrapper_'+index).prepend($compile(settingButton)($scope));
                        
                        index++;
                    });
                },500);*/
            });
        }
        function getColor(value){
            //value from 0 to 1
            var hue=((1-value)*50).toString(10);
            return ["hsl(",hue,",100%,50%)"].join("");
        }
        $scope.showFirst = false;
            
        vm.chart_types = [{
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
            {
                "value": "BubbleChart",
                "label": "Bubble Chart"
            }
        ];

        $scope.edit = function(){
            $state.go('app.genvisuals_edit',{'id':$state.params.id});

        }
        $scope.editViz = function(){
            $state.go('app.genvisuals_edit',{'id':$state.params.id});
        }

        function drawVisualization(){

            var formData = new FormData();
            formData.append('id',$state.params.id);
            formData.append('type','non-filter');
            api.postMethod.getVisual(formData).then(function(res){
                window.res = res;
                window.redrawSetting = res;
                res = res.data;
                $scope.showFilter = true;
                $scope.showCharts = true;
                if(res.status == 'error'){
                    $scope.showFilter = false;
                    $scope.filterNotFound = true;
                    $scope.showCharts = false;
                    $scope.noCharts = true;
                    return false;
                }
                $scope.filters = res.filters;
                $("#data_wrapper").text(JSON.stringify(res));
                
                $scope.charts = res;
                // console.log(res);
                setTimeout(function(){
                    var index = 1;
                    angular.forEach(res.chart_data, function(ind,val){
                        var chartTypes = JSON.parse(res.chart_types);
                        var options = res.settings;
                        var titles = res.titles;
                        
                        if(chartTypes[val] != 'CustomMap' && chartTypes[val] != 'TableChart'){
                            $scope.chart_cont = true;
                            $scope.map_cont = false;
                            chartWrapper['chart_'+index] = new google.visualization.ChartWrapper({
                                chartType: chartTypes[val],
                                dataTable: res.chart_data[val],
                                options: JSON.parse(options[val][0]),
                                containerId: 'chart_wrapper_'+index,
                            });
                            
                            //var chartSetoptions = JSON.parse(options[val][0]);
                            chartWrapper['chart_'+index].draw();
                        }else if(chartTypes[val] == 'TableChart'){
                            generateTable(res.chart_data[val], index);                            
                        }else{
                            
                            var chrtData = res.chart_data['chart_'+index];
                            var settings = res.settings['chart_'+index];
                            var chartHeaderArray = chrtData[0];
                            settings = JSON.parse(settings[0]);
                            try{
                                var haxColor = settings['chartColor']['colors']
                            }catch(e){
                                var haxColor = '#ED6F1D';
                            }
                            
                            var hex = haxColor.replace('#','');
                            var r = parseInt(hex.substring(0,2), 16);
                            var g = parseInt(hex.substring(2,4), 16);
                            var b = parseInt(hex.substring(4,6), 16);

                            if(res.map_display_val != null){
                                var stateCode = res.map_display_val;
                                var columnHeaderForSort = res.map_display_val[0];
                                var stateCode_Loop = res.map_display_val;
                            }
                            


                            /*console.log(chartHeaderArray);
                            return false;*/
                            if($.inArray(columnHeaderForSort, chartHeaderArray) !== -1){
                                var HeaderIndex = chartHeaderArray.indexOf(columnHeaderForSort);
                                var sortedArray = chrtData.sort(function(a, b){
                                    return a[HeaderIndex] - b[HeaderIndex]; 
                                });
                            }else{
                                angular.forEach(res.map_display_val, function(v,k){
                                    if($.isArray(chrtData[k])){
                                        chrtData[k].push(v);
                                    }else{
                                        var tempArray = [];
                                        tempArray.push(chrtData[k]);
                                        tempArray.push(v);
                                        chrtData[k] = tempArray;
                                    }
                                });
                                if(res.map_display_val == null){
                                    var arrayDataForSort = chrtData;
                                    delete arrayDataForSort[0];
                                    var sortedArray = arrayDataForSort.sort(function(a, b){
                                        return a[1] - b[1]; 
                                    });
                                    var putHeader = [];
                                    putHeader.push('String');
                                    putHeader.push('Frequecy');
                                    sortedArray.unshift(putHeader);
                                }else{
                                    var HeaderIndex = chartHeaderArray.indexOf(columnHeaderForSort);
                                    var sortedArray = chrtData.sort(function(a, b){
                                        return a[HeaderIndex] - b[HeaderIndex]; 
                                    });
                                }
                                
                            }
                            var highest_value = Math.max.apply(Math, stateCode);
                            
                            $scope.chart_cont = false;
                            $scope.map_cont = true;
                           
                            $('#chart_wrapper_'+index).html($compile(res.maps[val])($scope));
                            var stateInd = 0;
							var leagend = "<div class='map-leagend'>";
                            angular.forEach(sortedArray, function(val,ind){
                                if(ind != 0){
                                    var currentClass = $('#'+val[0]).attr('class');
                                    
                                    /*$('#'+val[0])
                                    .css({'fill': 'rgba('+r+','+g+','+b+','+(stateCode_Loop[stateInd]/highest_value) +')'}).attr('class','mapArea '+currentClass);*/
                                    var colorVal = stateInd/sortedArray.length;
									var leagendWidth = (1/(sortedArray.length-1))*100;
									var colorCode = getColor(colorVal);

                                    $('#chart_wrapper_'+index+' #'+val[0])
                                    .css({'fill': colorCode }).attr('class','mapArea '+currentClass);
                                    angular.forEach(val, function(v,i_nd){
                                        if(i_nd > 0){
                                           
                                            $('#chart_wrapper_'+index+' #'+val[0]).attr(chrtData[0][i_nd].replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, "_"),v);
                                        }
                                    });
                                    if(res.map_display_val == null){
    									leagend += '<div data-value="'+val[1]+'" style="width:'+leagendWidth+'%; background-color:'+colorCode+'"></div>';
                                    }else{
                                        leagend += '<div data-value="'+val[HeaderIndex]+'" style="width:'+leagendWidth+'%; background-color:'+colorCode+'"></div>';
                                    }
                                    stateInd++;
                                }
                            });
							leagend += "</div>";
							leagend += "<div class='smaart-watermark'>Created with <a href='http://smaartframework.com' target='_blank'>SMAART™ Framework</a></div>";
                            $('#chart_wrapper_'+index).append(leagend);
							//$(leagend).appendTo('body');
							
                            $('#chart_wrapper_'+index+' .mapArea').mouseover(function (e) {
                                var elm = $(this);
                                var title=$(this).attr('title');
                                var html = '';
                                html += '<div class="inf">';
                                html += '<span class="title">'+title + '</span>';
                                angular.forEach(chrtData[0], function(v, k_in){
                                    if(k_in > 0){
                                        var atr_id = v.replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, "_");
                                        html += '<span class="data">'+v+': '+ elm.attr(atr_id)+'</span>';
                                    }
                                });
                                html += '</div>';
                                $(html).appendTo('body');
                            })
                            .mouseleave(function () {
                                $('.inf').remove();
                            }).mousemove(function(e) {
                                var mouseX = e.pageX, //X coordinates of mouse
                                    mouseY = e.pageY; //Y coordinates of mouse

                                $('.inf').css({
                                    'top': mouseY-($('.inf').height()+30),
                                    'left': mouseX
                                });
                            });

                        }
                        index++;
                    });
                },5);
                /*setTimeout(function(){
                    var index = 1;
                    angular.forEach(res.chart_data, function(ind,val){
                        var settingButton = '<div layout="row" layout-align="end start" style="margin-top:-2%" class="seting"> <md-tooltip md-direction="top" class="md-body-1"><span class="p-10"> Design setting for this chart</span></md-tooltip><md-button class="md-fab md-mini md-primary" aria-label="settings" ng-click="showCustom($event,\'chart_'+index+'\','+index+')"> <md-icon md-font-icon="icon-cog"></md-icon> </md-button> </div>';
                        $('#chart_wrapper_'+index).prepend($compile(settingButton)($scope));
                        index++;
                    });
                },3000);*/
            });
            
        }

        $scope.showCustom = function(event, chart, ind) {
          /*  console.log(window.res);
            console.log(chart);*/
            $mdDialog.show({
              clickOutsideToClose: true,
              scope: $scope,        
              preserveScope: true,           
              templateUrl: 'app/main/visualizations/generated/edit/dialogs/visual-setting.html',
              controller: ["$scope", "$mdDialog", "$state", "api", function DialogController($scope, $mdDialog, $state, api) {
                 $scope.closeDialog = function() {
                    $mdDialog.hide();
                 }
                 setTimeout(function(){
                    if(newTempSettings[chart] == '' || newTempSettings[chart] == undefined){
                        $scope.visualSettings.chart_settings = JSON.parse(window.res.data.settings[chart][0]);
                    }else{
                        $scope.visualSettings.chart_settings = newTempSettings[chart];
                    }
                 },1);
                 var chartSetting = JSON.parse(window.res.data.default_settings);
                 $scope.settings = chartSetting;
                 $scope.saveVisualSettings = function(){
                    if($scope.visualSettings.chart_settings.colors == undefined || $scope.visualSettings.chart_settings.colors == ''){
                        delete $scope.visualSettings.chart_settings.colors;
                    }else{
                        var clrs = ($scope.visualSettings.chart_settings.colors).split(',');
                        $scope.visualSettings.chart_settings.colors = clrs.trim();

                    }
                    newTempSettings[chart] = $scope.visualSettings.chart_settings;
                    var formData = new FormData();
                    formData.append('chart',chart);
                    formData.append('settings',JSON.stringify($scope.visualSettings.chart_settings));
                    formData.append('visual_id',$state.params.id);
                    api.postMethod.saveSettings(formData).then(function(res){
                        if(res.data.status == 'success'){
                            chartWrapper['chart_'+ind].setOptions($scope.visualSettings.chart_settings);
                            chartWrapper['chart_'+ind].draw();
                            $mdDialog.hide();
                        }
                    });
                 }
              }]
           });
        };

        $scope.setVisualizationType = function() {
            var chart_type = $scope.visualization.charttype;
            chartWrapper.setChartType(chart_type);
            chartWrapper.draw();
        }

    }


    function checkAuth($state) {

        if (sessionStorage.api_token == undefined || sessionStorage.api_token == '') {

            $state.go('app.new-login');
            return false;
        }
    }
        /*function transpose(a) {
            var w = a.length ? a.length : 0,
            h = a[0] instanceof Array ? a[0].length : 0;
            if(h === 0 || w === 0) { return []; }
            var i, j, t = [];
            for(i=0; i<h; i++) {
                t[i] = [];
                for(j=0; j<w; j++) {
                    t[i][j] = a[j][i];
                }
            }
            return t;
        }*/
})();

function generateTable(data, index){
    var table = '<table class="tableChart">';
    table     +=    '<tr style="background:darkslategrey;color:white;">';
    $.each(data[0],function(key, value){
        table  +=       '<th>'+value+'</th>';
    });
    table     +=    '</tr>';
    $.each(data, function(key, value){
        if(key != 0){
            table  +=   '<tr>';
            $.each(value, function(k,v){
                table  +=    '<td>'+v+'</td>';
            });
            table  +=   '</tr>';
        }
    });
    table   +=  '</table>';
    $('#chart_wrapper_'+index).html(table);
}
(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "msApiProvider"];
    angular
        .module('app.visualizations.list', ['datatables'])
        .config(config);

    /** @ngInject */
    function config($stateProvider, msApiProvider)
    {
        $stateProvider.state('app.genvisuals_list', {
            url    : '/visual/list',
            views  : {
                'content@app': {
                    templateUrl: 'app/main/visualizations/list/list.html',
                    controller : 'ListVisualController as vm'
                }
            }
        });

    }

})();
(function ()
{
    'use strict';

    ListVisualController.$inject = ["$scope", "api", "$state", "$http", "$mdDialog", "$mdToast"];
    angular
        .module('app.visualizations.list')
        .controller('ListVisualController', ListVisualController);

    /** @ngInject */
    function ListVisualController($scope, api,$state, $http, $mdDialog, $mdToast)
    {
      $scope.deleteVisual = function(visualId, ev){

          var confirm = $mdDialog.confirm({
              
                    onComplete: function afterShowAnimation() {
                        var $dialog = angular.element(document.querySelector('md-dialog'));
                        var $actionsSection = $dialog.find('md-dialog-actions');
                        var $cancelButton = $actionsSection.children()[0];
                        var $confirmButton = $actionsSection.children()[1];
                        angular.element($confirmButton).addClass('md-raised red-bg ph-20 ');
                        angular.element($cancelButton).addClass('md-raised ph-20 ');
                    }
                
            })
                .title('Would you like to delete this visualization?')
                .textContent('The Visualization will be deleted permanently and no longer accesible by any user.')
                .ariaLabel('Delete Visualization')
                .targetEvent(ev)
                .ok('Yes, delete it!')
                .cancel('No, don\'t delete');

          $mdDialog.show(confirm).then(function() {
            api.visualizations.deleteVisualization.get({'id':visualId}, function(res){
                if(res.status == 'success'){
                    $mdToast.show(
                     $mdToast.simple()
                        .textContent('Visualization deleted successfully!')
                        .position('top right')
                        .hideDelay(5000)
                    );
                    $state.go($state.current, {}, {reload: true});
                } else{

                  $mdToast.show(
                     $mdToast.simple()
                        .textContent(res.message)
                        .position('top right')
                        .hideDelay(5000)
                    );
                }
            });
          }, function() {

          });
      }

      $scope.createClone = function(visualId){
        api.visual.createClone.get({id:visualId}, function(res){
            $mdToast.show(
               $mdToast.simple()
                  .textContent('Visualization cloned successfully!')
                  .position('top right')
                  .hideDelay(5000)
              );
              $state.go($state.current, {}, {reload: true});
        });
      }
      if(checkAuth($state) == false){
          return false;
      }
      //console.log(sessionStorage.api_token);
      var vm = this;
      $scope.isLoading = true;
      api.visual.list.get({},function(response){
          console.log(response);
          vm.visuals = response.list.visuals;
      });


      vm.dtOptions = {
          dom       : '<"top"<"left"<"length"l>><"right"<"search"f>>>rt<"bottom"<"left"<"info"i>><"right"<"pagination"p>>>',
          pagingType: 'full_numbers',
          order: [[ 0, "desc" ]],
          autoWidth : false,
          responsive: true
      };

     

          $scope.generateCode1 = function(visual_id,ev) {
            
              var formdata = new FormData();
              formdata.append('visual_id',visual_id);
              api.postMethod.generateEmbed(formdata).then(function(res){
                 $mdDialog.show({
                    clickOutsideToClose: true,
                    scope: $scope,        
                    preserveScope: true,           
                    templateUrl: 'app/main/visualizations/include/embed-dialog.html',
                    controller: ["$scope", "$mdDialog", "api", function DialogController($scope, $mdDialog, api) {
                      $scope.embedSrc = api.siteUrl+res.data.token;
                      $scope.closeDialog = function() {
                        $mdDialog.hide();
                      }
                    }]
                 });
              });
            };

        

         $scope.closeMe = function() {
      $mdDialog.hide();
    };

      
    }
    function checkAuth($state){
      //console.log(sessionStorage.api_token);
        if(sessionStorage.api_token == undefined || sessionStorage.api_token == ''){

            $state.go('app.new-login');
            return false;
        }
    }
    $('body').on('mouseover','.ActionView > md-menu-item',function(){
        $(this).find('.action-link').css({'color':'white'});
        $(this).find('md-icon').css({'color':'white'});
    });
    $('body').on('mouseout','.ActionView > md-menu-item',function(){
        $(this).find('.action-link').css({'color':'#039BE5'});
        $(this).find('md-icon').css({'color':'#757575'});
    });
     $('body').on('mouseover','.ActionView > .delete',function(){
        $(this).find('.action-link').css({'color':'white'});
        $(this).find('md-icon').css({'color':'white'});
    });
    $('body').on('mouseout','.ActionView > .delete',function(){
        $(this).find('.action-link').css({'color':'red'});
        $(this).find('md-icon').css({'color':'#757575'});
    });
})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "msApiProvider"];
    angular
        .module('app.visualizations.edit', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider, msApiProvider)
    {
        $stateProvider.state('app.genvisuals_edit', {
            url    : '/visual/edit/:id',
            views  : {
                'content@app': {
                    templateUrl: 'app/main/visualizations/edit/edit.html',
                    controller : 'EditVisualController as vm'
                }
            }
        });

    }

})();
(function ()
{
    'use strict';

    EditVisualController.$inject = ["$scope", "$state", "api", "$mdToast", "$compile", "$mdDialog"];
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
              controller: ["$scope", "$mdDialog", function DialogController($scope, $mdDialog) {
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
              }]
           });
        };

        $scope.AddEmbedCss = function(event) {
           $mdDialog.show({
                clickOutsideToClose: true,
                scope: $scope,        
                preserveScope: true,           
                templateUrl: 'app/main/visualizations/edit/dialogs/add-embed-css.html',
                controller: ["$scope", "$mdDialog", function DialogController($scope, $mdDialog) {
                    $scope.closeDialog = function() {
                        $mdDialog.hide();
                    }
                    
                    $scope.saveEmbedSettings = function(){
                        $mdDialog.hide();
                    }
                
                }]
            });
        };
        $scope.AddEmbedJs = function(event) {
           $mdDialog.show({
                clickOutsideToClose: true,
                scope: $scope,        
                preserveScope: true,           
                templateUrl: 'app/main/visualizations/edit/dialogs/add-embed-js.html',
                controller: ["$scope", "$mdDialog", function DialogController($scope, $mdDialog) {
                    $scope.closeDialog = function() {
                        $mdDialog.hide();
                    }
                    
                    $scope.saveEmbedSettings = function(){
                        $mdDialog.hide();
                    }
                
                }]
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

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "msApiProvider"];
    angular
        .module('app.visualizations.add', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider, msApiProvider)
    {
        $stateProvider.state('app.visualizations_add', {
            url    : '/visualizations/add',
            views  : {
                'content@app': {
                    templateUrl: 'app/main/visualizations/add/add.html',
                    controller : 'AddVisualizationsController as vm'
                }
            }
        });

    }

})();
(function ()
{
    'use strict';

    AddVisualizationsController.$inject = ["$scope", "api", "$state", "$http", "$mdToast"];
    angular
        .module('app.visualizations.add')
        .controller('AddVisualizationsController', AddVisualizationsController);

    /** @ngInject */
    function AddVisualizationsController($scope, api,$state, $http, $mdToast)
    {
        if(checkAuth($state) == false){
            return false;
        }
      var vm = this;

      api.listdataset.list.get({},function(response){
          // console.log(response);
          vm.datasets = response.data;
          $scope.dataset_id = sessionStorage.visualDataset;
          sessionStorage.visualDataset = '';
      });

      $scope.create_visualization = function(){
        var visualization_title = $scope.visualization.visualization_title;
        var dataset_id = $scope.dataset_id;
        $scope.isLoading = true;
        $http.defaults.headers.post['Content-Type'] = undefined;
        var SendData = new FormData();
        SendData.append('dataset',dataset_id);
        SendData.append('visual_name',visualization_title);
        SendData.append('visual_description', $scope.visualization.visualization_description);
        api.postMethod.saveNewVisual(SendData).then(function(res){
            if(res.data.status == 'success'){
                $mdToast.show(
                 $mdToast.simple()
                    .textContent('Visualization Saved Successfully!')
                    .position('top right')
                    .hideDelay(5000)
                );
                $scope.isLoading = false;
                $state.go('app.genvisuals_edit',{'id':res.data.visual_id});
            }else{
               $scope.isLoading = false;
              $scope.visualization.error_message = res.data.message;
            }
        });
      }

      vm.dtOptions = {
          dom       : '<"top"<"left"<"length"l>><"right"<"search"f>>>rt<"bottom"<"left"<"info"i>><"right"<"pagination"p>>>',
          pagingType: 'full_numbers',
          autoWidth : false,
          responsive: true
      };
    }

    function checkAuth($state){

        if(sessionStorage.api_token == undefined || sessionStorage.api_token == ''){

            $state.go('app.new-login');
            return false;
        }
    }
})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider"];
    angular
        .module('app.view-map', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider)
    {
        $stateProvider.state('app.view_map', {
            url    : '/view-map/:id',
            views  : {

                'content@app': {
                    templateUrl: 'app/main/svg-maps/view-map/view-map.html',
                    controller : 'ViewMapController as vm'
                }
            }
        });

      

     

    }

})();
(function ()
{
    'use strict';

    ViewMapController.$inject = ["$scope", "api", "$state", "$compile"];
    angular
        .module('app.view-map')
        .controller('ViewMapController', ViewMapController);

    /** @ngInject */
    function ViewMapController($scope, api, $state, $compile){
    	api.visual.singleMap.get({id:$state.params.id},function(res){
           	$('.appendMap').html($compile(res.response.map_data)($scope));
        });
    }
})();
(function ()
{
    'use strict';

    config.$inject = ["$stateProvider"];
    angular
        .module('app.list-maps', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider)
    {
        $stateProvider.state('app.list_maps', {
            url    : '/list-maps',
            views  : {

                'content@app': {
                    templateUrl: 'app/main/svg-maps/list-maps/list-maps.html',
                    controller : 'ListMapsController as vm'
                }
            }
        });

      

     

    }

})();
(function ()
{
    'use strict';

    ListMapsController.$inject = ["api", "$scope", "$state", "$mdDialog", "$mdToast"];
    angular
        .module('app.list-maps')
        .controller('ListMapsController', ListMapsController);

    /** @ngInject */
    function ListMapsController(api, $scope, $state, $mdDialog, $mdToast){
    
    	api.visual.mapsList.get({}, function(res){
            $scope.maps = res.response;
    		console.log(res);
    	});

    	$scope.deleteMap = function(ev,id){

    		var confirm = $mdDialog.confirm({
							
								onComplete: function afterShowAnimation() {
										var $dialog = angular.element(document.querySelector('md-dialog'));
										var $actionsSection = $dialog.find('md-dialog-actions');
										var $cancelButton = $actionsSection.children()[0];
										var $confirmButton = $actionsSection.children()[1];
										angular.element($confirmButton).addClass('md-raised red-bg ph-20');
										angular.element($cancelButton).addClass('md-raised ph-20');
								}
						
			}).title('Would you like to delete this map?')
				.textContent('The Map will be deleted permanently and no longer accesible by any user.')
				.ariaLabel('Delete Map')
				.targetEvent(ev)
				.ok('Yes, delete it!')
				.cancel('No, don\'t delete');

			$mdDialog.show(confirm).then(function() {
				api.visual.deleteMaps.get({id:id},function(res){
					console.log(res);
					$mdToast.show(
					 $mdToast.simple()
							.textContent('Map deleted successfully!')
							.position('top right')
							.hideDelay(5000)
					);
					$state.go($state.current, {}, {reload: true});
				});
				/*api.dataset.deleteDataset.get({'id':datasetID}, function(res){
						if(res.status == 'success'){
								$mdToast.show(
								 $mdToast.simple()
										.textContent('Dataset deleted successfully!')
										.position('top right')
										.hideDelay(5000)
								);
								$state.go($state.current, {}, {reload: true});
						}
				});*/
			}, function() {

			});
    	}
    }
})();
(function ()
{
    'use strict';

    config.$inject = ["$stateProvider"];
    angular
        .module('app.edit-map', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider)
    {
        $stateProvider.state('app.edit_map', {
            url    : '/edit-map/:id',
            views  : {

                'content@app': {
                    templateUrl: 'app/main/svg-maps/create-map/create-map.html',
                    controller : 'EditMapController as vm'
                }
            }
        });

      

     

    }

})();
(function ()
{
    'use strict';

    EditMapController.$inject = ["$scope", "$state", "api", "$mdToast"];
    angular
        .module('app.edit-map')
        .controller('EditMapController', EditMapController);

    /** @ngInject */
    function EditMapController($scope, $state, api, $mdToast){
    	api.visual.mapsList.get({}, function(res){
            $scope.maps = res.response;
        });
        api.visual.singleMap.get({id:$state.params.id},function(res){
            $scope.parentMap = res.response.parent;
            $scope.mapTitle = res.response.title;
            $scope.code = res.response.code;
            $scope.codeAlpha2 = res.response.code_albha_2;
            $scope.codeAlpha3 = res.response.code_albha_3;
            $scope.codeNumeric = res.response.code_numeric;
            $scope.mapData = res.response.map_data;
            $scope.mapDescription = res.response.description;
            console.log(res);
        });
        $scope.saveMap = function(){
            var formData = new FormData();
            formData.append('parentMap',$scope.parentMap);
            formData.append('mapTitle',$scope.mapTitle);
            formData.append('code',$scope.code);
            formData.append('codeAlpha2',$scope.codeAlpha2);
            formData.append('codeAlpha3',$scope.codeAlpha3);
            formData.append('codeNumeric',$scope.codeNumeric);
            formData.append('mapData',$scope.mapData);
            formData.append('mapDescription',$scope.mapDescription);
            formData.append('map_id',$state.params.id);
            api.postMethod.updateMap(formData).then(function(res){
                $mdToast.show(
                 $mdToast.simple()
                    .textContent('Map saved successfully!!')
                    .position('top right')
                    .hideDelay(5000)
                );
                $state.go('app.list_maps');
                console.log(res);
            });
            console.log($scope);
        }
    }
})();
(function ()
{
    'use strict';

    config.$inject = ["$stateProvider"];
    angular
        .module('app.create-map', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider)
    {
        $stateProvider.state('app.create_map', {
            url    : '/create-maps',
            views  : {

                'content@app': {
                    templateUrl: 'app/main/svg-maps/create-map/create-map.html',
                    controller : 'CreateMapController as vm'
                }
            }
        });

      

     

    }

})();
(function ()
{
    'use strict';

    CreateMapController.$inject = ["api", "$scope", "$mdToast", "$state"];
    angular
        .module('app.create-map')
        .controller('CreateMapController', CreateMapController);

    /** @ngInject */
    function CreateMapController(api, $scope, $mdToast,$state){
    	api.visual.mapsList.get({}, function(res){
            $scope.maps = res.response;
    		console.log(res);
    	});

        $scope.saveMap = function(){
            var formData = new FormData();
            formData.append('parentMap',$scope.parentMap);
            formData.append('mapTitle',$scope.mapTitle);
            formData.append('code',$scope.code);
            formData.append('codeAlpha2',$scope.codeAlpha2);
            formData.append('codeAlpha3',$scope.codeAlpha3);
            formData.append('codeNumeric',$scope.codeNumeric);
            formData.append('mapData',$scope.mapData);
            formData.append('mapDescription',$scope.mapDescription);
            api.postMethod.createMap(formData).then(function(res){
                $mdToast.show(
                 $mdToast.simple()
                    .textContent('Map created successfully!!')
                    .position('top right')
                    .hideDelay(5000)
                );
                $state.go('app.list_maps');
                console.log(res);
            });
            console.log($scope);
        }
    }
})();
(function ()
{
    'use strict';

    config.$inject = ["$stateProvider"];
    angular
        .module('app.survey.view', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider )
    {
        $stateProvider.state('app.survey_view', {
            url    : '/survey/view/:id',
            views  : {

                'content@app': {
                    templateUrl: 'app/main/survey/view/view.html',
                    controller : 'ViewSurveyController as vm'
                }
            }
        });

     

     

    }

})();

(function ()
{
    'use strict';

    ViewSurveyController.$inject = ["$scope", "api", "$mdDialog", "$state"];
    angular
        .module('app.survey.view')
        .controller('ViewSurveyController', ViewSurveyController);

    /** @ngInject */
    function ViewSurveyController($scope,api, $mdDialog,$state)
    {
    	$scope.settings = {

          	stretchH: 'all',
          	contextMenu: false,
          	colHeaders: true,
          	formulas: true,
          	readOnly: true
      	}
        api.survey.getSurveyDataById.get({id: $state.params.id},function(res){
        	$scope.items = res.data;
        });
    }
        
})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider"];
    angular
        .module('app.survey.preview', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider )
    {
        $stateProvider.state('app.survey_preview', {
            url    : '/survey/preview/:id',
            views  : {

                'content@app': {
                    templateUrl: 'app/main/survey/preview/preview.html',
                    controller : 'PreviewSurveyController as vm'
                }
            }
        });

     

     

    }

})();

(function ()
{
    'use strict';

    PreviewSurveyController.$inject = ["$scope", "api", "$state"];
    angular
        .module('app.survey.preview')
        .controller('PreviewSurveyController', PreviewSurveyController);

    /** @ngInject */
    function PreviewSurveyController($scope, api,$state)
    {
        var formData = new FormData();
        formData.append('survey_id',$state.params.id);
        api.postMethod.generateSurveyEmbed(formData).then(function(res){
        	//$scope.frame_url = api.surveyEmbed+'s/'+res.data.token;
        	$('.content').html('<iframe src="'+api.surveyEmbed+'s/'+res.data.token+'/minimal/1" width="100%" style="border:none;" height="480px"></iframe>');
        	console.log(api.surveyEmbed+'s/'+res.data.token);
            /*$mdDialog.show({
                clickOutsideToClose: true,
                scope: $scope,        
                preserveScope: true,           
                templateUrl: 'app/main/survey/list/include/embed-dialog.html',
                controller: function DialogController($scope, $mdDialog, api) {
                  $scope.embedSrc = api.surveyEmbed+'s/'+res.data.token;
                  $scope.closeMe = function() {
                    $mdDialog.hide();
                  }
                }
            });*/
        });
    }
        
})();


(function ()
{
    'use strict';

    config.$inject = ["$stateProvider"];
    angular
        .module('app.survey.list', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider )
    {
        $stateProvider.state('app.survey_list', {
            url    : '/survey/list',
            views  : {

                'content@app': {
                    templateUrl: 'app/main/survey/list/list.html',
                    controller : 'ListSurveyController as vm'
                }
            }
        });

     

     

    }

})();

(function ()
{
    'use strict';

    ListSurveyController.$inject = ["$scope", "api", "$mdDialog", "$state", "$mdToast"];
    angular
        .module('app.survey.list')
        .controller('ListSurveyController', ListSurveyController);

    /** @ngInject */
    function ListSurveyController($scope,api, $mdDialog,$state, $mdToast)
    {
    	api.survey.surveyList.get(function(res){
    		// console.log(res.response);
    		$scope.surveyList = res.response;
    	});

        //change status
        $scope.changeStatus = function(id) {
            api.survey.changeStatus.get({'id':id});
            $state.go($state.current , {} , {reload : true});

        }
        $scope.surveyEditList = function(id) {
            
            $scope.data = api.survey.surveyEditList.get();
            console.log($scope.data);
        }

        $scope.generateEmbed = function(surveyId){
            var formData = new FormData();
            formData.append('survey_id',surveyId);
            api.postMethod.generateSurveyEmbed(formData).then(function(res){
                $mdDialog.show({
                    clickOutsideToClose: true,
                    scope: $scope,        
                    preserveScope: true,           
                    templateUrl: 'app/main/survey/list/include/embed-dialog.html',
                    controller: ["$scope", "$mdDialog", "api", function DialogController($scope, $mdDialog, api) {
                      $scope.embedSrc = api.surveyEmbed+'s/'+res.data.token;
                      $scope.closeMe = function() {
                        $mdDialog.hide();
                      }
                    }]
                });
            });
        }
        $scope.sharecode = function(surveyId){
            var formData = new FormData();
            formData.append('survey_id',surveyId);
            api.postMethod.generateSurveyEmbed(formData).then(function(res){
                $mdDialog.show({
                    clickOutsideToClose: true,
                    scope: $scope,        
                    preserveScope: true,           
                    templateUrl: 'app/main/survey/list/include/share-code.html',
                    controller: ["$scope", "$mdDialog", "api", function DialogController($scope, $mdDialog, api) {
                      $scope.embedSrc = api.surveyEmbed+'s/'+res.data.token;
                      $scope.closeMe = function() {
                        $mdDialog.hide();
                      }
                    }]
                });
            });
        }

        $scope.createClone = function(surveyId){
            console.log(surveyId);
            api.survey.createClone.get({id:surveyId},function(res){
                $mdToast.show(
                   $mdToast.simple()
                  .textContent('Survey cloned Successfully!')
                    .position('top right')
                    .hideDelay(5000)
                );
                $state.go($state.current , {} , {reload : true});
            });
        }
        //delete survey
       $scope.deleteSurvey = function(id,ev){

          var confirm = $mdDialog.confirm({
            onComplete: function afterShowAnimation() {
                var $dialog = angular.element(document.querySelector('md-dialog'));
                var $actionsSection = $dialog.find('md-dialog-actions');
                var $cancelButton = $actionsSection.children()[0];
                var $confirmButton = $actionsSection.children()[1];
                angular.element($confirmButton).addClass('md-raised md-warn ph-15');
                angular.element($cancelButton).addClass('md-raised ph-15');
            }
                
            })
                .title('Would you like to delete this survey?')
                .textContent('The survey will be deleted permanently and no longer accesible by any user.')
                .ariaLabel('Delete survey')
                .targetEvent(ev)
                .ok('Delete it')
                .cancel('Don\'t delete');

            $mdDialog.show(confirm).then(function() {
                api.survey.delSurveyById.get({'id':id}, function(res){
                    $state.go($state.current , {} , {reload : true});
                });

              // $scope.status = 'You decided to get rid of your debt.';
            }, function() {
              $scope.status = 'You decided to keep your debt.';
            });
      }
        
    }
    $('body').on('mouseover','.ActionView > md-menu-item',function(){
        $(this).find('.action-link').css({'color':'white'});
        $(this).find('md-icon').css({'color':'white'});
    });
    $('body').on('mouseout','.ActionView > md-menu-item',function(){
        $(this).find('.action-link').css({'color':'#039BE5'});
        $(this).find('md-icon').css({'color':'#757575'});
    });
     $('body').on('mouseover','.ActionView > .delete',function(){
        $(this).find('.action-link').css({'color':'white'});
        $(this).find('md-icon').css({'color':'white'});
    });
    $('body').on('mouseout','.ActionView > .delete',function(){
        $(this).find('.action-link').css({'color':'red'});
        $(this).find('md-icon').css({'color':'#757575'});
    });
})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider"];
    angular
        .module('app.survey.fields', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider )
    {
        $stateProvider.state('app.survey_fields', {
            url    : '/survey/fields/:survey_id/:group_id',
            views  : {

                'content@app': {
                    templateUrl: 'app/main/survey/fields/fields.html',
                    controller : 'FieldsController as vm'
                }
            }
        });

     

     

    }

})();

(function ()
{
    'use strict';

    FieldsController.$inject = ["$scope", "$templateCache", "$compile", "$state", "api", "$mdToast", "$mdDialog"];
    angular
        .module('app.survey.fields')
        .controller('FieldsController', FieldsController);

    /** @ngInject */
    function FieldsController($scope, $templateCache, $compile, $state, api, $mdToast,$mdDialog){
        window.fieldHTML = '';
        window.fieldid = 1;
        $.get('app/main/survey/fields/_field.html', function(data){
            fieldHTML = data;
        });
        window.keyValHTML = '';
        $.get('app/main/survey/fields/key_value.html', function(data){
            keyValHTML = data;
        });

    	api.editFields.fields.get({'survey_id':$state.params.survey_id,'group_id':$state.params.group_id}, function(res){
    		if(res.data.group == undefined){
                $scope.survey_name = res.survey_name;
                $scope.group_name = res.group_name;
                return false;
            }
            $scope.survey_name = res.survey_name;
            $scope.group_name = res.group_name;
            $.each(res.data.group[1].group_questions, function(ikey, ival){
                console.log(ival);
                var stringArray = (ival.question_id).split('_');
                var questNumber = stringArray[2].replace ( /[^\d.]/g, '' );
                questNumber = parseInt(questNumber, 10);
                
                /*if(questNumber > quesid){
                    quesid = questNumber;
                }*/
                if(ikey == 1){
                    var questElm = $('.field-div');
                    questElm.find('.fieldID').html(ival.question_id);
                    questElm.find('.fieldType').val(ival.question_type);
                    questElm.find('.fieldTitle').val(ival.question);
                    questElm.find('.field_title').html(ival.question);
                    questElm.find('.fieldDescription').val(ival.question_desc);
                    questElm.find('.required').val(ival.required);
                    questElm.find('.nextQuest').val(ival.next_question);
                    questElm.find('.pattern').val(ival.pattern);
                    questElm.find('.fieldKey').val(ival.question_key);
                    questElm.find('.field-count').html(parseInt(ikey));
                    if(ival.pattern == 'others'){
                        questElm.find('.patternChange').show();
                        questElm.find('.othetPattern').val(ival.otherPattern);
                    }
                    if(ival.question_type == 'checkbox' || ival.question_type == 'radio' || ival.question_type == 'dropdown'){
                        questElm.find('.addQuestion_right_section').find('#div-one').show();
                        $('.keyValue').sortable();
                        var keyIndex = 0;
                        console.log(ival.extraOptions);
                        $.each(ival.extraOptions, function(optionKeys, optionVal){
                            if(keyIndex == 0){
                                questElm.find('.keyValue').find('.optionKey').val(optionVal['options']['label']);
                                questElm.find('.keyValue').find('.optionValue').val(optionVal['options']['value']);
                                questElm.find('.keyValue').find('.Condition').val(optionVal['options']['condition']);
                            }else{
                                questElm.find('.keyValue').append($compile(keyValHTML)($scope));
                                questElm.find('.keyValue').find('.optionKey:last').val(optionVal['options']['label']);
                                questElm.find('.keyValue').find('.optionValue:last').val(optionVal['options']['value']);
                                questElm.find('.keyValue').find('.Condition:last').val(optionVal['options']['condition']);
                            }
                            keyIndex++;
                        });
                    }
                }else{
                    $('.field-div').append($compile(fieldHTML)($scope));
                    $('.fieldDiv:last').find('.fieldID').html(ival.question_id);
                    $('.fieldDiv:last').find('.fieldType').val(ival.question_type);
                    $('.fieldDiv:last').find('.fieldTitle').val(ival.question);
                    $('.fieldDiv:last').find('.field_title').html(ival.question);
                    $('.fieldDiv:last').find('.fieldDescription').val(ival.question_desc);
                    $('.fieldDiv:last').find('.required').val(ival.required);
                    $('.fieldDiv:last').find('.nextQuest').val(ival.next_question);
                    $('.fieldDiv:last').find('.pattern').val(ival.pattern);
                    $('.fieldDiv:last').find('.fieldKey').val(ival.question_key);
                    $('.fieldDiv:last').find('.field-count').html(ikey);
                    if(ival.pattern == 'others'){
                        $('.fieldDiv:last').find('.patternChange').show();
                        $('.fieldDiv:last').find('.othetPattern').val(ival.otherPattern);
                    }
                    if(ival.question_type == 'checkbox' || ival.question_type == 'radio' || ival.question_type == 'dropdown'){
                        $('.fieldDiv:last').find('.addQuestion_right_section').find('#div-one').show();
                        $('.keyValue').sortable();
                        var keyIndex = 0;
                        $.each(ival.extraOptions, function(optionKeys, optionVal){
                            if(keyIndex == 0){
                                $('.keyValue:last').find('.optionKey').val(optionVal['options']['label']);
                                $('.keyValue:last').find('.optionValue').val(optionVal['options']['value']);
                                $('.keyValue:last').find('.Condition').val(optionVal['options']['condition']);
                            }else{
                                $('.keyValue:last').append($compile(keyValHTML)($scope));
                                $('.keyValue:last').find('.optionKey:last').val(optionVal['options']['label']);
                                $('.keyValue:last').find('.optionValue:last').val(optionVal['options']['value']);
                                $('.keyValue:last').find('.Condition:last').val(optionVal['options']['condition']);

                            }
                            keyIndex++;
                        });
                    }
                }
                $('.field-div').sortable({
                    handle: '.field-count'
                });
            });
    	});
        
        $scope.saveFields = function(){
            var fieldData = {};
            console.log($('.fieldDiv').length);
            $('.fieldDiv').each(function(){
                var fieldType = $(this).find('.fieldType').val();
                var fieldId = $(this).find('.fieldID').html();
                var tempQuestData = {};
                var pattern = $(this).find('.pattern').val();
                var otherPattern = '';

                if(pattern == 'others'){
                    otherPattern = $(this).find('.othetPattern').val();
                }
                var optionsList = [];
                if(fieldType == 'checkbox' || fieldType == 'radio' || fieldType == 'dropdown'){
                    $(this).find('.extraOptions').each(function(){
                            var extraOptions = {};
                            extraOptions['options'] = {};
                            extraOptions['options']['label'] = $(this).find('.optionKey').val();
                            extraOptions['options']['value'] = $(this).find('.optionValue').val();
                            extraOptions['options']['condition'] = $(this).find('.Condition').val();
                            optionsList.push(extraOptions);
                    });
                }
                tempQuestData['question_id']        = fieldId;
                tempQuestData['question_type']      = fieldType;
                tempQuestData['question']           = $(this).find('.fieldTitle').val(); 
                tempQuestData['question_desc']      = $(this).find('.fieldDescription').val();
                tempQuestData['question_key']       = $(this).find('.fieldKey').val();
                tempQuestData['next_question']      = $(this).find('.nextQuest').val();
                tempQuestData['pattern']            = pattern;
                tempQuestData['otherPattern']       = otherPattern;  
                tempQuestData['extraOptions']       = optionsList;
                tempQuestData['required']           = $(this).find('.required').val();
                fieldData[fieldId] = tempQuestData;
            });
            var formData = new FormData();
            formData.append('survey_data',JSON.stringify(fieldData));
            formData.append('survey_id',$state.params.survey_id);
            formData.append('group_id',$state.params.group_id);
            api.postMethod.saveFields(formData).then(function(res){
                $state.go($state.current, {}, {reload: true});
            });
        }
        window.compile = $compile;
        window.scope = $scope;
        window.$state = $state;
        window.$mdDialog = $mdDialog;
        $('.field-div').find('.fieldID:last').html('SID'+$state.params.survey_id+'_GID'+$state.params.group_id+'_QID1');
        $scope.getIcon = function(type){
            var icon_class = {};
            if(type == 'jpg' || type == 'jpeg'){
               
                return 'icon-file-image';
            }
            if(type == 'mp3'){
                
                return 'icon-file-music';
            }
            if(type == 'wav'){
               
                return 'icon-music-note-eighth';
            }
            if(type == 'png'){
                
                return 'icon-image-area';
            }
           
            return 'icon-image-broken';
        }

        $scope.getClass = function(type){
            var icon_class = {};
            if(type == 'jpg' || type == 'jpeg'){
                
                return 'red-fg';
            }
            if(type == 'mp3'){
               
                return 'green-fg';
            }
            if(type == 'wav'){
               
                return '';
            }
            if(type == 'png'){
                
                return 'cyan-fg';
            }
            return 'red-fg';
        }
    }
        
})();
$('.keyValue').sortable({
    handle: '.move-key'
});
$('body').on('click','.add-field', function(){
    var elem = $('.field-div').find('.fieldDiv');
    var total = elem.length;
    var tempQid = 0;
    $('.field-div').find('.fieldID').each(function(){
        var splitedData = $(this).html().split('_');
        var newQuestId = splitedData[2].replace ( /[^\d.]/g, '' );
        if(parseInt(newQuestId) > parseInt(tempQid)){
            tempQid = parseInt(newQuestId);
        }
    });
    // var lastQID = $('.field-div').find('.fieldID:last').html();
    
    $('.field-div').append(compile(fieldHTML)(scope));
    $('.field-div').find('.field-count:last').html(parseInt(total+1));
    var questLength = parseInt(tempQid)+1;
    var groupNumber = $state.params.group_id;

    $('.field-div').find('.fieldID:last').html('SID'+$state.params.survey_id+'_GID'+groupNumber+'_QID'+parseInt(questLength));
    $('.field-div').sortable({
        handle: '.field-count'
    });
    $(this).off('click');
});
$('body').on('click','.field-div img',function(){
    if(!$(this).parents('.fieldDiv').hasClass('expended')){
        $(this).parents('.fieldDiv').addClass('expended');
        $(this).parents('.fieldDiv').css({"display":"block" , 'height':"auto"});
        $(this).css("transform", "rotate(180deg)");
    }else{
        $(this).parents('.fieldDiv').removeClass('expended');
        $(this).parents('.fieldDiv').css({"display":"block" , 'height':"50px"});
        $(this).css("transform", "rotate(360deg)");
    }
});
/*$('body').on('click','.delete-field',function(){
    
    if($(this).parents('.field-div').find('.fieldDiv').length == 1){
        $(this).parents('.field-frame').find('.survey_question_error').show();
        // alert("yuhooo is 1");
    }else{
        $(this).parents('.field-frame').find('.survey_question_error').hide();
        // alert("oops not 1");
    }
});*/
$('body').on('click', '.delete-field', function(){
    var reorder = $(this).parent('div').parent('div').parent('.field-frame').parent('.field-div');
    var elem = $(this).parent('div').parent('div').parent('.field-frame'); 
    elem.animate({
        'margin-left':'40%',
        'opacity':'0.5'
    },200, function(){
        elem.remove();
        reorder.find('.field-count').each(function(i){
            $(this).html(i+1); 
        });
    });
});
$(document).on('keyup','.fieldTitle',function(){
    var value = $(this).val();
    $(this).parents('.fieldDiv').find('.field_title').html(value);
});
$('body').on('change','.fieldType', function(){
    if($(this).val() == "text"){
        $('.addQuestion_right_section > div > div , .div-two').hide();
    }
    if($(this).val() == "checkbox" || $(this).val() == "radio" || $(this).val() == "dropdown"){
        $(this).parent().parent().parent().find('.addQuestion_right_section').find('#div-one').show();
        $('.div-two').hide();
    }
   
    if($(this).val() == "text_only"){
        $('.addQuestion_right_section > div > div , .div-two').hide();
    }
    if($(this).val() == "text_with_image"){
    $('.addQuestion_right_section > div > div').hide();
        $('.div-two').show();
    }
});
$('body').on('click','.addOpts', function(){
    $(this).parents('.keyValue').prepend(window.compile(keyValHTML)(window.scope));
});
$('body').on('click','.add_media',function(){
    var elm = $(this).parents('.fieldDiv');
    $mdDialog.show({
        clickOutsideToClose: true,
        scope: scope,        
        preserveScope: true,           
        templateUrl: 'app/main/survey/addQuestion/dialog/filesDialog.html',
        controller: function DialogController($scope, $mdDialog, api) {
            api.getallFiles.filesList.get({}, function(res){
                $scope.listFiles = res.response;
            });
            $scope.insertSlug = function(slug){
                var text = $('.currentSelection').val();
                $('.currentSelection').val(text+' ['+slug+']');
                $mdDialog.hide();
            }
            $scope.closeDialog = function() {
                $mdDialog.hide();
            }
        }
    });
});
$('body').on('click','.optionKey', function(){
    $('.currentSelection').removeClass('currentSelection');
     $(this).addClass('currentSelection');
});
$('body').on('click','.fieldDescription', function(){
    $('.currentSelection').removeClass('currentSelection');
    $(this).addClass('currentSelection');
});
$('body').on('click','.delKeyVal', function(){
    $(this).parents('.keyValMinus').remove(); 
});
(function ()
{
    'use strict';

    config.$inject = ["$stateProvider"];
    angular
        .module('app.survey.addQuestion', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider )
    {
        $stateProvider.state('app.survey_addQuestion', {
            url    : '/survey/addQuestion/:id',
            views  : {

                'content@app': {
                    templateUrl: 'app/main/survey/addQuestion/addQuestion.html',
                    controller : 'AddQuestionSurveyController as vm'
                }
            }
        });

     

     

    }

})();

	(function ()
{
    'use strict';

    EditSurveyController.$inject = ["$scope", "api", "$state", "$mdToast", "$mdDialog"];
    angular
        .module('app.survey.addQuestion')
        .controller('EditSurveyController', EditSurveyController);

    /** @ngInject */
    function EditSurveyController($scope,api,$state,$mdToast,$mdDialog)
    {		$scope.users_list = false;
		    var dt = new Date;
		    $scope.maxdt = (dt.getFullYear()+2)+'-'+(dt.getMonth()+1)+'-'+dt.getDay();
		    $scope.mindt = (dt.getFullYear()-2)+'-'+(dt.getMonth()+1)+'-'+dt.getDay();	

    		api.roles.list.get({},function(res){
		    	$scope.rolesList = res.roles;
		    });
		    api.listuser.list.get({}, function(res){
		    	$scope.usersList = res.user_list;
		    });
		    $scope.update_survey = function(){
		    	var SendData = new FormData();
		    	var settingsArray = {};
    			// settingsArray['enableDisable'] = $scope.enableDisable;
    			settingsArray['authentication_required']    		= ($scope.authReq == undefined)?false:$scope.authReq;
    			settingsArray['authentication_type']    			= ($scope.authType == undefined)?false:$scope.authType;
    			settingsArray['authorized_users']    				= ($scope.users == undefined)?false:$scope.users;
    			settingsArray['authorized_roles']    				= ($scope.roles == undefined)?false:$scope.roles;
    			settingsArray['survey_scheduling_status']    		= ($scope.scheduling == undefined)?false:$scope.scheduling;
				settingsArray['survey_start_date']    				= ($scope.startDate == undefined)?false:$scope.startDate;
				settingsArray['survey_expiry_date']    				= ($scope.expiryDate == undefined)?false:$scope.expiryDate;
    			settingsArray['survey_timer_status']    			= ($scope.surveyTimer == undefined)?false:$scope.surveyTimer;
    			settingsArray['survey_timer_type']    				= ($scope.timerType == undefined)?false:$scope.timerType;
    			settingsArray['survey_duration']    				= ($scope.duration == undefined)?false:$scope.duration;
    			settingsArray['survey_respone_limit_status']    	= ($scope.responeLimit == undefined)?false:$scope.responeLimit;
    			settingsArray['survey_response_limit_value']    	= ($scope.responseLimitValue == undefined)?false:$scope.responseLimitValue;
    			settingsArray['survey_response_limit_type']    		= ($scope.responseLimitType == undefined)?false:$scope.responseLimitType;
    			settingsArray['survey_custom_error_message_status'] = ($scope.displayCustomMessage == undefined)?false:$scope.displayCustomMessage;
    			settingsArray['survey_custom_error_messages_list']  = ($scope.customMesg == undefined)?false:$scope.customMesg;
    			settingsArray['customCss'] = $scope.customCss;
              	settingsArray['customJs'] = $scope.customJS;
              	
              	settingsArray['groupDescription']                  = ($scope.groupDescription == undefined)?false:$scope.groupDescription;
              	settingsArray['showGroupTitle']                    = ($scope.showGroupTitle == undefined)?false:$scope.showGroupTitle;
              	settingsArray['surveyDescription']                 = ($scope.surveyDescription == undefined)?false:$scope.surveyDescription;
              	settingsArray['surveyTitle']                       = ($scope.surveyTitle == undefined)?false:$scope.surveyTitle;
              	settingsArray['surveyThemes']                      = ($scope.surveyThemes == undefined)?false:$scope.surveyThemes;
              	settingsArray['showProgressbar']                   = ($scope.showProgressbar == undefined)?false:$scope.showProgressbar;
              	settingsArray['showNavigation']                    = ($scope.showNavigation == undefined)?false:$scope.showNavigation;
              	settingsArray['questionPlacement']                 = $scope.questionPlacement;
              	settingsArray['labelPlacement']                    = $scope.labelPlacement;
              	settingsArray['surveyViewType']                    = $scope.surveyViewType;

	        	var name = $scope.surveyDataForEdit.response.name;		
          		var description = $scope.surveyDataForEdit.response.description;
	        	var enableDisable = $scope.enableDisable;
	          	SendData.append('name',name);
          		SendData.append('description',description);
	        	SendData.append('enableDisable',enableDisable);
	        	SendData.append('settings',JSON.stringify(settingsArray));
	        	SendData.append('id',$state.params.id);
		    	api.postMethod.updateSurvey(SendData).then(function(res){
		    		$mdToast.show(
		               $mdToast.simple()
						.textContent('Survey Updated Successfully!')
		                .position('top right')
		                .hideDelay(3000)
		            );
		            $scope.isLoading = false;

		    		
		    	});
		    }
		    $scope.AddCode = function(event) {
	           $mdDialog.show({
	                clickOutsideToClose: true,
	                scope: $scope,        
	                preserveScope: true,           
	                templateUrl: 'app/main/survey/add-code.html',
	                controller: ["$scope", "$mdDialog", function DialogController($scope, $mdDialog) {
	                    $scope.closeDialog = function() {
	                        $mdDialog.hide();
	                    }
	                    
	                    $scope.saveEmbedSettings = function(){
	                        $mdDialog.hide();
	                    }
	                
	                }]
	            });
	        };
        	api.survey.getThemes.get({},function(res){
		          $scope.themes = res.themes;
		      });

       api.survey.surveyEditList.get({'id':$state.params.id},function(res){
            	// console.log(res.response.survey_custom_error_messages_list);
            	try{
            		$scope.surveyDataForEdit = res;
	                $scope.id = res.response;
	          		$scope.enableDisable 			= (res.response.status == "1")?true:false;
	      			$scope.authReq 					= (res.response.authentication_required == "1")?true:false;
	      			$scope.authType 				= res.response.authentication_type;
	      			$scope.users 					= JSON.parse(res.response.authorized_users);
	      			$scope.roles 					= JSON.parse(res.response.authorized_roles);
	      			$scope.scheduling 				= (res.response.survey_scheduling_status == "1")?true:false;
	      			$scope.startDate 				= (res.response.survey_start_date == "0")?'':res.response.survey_start_date;
	      			$scope.expiryDate 				= (res.response.survey_expiry_date == "0")?'':res.response.survey_expiry_date;
	      			$scope.surveyTimer 				= (res.response.survey_timer_status == "1")?true:false;
	      			$scope.timerType 				= res.response.survey_timer_type;
	      			$scope.duration 				= res.response.survey_duration;
	      			$scope.responeLimit 			= (res.response.survey_respone_limit_status == "1")?true:false;
	      			$scope.responseLimitValue 		= res.response.survey_response_limit_value;
	      			$scope.responseLimitType 		= res.response.survey_response_limit_type;

	      			$scope.groupDescription   		= (res.response.groupDescription == 1)?true:false;
              		$scope.showGroupTitle     		= (res.response.showGroupTitle == 1)?true:false;
              		$scope.surveyDescription  		= (res.response.surveyDescription == 1)?true:false;
              		$scope.surveyTitle        		= (res.response.surveyTitle == 1)?true:false;
              		$scope.surveyThemes       		= res.response.surveyThemes;
              		$scope.questionPlacement        = res.response.questionPlacement;
              		$scope.labelPlacement           = res.response.labelPlacement;
              		$scope.showProgressbar          = (res.response.showProgressbar == 1)?true:false;
	              	$scope.showNavigation           = (res.response.showNavigation == 1)?true:false;
	              	$scope.questionPlacement        = res.response.questionPlacement;
	              	$scope.surveyViewType           = res.response.surveyViewType;

	      			$scope.displayCustomMessage 	= (res.response.survey_custom_error_message_status == "1")?true:false;
	      			$scope.customMesg = (res.response.survey_custom_error_messages_list == undefined)?null:JSON.parse(res.response.survey_custom_error_messages_list);
	      			$scope.customJS = res.response.customJs;
	      			$scope.customCss = res.response.customCss;
				 	if($scope.authReq == true){
		      			$scope.authtype = true;
		            if($scope.authType == "role"){
		                $scope.role_list = true;
		                $scope.users_list = false;
		            }else{
		                $scope.role_list = false;
		                $scope.users_list = true;
		            }
		      		}else{
		      			$scope.authtype = false;
		            $scope.users_list = false;
		            $scope.role_list = false;
		      		}
		      		if($scope.surveyTimer == true){
		      			$scope.surveyTimerShow = true;
		            $scope.surveyDuration = true;
		      		}else{
		      			$scope.surveyTimerShow = false;
		            $scope.surveyDuration = false;
		      		}
		      		if($scope.displayCustomMessage == true){
		      			$scope.customMess = true;
		      		}else{
		      			$scope.customMess = false;
		      		}
		      		if($scope.responeLimit == true){
		      			$scope.surevyResponseLimit = true;
		      		}else{
		      			$scope.surevyResponseLimit = false;
		      		}
		      		if($scope.timerType == 'duration'){
		      			$scope.surveyDuration = true;
		      		}else{
		      			$scope.surveyDuration = false;
		      		}
		      		if($scope.scheduling == true){
		                $scope.surveyDates = true;
		            }else{
		                $scope.surveyDates = false;
		            }
		            if($scope.authType == 'role'){
			            if($scope.authReq == true){
			        			$scope.role_list = true;
			            }else{
			              $scope.role_list = false;
			            }
		      			$scope.users_list = false;
			      	}else{
			            if($scope.authReq == true){
			              $scope.users_list = true;
			            }else{
			              $scope.users_list = false;
			            }
			            $scope.role_list = false;
		      		}
            	}catch(e){

            	}
            	$scope.checkAuthEnable = function(){

		      		if($scope.authReq == true){
		      			$scope.authtype = true;
		            if($scope.authType == "role"){
		                $scope.role_list = true;
		                $scope.users_list = false;
		            }else{
		                $scope.role_list = false;
		                $scope.users_list = true;
		            }
		      		}else{
		      			$scope.authtype = false;
		            $scope.users_list = false;
		            $scope.role_list = false;
		      		}
		      	}
		      	$scope.checkSurveyTimer = function(){
		      		if($scope.surveyTimer == true){
		      			$scope.surveyTimerShow = true;
		            $scope.surveyDuration = true;
		      		}else{
		      			$scope.surveyTimerShow = false;
		            $scope.surveyDuration = false;
		      		}
		      	}

		      	$scope.displayCustomMess = function(){
		      		if($scope.displayCustomMessage == true){
		      			$scope.customMess = true;
		      		}else{
		      			$scope.customMess = false;
		      		}
		      	}

		      	$scope.enableResponseLimit = function(){
		      		if($scope.responeLimit == true){
		      			$scope.surevyResponseLimit = true;
		      		}else{
		      			$scope.surevyResponseLimit = false;
		      		}
		      	}

		      	$scope.checkTimerType = function(){
		      		if($scope.timerType == 'duration'){
		      			$scope.surveyDuration = true;
		      		}else{
		      			$scope.surveyDuration = false;
		      		}
		      	}

		        $scope.surveyScheduling = function(){

		            if($scope.scheduling == true){
		                $scope.surveyDates = true;
		            }else{
		                $scope.surveyDates = false;
		            }
		        }

		      	$scope.chechAuthType = function(){

		      		if($scope.authType == 'role'){
			            if($scope.authReq == true){
			        			$scope.role_list = true;
			            }else{
			              $scope.role_list = false;
			            }
		      			$scope.users_list = false;
			      	}else{
			            if($scope.authReq == true){
			              $scope.users_list = true;
			            }else{
			              $scope.users_list = false;
			            }
			            $scope.role_list = false;
		      		}
		      	}
            });  
    }
    //acordion 
    $('body').on('click','.accrodian', function(){
         var elm = $(this).parents('.expanded').find('.survey_set');
         if(elm.hasClass('expanded')){
          $('.accrodian').css('transform','rotate(0deg)');
            elm.slideUp();
            elm.removeClass('expanded');
         }else{
            elm.slideDown();
            elm.addClass('expanded');
          $('.accrodian').css('transform','rotate(180deg)');
          
         }
     });
     $('body').on('click','.accrodian-1', function(){
         var elm = $(this).parents('.expanded-1').find('.survey_set');
         if(elm.hasClass('expanded-1')){
          $('.accrodian-1').css('transform','rotate(0deg)');
            elm.slideUp();
            elm.removeClass('expanded-1');
         }else{
            elm.slideDown();
            elm.addClass('expanded-1');
          $('.accrodian-1').css('transform','rotate(180deg)');
          
         }
     });
})();

(function ()
{
    'use strict';

    AddQuestionSurveyController.$inject = ["$scope", "$templateCache", "$compile", "$state", "api", "$mdToast", "$mdDialog"];
    angular
        .module('app.survey.addQuestion')
        .controller('AddQuestionSurveyController', AddQuestionSurveyController);

    /** @ngInject */
    function AddQuestionSurveyController($scope, $templateCache, $compile, $state, api, $mdToast,$mdDialog)
    {    
        // $scope.htmlVariable = [
        //                 ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'pre', 'quote'],
        //                 ['bold', 'italics', 'underline', 'strikeThrough', 'ul', 'ol', 'redo', 'undo', 'clear'],
        //                 ['justifyLeft','justifyCenter','justifyRight','justifyFull','indent','outdent'],
        //                 ['html', 'insertImage', 'insertLink', 'insertVideo', 'wordcount', 'charcount']
        //             ],
        window.sortedArray = [];
       /* $('body').on('click','.add_media',function(){
            var elm = $(this).parents('.questDiv');
            $mdDialog.show({
                clickOutsideToClose: true,
                scope: $scope,        
                preserveScope: true,           
                templateUrl: 'app/main/survey/addQuestion/dialog/filesDialog.html',
                controller: function DialogController($scope, $mdDialog, api) {
                    api.getallFiles.filesList.get({}, function(res){
                        $scope.listFiles = res.response;
                        
                    });
                    $scope.insertSlug = function(slug){
                        var text = $('.currentSelection').val();
                        $('.currentSelection').val(text+' ['+slug+']');
                        $mdDialog.hide();
                    }
                    $scope.closeDialog = function() {
                        $mdDialog.hide();
                    }
                }
            });
        });*/
        $scope.getIcon = function(type){
            var icon_class = {};
            if(type == 'jpg' || type == 'jpeg'){
               
                return 'icon-file-image';
            }
            if(type == 'mp3'){
                
                return 'icon-file-music';
            }
            if(type == 'wav'){
               
                return 'icon-music-note-eighth';
            }
            if(type == 'png'){
                
                return 'icon-image-area';
            }
           
            return 'icon-image-broken';
        }

        $scope.getClass = function(type){
            var icon_class = {};
            if(type == 'jpg' || type == 'jpeg'){
                
                return 'red-fg';
            }
            if(type == 'mp3'){
               
                return 'green-fg';
            }
            if(type == 'wav'){
               
                return '';
            }
            if(type == 'png'){
                
                return 'cyan-fg';
            }
            return 'red-fg';
        }
        
        /*$scope.menuOptions = [
            ['<md-icon md-font-icon="icon-pencil"></md-icon><label>Select</label>', function ($itemScope, $event, modelValue, text, $li) {
                
                //$scope.selected = $itemScope.item.name;
            }],
            null, // Dividier
            ['Remove', function ($itemScope, $event, modelValue, text, $li) {
                $scope.items.splice($itemScope.$index, 1);
            }]
        ];*/
        $scope.uploadFile = function(fileData){
            var formData = new FormData();
            $scope.showLoading = true;
            formData.append('file',fileData.target.files[0]);
            api.postMethod.uploadFile(formData, $scope).then(function(res){
                api.getallFiles.filesList.get({}, function(res){
                    $scope.listFiles = res.response;
                });
                $scope.showLoading = false;
                $scope.completeUpload = true;
                setTimeout(function(){
                    $scope.completeUpload = false;
                },5000)
            });
        }
        $scope.insertSlug = function(slug){

        }
        window.compile = $compile;
        window.scope = $scope;
        $scope.surveyID = $state.params.id;
        window.groupHTML = '';
        window.$state = $state;
        $.get('app/main/survey/addQuestion/html/group.html', function(data) {
            groupHTML = data;
        });
        window.questHTML = '';
        $.get('app/main/survey/addQuestion/html/question.html', function(data){
            questHTML = data;
        });
        window.keyValHTML = '';
        $.get('app/main/survey/addQuestion/html/key_value.html', function(data){
            keyValHTML = data;
        });
        $('.question-div').sortable({
            handle: '.quest-count',
        });
        api.editQuestions.questions.get({'id':$state.params.id}, function(res){
            console.log(res);
            $scope.id = res.response;
            $scope.responceData = res.response;

            window.quesid = 1;
            
                // for group div
                $('.survey_group_error').addClass("none");

                $(document).on('click','.delete-group',function(){
                    if($(this).parents('.group-div').find('.groupFrameDiv').length == 1){
                        $('.survey_group_error').show('2000');
                        // alert('is 1');
                    }
                });
                $(document).on('click','.add-group',function(){
                   $(this).parents('.layout-align-start-start').find('.survey_group_error').hide();
                });

                // for question div
                $('.survey_question_error').addClass("none");
                if(res.response.group.group_questions != "" || res.response.group.group_questions != null || res.response.group.group_questions != undefined){
                    $('.survey_question_error').hide();
                }
                $(document).on('click','.delete-question',function(){
                    if($(this).parents('.question-div').find('.questDiv').length == 1){
                        $(this).parents('.group-frame').find('.survey_question_error').show();
                        // alert("yuhooo is 1");
                    }else{
                        $(this).parents('.group-frame').find('.survey_question_error').hide();
                        // alert("oops not 1");
                    }
                });
                $(document).on('click','.add-question',function(){
                   $(this).parents('.group-frame').find('.survey_question_error').hide();
                });



            $.each(res.response.group, function(key, val){
               

                if(key == 0){
                    var elm = $('.groupFrameDiv');
                    elm.attr('id',val.group_order);
                    elm.attr('group-id',val.group_id);
                    elm.find('.groupName').val(val.group_name).change();
                    elm.find('.group_title').text(val.group_name);
                    elm.find('.groupDescription').val(val.group_description);
                    elm.find('.countNumber').html(key+1);
                    elm.find('.sectionId').val(val.group_id);
                    elm.find('.add_fields').attr('data-id',val.group_id);

                  /*  console.log(val.group_questions);*/
                    if(val.group_questions != "" || val.group_questions != null || val.group_questions != undefined){
                        $('.survey_question_error').hide();
                    }
                    $(document).on('click','.delete-question',function(){
                        if($(this).parents('.question-div').find('.questDiv').length == 1){
                            $(this).parents('.group-frame').find('.survey_question_error').show();
                        }else{
                            $(this).parents('.group-frame').find('.survey_question_error').hide();
                        }
                    });
                    $(document).on('click','.add-question',function(){
                       $(this).parents('.group-frame').find('.survey_question_error').hide();
                    });
                    if(val.group_questions != undefined){
                        $('.fields_count:first').html(val.group_questions.length);
                    }else{
                        $('.fields_count:first').html(0);
                    }
                }else{

                    $('.group-div').append($compile(groupHTML)($scope));
                    var elm = $('.groupFrameDiv:last');
                    elm.attr('id',val.group_order);
                    elm.attr('group-id',val.group_id);
                    var groupLength = $('.groupFrameDiv').length;
                    $('.groupFrameDiv:last').attr('data-number',groupLength);
                    elm.find('.groupName:last').val(val.group_name).change();
                    elm.find('.group_title:last').text(val.group_name);
                    elm.find('.groupDescription:last').val(val.group_description);
                    elm.find('.countNumber').html(key+1);
                    elm.find('.sectionId:last').val(val.group_id);
                    elm.find('.add_fields:last').attr('data-id',val.group_id);
                    if(val.group_questions != undefined){
                        $('.fields_count:last').html(val.group_questions.length);
                    }else{
                        $('.fields_count:last').html(0);
                    }
                }
                $('.question-div').sortable({
                    handle: '.quest-count'
                });
            });
        });
        $(document).on('click','.add_fields', function(){
            var group_id = $(this).attr('data-id');
            var survey_id = $state.params.id;
            $state.go('app.survey_fields',{survey_id: survey_id, group_id: group_id});
        });
        $scope.rand = Math.random();
        /*$scope.saveSurvey = function(survey){
            var index = 1;
            var groupsData = {};
            $('.groupFrameDiv').each(function(){
                var groupName = $(this).find('.groupName').val();
                var groupDesc = $(this).find('.groupDescription').val();
                var questData = {};
                var questId = '';
                $(this).find('.questDiv').each(function(){
                    var questType = $(this).find('.questType').val();
                    questId = $(this).find('.questID').html();
                    var tempQuestData = {};
                    var pattern = $(this).find('.pattern').val();
                    var otherPattern = '';

                    if(pattern == 'others'){
                        otherPattern = $(this).find('.othetPattern').val();
                    }
                    var optionsList = [];
                    if(questType == 'checkbox' || questType == 'radio' || questType == 'dropdown'){
                        $(this).find('.extraOptions').each(function(){
                                var extraOptions = {};
                                extraOptions['options'] = {};
                                extraOptions['options']['label'] = $(this).find('.optionKey').val();
                                extraOptions['options']['value'] = $(this).find('.optionValue').val();
                                extraOptions['options']['condition'] = $(this).find('.Condition').val();
                                optionsList.push(extraOptions);
                        });
                    }
                    tempQuestData['question_id']        = questId;
                    tempQuestData['question_type']      = questType;
                    tempQuestData['question']           = $(this).find('.questTitle').val(); 
                    tempQuestData['question_desc']      = $(this).find('.questDescription').val();
                    tempQuestData['question_key']       = $(this).find('.questKey').val();
                    tempQuestData['next_question']      = $(this).find('.nextQuest').val();
                    tempQuestData['pattern']            = pattern;
                    tempQuestData['otherPattern']       = otherPattern;  
                    tempQuestData['extraOptions']       = optionsList;
                    tempQuestData['required']           = $(this).find('.required').val();
                    questData[questId] = tempQuestData;
                });
                var dataTemp = {}
                dataTemp['group_name'] = groupName;
                dataTemp['group_description'] = groupDesc;
                dataTemp['group_questions'] = questData;
                groupsData['group_'+index] = dataTemp;
                index++;
            });
            var formData = new FormData();
            formData.append('survey_data',JSON.stringify(groupsData));
            formData.append('survey_id',$state.params.id);
            $scope.isLoading = true;
            api.postMethod.saveSurveyQuest(formData).then(function(result){
                $scope.isLoading = false;
                $mdToast.show(
                    $mdToast.simple()
                    .textContent('Survey saved Successfully!')
                    .position('top right')
                    .hideDelay(5000)
                );
                $state.go('app.survey_list');
            });
        }*/

        $scope.saveSurvey = function(survey){
            var index = 1;
            var groupsData = {};
            $('.groupFrameDiv').each(function(){
                var groupName = $(this).find('.groupName').val();
                var groupDesc = $(this).find('.groupDescription').val();
                var groupId = $(this).find('.sectionId').val();
                var dataTemp = {}
                dataTemp['group_name'] = groupName;
                dataTemp['group_description'] = groupDesc;
                dataTemp['group_id'] = groupId;
                groupsData['group_'+index] = dataTemp;
                index++;
            });
            var formData = new FormData();
            formData.append('survey_data',JSON.stringify(groupsData));
            formData.append('survey_id',$state.params.id);
            $scope.isLoading = true;
            api.postMethod.saveSection(formData).then(function(result){
                $scope.isLoading = false;
                $mdToast.show(
                    $mdToast.simple()
                    .textContent('Sections saved Successfully!')
                    .position('top right')
                    .hideDelay(5000)
                );
                $state.go($state.current, {}, {reload: true});
            });
        }
        

        $('body').on('click','.delKeyVal', function(){
            $(this).parents('.keyValMinus').remove(); 
        });
        if(!$('.groupFrameDiv').hasClass('expanded')){
            $('.group-div').removeClass('ui-sortable');
            $('.group-div').sortable({
                handle: '.countNumber',
                stop : function(event, ui){
                  var sortArray = $(this).sortable('toArray');
                  window.sortedArray = sortArray.clean("");
                }
            });
        }
        
        Array.prototype.clean = function(deleteValue) {
          for (var i = 0; i < this.length; i++) {
            if (this[i] == deleteValue) {         
              this.splice(i, 1);
              i--;
            }
          }
          return this;
        };
        $('body').on('click','.delete-group', function(){
            var groupFrame = $(this).parents('.group-frame');
            groupFrame.animate({
                'margin-left':'40%',
                'opacity':'0.5'
            },200, function(){
                groupFrame.remove();
                $('.countNumber').each(function(i){
                    $(this).html(i+1); 
                });
            });
        });

        $('body').on('click', '.delete-question', function(){
            var reorder = $(this).parent('div').parent('div').parent('.group-frame').parent('.question-div');
            var elem = $(this).parent('div').parent('div').parent('.group-frame'); 
            elem.animate({
                'margin-left':'40%',
                'opacity':'0.5'
            },200, function(){
                elem.remove();
                reorder.find('.quest-count').each(function(i){
                    $(this).html(i+1); 
                });
            });
        });

        //hide show


        $('body').on('change','.questType', function(){
            if($(this).val() == "text"){
                $('.addQuestion_right_section > div > div , .div-two').hide();
            }
           if($(this).val() == "checkbox" || $(this).val() == "radio" || $(this).val() == "dropdown"){
                $(this).parent().parent().parent().find('.addQuestion_right_section').find('#div-one').show();
                $('.div-two').hide();
           }
           
           if($(this).val() == "text_only"){
                $('.addQuestion_right_section > div > div , .div-two').hide();
           }
           if($(this).val() == "text_with_image"){
            $('.addQuestion_right_section > div > div').hide();
                $('.div-two').show();
           }

        });

        // $scope.other_pattern = false;
        $(document).on('change','.pattern',function(){
            
            if($(this).val() == 'others'){
                $(this).parents('.main-row').find('.patternChange').fadeIn();
            }else{
                $(this).parents('.main-row').find('.patternChange').fadeOut();
            }
        });
    }
})();

$('body').on('click','.add-group', function(){
    var total = $('.countNumber').length;
    $('.group-div').append(compile(groupHTML)(scope));
    $('.add_fields:last').remove();
    var elm = $('.groupFrameDiv:last');
    elm.attr('id',$('.groupFrameDiv').length);

    $('.countNumber:last').html(parseInt(total+1));
    scope.rand = Math.random();
    var groupLength = $('.groupFrameDiv').length;
    quesid++;
    var questLength = quesid;
    $('.groupFrameDiv:last').attr('data-number',groupLength);
    $('.questID:last').html('SID'+$state.params.id+'_GID'+groupLength+'_QID'+questLength);
    $('.question-div').sortable({
        handle: '.quest-count'
    });
});


$('body').on('click','.add-question', function(){
    var elem = $(this).parent('div').parent('.group-frame').find('.quest-count');
    var total = elem.length;
    $(this).parent('div').parent('.group-frame').find('.question-div').append(compile(questHTML)(scope));
    $(this).parent('div').parent('.group-frame').find('.quest-count:last').html(parseInt(total+1));
    quesid++;
    var questLength = quesid;
    var groupNumber = $(this).parent('div').parents('.group-frame').attr('data-number');
    $(this).parent('div').parent('.group-frame').find('.questID:last').html('SID'+$state.params.id+'_GID'+groupNumber+'_QID'+parseInt(questLength));
    $('.question-div').sortable({
        handle: '.quest-count'
    });
    $(this).off('click');
});
$('body').on('click','.addKeys', function(){
    $(this).parents('.keyValue').prepend(window.compile(keyValHTML)(window.scope));
});

$('body').on('click','.accrodian', function(){
    var elem = $(this);
    var groupFrame = $(this).parent('div').parent('div').parent('.group-frame');
    if(groupFrame.hasClass('expanded')){
        groupFrame.animate({
            'height': '50px'
        },300);
        groupFrame.removeClass('expanded');
        elem.css('transform','rotate(0deg)');
    }else{
        groupFrame.addClass('expanded');
        groupFrame.animate({
            'height': $(this).get(0).scrollHeight + 17
        },200, function(){
            $(this).height('auto');
            elem.css('transform','rotate(180deg)');
        });
    }
    
});
$('body').on('click','.upload-button',function(){
   $('.upload-file').click(); 
});
$('body').on('click','.optionKey', function(){
    $('.currentSelection').removeClass('currentSelection');
     $(this).addClass('currentSelection');
});
$('body').on('click','.questDescription', function(){
    $('.currentSelection').removeClass('currentSelection');
    $(this).addClass('currentSelection');
});
$('body').on('click','.icon-arrow img',function(){
    if(!$(this).parents('.group-frame').hasClass('expended')){
        $(this).parents('.group-frame').addClass('expended');
        $(this).parents('.group-frame').css({"display":"block" , 'height':"auto"});
        $(this).css("transform", "rotate(180deg)");
    }else{
        $(this).parents('.group-frame').removeClass('expended');
        $(this).parents('.group-frame').css({"display":"block" , 'height':"50px"});
        $(this).css("transform", "rotate(360deg)");
    }
});
$('body').on('click','.qstn-div img',function(){
    if(!$(this).parents('.questDiv').hasClass('expended')){
        $(this).parents('.questDiv').addClass('expended');
        $(this).parents('.questDiv').css({"display":"block" , 'height':"auto"});
        $(this).css("transform", "rotate(180deg)");
    }else{
        $(this).parents('.questDiv').removeClass('expended');
        $(this).parents('.questDiv').css({"display":"block" , 'height':"50px"});
        $(this).css("transform", "rotate(360deg)");
    }
});
 $(document).on('keyup','.questTitle',function(){
    var value = $(this).val();
    $(this).parents('.questDiv').find('.qust_title').html(value);
});

/*$(document).bind("contextmenu", function (event) {
    
    // Avoid the real one
    event.preventDefault();
    
    // Show contextmenu
    $(".custom-menu").finish().toggle(100).
    
    // In the right position (the mouse)
    css({
        top: (event.pageY-60) + "px",
        left: (event.pageX-200) + "px",
        position: 'absolute',
        'overflow-y': 'hidden'
    });
});


// If the document is clicked somewhere
$(document).bind("mousedown", function (e) {
    
    // If the clicked element is not the menu
    if (!$(e.target).parents(".custom-menu").length > 0) {
        
        // Hide it
        $(".custom-menu").hide(100);
    }
});


// If the menu element is clicked
$(".custom-menu li").click(function(){
    
    // This is the triggered action name
    switch($(this).attr("data-action")) {
        
        // A case for each action. Your actions here
        case "first": alert("first"); break;
        case "second": alert("second"); break;
        case "third": alert("third"); break;
    }

    // Hide it AFTER the action was triggered
    $(".custom-menu").hide(100);
});*/
$('.survey_question_error').hide();
(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "msApiProvider"];
    angular
        .module('app.survey.add', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider ,msApiProvider)
    {
        $stateProvider.state('app.survey_add', {
            url    : '/survey/add',
            views  : {

                'content@app': {
                    templateUrl: 'app/main/survey/add/add.html',
                    controller : 'AddSurveyController as vm'
                }
            }
        });

     

     

    }

})();

(function ()
{
    'use strict';

    AddSurveyController.$inject = ["$scope", "$http", "api", "$mdToast", "$state", "$mdDialog"];
    angular
        .module('app.survey.add')
        .controller('AddSurveyController', AddSurveyController);

    /** @ngInject */
    function AddSurveyController($scope,$http,api,$mdToast,$state,$mdDialog)
    {
      $scope.users_list = false;
      var dt = new Date;
      $scope.maxdt = (dt.getFullYear()+2)+'-'+(dt.getMonth()+1)+'-'+dt.getDay();
      $scope.mindt = (dt.getFullYear()-2)+'-'+(dt.getMonth()+1)+'-'+dt.getDay();
    	$scope.customMesg = {
       /* survey_status           : "You need to be logged in to access the survey.",*/
        survey_status           : "Survey is disabled.",
        survey_auth_required    : "Your user role do not have permissions access the survey.",
        survey_unauth_role      : "Your user role do not have permissions access the survey.", 
        survey_unauth_user      : "You do not have permissions to access the survey.", 
        invalid_survey_id       : "Invalid survey ID.", 
        empty_survey            : "Empty survey.", 
        survey_not_started      : "Survey not started yet.",
        survey_expired          : "Survey is expired.",
        responce_limit_exceeded : "Responce limit exceeded."
      };
      $scope.groupDescription = true;
      $scope.showGroupTitle = true;
      $scope.surveyDescription = true;
      $scope.surveyTitle = true;
      $scope.surveyThemes = 'minimal';
      api.survey.getThemes.get({},function(res){
          $scope.themes = res.themes;
          console.log(res);
      });
    	$scope.enableDisable = true;
		  $scope.create_survey = function(){
          
    			var settingsArray = {};
    			// settingsArray['enableDisable'] = $scope.enableDisable;
        			settingsArray['authentication_required']           = ($scope.authReq == undefined)?false:$scope.authReq;
        			settingsArray['authentication_type']               = ($scope.authType == undefined)?false:$scope.authType;
        			settingsArray['authorized_users']                  = ($scope.users == undefined)?false:$scope.users;
        			settingsArray['authorized_roles']                  = ($scope.roles == undefined)?false:$scope.roles;
        			settingsArray['survey_scheduling_status']          = ($scope.scheduling == undefined)?false:$scope.scheduling;
              settingsArray['survey_start_date']                 = ($scope.startDate == undefined)?false:$scope.startDate;
              settingsArray['survey_expiry_date']                = ($scope.expiryDate == undefined)?false:$scope.expiryDate;
              settingsArray['survey_timer_status']               = ($scope.surveyTimer == undefined)?false:$scope.surveyTimer;
        			settingsArray['survey_timer_type']                 = ($scope.timerType == undefined)?false:$scope.timerType;
        			settingsArray['survey_duration']                   = ($scope.duration == undefined)?false:$scope.duration;
        			settingsArray['survey_respone_limit_status']       = ($scope.responeLimit == undefined)?false:$scope.responeLimit;
        			settingsArray['survey_response_limit_value']       = ($scope.responseLimitValue == undefined)?false:$scope.responseLimitValue;
        			settingsArray['survey_response_limit_type']        = ($scope.responseLimitType == undefined)?false:$scope.responseLimitType;
        			settingsArray['survey_custom_error_message_status']= ($scope.displayCustomMessage == undefined)?false:$scope.displayCustomMessage;
        			settingsArray['survey_custom_error_messages_list'] = ($scope.customMesg == undefined)?false:$scope.customMesg;
              settingsArray['customCss']                         = $scope.customCss;
              settingsArray['customJs']                          = $scope.customJS;
              settingsArray['groupDescription']                  = ($scope.groupDescription == undefined)?false:$scope.groupDescription;
              settingsArray['showGroupTitle']                    = ($scope.showGroupTitle == undefined)?false:$scope.showGroupTitle;
              settingsArray['surveyDescription']                 = ($scope.surveyDescription == undefined)?false:$scope.surveyDescription;
              settingsArray['surveyTitle']                       = ($scope.surveyTitle == undefined)?false:$scope.surveyTitle;
              settingsArray['surveyThemes']                      = ($scope.surveyThemes == undefined)?false:$scope.surveyThemes;
              settingsArray['labelPlacement']                    = $scope.labelPlacement;
              settingsArray['surveyViewType']                    = $scope.surveyViewType;
              settingsArray['showProgressbar']                   = ($scope.showProgressbar == undefined)?false:$scope.showProgressbar;
              settingsArray['showNavigation']                    = ($scope.showNavigation == undefined)?false:$scope.showNavigation;

	        var name = $scope.survey.title;		
          var description = $scope.survey.des;
	        var enableDisable = $scope.enableDisable;
	         
	        $scope.isLoading = true;
	        $http.defaults.headers.post['Content-Type'] = undefined;
	        var SendData = new FormData();
	        SendData.append('name',name);
          SendData.append('description',description);
          SendData.append('enableDisable',enableDisable);
        

	        SendData.append('settings',JSON.stringify(settingsArray));
	        api.postMethod.saveNewSurvey(SendData).then(function(res){
            $scope.surv_id = res.data.survey_id;
            $state.go('app.survey_edit',{ 'id' : res.data.survey_id });
            

	          	$mdToast.show(
	               $mdToast.simple()
                  .textContent('Survey Saved Successfully!')
	                .position('top right')
	                .hideDelay(5000)
	            );

	            $scope.isLoading = false;
	        });
	    }
	    api.roles.list.get({},function(res){
	    	$scope.rolesList = res.roles;
	    });
	    api.listuser.list.get({}, function(res){
	    	$scope.usersList = res.user_list;
	    });

      $scope.AddCode = function(event) {
           $mdDialog.show({
                clickOutsideToClose: true,
                scope: $scope,        
                preserveScope: true,           
                templateUrl: 'app/main/survey/add-code.html',
                controller: ["$scope", "$mdDialog", function DialogController($scope, $mdDialog) {
                    $scope.closeDialog = function() {
                        $mdDialog.hide();
                    }
                    
                    $scope.saveEmbedSettings = function(){
                        $mdDialog.hide();
                    }
                
                }]
            });
        };

      	$scope.checkAuthEnable = function(){

      		if($scope.authReq == true){
      			$scope.authtype = true;
            if($scope.authType == "role"){
                $scope.role_list = true;
                $scope.users_list = false;
            }else{
                $scope.role_list = false;
                $scope.users_list = true;
            }
      		}else{
      			$scope.authtype = false;
            $scope.users_list = false;
            $scope.role_list = false;
      		}
      	}
      	$scope.checkSurveyTimer = function(){
      		if($scope.surveyTimer == true){
      			$scope.surveyTimerShow = true;
            $scope.surveyDuration = true;
      		}else{
      			$scope.surveyTimerShow = false;
            $scope.surveyDuration = false;
      		}
      	}

      	$scope.displayCustomMess = function(){
      		if($scope.displayCustomMessage == true){
      			$scope.customMess = true;
      		}else{
      			$scope.customMess = false;
      		}
      	}

      	$scope.enableResponseLimit = function(){
      		if($scope.responeLimit == true){
      			$scope.surevyResponseLimit = true;

      		}else{
      			$scope.surevyResponseLimit = false;
      		}
      	}

      	$scope.checkTimerType = function(){
      		if($scope.timerType == 'duration'){
      			$scope.surveyDuration = true;
      		}else{
      			$scope.surveyDuration = false;
      		}
      	}

        $scope.surveyScheduling = function(){

            if($scope.scheduling == true){
                $scope.surveyDates = true;
            }else{
                $scope.surveyDates = false;
            }
        }

      	$scope.chechAuthType = function(){

      		if($scope.authType == 'role'){
            if($scope.authReq == true){
        			$scope.role_list = true;
            }else{
              $scope.role_list = false;
            }
      			$scope.users_list = false;
      		}else{
            if($scope.authReq == true){
              $scope.users_list = true;
            }else{
              $scope.users_list = false;
            }
            $scope.role_list = false;
      		}
      	}
    }
})();
(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "msApiProvider", "msNavigationServiceProvider"];
    angular
        .module('app.login.profile', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider, msApiProvider, msNavigationServiceProvider)
    {
        $stateProvider.state('app.profile', {
            url      : '/profile',
            views    : {
                'content@app': {
                    templateUrl: 'app/main/login/profile/profile.html',
                    controller : 'ProfileController as vm'
                }
            },
         
        });

        // Translation
       /* $translatePartialLoaderProvider.addPart('app/main/pages/profile');

        // Api
        msApiProvider.register('profile.timeline', ['app/data/profile/timeline.json']);
        msApiProvider.register('profile.about', ['app/data/profile/about.json']);
        msApiProvider.register('profile.photosVideos', ['app/data/profile/photos-videos.json']);

        // Navigation
        msNavigationServiceProvider.saveItem('pages.profile', {
            title : 'Profile',
            icon  : 'icon-account',
            state : 'app.pages_profile',
            weight: 6
        });*/
    }

})();
(function ()
{
    'use strict';

    ProfileController.$inject = ["$scope", "api", "$state"];
    angular
        .module('app.login.profile')
        .controller('ProfileController', ProfileController);

    /** @ngInject */
    function ProfileController($scope, api, $state)
    {
        if(checkAuth($state) == false){
            return false;
        }
        var vm = this;
        api.profile.details.get({},function(res){
            
            console.log(res);
            $scope.userProfile = res.details;
          

        });
         api.organization.list.get({},function(res){
            
            
             $scope.orgs = res.records;
             
         });

        $scope.editProfile = function(){
            $state.go('app.edit-profile');
        }
    }

    function checkAuth($state){

        if(sessionStorage.api_token == undefined || sessionStorage.api_token == ''){

            $state.go('app.new-login');
            return false;
        }
    }

})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider"];
    angular
        .module('app.login.new-password', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider)
    {
        $stateProvider.state('app.new_password', {
            url      : '/newPassword/:token',
            views    : {
                'content@app': {
                    templateUrl: 'app/main/login/new-password/new-password.html',
                    controller : 'NewPasswordController as vm'
                }
            },
           /* resolve  : {
                Timeline    : function (msApi)
                {
                    return msApi.resolve('profile.timeline@get');
                },
                About       : function (msApi)
                {
                    return msApi.resolve('profile.about@get');
                },
                PhotosVideos: function (msApi)
                {
                    return msApi.resolve('profile.photosVideos@get');
                }
            },
            bodyClass: 'profile'*/
        });

        // Translation
       /* $translatePartialLoaderProvider.addPart('app/main/pages/profile');

        // Api
        msApiProvider.register('profile.timeline', ['app/data/profile/timeline.json']);
        msApiProvider.register('profile.about', ['app/data/profile/about.json']);
        msApiProvider.register('profile.photosVideos', ['app/data/profile/photos-videos.json']);

        // Navigation
        msNavigationServiceProvider.saveItem('pages.profile', {
            title : 'Profile',
            icon  : 'icon-account',
            state : 'app.pages_profile',
            weight: 6
        });*/
    }

})();
(function ()
{
    'use strict';

    NewPasswordController.$inject = ["$state", "$scope", "api"];
    angular
        .module('app.login.new-password')
        .controller('NewPasswordController', NewPasswordController);

    /** @ngInject */
    function NewPasswordController($state, $scope, api){

        var vm = this;
        $scope.isDisables = false;
        api.forgetPass.validate.get({token:$state.params.token},function(res){
            if(res.status == 'error'){
                $state.go('app.page',{slug:'access-denied'});
            }
        });

        $scope.proceed = function(){

            $scope.isLoading = true;
            $scope.isDisables = true;
            var formdata = new FormData();
            formdata.append('reset_token', $state.params.token);
            formdata.append('newpassword', vm.newPasswordForm.password);
            formdata.append('confpassword', vm.newPasswordForm.confpassword);
            api.postMethod.resetPass(formdata).then(function(res){
                $scope.isLoading = false;
                $scope.isDisables = false;
                if(res.data.status == 'success'){
                    $state.go('app.page',{slug:'changepass-success'})
                }else{
                    $scope.error_message = res.data.message;
                }
            });
        }
    }


})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider"];
    angular
        .module('app.login.new-login', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider)
    {
        $stateProvider.state('app.new-login', {
            url      : '/login',
            views    : {
                'content@app': {
                    templateUrl: 'app/main/login/new-login/new-login.html',
                    controller : 'NewLoginController as vm'
                }
            },
           
        });

    }

})();

(function ()
{
    'use strict';

    NewLoginController.$inject = ["api", "$http", "$scope", "$state", "$location"];
    angular
        .module('app.login.new-login')
        .controller('NewLoginController', NewLoginController);

    /** @ngInject */
    function NewLoginController(api, $http, $scope, $state, $location){
        /*if(checkLogined($state) == false){
            return false;
        }*/
        $scope.isLoading = false;
    	 if($state.current.name == 'app.logout'){
            sessionStorage.api_token = '';
            window.location.href= 'login';
          }
    	$scope.user_error = 'true';
    	var vm = this;
    	var SendData = new FormData();
    	$scope.userLogin = function(){
            $scope.isLoading = true;
            $scope.loginForm.$invalid = true;
    		SendData.append('email',vm.form.email);
    		SendData.append('password',vm.form.password);
	    	api.postMethod.userLogin(SendData).then(function(res){
                $scope.isLoading = false;
                $scope.loginForm.$invalid = false;
	    		if(res.data.status == 'error'){
	    			vm.user_error = 'false';
	    			$scope.error_user_login = res.data.message;
	    		}else{
                    sessionStorage.userName = res.data.user_detail.name;
                    sessionStorage.profile_pic = res.data.profile_pic;
	    			sessionStorage.api_token = res.data.user_detail.api_token;
                    window.location.href='dashboard';
	    			//$state.go('app.goal_list',{}, {reload: true});
	    		}
	    	});
    	}
    	 $scope.goHome = function(){
            $state.go('app.page',{'slug':'dashboard'});
        }

    }

    function checkLogined($state){
        if(sessionStorage.api_token != ''){

            $state.go('app.profile');
            return false;
        }
    }

})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider"];
    angular
        .module('app.login.forgot-password', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider)
    {
        $stateProvider.state('app.forgot_password', {
            url      : '/forgotpass',
            views    : {
                'content@app': {
                    templateUrl: 'app/main/login/forgot-password/forgot-password.html',
                    controller : 'ForgotPassController as vm'
                }
            },
           /* resolve  : {
                Timeline    : function (msApi)
                {
                    return msApi.resolve('profile.timeline@get');
                },
                About       : function (msApi)
                {
                    return msApi.resolve('profile.about@get');
                },
                PhotosVideos: function (msApi)
                {
                    return msApi.resolve('profile.photosVideos@get');
                }
            },
            bodyClass: 'profile'*/
        });

        // Translation
       /* $translatePartialLoaderProvider.addPart('app/main/pages/profile');

        // Api
        msApiProvider.register('profile.timeline', ['app/data/profile/timeline.json']);
        msApiProvider.register('profile.about', ['app/data/profile/about.json']);
        msApiProvider.register('profile.photosVideos', ['app/data/profile/photos-videos.json']);

        // Navigation
        msNavigationServiceProvider.saveItem('pages.profile', {
            title : 'Profile',
            icon  : 'icon-account',
            state : 'app.pages_profile',
            weight: 6
        });*/
    }

})();
(function ()
{
    'use strict';

    ForgotPassController.$inject = ["$state", "$scope", "api"];
    angular
        .module('app.login.forgot-password')
        .controller('ForgotPassController', ForgotPassController);

    /** @ngInject */
    function ForgotPassController($state, $scope, api){

        var vm = this;
        $scope.isLoading = false;
        $scope.isDisabled = false;
        $scope.getNewPass = function(){
            $scope.error_message = '';
            $scope.isLoading = true;
            $scope.resetPasswordForm.$invalid = true;
            var formData = new FormData();
            formData.append('email_id',vm.email);
            api.postMethod.forgetPass(formData).then(function(res){
                $scope.isLoading = false;
                $scope.resetPasswordForm.$invalid = false;
                if(res.data.status == 'success'){
                    $state.go('app.page',{slug:'forgot_email_sent'});
                }else{
                    $scope.error_message = res.data.message;
                }
            });
        }

        $scope.back = function(){
            window.history.back();
            console.log(window.history);
        }
    }


})();
(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "msApiProvider", "msNavigationServiceProvider"];
    angular
        .module('app.login.edit-profile', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider, msApiProvider, msNavigationServiceProvider)
    {
        $stateProvider.state('app.edit-profile', {
            url      : '/edit-profile',
            views    : {
                'content@app': {
                    templateUrl: 'app/main/login/edit-profile/edit-profile.html',
                    controller : 'EditProfileController as vm'
                }
            },
           /* resolve  : {
                Timeline    : function (msApi)
                {
                    return msApi.resolve('profile.timeline@get');
                },
                About       : function (msApi)
                {
                    return msApi.resolve('profile.about@get');
                },
                PhotosVideos: function (msApi)
                {
                    return msApi.resolve('profile.photosVideos@get');
                }
            },
            bodyClass: 'profile'*/
        });

        // Translation
       /* $translatePartialLoaderProvider.addPart('app/main/pages/profile');

        // Api
        msApiProvider.register('profile.timeline', ['app/data/profile/timeline.json']);
        msApiProvider.register('profile.about', ['app/data/profile/about.json']);
        msApiProvider.register('profile.photosVideos', ['app/data/profile/photos-videos.json']);

        // Navigation
        msNavigationServiceProvider.saveItem('pages.profile', {
            title : 'Profile',
            icon  : 'icon-account',
            state : 'app.pages_profile',
            weight: 6
        });*/
    }

})();
(function ()
{
    'use strict';

    EditProfileController.$inject = ["$scope", "api", "$state", "$mdToast"];
    angular
        .module('app.login.edit-profile')
        .directive('ngFiles', ['$parse', function ($parse) {

            function fn_link(scope, element, attrs) {
                var onChange = $parse(attrs.ngFiles);
                element.on('change', function (event) {
                    onChange(scope, { $files: event.target.files });
                });
            };
            return {
                link: fn_link
            }
        } ])    
        .controller('EditProfileController', EditProfileController);

    /** @ngInject */
    function EditProfileController($scope, api, $state, $mdToast){
        if(checkAuth($state) == false){
            return false;
        }
        var vm = this;
        api.profile.details.get({},function(res){
            $scope.userProfile = res.details;
            // console.log(res.details);
            $scope.org = res.details.organization;
            // console.log(res.details.organization.id);
            var userDet = res.details;
        });
         api.organization.list.get({},function(res){
            
             // console.log(res.records);
             $scope.orgs = res.records;
             
         });



                $scope.single = function(image) {
                    
                    // var formData = new FormData();
                    // formData.append('image', image, image.name);
                    // $http.post('upload', formData, {
                    //     headers: { 'Content-Type': false },
                    //     transformRequest: angular.identity
                    // }).success(function(result) {
                    //     $scope.uploadedImgSrc = result.src;
                    //     $scope.sizeInBytes = result.size;
                    // });
                };
            

        $scope.updateProfile = function(){
           
            var formData = new FormData();
            formData.append('name',$scope.vm.name);
            formData.append('phone',$scope.userProfile.phone);
            formData.append('app_pass',$scope.userProfile.app_pass);
            try{
                formData.append('new_img',$scope.$$childTail.files[0].lfFile);
            }catch(e){

            }
            formData.append('address',$scope.userProfile.address);
            formData.append('organization',$scope.vm.Organization);
            api.postMethod.saveProfile(formData).then(function(res){
                if(res.data.status == 'success'){
                    $mdToast.show(
                     $mdToast.simple()
                        .textContent('Profile Update Successfully!')
                        .position('top right')
                        .hideDelay(5000)
                    );
                    // $state.go('app.profile');
                    $state.go($state.current, {}, {reload: true});
                }
            });
        }

        $scope.goBack = function(){
            
            $state.go('app.profile');
        }
        $scope.triggerUpload=function()
        { 
            var fileuploader = angular.element("#fileInput");
             fileuploader.on('click',function(){
             console.log("File upload triggered programatically");
             })
                fileuploader.trigger('click')
        }


    }

    function checkAuth($state){

        if(sessionStorage.api_token == undefined || sessionStorage.api_token == ''){

            $state.go('app.new-login');
            return false;
        }
    }

  

})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "$translatePartialLoaderProvider", "msApiProvider", "msNavigationServiceProvider"];
    angular
        .module('app.login.change-password', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider, $translatePartialLoaderProvider, msApiProvider, msNavigationServiceProvider)
    {
        $stateProvider.state('app.change_password', {
            url      : '/changepass',
            views    : {
                'content@app': {
                    templateUrl: 'app/main/login/change-password/change-password.html',
                    controller : 'ChangePassController as vm'
                }
            },
           /* resolve  : {
                Timeline    : function (msApi)
                {
                    return msApi.resolve('profile.timeline@get');
                },
                About       : function (msApi)
                {
                    return msApi.resolve('profile.about@get');
                },
                PhotosVideos: function (msApi)
                {
                    return msApi.resolve('profile.photosVideos@get');
                }
            },
            bodyClass: 'profile'*/
        });

        // Translation
       /* $translatePartialLoaderProvider.addPart('app/main/pages/profile');

        // Api
        msApiProvider.register('profile.timeline', ['app/data/profile/timeline.json']);
        msApiProvider.register('profile.about', ['app/data/profile/about.json']);
        msApiProvider.register('profile.photosVideos', ['app/data/profile/photos-videos.json']);

        // Navigation
        msNavigationServiceProvider.saveItem('pages.profile', {
            title : 'Profile',
            icon  : 'icon-account',
            state : 'app.pages_profile',
            weight: 6
        });*/
    }

})();
(function ()
{
    'use strict';

    ChangePassController.$inject = ["$scope", "$state", "api", "$mdToast"];
    angular
        .module('app.login.change-password')
        .controller('ChangePassController', ChangePassController);

    /** @ngInject */
    function ChangePassController($scope, $state, api, $mdToast){
        if(checkAuth($state) == false){
            return false;
        }
        var vm = this;
        $scope.isLoading = false;
        $scope.server_message = ' ';
        $scope.updateNewPass = function(){
            $('.changePass').prop('disabled',true);
            $scope.isLoading = true;
            var formData = new FormData();
            formData.append('old_pass',vm.changePass.oldpass);
            formData.append('new_pass',vm.changePass.newpassword);
            formData.append('conf_pass',vm.changePass.passwordConfirm);
            api.postMethod.changePassword(formData).then(function(res){

                if(res.data.status == 'error'){
                    $scope.isLoading = false;
                    $scope.server_message = res.data.message;
                    $('.changePass').prop('disabled',false);
                }else{
                    $scope.isLoading = false;
                    $mdToast.show(
                     $mdToast.simple()
                        .textContent('Password Chnaged Successfully!')
                        .position('top right')
                        .hideDelay(5000)
                    );
                    $('.changePass').prop('disabled',false);
                    $state.go('app.profile');
                }
            });
        }
    }

    function checkAuth($state){

        if(sessionStorage.api_token == undefined || sessionStorage.api_token == ''){

            $state.go('app.new-login');
            return false;
        }
    }

})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "msApiProvider"];
    angular
        .module('app.dataset.view-dataset', ['datatables'])
        .config(config);

    /** @ngInject */
    function config($stateProvider, msApiProvider)
    {
        $stateProvider.state('app.dataset_view', {
            url    : '/dataset/view/:id',
            views  : {
                'content@app': {
                    templateUrl: 'app/main/dataset/view-dataset/view-dataset.html',
                    controller : 'ViewDatasetController as vm'
                }
            }
        });

    }

})();

(function ()
{
    'use strict';

    ViewDatasetController.$inject = ["$state", "api", "$scope", "$mdToast", "hotRegisterer", "$mdDialog"];
    angular
        .module('app.dataset.view-dataset')
        .controller('ViewDatasetController', ViewDatasetController);

    /** @ngInject */
    function ViewDatasetController($state, api, $scope, $mdToast, hotRegisterer, $mdDialog)
    {
      if(checkAuth($state) == false){
          return false;
      }
      $scope.st = $state.current.name;
      var vm = this;
      var changedRows = [];
      $scope.isDisabled = true;
      $scope.isLoading = false;
      $scope.error_message = '';
      var limit = 500;
      var skip = 0;
      vm.dataset_id = $state.params.id;
      if($state.current.name == 'app.dataset_view'){
          api.dataset.getById.get({'id': $state.params.id, 'skip': skip},
      
          function (response){
              limit = parseInt(response.limit);
              $scope.dataset_name = response.records.dataset_name;
              if(response.skip == "0"){
                  $scope.isDisabledPrev = true;
              }else{
                  $scope.isDisabledPrev = false;
              }
              if(parseInt(skip + limit) >= parseInt(response.total)){
                  /*console.log(skip+limit);
                  console.log(response.total);*/
                  $scope.isDisabledNext = true;
              }else{
                  /*console.log(skip+limit);
                  console.log(response.total);*/
                  $scope.isDisabledNext = false;
              }
              $scope.total_records = response.total;
              $scope.totalLimit = limit;
            /*  console.log(response.records.records);*/
              vm.items = response.records.records;
              $scope.headerColumns = response.records.records[0];
              $scope.nextPage = skip + limit;
              $scope.from = skip+1;
              $scope.to = skip + limit; 
          }
        );
      }
       $scope.showCustom = function(event,datasetID) {
            $mdDialog.show({
              clickOutsideToClose: true,
              scope: $scope,        
              preserveScope: true,           
              templateUrl: 'app/main/dataset/include/_view_visuals.html',
              controller: ["$scope", "$mdDialog", "api", function DialogController($scope, $mdDialog, api) {
                api.visual.visualByDatset.get({'id':datasetID},function(res){
                  console.log(res);
                    $scope.dataset_id = datasetID;
                    $scope.listVisual = res.list_visuals;
                });
                $scope.closeDialog = function() {
                  $mdDialog.hide();
                }
              }]
            });
        };
        $scope.datasetRecords = {};
       $scope.insertRecord = function(event) {
           $mdDialog.show({
              clickOutsideToClose: true,
              scope: $scope,        
              preserveScope: true,           
             templateUrl: 'app/main/dataset/include/_insert_record.html',
              controller: ["$scope", "$mdDialog", function DialogController($scope, $mdDialog) {
                console.log($scope.headerColumns);
                 $scope.closeDialog = function() {
                    $mdDialog.hide();
                 }
                 $scope.insertRecordData = function(){
                  var formData = new FormData();
                  formData.append('dataset_id',$state.params.id);
                  formData.append('records',JSON.stringify($scope.datasetRecords));
                  api.postMethod.insertDatasetRecord(formData).then(function(res){
                    console.log(res);
                    if(res.data.status == 'success'){
                      console.log('here');
                        $mdToast.show(
                         $mdToast.simple()
                            .textContent('Record inserted successfully!')
                            .position('top right')
                            .hideDelay(5000)
                        );
                        $mdDialog.hide();
                        $state.go($state.current,{},{reload:true});
                    }
                  });
                 }
              }]
           });
        };
        $scope.editName = function(event,datasetID, name) {

              $mdDialog.show({
                clickOutsideToClose: true,
                scope: $scope,        
                preserveScope: true,           
                templateUrl: 'app/main/dataset/include/_edit_name.html',
                controller: ["$scope", "$mdDialog", "api", function DialogController($scope, $mdDialog, api) {  
                  $scope.newName = name;                
                  $scope.rename = function(){
                    var formdata = new FormData();
                    formdata.append('id',datasetID);
                    formdata.append('dataset_name',$scope.newName);
                    api.postMethod.renameDataset(formdata).then( function(res){
                        $mdDialog.hide();
                        $scope.dataset_name = $scope.newName;
                        $state.go($state.current,{},{reload:true});
                    })
                    
                  }
                  $scope.closeDialog = function() {
                    $mdDialog.hide();
                  }
                }]
              });
          };
     $scope.next = function(skip){
        $scope.isDisabledNext = true;
        $scope.isLoadingNext = true;
        api.dataset.getById.get({'id': $state.params.id, 'skip': skip},
      
            function (response){
                if(response.skip == "0"){
                    $scope.isDisabledPrev = true;
                }else{
                    $scope.isDisabledPrev = false;
                }
                $scope.from = skip+1;
                if(parseInt(skip + limit) >= parseInt(response.total)){
                    $scope.to = response.total;
                }else{
                    $scope.to = skip + limit;
                }
                vm.items = response.records.records;           
                vm.dataset_id = response.records.dataset_id;
                $scope.nextPage = skip + limit;
                $scope.prevPage = response.skip - limit;
                $scope.isLoadingNext = false;
                if(parseInt(skip + limit) >= parseInt(response.total)){
                    $scope.isDisabledNext = true;
                }else{
                    $scope.isDisabledNext = false;
                }
            }
        );
     }
     $scope.prev = function(skip){
        $scope.isDisabledPrev = true;
        $scope.isLoadingPrev = true;
        api.dataset.getById.get({'id': $state.params.id, 'skip': skip},
      
            function (response){

              if(response.skip == "0"){
                  $scope.isDisabledPrev = true;

              }else{
                  $scope.isDisabledPrev = false;
              }
              $scope.from = skip+1;
              if(parseInt(skip + limit) >= parseInt(response.total)){
                    $scope.to = response.total;
              }else{
                    $scope.to = skip + limit;
              }
              
              vm.items = response.records.records;           
              vm.dataset_id = response.records.dataset_id;
              $scope.prevPage = skip - limit;
              $scope.nextPage = skip + limit;
              $scope.isLoadingPrev = false;
              if(parseInt(skip + limit) >= parseInt(response.total)){
                  $scope.isDisabledNext = true;
              }else{
                  $scope.isDisabledNext = false;
              }
            }
        );
     }
     $scope.settings = {

          stretchH: 'all',
          contextMenu: false,
          colHeaders: true,
          formulas: true,
          afterChange: afterChange,
          readOnly: true
      }
      function afterChange(data, source){
          
          if(source != 'loadData'){
              $scope.isDisabled = false;
              changedRows.push(data[0][0]);
          }
      }
      $scope.saveEditedDataset = function(){
          changedRows = changedRows.filter(function(itm,i,a){
              return i==a.indexOf(itm);
          });
          console.log(changedRows);
          var changedData = $.grep(vm.items, function(value, index){
              return ($.inArray(index,changedRows) !== -1);
          })
          $scope.isDisabled = true;
          $scope.isLoading = true;
          var formData = new FormData();
          formData.append('dataset_id',$state.params.id);
          formData.append('records',JSON.stringify(changedData));
          api.postMethod.saveEditedDatset(formData).then(function(res){
              if(res.data.status == 'success'){
                  $scope.error_message = '';
                  $mdToast.show(
                   $mdToast.simple()
                      .textContent('Dataset Updated Successfully!')
                      .position('top right')
                      .hideDelay(5000)
                  );
                  $scope.isDisabled = false;
                  $scope.isLoading = false;
                  //$state.go('app.dataset_list');
              }else{
                  $scope.isDisabled = false;
                  $scope.isLoading = false;
                  $scope.error_message = res.data.message;
              }
          });
      }
       $scope.deleteDataset = function(datasetID,ev){

            var confirm = $mdDialog.confirm({
              
                    onComplete: function afterShowAnimation() {
                        var $dialog = angular.element(document.querySelector('md-dialog'));
                        var $actionsSection = $dialog.find('md-dialog-actions');
                        var $cancelButton = $actionsSection.children()[0];
                        var $confirmButton = $actionsSection.children()[1];
                        angular.element($confirmButton).addClass('md-raised red-bg ph-20');
                        angular.element($cancelButton).addClass('md-raised ph-20');
                    }
                
            })
                  .title('Would you like to delete this dataset?')
                  .textContent('The Dataset will be deleted permanently and no longer accesible by any user.')
                  .ariaLabel('Delete Dataset')
                  .targetEvent(ev)
                  .ok('Yes, delete it!')
                  .cancel('No, don\'t delete');


            $mdDialog.show(confirm).then(function() {
              api.dataset.deleteDataset.get({'id':datasetID}, function(res){
                  if(res.status == 'success'){
                      $mdToast.show(
                       $mdToast.simple()
                          .textContent('Dataset deleted successfully!')
                          .position('top right')
                          .hideDelay(5000)
                      );
                      $state.go($state.current, {}, {reload: true});
                  }
              });
            }, function() {

            });
        }
      
    }

    /*function pagination(records){

        var currentPage = 1;
        var limit = 10;
        
        var offset = (currentPage - 1) * limit;

        var records = records[0].slice(offset, offset + limit);

        return records
    }*/

    function checkAuth($state){

        if(sessionStorage.api_token == undefined || sessionStorage.api_token == ''){

            $state.go('app.new-login');
            return false;
        }
    }

})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "msApiProvider"];
    angular
        .module('app.dataset.validate-dataset', ['datatables'])
        .config(config);

    /** @ngInject */
    function config($stateProvider, msApiProvider)
    {
        $stateProvider.state('app.validate', {
            url    : '/dataset/validate/:id',
            views  : {
                'content@app': {
                    templateUrl: 'app/main/dataset/validate-dataset/validate-dataset.html',
                    controller : 'ValidateDatasetController as vm'
                }
            }
        });

    }

})();

(function ()
{
    'use strict';

    ValidateDatasetController.$inject = ["$state", "api", "$scope", "$mdToast", "hotRegisterer", "$mdDialog"];
    angular
        .module('app.dataset.validate-dataset')
        .controller('ValidateDatasetController', ValidateDatasetController);

    /** @ngInject */
    function ValidateDatasetController($state, api, $scope, $mdToast, hotRegisterer, $mdDialog)
    {
      	if(checkAuth($state) == false){
          	return false;
      	}
        $scope.st = $state.current.name;
      	var vm = this;
      	var changedRows = [];
      	vm.dataset_id = $state.params.id;
      	api.dataset.columnValidate.get({'id': $state.params.id},
      
        	function (response){
              
              if(response.defined == 'false'){
                  $scope.showNotdefined = true;
                  return false;
              }
              if(response.wrong_rows.length == 0){
                $scope.showTable = false;
                $scope.noWrongData = true;
              }else{
                $scope.noWrongData = false;
                $scope.showTable = true;
                $scope.dataset_name = response.dataset_name;
                $scope.items = response.wrong_rows; 
                $scope.dataset_id = $state.params.id;
              }
        	}
      	);
        
     
     	$scope.settings = {

          	stretchH: 'all',
          	contextMenu: false,
          	formulas: true,
          	afterChange: afterChange,
          	renderer: function(instance, td, row, col, prop, value, cellProperties){
          		
          		if(isHTML(value)){
          			td.style.backgroundColor = 'red';
					var div = document.createElement("div");
					div.innerHTML = value;
					var text = div.textContent || div.innerText || "";
          			td.innerHTML = text;
          		}else{
          			td.innerHTML = value;
          		}
          	}
      	}
      	function isHTML(str) {
		    var a = document.createElement('div');
		    a.innerHTML = str;
		    for (var c = a.childNodes, i = c.length; i--; ) {
		        if (c[i].nodeType == 1) return true; 
		    }
		    return false;
		}
      	function afterChange(data, source){
          
          	if(source != 'loadData'){
              	$scope.isDisabled = false;
              	changedRows.push(data[0][0]);
          	}
      	}
      	$scope.saveEditedDataset = function(){
          	changedRows = changedRows.filter(function(itm,i,a){
              	return i==a.indexOf(itm);
          	});
          
          	var changedData = $.grep($scope.items, function(value, index){
              	return ($.inArray(index,changedRows) !== -1);
          	});
          
          	$scope.isDisabled = true;
          	$scope.isLoading = true;
          	var formData = new FormData();
          	formData.append('dataset_id',$state.params.id);
          	formData.append('records',JSON.stringify(changedData));
          	api.postMethod.saveEditedDatset(formData).then(function(res){
              	if(res.data.status == 'success'){
                  	$scope.error_message = '';
                  	$mdToast.show(
                   	$mdToast.simple()
                      	.textContent('Dataset Updated Successfully!')
                      	.position('top right')
                      	.hideDelay(5000)
                  	);
                  	$scope.isDisabled = false;
                  	$scope.isLoading = false;
                  //$state.go('app.dataset_list');
              	}else{
                  	$scope.isDisabled = false;
                  	$scope.isLoading = false;
                  	$scope.error_message = res.data.message;
              	}
          	});
      	}
     	$scope.deleteDataset = function(datasetID,ev){

            	var confirm = $mdDialog.confirm({
              
                    onComplete: function afterShowAnimation() {
                        var $dialog = angular.element(document.querySelector('md-dialog'));
                        var $actionsSection = $dialog.find('md-dialog-actions');
                        var $cancelButton = $actionsSection.children()[0];
                        var $confirmButton = $actionsSection.children()[1];
                        angular.element($confirmButton).addClass('md-raised md-warn ph-15');
                        angular.element($cancelButton).addClass('md-raised ph-15');
                    }
                
            })
                .title('Would you like to delete this dataset?')
                .textContent('The Dataset will be deleted permanently and no longer accesible by any user.')
                .ariaLabel('Delete Dataset')
                .targetEvent(ev)
                .ok('Yes, delete it!')
                .cancel('No, don\'t delete');


            $mdDialog.show(confirm).then(function() {
              api.dataset.deleteDataset.get({'id':datasetID}, function(res){
                  if(res.status == 'success'){
                      $mdToast.show(
                       $mdToast.simple()
                          .textContent('Dataset deleted successfully!')
                          .position('top right')
                          .hideDelay(5000)
                      );
                      $state.go($state.current, {}, {reload: true});
                  }
              });
            }, function() {

            });
        } 
    }


    /*function pagination(records){

        var currentPage = 1;
        var limit = 10;
        
        var offset = (currentPage - 1) * limit;

        var records = records[0].slice(offset, offset + limit);

        return records
    }*/

    function checkAuth($state){

        if(sessionStorage.api_token == undefined || sessionStorage.api_token == ''){

            $state.go('app.new-login');
            return false;
        }
    }

})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "msApiProvider"];
    angular
        .module('app.dataset.list-dataset', ['datatables'])
        .config(config);

    /** @ngInject */
    function config($stateProvider, msApiProvider)
    {
        $stateProvider.state('app.dataset_list', {
            url    : '/dataset/list',
            views  : {
                'content@app': {
                    templateUrl: 'app/main/dataset/list-dataset/list-dataset.html',
                    controller : 'ListDatasetController as vm'
                }
            }
        });
    }

})();
(function ()
{
		'use strict';

		ListDatasetController.$inject = ["$scope", "$mdDialog", "api", "$state", "$mdToast"];
		angular
				.module('app.dataset.list-dataset')
				.controller('ListDatasetController', ListDatasetController);

		/** @ngInject */
		function ListDatasetController($scope, $mdDialog, api, $state, $mdToast)
		{
			$scope.showCustom = function(event,datasetID) {
	            $mdDialog.show({
	              clickOutsideToClose: true,
	              scope: $scope,        
	              preserveScope: true,           
	              templateUrl: '/app/main/dataset/include/_view_visuals.html',
	              controller: ["$scope", "$mdDialog", "api", function DialogController($scope, $mdDialog, api) {
	                api.visual.visualByDatset.get({'id':datasetID},function(res){
	                	console.log(res);
	                    $scope.dataset_id = datasetID;
	                    $scope.listVisual = res.list;
	                });
	                $scope.closeDialog = function() {
	                  $mdDialog.hide();
	                }
	              }]
	            });
	        };
				$scope.createClone = function(datasetId){
					api.dataset.createClone.get({id: datasetId}, function(res){
						$mdToast.show(
							 $mdToast.simple()
									.textContent('Dataset cloned successfully!')
									.position('top right')
									.hideDelay(5000)
							);
							$state.go($state.current, {}, {reload: true});
					});
				}
				if(checkAuth($state) == false){
						return false;
				}

				var vm = this;

				api.listdataset.list.get({},function(res){
						// Data
						
						vm.datasets = res.data;
				});
				vm.HotTableData = [
								['A','B','C','D','E','F'],
								['G','H','I','J','K','L'],
								['M','N','O','P','Q','R'],
				];
		vm.dtOptions = {
						dom       : '<"top"<"left"<"length"l>><"right"<"search"f>>>rt<"bottom"<"left"<"info"i>><"right"<"pagination"p>>>',
						pagingType: 'full_numbers',
						order: [[ 0, "desc" ]],
						autoWidth : false,
						responsive: true
				};
				$scope.downloadCSV = function(id){
						window.location.href = api.downloadFile.downloadDatasetFile(id,'csv');
				}

				$scope.downloadExcel = function(id){
						window.location.href = api.downloadFile.downloadDatasetFile(id,'xls');
				}
				$scope.deleteDataset = function(datasetID,ev){

						var confirm = $mdDialog.confirm({
							
										onComplete: function afterShowAnimation() {
												var $dialog = angular.element(document.querySelector('md-dialog'));
												var $actionsSection = $dialog.find('md-dialog-actions');
												var $cancelButton = $actionsSection.children()[0];
												var $confirmButton = $actionsSection.children()[1];
												angular.element($confirmButton).addClass('md-raised red-bg ph-20');
												angular.element($cancelButton).addClass('md-raised ph-20');
										}
								
						})
									.title('Would you like to delete this dataset?')
									.textContent('The Dataset will be deleted permanently and no longer accesible by any user.')
									.ariaLabel('Delete Dataset')
									.targetEvent(ev)
									.ok('Yes, delete it!')
									.cancel('No, don\'t delete');


						$mdDialog.show(confirm).then(function() {
							api.dataset.deleteDataset.get({'id':datasetID}, function(res){
									if(res.status == 'success'){
											$mdToast.show(
											 $mdToast.simple()
													.textContent('Dataset deleted successfully!')
													.position('top right')
													.hideDelay(5000)
											);
											$state.go($state.current, {}, {reload: true});
									}
							});
						}, function() {

						});
				}
			$scope.createVisual = function(datasetID){
				sessionStorage.visualDataset = datasetID;
				$state.go('app.visualizations_add');
			}
			
		}
		function checkAuth($state){
				if(sessionStorage.api_token == undefined || sessionStorage.api_token == ''){
						$state.go('app.new-login');
						return false;
				} else{
					return true;
				}

		}



})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "msApiProvider"];
    angular
        .module('app.dataset.import-dataset', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider, msApiProvider)
    {
        $stateProvider.state('app.dataset_import', {
            url    : '/dataset/import',
            views  : {
                'content@app': {
                    templateUrl: 'app/main/dataset/import-dataset/import-dataset.html',
                    controller : 'ImportDatasetController as vm'
                }
            }
        });
        $stateProvider.state('app.dataset_import_wizard', {
            url    : '/dataset/import/:wizard',
            views  : {
                'content@app': {
                    templateUrl: 'app/main/dataset/import-dataset/import-dataset.html',
                    controller : 'ImportDatasetController as vm'
                }
            }
        });

    }

})();
(function ()
{
    'use strict';

    ImportDatasetController.$inject = ["$state", "$scope", "$http", "api"];
    angular
        .module('app.dataset.import-dataset')
        .directive('ngFiles', ['$parse', function ($parse) {

            function fn_link(scope, element, attrs) {
                var onChange = $parse(attrs.ngFiles);
                element.on('change', function (event) {
                    onChange(scope, { $files: event.target.files });
                });
            };
            return {
                link: fn_link
            }
        } ])
        .controller('ImportDatasetController', ImportDatasetController);

    /** @ngInject */
    function ImportDatasetController($state, $scope, $http, api)
    {
        if(checkAuth($state) == false){
            return false;
        }
        $scope.disable_button = false;
        $scope.isLoading = false;
        api.listdataset.list.get({},function(res){
            $scope.datasetsList = res.data;
        });
        $scope.showIcon = false;
        var formdata = new FormData();
        api.dataset.getAnsweredSurvey.get({}, function(res){
            $scope.surveyList = res.survey_list;
          /*  console.log(res);*/
        });

        //var uploadedDatasetId = '';
        $scope.uploadFiles = function (source) {
            $scope.dataset.$invalid = true;
            $scope.isLoading = true;
            if(source == 'file'){
                if($scope.files.length == 0){
                    $scope.error_message = 'Please select file to upload!'
                    $scope.dataset.$invalid = false;
                    $scope.isLoading = false;
                    return false;
                }else{
                    $scope.error_message = '';
                }

                var typeArray = [
                                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                    'application/vnd.ms-excel',
                                    'text/csv',
                                    '',
                                    'application/sql'
                                ];
                if($.inArray($scope.files[0].lfFile.type, typeArray) !== -1){
                    $scope.message_text = '';
                    $scope.message_color = '';
                    
                    formdata.append('file',$scope.files[0].lfFile);
                    var formFields = $scope.data;
                    $scope.error_message = '';
                    formdata.append('format', formFields.state);
                    formdata.append('add_replace', formFields.action);
                    formdata.append('with_dataset', formFields.tableToreplace);
                    formdata.append('dataset_name', formFields.datasetname);
                    formdata.append('source',source);
                    postDataset(formdata, $scope, $state, api);
                }else{
                    $scope.dataset.$invalid = false;
                    $scope.isLoading = false;
                    $scope.message_text = 'Wrong file selected!';
                    $scope.message_color = 'red';
                }
            }else if(source == 'url'){
                
                if($scope.data.fileurl == undefined){
                    $scope.error_message = 'Please enter file url!'
                    $scope.dataset.$invalid = false;
                    $scope.isLoading = false;
                    return false;
                }else{
                    $scope.error_message = ''
                }
                $scope.message_text = '';
                $scope.message_color = '';
                var formFields = $scope.data;
                formdata.append('fileurl',formFields.fileurl);
                $scope.error_message = '';
                formdata.append('format', formFields.state);
                formdata.append('add_replace', formFields.action);
                formdata.append('with_dataset', formFields.tableToreplace);
                formdata.append('dataset_name', formFields.datasetname);
                formdata.append('source',source);
                postDataset(formdata, $scope, $state, api);

            }else if(source == 'file_server'){
                if($scope.data.filepath == undefined){
                    $scope.error_message = 'Please enter file path!'
                    $scope.dataset.$invalid = false;
                    $scope.isLoading = false;
                    return false;
                }else{
                    $scope.error_message = ''
                }
                $scope.message_text = '';
                $scope.message_color = '';
                var formFields = $scope.data;
                formdata.append('filepath',formFields.filepath);
                $scope.error_message = '';
                formdata.append('format', formFields.state);
                formdata.append('add_replace', formFields.action);
                formdata.append('with_dataset', formFields.tableToreplace);
                formdata.append('dataset_name', formFields.datasetname);
                formdata.append('source',source);
                postDataset(formdata, $scope, $state, api);
            }else if(source == 'import_survey'){
                var formFields = $scope.data;
                formdata.append('source',source);
                formdata.append('survey',formFields.surveyID);
                formdata.append('add_replace', formFields.action);
                formdata.append('with_dataset', formFields.tableToreplace);
                formdata.append('dataset_name', formFields.datasetname);
                postDataset(formdata, $scope, $state, api);
            }
        }

        $scope.data = {
            uploadby : 'file'
        }

        $scope.model = {
            isDisabled: true
        }
        $scope.upload = function () {
            angular.element(document.querySelector('#fileInput')).click();
        };
        $scope.addReplaceOrAppend = function(){
            var action = $scope.data.action;
            if(action == 'replace' || action == 'append'){
                $scope.model = {
                    isDisabled: false
                }
            }else{
                $scope.model = {
                    isDisabled: true
                };
            }
        }

        $scope.wizardSteps = false;
        $scope.allDataset = true
        if($state.params.wizard != '' && $state.params.wizard == 'wizard'){
            $scope.wizardSteps = true;
            $scope.next = true;
            $scope.allDataset = false;
        }
        
        $scope.nextStep = function(){
            var completedStatus = {};
            completedStatus['step1'] = 1;
            sessionStorage.completedStatus = JSON.stringify(completedStatus);
            $state.go('app.data_filtration',{'id':uploadedDatasetId,'wizard':'wizard'});
        }

        $scope.showFileUpload = true;
        $scope.uploadby = function(val){

            if(val == 'file'){
                $scope.showFileUpload = true;
                $scope.showFilePath = false;
                $scope.showFileUrl = false;
                $scope.showImportDropdown = false;
            }else if(val == 'file_server'){
                $scope.showFileUpload = false;
                $scope.showFilePath = true;
                $scope.showFileUrl = false;
                $scope.showImportDropdown = false;
            }else if(val == 'url'){
                $scope.showFileUrl = true;
                $scope.showFileUpload = false;
                $scope.showFilePath = false;
                $scope.showImportDropdown = false;
            }else if(val == 'import_survey'){
                $scope.showFileUrl = false;
                $scope.showFileUpload = false;
                $scope.showFilePath = false;
                $scope.showImportDropdown = true;
            }
        }
    }
    
    function postDataset(formData, $scope, $state, api){

        api.postMethod.importDataset(formData,$scope).then(function(res){
            // console.log(res);
            if(res.data.status == 'error'){
                $scope.disable_button = false;
                $scope.isLoading = false;
                $scope.error_message = res.data.message;
            }else{
                if($state.params.wizard == ''){
                    $state.go('app.column_validate',{'id':res.data.id});
                }else{
                    $scope.disable_button = false;
                    $scope.isLoading = false;
                    $scope.error_message = res.data.message;
                    $scope.next = false;
                    window.uploadedDatasetId = res.data.id;
                    $state.go('app.column_validate',{'id':res.data.id});
                }
            }
        },function(error){
            $scope.isLoading = false;
            $scope.error_message = 'Unable to upload your dataset';
        },function(evt){
            var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
            // console.log('progress: ' + progressPercentage + '% ');
        });
    }

    function checkAuth($state){

        if(sessionStorage.api_token == undefined || sessionStorage.api_token == ''){

            $state.go('app.new-login');
            return false;
        }
    }

})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "msApiProvider"];
    angular
        .module('app.dataset.export-dataset', ['datatables'])
        .config(config);

    /** @ngInject */
    function config($stateProvider, msApiProvider)
    {
        $stateProvider.state('app.dataset_export', {
            url    : '/dataset/export',
            views  : {
                'content@app': {
                    templateUrl: 'app/main/dataset/export-dataset/export-dataset.html',
                    controller : 'ExportDatasetController as vm'
                }
            },
        });
    }

})();

(function ()
{
    'use strict';

    ExportDatasetController.$inject = ["api", "$state", "$scope", "$mdDialog"];
    angular
        .module('app.dataset.export-dataset')
        .controller('ExportDatasetController', ExportDatasetController);

    /** @ngInject */
    function ExportDatasetController(api, $state, $scope, $mdDialog)
    {
        if(checkAuth($state) == false){
            return false;
        }
        var vm = this;
        api.listdataset.list.get({},function(res){
            // Data
            vm.datasets = res.data;
        });

        $scope.generateLink = function(id){

            window.location.href = api.downloadFile.downloadDatasetFile(id);
        }
		vm.dtOptions = {
            dom       : '<"top"f>rt<"bottom"<"left"<"length"l>><"right"<"info"i><"pagination"p>>>',
            pagingType: 'full_numbers',
            autoWidth : false,
            responsive: true
        };
        vm.announceClick = function(index) {
            $mdDialog.show(
            $mdDialog.alert()
                .title('You clicked!')
                .textContent('You clicked the menu item at index ' + index)
                .ok('Nice')
            );
        };

        $scope.downloadSql = function(id){

            $mdDialog.show(
            $mdDialog.alert()
                .title('You clicked! on SQL')
                .textContent('Dataset id '+id)
                .ok('Nice')
            );
        }

        $scope.downloadCSV = function(id){
            window.location.href = api.downloadFile.downloadDatasetFile(id,'csv');
        }

        $scope.downloadExcel = function(id){
            window.location.href = api.downloadFile.downloadDatasetFile(id,'xls');
        }
    }

    function checkAuth($state){

        if(sessionStorage.api_token == undefined || sessionStorage.api_token == ''){

            $state.go('app.new-login');
            return false;
        }
    }

})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "msApiProvider"];
    angular
        .module('app.dataset.edit-dataset', ['datatables'])
        .config(config);

    /** @ngInject */
    function config($stateProvider, msApiProvider)
    {
        $stateProvider.state('app.dataset_edit', {
            url    : '/dataset/edit/:id',
            views  : {
                'content@app': {
                    templateUrl: 'app/main/dataset/edit-dataset/edit-dataset.html',
                    controller : 'EditDatasetController as vm'
                }
            }
        });

    }

})();

(function ()
{
    'use strict';

    EditDatasetController.$inject = ["$state", "api", "$scope", "$mdToast", "hotRegisterer", "$mdDialog"];
    angular
        .module('app.dataset.edit-dataset')
        .controller('EditDatasetController', EditDatasetController);

    /** @ngInject */
    function EditDatasetController($state, api, $scope, $mdToast, hotRegisterer, $mdDialog)
    {
      if(checkAuth($state) == false){
          return false;
      }
      $scope.st = $state.current.name;
      var vm = this;
      var changedRows = [];
      var deletedRows = [];
      $scope.isDisabled = false;
      $scope.isLoading = false;
      $scope.error_message = '';
      var limit = 500;
      var skip = 0;
      vm.dataset_id = $state.params.id;
      api.dataset.getById.get({'id': $state.params.id, 'skip': skip},
      
        function (response){
            
            $scope.dataset_name = response.records.dataset_name;
            if(response.skip == "0"){
                $scope.isDisabledPrev = true;
            }else{
                $scope.isDisabledPrev = false;
            }
            if(parseInt(skip + limit) >= parseInt(response.total)){
                /*console.log(skip+limit);
                console.log(response.total);*/
                $scope.isDisabledNext = true;
            }else{
                /*console.log(skip+limit);
                console.log(response.total);*/
                $scope.isDisabledNext = false;
            }
            $scope.total_records = response.total;
            vm.items = response.records.records;           
            
            $scope.nextPage = skip + limit;
            $scope.from = skip+1;
            $scope.to = skip + limit;
        }
      );
     $scope.next = function(skip){
        $scope.isDisabledNext = true;
        $scope.isLoadingNext = true;
        api.dataset.getById.get({'id': $state.params.id, 'skip': skip},
      
            function (response){
                if(response.skip == "0"){
                    $scope.isDisabledPrev = true;
                }else{
                    $scope.isDisabledPrev = false;
                }
                $scope.from = skip+1;
                if(parseInt(skip + limit) >= parseInt(response.total)){
                    $scope.to = response.total;
                }else{
                    $scope.to = skip + limit;
                }
                vm.items = response.records.records;           
                vm.dataset_id = response.records.dataset_id;
                $scope.nextPage = skip + limit;
                $scope.prevPage = response.skip - limit;
                $scope.isLoadingNext = false;
                if(parseInt(skip + limit) >= parseInt(response.total)){
                    $scope.isDisabledNext = true;
                }else{
                    $scope.isDisabledNext = false;
                }
            }
        );
     }
     $scope.prev = function(skip){
        $scope.isDisabledPrev = true;
        $scope.isLoadingPrev = true;
        api.dataset.getById.get({'id': $state.params.id, 'skip': skip},
      
            function (response){

              if(response.skip == "0"){
                  $scope.isDisabledPrev = true;

              }else{
                  $scope.isDisabledPrev = false;
              }
              $scope.from = skip+1;
              if(parseInt(skip + limit) >= parseInt(response.total)){
                    $scope.to = response.total;
              }else{
                    $scope.to = skip + limit;
              }
              
              vm.items = response.records.records;           
              vm.dataset_id = response.records.dataset_id;
              $scope.prevPage = skip - limit;
              $scope.nextPage = skip + limit;
              $scope.isLoadingPrev = false;
              if(parseInt(skip + limit) >= parseInt(response.total)){
                  $scope.isDisabledNext = true;
              }else{
                  $scope.isDisabledNext = false;
              }
            }
        );
     }
     $scope.settings = {

          stretchH: 'all',
          contextMenu: [
              'row_above', 
              'row_below', 
              'remove_row'
              /*'---------',
              'col_left',
              'col_right',
              'remove_col',
              '---------',
              'undo','redo',
              '---------',
              'make_read_only',
              'alignment'*/
          ],
          formulas: true,
          afterChange: afterChange,
          beforeRemoveRow: beforeRemoveRw

          /*
          columns : [
            {
              data: 'id',
              title: 'ID',
              readOnly: true
            }
          ]
          */
         /* colHeaders: ['stateid', 'district_id', 'taluka_id'],
          columns: [
            {data: 'stateid', type: 'text', renderer: function(instance, td, row, col, prop, value, cellProperties) {
                                                        td.style.backgroundColor = 'yellow';
                                                        td.innerHTML = value;
                                                      }},
            {data: 'district_id', type: 'text'},
            {data: 'taluka_id', type: 'numeric'},
          ]*/
      }
     
      function beforeRemoveRw(index, amount){
      		var rowId = this.getDataAtCell(index,0);
      		deletedRows.push(rowId);
      		$scope.isDisabled = false;
      }
      function afterChange(data, source){
          
          if(source != 'loadData'){
              $scope.isDisabled = false;
              changedRows.push(data[0][0]);
          }
      }
      $scope.saveEditedDataset = function(){
        
          changedRows = changedRows.filter(function(itm,i,a){
              return i==a.indexOf(itm);
          });
          var changedData = $.grep(vm.items, function(value, index){
              return ($.inArray(index,changedRows) !== -1);
          })
          $scope.isDisabled = true;
          $scope.isLoading = true;
          var formData = new FormData();
          formData.append('dataset_id',$state.params.id);
          formData.append('records',JSON.stringify(changedData));
          formData.append('deletedRows',JSON.stringify(deletedRows));
          api.postMethod.saveEditedDatset(formData).then(function(res){
              if(res.data.status == 'success'){
                  $scope.error_message = '';
                  $mdToast.show(
                   $mdToast.simple()
                      .textContent('Dataset Updated Successfully!')
                      .position('top right')
                      .hideDelay(5000)
                  );
                  $scope.isDisabled = false;
                  $scope.isLoading = false;
                  //$state.go('app.dataset_list');
              }else{
                  $scope.isDisabled = false;
                  $scope.isLoading = false;
                  $scope.error_message = res.data.message;
              }
          });
      }
       $scope.deleteDataset = function(datasetID,ev){

            var confirm = $mdDialog.confirm({
              
                    onComplete: function afterShowAnimation() {
                        var $dialog = angular.element(document.querySelector('md-dialog'));
                        var $actionsSection = $dialog.find('md-dialog-actions');
                        var $cancelButton = $actionsSection.children()[0];
                        var $confirmButton = $actionsSection.children()[1];
                        angular.element($confirmButton).addClass('md-raised md-warn ph-15');
                        angular.element($cancelButton).addClass('md-raised ph-15');
                    }
                
            })
                  .title('Would you like to delete this dataset?')
                  .textContent('The Dataset will be deleted permanently and no longer accesible by any user.')
                  .ariaLabel('Delete Dataset')
                  .targetEvent(ev)
                  .ok('Yes, delete it!')
                  .cancel('No, don\'t delete');


            $mdDialog.show(confirm).then(function() {
              api.dataset.deleteDataset.get({'id':datasetID}, function(res){
                  if(res.status == 'success'){
                      $mdToast.show(
                       $mdToast.simple()
                          .textContent('Dataset deleted successfully!')
                          .position('top right')
                          .hideDelay(5000)
                      );
                      $state.go($state.current, {}, {reload: true});
                  }
              });
            }, function() {

            });
        }
      
    }

    /*function pagination(records){

        var currentPage = 1;
        var limit = 10;
        
        var offset = (currentPage - 1) * limit;

        var records = records[0].slice(offset, offset + limit);

        return records
    }*/

    function checkAuth($state){

        if(sessionStorage.api_token == undefined || sessionStorage.api_token == ''){

            $state.go('app.new-login');
            return false;
        }
    }

})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "msApiProvider"];
    angular
        .module('app.dataset.data-filtration', ['datatables'])
        .config(config);

    /** @ngInject */
    function config($stateProvider, msApiProvider)
    {
        $stateProvider.state('app.data_filtration', {
            url    : '/dataset/filter/:id/:wizard',
            views  : {
                'content@app': {
                    templateUrl: 'app/main/dataset/data-filtration/data-filtration.html',
                    controller : 'DataFiltrationController as vm'
                }
            }
        });

    }

})();

(function() {
    'use strict';

    DataFiltrationController.$inject = ["$state", "api", "$scope", "$mdDialog", "$mdToast", "$timeout"];
    angular
        .module('app.dataset.data-filtration')
        .controller('DataFiltrationController', DataFiltrationController);

    /** @ngInject */
    function DataFiltrationController($state, api, $scope, $mdDialog, $mdToast, $timeout) {

        if (checkAuth($state) == false) {
            return false;
        }
        $scope.st = $state.current.name;
        var vm = this;
        var recordsArray = '';
        vm.dataset_id = $state.params.id;
        $scope.showHot = false;
        $scope.isDisabled = false;
        api.dataset.getcolumnsById.get({
                'id': $state.params.id
            },
            // Success
            function(response) {
                window.responseData = response;
                $scope.dataset_name = response.records.dataset_name;
                $scope.id = $state.params.id;
                //$scope.columns = Object.keys(response.records.records[0]);
                $scope.columns = response.records.records[0];

                console.log(response);
                recordsArray = response.records.records;

                if ($scope.showDataset == true) {
                    vm.items = response.records.records;

                }
            },
            // Error
            function(response) {
                console.error(response);
            }
        );

        $scope.selectColumn = function(colKey){
            var records = responseData.records.records;
            $scope.colData = $.unique(arrayColumn(records,colKey));
        }

        $scope.clear = function(){
            $scope.column_key = '';
            $scope.column_val = '';
        }

        function filter(search) {
            var row, r_len;
            var data = myData;
            var array = [];
            for (row = 0, r_len = data.length; row < r_len; row++) {
                for (col = 0, c_len = data[row].length; col < c_len; col++) {
                    if (('' + data[row][col]).toLowerCase().indexOf(search) > -1) {
                        array.push(data[row]);
                        break;
                    }
                }
            }
        }

        $scope.settings = {

            stretchH: 'all',
            contextMenu: [
                
                'undo','redo'
            ],
            formulas: false
        }
          $scope.checkAll = function () {
        if ($scope.selectedAll) {
            $scope.selectedAll = true;
        } else {
            $scope.selectedAll = false;
        }
        angular.forEach($scope.columns, function (column) {
            column.selected = $scope.selectedAll;
        });

    };
    
        $scope.checkNow = function(index){
           
            $scope['check_'+index] = true;
        }
        $scope.displayColumns = function(key) {
            
            var colArray = [];
            angular.forEach(vm.datasetColumns, function(val,key){
                if(val == true){
                    colArray.push(key);
                }
            });
            //console.log(colArray);
            if ($scope.showDataset == true) {
                var filteredColumns = [];
                angular.forEach(recordsArray, function(value, key) {
                    var oneRow = {};
                    angular.forEach(value, function(iVal, iKey) {
                        angular.forEach(colArray, function(colVal) {
                            oneRow[colVal] = value[colVal];
                        });
                    });
                    filteredColumns.push(oneRow);
                });
                vm.items = filteredColumns;
                //console.log(filteredColumns);
            }
        }

        $scope.previewData = function() {
            if ($scope.showDataset == true) {
                $scope.showHot = true;
                $scope.displayColumns();
            } else {
                $scope.showHot = false;
            }
        }
        var uploadedSubsetId = '';
        $scope.saveSubset = function(ev) {
            var confirm = $mdDialog.prompt()
                .title('By what name you want to save your subset?')
                .textContent('Enter your new subset name.')
                .placeholder('Subset Name')
                .ariaLabel('Subset Name')
                .initialValue('')
                .targetEvent(ev)
                .ok('Save Sub-Set')
                .cancel('Don\'t want to save');

            $mdDialog.show(confirm).then(function(result) {
                
                $scope.isDisabled = true;
                $scope.isLoading = true;
                if (vm.datasetColumns === undefined) {
                    var columnsArray = {};
                    angular.forEach(responseData.records.records[0], function(v,k){
                        if(k != 'id'){
                            columnsArray[k] = 'true';
                        }
                    });
                    vm.datasetColumns = columnsArray;
                }
                var formData = new FormData();
                formData.append('dataset_id', $state.params.id);
                formData.append('subset_columns', JSON.stringify(vm.datasetColumns));
                formData.append('subset_name', result);
                formData.append('column_key',$scope.column_key);
                formData.append('column_val',$scope.column_val);
                api.postMethod.saveSubset(formData).then(function(res) {
                    $scope.isLoading = false;
                    $scope.isDisabled = false;
                    if(res.data.status == 'error') {
                        $scope.error_message = res.data.message;
                    }else{
                        $mdToast.show(
                            $mdToast.simple()
                            .textContent('Sub-set Created Successfully!')
                            .position('top right')
                            .hideDelay(5000)
                        );
                        if($state.params.wizard == ''){
                            $state.go('app.dataset_view', {
                                'id': res.data.dataset_id
                            });
                        }else{
                            $scope.next = false;
                            $scope.disabelSkip = true;
                            uploadedSubsetId = res.data.dataset_id;
                        }
                        
                    }
                });
                
            }, function() {

            });
        }

         $scope.rowSubset = function(event) {
               $mdDialog.show({
                  clickOutsideToClose: true,
                  scope: $scope,        
                  preserveScope: true,           
                   templateUrl: '/app/main/dataset/include/_subset.html',
                  controller: ["$scope", "$mdDialog", function DialogController($scope, $mdDialog) {
                     $scope.closeDialog = function() {
                        $mdDialog.hide();
                     }
                  }]
               });
            };

        $scope.wizardSteps = false;
        $scope.wizardCheck = true;
        if($state.params.wizard != '' && $state.params.wizard == 'wizard'){
            $scope.wizardSteps = true;
            $scope.next = true;
            $scope.wizardCheck = false;
            var completedStatus = JSON.parse(sessionStorage.completedStatus);
            if(completedStatus.step1 == 1){
                $('.wiz_step1').html('&#9679;');
                $('.wiz_step1').addClass('completed');
            }
            //console.log(sessionStorage.completedStatus);
        }
        $scope.nextStep = function(){
            var completedStatus = JSON.parse(sessionStorage.completedStatus);
            completedStatus['step2'] = 1;
            sessionStorage.completedStatus = JSON.stringify(completedStatus);
            $state.go('app.column_validate',{'id':uploadedSubsetId, 'wizard':'wizard'});
        }
        $scope.skipStep = function(){
            var completedStatus = JSON.parse(sessionStorage.completedStatus);
            completedStatus['step2'] = 0;
            sessionStorage.completedStatus = JSON.stringify(completedStatus);
            $state.go('app.column_validate',{'id':$state.params.id, 'wizard':'wizard'});
        }
        $scope.deleteDataset = function(datasetID,ev){

            var confirm = $mdDialog.confirm({
              
                    onComplete: function afterShowAnimation() {
                        var $dialog = angular.element(document.querySelector('md-dialog'));
                        var $actionsSection = $dialog.find('md-dialog-actions');
                        var $cancelButton = $actionsSection.children()[0];
                        var $confirmButton = $actionsSection.children()[1];
                        angular.element($confirmButton).addClass('md-raised md-warn ph-15');
                        angular.element($cancelButton).addClass('md-raised ph-15');
                    }
                
            })
                  .title('Would you like to delete this dataset?')
                  .textContent('The Dataset will be deleted permanently and no longer accesible by any user.')
                  .ariaLabel('Delete Dataset')
                  .targetEvent(ev)
                  .ok('Yes, delete it!')
                  .cancel('No, don\'t delete');


            $mdDialog.show(confirm).then(function() {
              api.dataset.deleteDataset.get({'id':datasetID}, function(res){
                  if(res.status == 'success'){
                      $mdToast.show(
                       $mdToast.simple()
                          .textContent('Dataset deleted successfully!')
                          .position('top right')
                          .hideDelay(5000)
                      );
                      $state.go($state.current, {}, {reload: true});
                  }
              });
            }, function() {

            });
        }

    }

    function checkAuth($state) {

        if (sessionStorage.api_token == undefined || sessionStorage.api_token == '') {

            $state.go('app.new-login');
            return false;
        }
    }

    function arrayColumn(inputArray, columnKey, indexKey)
    {
            function isArray(inputValue)
            {
                return Object.prototype.toString.call(inputValue) === '[object Array]';
            }

            // If input array is an object instead of an array,
            // convert it to an array.
            if(!isArray(inputArray))
            {
                var newArray = [];
                for(var key in inputArray)
                {
                    if(!inputArray.hasOwnProperty(key))
                    {
                        continue;
                    }
                    newArray.push(inputArray[key]);
                }
                inputArray = newArray;
            }

            // Process the input array.
            var isReturnArray = (typeof indexKey === 'undefined' || indexKey === null);
            var outputArray = [];
            var outputObject = {};
            for(var inputIndex = 0; inputIndex < inputArray.length; inputIndex++)
            {
                var inputElement = inputArray[inputIndex];

                var outputElement;
                if(columnKey === null)
                {
                    outputElement = inputElement;
                }
                else
                {
                    if(isArray(inputElement))
                    {
                        if(columnKey < 0 || columnKey >= inputElement.length)
                        {
                            continue;
                        }
                    }
                    else
                    {
                        if(!inputElement.hasOwnProperty(columnKey))
                        {
                            continue;
                        }
                    }

                    outputElement = inputElement[columnKey];
                }

                if(isReturnArray)
                {
                    outputArray.push(outputElement);
                }
                else
                {
                    outputObject[inputElement[indexKey]] = outputElement;
                }
            }

            return (isReturnArray ? outputArray : outputObject);
        }

})();
(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "msApiProvider"];
    angular
        .module('app.dataset.create-dataset', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider, msApiProvider)
    {
        $stateProvider.state('app.dataset_create', {
            url    : '/dataset/create',
            views  : {
                'content@app': {
                    templateUrl: 'app/main/dataset/create-dataset/create-dataset.html',
                    controller : 'CreateDatasetController as vm'
                }
            }
        });

    }

})();
(function ()
{
    'use strict';

    CreateDatasetController.$inject = ["$scope", "api", "$mdToast", "$state"];
    angular
        .module('app.dataset.create-dataset')
       
        .controller('CreateDatasetController', CreateDatasetController);

    /** @ngInject */
    function CreateDatasetController($scope,api,$mdToast,$state){
        
      	$scope.listColumns = {}

      	$scope.drawColumn = function(){
      		var numColumns = [];
      		for(var i = 0; i < $scope.someModel; i++){
      			numColumns.push(i);
      		}
      		$scope.columns = numColumns;
      		console.log(numColumns);
      	}
        var vars = [];
        for(var i = 1; i<=100;i++){
          vars.push(i);
        }
        $scope.numberOfColumns = vars;
      	$scope.saveDatset = function(){
          
      		var formData = new FormData();
      		formData.append('dataset_name',$scope.datasetname);
          formData.append('number_of_columns',$scope.someModel);
      		formData.append('dataset_columns',JSON.stringify($scope.listColumns));
      		api.postMethod.saveNewDataset(formData).then(function(res){
            if(res.data.status == 'success'){
              $mdToast.show(
                 $mdToast.simple()
                    .textContent('Dataset Created Successfully!')
                    .position('top right')
                    .hideDelay(5000)
                );

              $state.go('app.dataset_list');
            }
      			console.log(res);
      		});
      	}
    }
    
   

})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "msApiProvider"];
    angular
        .module('app.dataset.column-validate', ['720kb.tooltips'])
        .config(config);

    /** @ngInject */
    function config($stateProvider, msApiProvider)
    {
        $stateProvider.state('app.column_validate', {
            url    : '/column/validate/:id/:wizard',
            views  : {
                'content@app': {
                    templateUrl: 'app/main/dataset/column-validate/column-validate.html',
                    controller : 'ColumnValidateController as vm'
                }
            },
        });


    }

})();

(function ()
{
    'use strict';

    ColumnValidateController.$inject = ["$state", "$scope", "api", "$mdToast", "$mdDialog"];
    DialogController.$inject = ["$scope", "$mdDialog", "dataColumns", "$compile", "api", "$state"];
    angular
        .module('app.dataset.column-validate')
        .controller('ColumnValidateController', ColumnValidateController);

    /** @ngInject */
    function ColumnValidateController($state, $scope, api, $mdToast, $mdDialog){

        if(checkAuth($state) == false){
            return false;
        }
        $scope.st = $state.current.name;
        var vm = this;
        $scope.dataset_id = $state.params.id;
        api.dataset.getLastColumns.get({'id':$state.params.id},function(res){
            console.log(res);
            if(res.status != 'error'){

                $scope.dataset_name = res.dataset_name;
                $scope.rawColumns = res.data.dataset_columns;
                $scope.columns = res.data.columns;
            }
        });



        $scope.goBack = function(){
            
            $state.go('app.dataset_list');
        }

        vm.dataset_id = $state.params.id;
        $scope.createColumn = function(ev){

            $mdDialog.show({
              controller: DialogController,
              locals:{dataColumns: $scope.columns},
              templateUrl: 'app/main/dataset/column-validate/create-column.html',
              parent: angular.element(document.body),
              targetEvent: ev,
              clickOutsideToClose: true,
              fullscreen: false // Only for -xs, -sm breakpoints.
            })
            .then(function(answer) {

            }, function() {
              
            });
        }

        $scope.SavevalidateColumns = function(){

            var columns = [];
            var types = []
            $(function(){

                $('input[name=column_name]').each(function(k){
                    columns.push($(this).val());
                });
                $('select[name=type]').each(function(k){
                    types.push($(this).val());
                });

            });
            var columnsAndTypes = {};
            for(var i = 0; i < columns.length; i++){

                columnsAndTypes[columns[i]] = types[i];
            }
            var sendData = new FormData();
            sendData.append('id',$state.params.id);
            sendData.append('columns',JSON.stringify(columnsAndTypes));
            sendData.append('create_columns',$('#colData').val());
            api.postMethod.saveDatasetColumns(sendData).then(function(res){
                $mdToast.show(
                 $mdToast.simple()
                    .textContent('Data Definition Saved Successfully!')
                    .position('top right')
                    .hideDelay(5000)
                );
                
                if($state.params.wizard == ''){
                    //$state.go('app.dataset_list');
                }else{
                    $scope.next = false;
                    $scope.skipThisStep = true;
                }
            });
        }

        $scope.wizardSteps = false;
        $scope.wizardCheck = true;
        if($state.params.wizard != '' && $state.params.wizard == 'wizard'){
            $scope.wizardSteps = true;
            $scope.next = true;
            $scope.skipThisStep = false;
            $scope.wizardCheck = false;
            var completedStatus = JSON.parse(sessionStorage.completedStatus);
            if(completedStatus.step1 == 1){
                $('.wiz_step1').html('&#9679;');
                $('.wiz_step1').addClass('completed');
            }else{
                $('.wiz_step1').html('&#x25CB;');
            }
            if(completedStatus.step2 == 1){
                $('.wiz_step2').html('&#9679;');
                $('.wiz_step2').addClass('completed');
            }else{
                $('.wiz_step2').html('&#9678;');
                $('.wiz_step2').addClass('skipped');
            }
        }
        $scope.nextStep = function(ev){
            
            var confirm = $mdDialog.prompt()
                .title('By what name you want to create your visualizations?')
                .textContent('This step going to create your visualizations with current created dataset for future use.')
                .placeholder('Visualization Name')
                .ariaLabel('Visualization Name')
                .initialValue('')
                .targetEvent(ev)
                .ok('Save Visualization')
                .cancel('Don\'t want to save');
            $mdDialog.show(confirm).then(function(result) {
                var SendData = new FormData();
                SendData.append('dataset',$state.params.id);
                SendData.append('visual_name',result);
                api.postMethod.saveNewVisual(SendData).then(function(res){
                    $mdToast.show(
                     $mdToast.simple()
                        .textContent('Visualization Saved Successfully!')
                        .position('top right')
                        .hideDelay(5000)
                    );
                    $scope.isLoading = false;
                    var completedStatus = JSON.parse(sessionStorage.completedStatus);
                    completedStatus['step3'] = 1;
                    sessionStorage.completedStatus = JSON.stringify(completedStatus);
                    $state.go('app.visualizations_view',{'id':res.data.visual_id,'dataset':$state.params.id});
                });
            });
        }

       

        $scope.skipStep = function(ev){
            var confirm = $mdDialog.confirm()
                .title('Are you sure to skip this step?')
                .textContent('If you will skip this step, may some errors can occur during visualizations creation.')
                .ariaLabel('Skip Step')
                .targetEvent(ev)
                .ok('Yes, skip it!')
                .cancel('No, don\'t skip');

            $mdDialog.show(confirm).then(function() {
                var confirm = $mdDialog.prompt()
                    .title('By what name you want to create your visualizations?')
                    .textContent('This step going to create your visualizations with current created dataset for future use.')
                    .placeholder('Visualization Name')
                    .ariaLabel('Visualization Name')
                    .initialValue('')
                    .targetEvent(ev)
                    .ok('Save Visualization')
                    .cancel('Don\'t want to save');
                $mdDialog.show(confirm).then(function(result) {
                    var SendData = new FormData();
                    SendData.append('dataset',$state.params.id);
                    SendData.append('visual_name',result);
                    api.postMethod.saveNewVisual(SendData).then(function(res){
                        $mdToast.show(
                         $mdToast.simple()
                            .textContent('Visualization Saved Successfully!')
                            .position('top right')
                            .hideDelay(5000)
                        );
                        $scope.isLoading = false;
                        var completedStatus = JSON.parse(sessionStorage.completedStatus);
                        completedStatus['step3'] = 0;
                        sessionStorage.completedStatus = JSON.stringify(completedStatus);
                        window.wizard_error = 'You did not filtered your current visualization dataset, may some errors can occur while creating visualization.';
                        $state.go('app.visualizations_view',{'id':res.data.visual_id,'dataset':$state.params.id});
                    });
                });
            });
        }
         $scope.deleteDataset = function(datasetID,ev){

            var confirm = $mdDialog.confirm({
              
                    onComplete: function afterShowAnimation() {
                        var $dialog = angular.element(document.querySelector('md-dialog'));
                        var $actionsSection = $dialog.find('md-dialog-actions');
                        var $cancelButton = $actionsSection.children()[0];
                        var $confirmButton = $actionsSection.children()[1];
                        angular.element($confirmButton).addClass('md-raised md-warn ph-15');
                        angular.element($cancelButton).addClass('md-raised ph-15');
                    }
                
            })
                  .title('Would you like to delete this dataset?')
                  .textContent('The Dataset will be deleted permanently and no longer accesible by any user.')
                  .ariaLabel('Delete Dataset')
                  .targetEvent(ev)
                  .ok('Yes, delete it!')
                  .cancel('No, don\'t delete');


            $mdDialog.show(confirm).then(function() {
              api.dataset.deleteDataset.get({'id':datasetID}, function(res){
                  if(res.status == 'success'){
                      $mdToast.show(
                       $mdToast.simple()
                          .textContent('Dataset deleted successfully!')
                          .position('top right')
                          .hideDelay(5000)
                      );
                      $state.go($state.current, {}, {reload: true});
                  }
              });
            }, function() {

            });
        }
    }
    function DialogController($scope, $mdDialog, dataColumns, $compile, api, $state) {
        api.listdataset.list.get({},function(res){
            $scope.datasets = res.data;
            console.log(res);
        });
        $scope.getDatasetColumn = function(){
            api.dataset.getColumnsOfSelectedDataset.get({dataset_id: $scope.withDataset}, function(res){
                $scope.selectedDSColumns = res.columns;
                $scope.showDatsetColumns = true;
                $scope.showColumnReplaceWith = true;
            });
        }
        $scope.formulaDiv = true;
        $scope.setFormulacheck = function(){
            if($scope.setFormula == true){
                $scope.formulaDiv = true;
            }else{
                $scope.formulaDiv = false;
            }
        }
         $scope.showDiv = function(val){

             if(val == 'static'){
                $scope.showA = true;
                $scope.showB = false;
                $scope.showC = false;
                
            }else if(val == 'value_ref'){
                $scope.showA = false;
                $scope.showB = true;
                $scope.showC = false;
            }else if(val == 'formula'){
               $scope.showA = false;
                $scope.showB = false;
                $scope.showC = true;
            }

        }
        $scope.showDatasetFunc = function(){
            $scope.showDataset = true;
        }
        $scope.columns = dataColumns;
        $scope.createCol = function(){
            var cloneDiv = $('#cloneDiv').clone();
            cloneDiv.find('.colName').html($scope.colNm);
            cloneDiv.find('.colmType').val($scope.columnType);
            cloneDiv.attr('style','');
            $(cloneDiv).insertAfter('#'+$scope.columnAfter);
            var dataArray = [];
            if($('#colData').val() == ''){
                var newColumnsData = {};
                newColumnsData['col_name'] = $scope.colNm;
                newColumnsData['col_after'] = $scope.columnAfter;
                newColumnsData['col_type'] = $scope.columnType;
                newColumnsData['formula_type'] = $scope.applyOperation;
                var formulaData = {};
                if($scope.applyOperation == 'static'){
                    formulaData['static'] = $scope.static;
                }
                if($scope.applyOperation == 'value_ref'){
                    formulaData['current_dataset'] = $state.params.id;
                    formulaData['currentDSColumn'] = $scope.currentDSColumn;
                    formulaData['withDataset'] = $scope.withDataset;
                    formulaData['selecteddbColumn'] = $scope.selecteddbColumn;
                    formulaData['replaceWithColumn'] = $scope.replaceWithColumn;
                }
                if($scope.applyOperation == 'formula'){
                    formulaData['operation'] = $scope.operation;
                }
                newColumnsData['formula'] = formulaData;
                dataArray.push(newColumnsData);
                $('#colData').val(JSON.stringify(dataArray));
            }else{
                var newColumnsData = {};
                var oldArray = JSON.parse($('#colData').val());
                newColumnsData['col_name'] = $scope.colNm;
                newColumnsData['col_after'] = $scope.columnAfter;
                newColumnsData['col_type'] = $scope.columnType;
                newColumnsData['formula'] = $scope.formulaDiv;
                var formulaData = {};
                if($scope.applyOperation == 'static'){
                    formulaData['static'] = $scope.static;
                }
                if($scope.applyOperation == 'value_ref'){
                    formulaData['current_dataset'] = $state.params.id;
                    formulaData['currentDSColumn'] = $scope.currentDSColumn;
                    formulaData['withDataset'] = $scope.withDataset;
                    formulaData['selecteddbColumn'] = $scope.selecteddbColumn;
                    formulaData['replaceWithColumn'] = $scope.replaceWithColumn;
                }
                if($scope.applyOperation == 'formula'){
                    formulaData['operation'] = $scope.operation;
                }
                newColumnsData['formula'] = formulaData;
                oldArray.push(newColumnsData);
                $('#colData').val(JSON.stringify(oldArray));
            }
            $mdDialog.hide();
            /*console.log(JSON.parse($('#colData').val()));*/
        }
        $scope.hide = function() {
          $mdDialog.hide();
        };

        $scope.cancel = function() {
          $mdDialog.cancel();
        };

        $scope.answer = function(answer) {
          $mdDialog.hide(answer);
        };
    }
    function checkAuth($state){

        if(sessionStorage.api_token == undefined || sessionStorage.api_token == ''){

            $state.go('app.new-login');
            return false;
        }
    }

})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "msApiProvider"];
    angular
        .module('app.dataset.add-dataset', ['datatables'])
        .config(config);

    /** @ngInject */
    function config($stateProvider, msApiProvider)
    {
        $stateProvider.state('app.dataset_add', {
            url    : '/dataset/add',
            views  : {
                'content@app': {
                    templateUrl: 'app/main/dataset/add-dataset/add-dataset.html',
                    controller : 'AddDatasetController as vm'
                }
            }
        });

        
    }

})();
(function ()
{
    'use strict';

    AddDatasetController.$inject = ["AddDataset"];
    angular
        .module('app.dataset.add-dataset')
        .directive('ngFiles', ['$parse', function ($parse) {

            function fn_link(scope, element, attrs) {
                var onChange = $parse(attrs.ngFiles);
                element.on('change', function (event) {
                    onChange(scope, { $files: event.target.files });
                });
            };

            return {
                link: fn_link
            }
        } ])
        .controller('AddDatasetController', AddDatasetController);

    /** @ngInject */
    function AddDatasetController(AddDataset)
    {
        if(checkAuth($state) == false){
            return false;
        }
            var vm = this;

            // Data
            vm.datasets = ImportDataset.data;
            //console.log(ImportDataset);

         /* vm.dtOptions = {
                dom       : '<"top"f>rt<"bottom"<"left"<"length"l>><"right"<"info"i><"pagination"p>>>',
                pagingType: 'full_numbers',
                autoWidth : false,
                responsive: true
            };
*/
        // Methods

        //////////
    }

    function checkAuth($state){

        if(sessionStorage.api_token == undefined || sessionStorage.api_token == ''){

            $state.go('app.new-login');
            return false;
        }
    }

})();

(function ()
{
    'use strict';

    angular
        .module('app.core',
            [
                'ngAnimate',
                'ngAria',
                'ngCookies',
                'ngMessages',
                'ngResource',
                'ngSanitize',
                'ngMaterial',
                'pascalprecht.translate',
                'ui.router'
            ]);
})();

(function ()
{
    'use strict';

    MsWidgetController.$inject = ["$scope", "$element"];
    angular
        .module('app.core')
        .controller('MsWidgetController', MsWidgetController)
        .directive('msWidget', msWidgetDirective)
        .directive('msWidgetFront', msWidgetFrontDirective)
        .directive('msWidgetBack', msWidgetBackDirective);

    /** @ngInject */
    function MsWidgetController($scope, $element)
    {
        var vm = this;

        // Data
        vm.flipped = false;

        // Methods
        vm.flip = flip;

        //////////

        /**
         * Flip the widget
         */
        function flip()
        {
            if ( !isFlippable() )
            {
                return;
            }

            // Toggle flipped status
            vm.flipped = !vm.flipped;

            // Toggle the 'flipped' class
            $element.toggleClass('flipped', vm.flipped);
        }

        /**
         * Check if widget is flippable
         *
         * @returns {boolean}
         */
        function isFlippable()
        {
            return (angular.isDefined($scope.flippable) && $scope.flippable === true);
        }
    }

    /** @ngInject */
    function msWidgetDirective()
    {
        return {
            restrict  : 'E',
            scope     : {
                flippable: '=?'
            },
            controller: 'MsWidgetController',
            transclude: true,
            compile   : function (tElement)
            {
                tElement.addClass('ms-widget');

                return function postLink(scope, iElement, iAttrs, MsWidgetCtrl, transcludeFn)
                {
                    // Custom transclusion
                    transcludeFn(function (clone)
                    {
                        iElement.empty();
                        iElement.append(clone);
                    });

                    //////////
                };
            }
        };
    }

    /** @ngInject */
    function msWidgetFrontDirective()
    {
        return {
            restrict  : 'E',
            require   : '^msWidget',
            transclude: true,
            compile   : function (tElement)
            {
                tElement.addClass('ms-widget-front');

                return function postLink(scope, iElement, iAttrs, MsWidgetCtrl, transcludeFn)
                {
                    // Custom transclusion
                    transcludeFn(function (clone)
                    {
                        iElement.empty();
                        iElement.append(clone);
                    });

                    // Methods
                    scope.flipWidget = MsWidgetCtrl.flip;
                };
            }
        };
    }

    /** @ngInject */
    function msWidgetBackDirective()
    {
        return {
            restrict  : 'E',
            require   : '^msWidget',
            transclude: true,
            compile   : function (tElement)
            {
                tElement.addClass('ms-widget-back');

                return function postLink(scope, iElement, iAttrs, MsWidgetCtrl, transcludeFn)
                {
                    // Custom transclusion
                    transcludeFn(function (clone)
                    {
                        iElement.empty();
                        iElement.append(clone);
                    });

                    // Methods
                    scope.flipWidget = MsWidgetCtrl.flip;
                };
            }
        };
    }

})();
(function ()
{
    'use strict';

    msTimelineItemDirective.$inject = ["$timeout", "$q"];
    angular
        .module('app.core')
        .controller('MsTimelineController', MsTimelineController)
        .directive('msTimeline', msTimelineDirective)
        .directive('msTimelineItem', msTimelineItemDirective);

    /** @ngInject */
    function MsTimelineController()
    {
        var vm = this;

        // Data
        vm.scrollEl = undefined;

        // Methods
        vm.setScrollEl = setScrollEl;
        vm.getScrollEl = getScrollEl;

        //////////

        /**
         * Set scroll element
         *
         * @param scrollEl
         */
        function setScrollEl(scrollEl)
        {
            vm.scrollEl = scrollEl;
        }

        /**
         * Get scroll element
         *
         * @returns {undefined|*}
         */
        function getScrollEl()
        {
            return vm.scrollEl;
        }
    }

    /** @ngInject */
    function msTimelineDirective()
    {
        return {
            scope     : {
                msTimeline: '=?',
                loadMore  : '&?msTimelineLoadMore'
            },
            controller: 'MsTimelineController',
            compile   : function (tElement)
            {
                tElement.addClass('ms-timeline');

                return function postLink(scope, iElement, iAttrs, MsTimelineCtrl)
                {
                    // Create an element for triggering the load more action and append it
                    var loadMoreEl = angular.element('<div class="ms-timeline-loader md-accent-bg md-whiteframe-4dp"><span class="spinner animate-rotate"></span></div>');
                    iElement.append(loadMoreEl);

                    // Default config
                    var config = {
                        scrollEl: '#content'
                    };

                    // Extend the configuration
                    config = angular.extend(config, scope.msTimeline, {});
                    
                    // Grab the scrollable element and store it in the controller for general use
                    var scrollEl = angular.element(config.scrollEl);
                    MsTimelineCtrl.setScrollEl(scrollEl);

                    // Threshold
                    var threshold = 144;

                    // Register onScroll event for the first time
                    registerOnScroll();

                    /**
                     * onScroll Event
                     */
                    function onScroll()
                    {
                        if ( scrollEl.scrollTop() + scrollEl.height() + threshold > loadMoreEl.position().top )
                        {
                            // Show the loader
                            loadMoreEl.addClass('show');

                            // Unregister scroll event to prevent triggering the function over and over again
                            unregisterOnScroll();

                            // Trigger load more event
                            scope.loadMore().then(
                                // Success
                                function ()
                                {
                                    // Hide the loader
                                    loadMoreEl.removeClass('show');

                                    // Register the onScroll event again
                                    registerOnScroll();
                                },

                                // Error
                                function ()
                                {
                                    // Remove the loader completely
                                    loadMoreEl.remove();
                                }
                            );
                        }
                    }

                    /**
                     * onScroll event registerer
                     */
                    function registerOnScroll()
                    {
                        scrollEl.on('scroll', onScroll);
                    }

                    /**
                     * onScroll event unregisterer
                     */
                    function unregisterOnScroll()
                    {
                        scrollEl.off('scroll', onScroll);
                    }

                    // Cleanup
                    scope.$on('$destroy', function ()
                    {
                        unregisterOnScroll();
                    });
                };
            }
        };
    }

    /** @ngInject */
    function msTimelineItemDirective($timeout, $q)
    {
        return {
            scope  : true,
            require: '^msTimeline',
            compile: function (tElement)
            {
                tElement.addClass('ms-timeline-item').addClass('hidden');

                return function postLink(scope, iElement, iAttrs, MsTimelineCtrl)
                {
                    var threshold = 72,
                        itemLoaded = false,
                        itemInViewport = false,
                        scrollEl = MsTimelineCtrl.getScrollEl();

                    //////////

                    init();

                    /**
                     * Initialize
                     */
                    function init()
                    {
                        // Check if the timeline item has ms-card
                        if ( iElement.find('ms-card') )
                        {
                            // If the ms-card template loaded...
                            scope.$on('msCard::cardTemplateLoaded', function (event, args)
                            {
                                var cardEl = angular.element(args[0]);

                                // Test the card to see if there is any image on it
                                testForImage(cardEl).then(function ()
                                {
                                    $timeout(function ()
                                    {
                                        itemLoaded = true;
                                    });
                                });
                            });
                        }
                        else
                        {
                            // Test the element to see if there is any image on it
                            testForImage(iElement).then(function ()
                            {
                                $timeout(function ()
                                {
                                    itemLoaded = true;
                                });
                            });
                        }

                        // Check if the loaded element also in the viewport
                        scrollEl.on('scroll', testForVisibility);

                        // Test for visibility for the first time without waiting for the scroll event
                        testForVisibility();
                    }

                    // Item ready watcher
                    var itemReadyWatcher = scope.$watch(
                        function ()
                        {
                            return itemLoaded && itemInViewport;
                        },
                        function (current, old)
                        {
                            if ( angular.equals(current, old) )
                            {
                                return;
                            }

                            if ( current )
                            {
                                iElement.removeClass('hidden').addClass('animate');

                                // Unbind itemReadyWatcher
                                itemReadyWatcher();
                            }
                        }, true);

                    /**
                     * Test the given element for image
                     *
                     * @param element
                     * @returns promise
                     */
                    function testForImage(element)
                    {
                        var deferred = $q.defer(),
                            imgEl = element.find('img');

                        if ( imgEl.length > 0 )
                        {
                            imgEl.on('load', function ()
                            {
                                deferred.resolve('Image is loaded');
                            });
                        }
                        else
                        {
                            deferred.resolve('No images');
                        }

                        return deferred.promise;
                    }

                    /**
                     * Test the element for visibility
                     */
                    function testForVisibility()
                    {
                        if ( scrollEl.scrollTop() + scrollEl.height() > iElement.position().top + threshold )
                        {
                            $timeout(function ()
                            {
                                itemInViewport = true;
                            });

                            // Unbind the scroll event
                            scrollEl.off('scroll', testForVisibility);
                        }
                    }
                };
            }
        };
    }
})();
(function ()
{
    'use strict';

    msSplashScreenDirective.$inject = ["$animate"];
    angular
        .module('app.core')
        .directive('msSplashScreen', msSplashScreenDirective);

    /** @ngInject */
    function msSplashScreenDirective($animate)
    {
        return {
            restrict: 'E',
            link    : function (scope, iElement)
            {
                var splashScreenRemoveEvent = scope.$on('msSplashScreen::remove', function ()
                {
                    $animate.leave(iElement).then(function ()
                    {
                        // De-register scope event
                        splashScreenRemoveEvent();

                        // Null-ify everything else
                        scope = iElement = null;
                    });
                });
            }
        };
    }
})();
(function ()
{
    'use strict';

    MsStepperController.$inject = ["$timeout"];
    msVerticalStepperDirective.$inject = ["$timeout"];
    angular
        .module('app.core')
        .controller('MsStepperController', MsStepperController)
        .directive('msHorizontalStepper', msHorizontalStepperDirective)
        .directive('msHorizontalStepperStep', msHorizontalStepperStepDirective)
        .directive('msVerticalStepper', msVerticalStepperDirective)
        .directive('msVerticalStepperStep', msVerticalStepperStepDirective);

    /** @ngInject */
    function MsStepperController($timeout)
    {
        var vm = this;

        // Data
        vm.mainForm = undefined;

        vm.orientation = 'horizontal';
        vm.steps = [];
        vm.currentStep = undefined;
        vm.currentStepNumber = 1;

        // Methods
        vm.setOrientation = setOrientation;
        vm.registerMainForm = registerMainForm;
        vm.registerStep = registerStep;
        vm.setupSteps = setupSteps;
        vm.resetForm = resetForm;

        vm.setCurrentStep = setCurrentStep;

        vm.gotoStep = gotoStep;
        vm.gotoPreviousStep = gotoPreviousStep;
        vm.gotoNextStep = gotoNextStep;
        vm.gotoFirstStep = gotoFirstStep;
        vm.gotoLastStep = gotoLastStep;

        vm.isFirstStep = isFirstStep;
        vm.isLastStep = isLastStep;

        vm.isStepCurrent = isStepCurrent;
        vm.isStepDisabled = isStepDisabled;
        vm.isStepOptional = isStepOptional;
        vm.isStepHidden = isStepHidden;
        vm.filterHiddenStep = filterHiddenStep;
        vm.isStepValid = isStepValid;
        vm.isStepNumberValid = isStepNumberValid;

        vm.isFormValid = isFormValid;

        //////////

        /**
         * Set the orientation of the stepper
         *
         * @param orientation
         */
        function setOrientation(orientation)
        {
            vm.orientation = orientation || 'horizontal';
        }

        /**
         * Register the main form
         *
         * @param form
         */
        function registerMainForm(form)
        {
            vm.mainForm = form;
        }

        /**
         * Register a step
         *
         * @param element
         * @param scope
         * @param form
         */
        function registerStep(element, scope, form)
        {
            var step = {
                element           : element,
                scope             : scope,
                form              : form,
                stepNumber        : scope.step || (vm.steps.length + 1),
                stepTitle         : scope.stepTitle,
                stepTitleTranslate: scope.stepTitleTranslate
            };

            // Push the step into steps array
            vm.steps.push(step);

            // Sort steps by stepNumber
            vm.steps.sort(function (a, b)
            {
                return a.stepNumber - b.stepNumber;
            });

            return step;
        }

        /**
         * Setup steps for the first time
         */
        function setupSteps()
        {
            vm.setCurrentStep(vm.currentStepNumber);
        }

        /**
         * Reset steps and the main form
         */
        function resetForm()
        {
            // Timeout is required here because we need to
            // let form model to reset before setting the
            // statuses
            $timeout(function ()
            {
                // Reset all the steps
                for ( var x = 0; x < vm.steps.length; x++ )
                {
                    vm.steps[x].form.$setPristine();
                    vm.steps[x].form.$setUntouched();
                }

                // Reset the main form
                vm.mainForm.$setPristine();
                vm.mainForm.$setUntouched();

                // Go to first step
                gotoFirstStep();
            });
        }

        /**
         * Set current step
         *
         * @param stepNumber
         */
        function setCurrentStep(stepNumber)
        {
            // If the stepNumber is not a valid step number, bail...
            if ( !isStepNumberValid(stepNumber) )
            {
                return;
            }

            // Update the current step number
            vm.currentStepNumber = stepNumber;

            if ( vm.orientation === 'horizontal' )
            {
                // Hide all steps
                for ( var i = 0; i < vm.steps.length; i++ )
                {
                    vm.steps[i].element.hide();
                }

                // Show the current step
                vm.steps[vm.currentStepNumber - 1].element.show();
            }
            else if ( vm.orientation === 'vertical' )
            {
                // Hide all step content
                for ( var j = 0; j < vm.steps.length; j++ )
                {
                    vm.steps[j].element.find('.ms-stepper-step-content').hide();
                }

                // Show the current step content
                vm.steps[vm.currentStepNumber - 1].element.find('.ms-stepper-step-content').show();
            }
        }

        /**
         * Go to a step
         *
         * @param stepNumber
         */
        function gotoStep(stepNumber)
        {
            // If the step we are about to go
            // is hidden, bail...
            if ( isStepHidden(stepNumber) )
            {
                return;
            }

            vm.setCurrentStep(stepNumber);
        }

        /**
         * Go to the previous step
         */
        function gotoPreviousStep()
        {
            var stepNumber = vm.currentStepNumber - 1;

            // Test the previous steps and make sure we
            // will land to the one that is not hidden
            for ( var s = stepNumber; s >= 1; s-- )
            {
                if ( !isStepHidden(s) )
                {
                    stepNumber = s;
                    break;
                }
            }

            vm.setCurrentStep(stepNumber);
        }

        /**
         * Go to the next step
         */
        function gotoNextStep()
        {
            var stepNumber = vm.currentStepNumber + 1;

            // Test the following steps and make sure we
            // will land to the one that is not hidden
            for ( var s = stepNumber; s <= vm.steps.length; s++ )
            {
                if ( !isStepHidden(s) )
                {
                    stepNumber = s;
                    break;
                }
            }

            vm.setCurrentStep(stepNumber);
        }

        /**
         * Go to the first step
         */
        function gotoFirstStep()
        {
            vm.setCurrentStep(1);
        }

        /**
         * Go to the last step
         */
        function gotoLastStep()
        {
            vm.setCurrentStep(vm.steps.length);
        }

        /**
         * Check if the current step is the first step
         *
         * @returns {boolean}
         */
        function isFirstStep()
        {
            return vm.currentStepNumber === 1;
        }

        /**
         * Check if the current step is the last step
         *
         * @returns {boolean}
         */
        function isLastStep()
        {
            return vm.currentStepNumber === vm.steps.length;
        }

        /**
         * Check if the given step is the current one
         *
         * @param stepNumber
         * @returns {null|boolean}
         */
        function isStepCurrent(stepNumber)
        {
            // If the stepNumber is not a valid step number, bail...
            if ( !isStepNumberValid(stepNumber) )
            {
                return null;
            }

            return vm.currentStepNumber === stepNumber;
        }

        /**
         * Check if the given step should be disabled
         *
         * @param stepNumber
         * @returns {null|boolean}
         */
        function isStepDisabled(stepNumber)
        {
            // If the stepNumber is not a valid step number, bail...
            if ( !isStepNumberValid(stepNumber) )
            {
                return null;
            }

            var disabled = false;

            for ( var i = 1; i < stepNumber; i++ )
            {
                if ( !isStepValid(i) )
                {
                    disabled = true;
                    break;
                }
            }

            return disabled;
        }

        /**
         * Check if the given step is optional
         *
         * @param stepNumber
         * @returns {null|boolean}
         */
        function isStepOptional(stepNumber)
        {
            // If the stepNumber is not a valid step number, bail...
            if ( !isStepNumberValid(stepNumber) )
            {
                return null;
            }

            return vm.steps[stepNumber - 1].scope.optionalStep;
        }

        /**
         * Check if the given step is hidden
         *
         * @param stepNumber
         * @returns {null|boolean}
         */
        function isStepHidden(stepNumber)
        {
            // If the stepNumber is not a valid step number, bail...
            if ( !isStepNumberValid(stepNumber) )
            {
                return null;
            }

            return !!vm.steps[stepNumber - 1].scope.hideStep;
        }

        /**
         * Check if the given step is hidden as a filter
         *
         * @param step
         * @returns {boolean}
         */
        function filterHiddenStep(step)
        {
            return !isStepHidden(step.stepNumber);
        }

        /**
         * Check if the given step is valid
         *
         * @param stepNumber
         * @returns {null|boolean}
         */
        function isStepValid(stepNumber)
        {
            // If the stepNumber is not a valid step number, bail...
            if ( !isStepNumberValid(stepNumber) )
            {
                return null;
            }

            // If the step is optional, always return true
            if ( isStepOptional(stepNumber) )
            {
                return true;
            }

            return vm.steps[stepNumber - 1].form.$valid;
        }

        /**
         * Check if the given step number is a valid step number
         *
         * @param stepNumber
         * @returns {boolean}
         */
        function isStepNumberValid(stepNumber)
        {
            return !(angular.isUndefined(stepNumber) || stepNumber < 1 || stepNumber > vm.steps.length);
        }

        /**
         * Check if the entire form is valid
         *
         * @returns {boolean}
         */
        function isFormValid()
        {
            return vm.mainForm.$valid;
        }
    }

    /** @ngInject */
    function msHorizontalStepperDirective()
    {
        return {
            restrict        : 'A',
            scope           : {},
            require         : ['form', 'msHorizontalStepper'],
            priority        : 1001,
            controller      : 'MsStepperController as MsStepper',
            bindToController: {
                model: '=ngModel'
            },
            transclude      : true,
            templateUrl     : 'app/core/directives/ms-stepper/templates/horizontal/horizontal.html',
            compile         : function (tElement)
            {
                tElement.addClass('ms-stepper');

                return function postLink(scope, iElement, iAttrs, ctrls)
                {
                    var FormCtrl = ctrls[0],
                        MsStepperCtrl = ctrls[1];

                    // Register the main form and setup
                    // the steps for the first time
                    MsStepperCtrl.setOrientation('horizontal');
                    MsStepperCtrl.registerMainForm(FormCtrl);
                    MsStepperCtrl.setupSteps();
                };
            }
        };
    }

    /** @ngInject */
    function msHorizontalStepperStepDirective()
    {
        return {
            restrict: 'E',
            require : ['form', '^msHorizontalStepper'],
            priority: 1000,
            scope   : {
                step              : '=?',
                stepTitle         : '=?',
                stepTitleTranslate: '=?',
                optionalStep      : '=?',
                hideStep          : '=?'
            },
            compile : function (tElement)
            {
                tElement.addClass('ms-stepper-step');

                return function postLink(scope, iElement, iAttrs, ctrls)
                {
                    var FormCtrl = ctrls[0],
                        MsStepperCtrl = ctrls[1];

                    // Is it an optional step?
                    scope.optionalStep = angular.isDefined(iAttrs.optionalStep);

                    // Register the step
                    MsStepperCtrl.registerStep(iElement, scope, FormCtrl);

                    // Hide the step by default
                    iElement.hide();
                };
            }
        };
    }

    /** @ngInject */
    function msVerticalStepperDirective($timeout)
    {
        return {
            restrict        : 'A',
            scope           : {},
            require         : ['form', 'msVerticalStepper'],
            priority        : 1001,
            controller      : 'MsStepperController as MsStepper',
            bindToController: {
                model: '=ngModel'
            },
            transclude      : true,
            templateUrl     : 'app/core/directives/ms-stepper/templates/vertical/vertical.html',
            compile         : function (tElement)
            {
                tElement.addClass('ms-stepper');

                return function postLink(scope, iElement, iAttrs, ctrls)
                {
                    var FormCtrl = ctrls[0],
                        MsStepperCtrl = ctrls[1];

                    // Register the main form and setup
                    // the steps for the first time

                    // Timeout is required in vertical stepper
                    // as we are using transclusion in steps.
                    // We have to wait for them to be transcluded
                    // and registered to the controller
                    $timeout(function ()
                    {
                        MsStepperCtrl.setOrientation('vertical');
                        MsStepperCtrl.registerMainForm(FormCtrl);
                        MsStepperCtrl.setupSteps();
                    });
                };
            }
        };
    }

    /** @ngInject */
    function msVerticalStepperStepDirective()
    {
        return {
            restrict   : 'E',
            require    : ['form', '^msVerticalStepper'],
            priority   : 1000,
            scope      : {
                step              : '=?',
                stepTitle         : '=?',
                stepTitleTranslate: '=?',
                optionalStep      : '=?',
                hideStep          : '=?'
            },
            transclude : true,
            templateUrl: 'app/core/directives/ms-stepper/templates/vertical/step/vertical-step.html',
            compile    : function (tElement)
            {
                tElement.addClass('ms-stepper-step');

                return function postLink(scope, iElement, iAttrs, ctrls)
                {
                    var FormCtrl = ctrls[0],
                        MsStepperCtrl = ctrls[1];

                    // Is it an optional step?
                    scope.optionalStep = angular.isDefined(iAttrs.optionalStep);

                    // Register the step
                    scope.stepInfo = MsStepperCtrl.registerStep(iElement, scope, FormCtrl);

                    // Expose the controller to the scope
                    scope.MsStepper = MsStepperCtrl;

                    // Hide the step content by default
                    iElement.find('.ms-stepper-step-content').hide();
                };
            }
        };
    }
})();
(function ()
{
    'use strict';

    angular
        .module('app.core')
        .directive('msSidenavHelper', msSidenavHelperDirective);

    /** @ngInject */
    function msSidenavHelperDirective()
    {
        return {
            restrict: 'A',
            require : '^mdSidenav',
            link    : function (scope, iElement, iAttrs, MdSidenavCtrl)
            {
                // Watch md-sidenav open & locked open statuses
                // and add class to the ".page-layout" if only
                // the sidenav open and NOT locked open
                scope.$watch(function ()
                {
                    return MdSidenavCtrl.isOpen() && !MdSidenavCtrl.isLockedOpen();
                }, function (current)
                {
                    if ( angular.isUndefined(current) )
                    {
                        return;
                    }

                    iElement.parent().toggleClass('full-height', current);
                    angular.element('html').toggleClass('sidenav-open', current);
                });
            }
        };
    }
})();
(function ()
{
    'use strict';

    MsShortcutsController.$inject = ["$scope", "$cookies", "$document", "$timeout", "$q", "msNavigationService"];
    angular
        .module('app.core')
        .controller('MsShortcutsController', MsShortcutsController)
        .directive('msShortcuts', msShortcutsDirective);

    /** @ngInject */
    function MsShortcutsController($scope, $cookies, $document, $timeout, $q, msNavigationService)
    {
        var vm = this;

        // Data
        vm.query = '';
        vm.queryOptions = {
            debounce: 300
        };
        vm.resultsLoading = false;
        vm.selectedResultIndex = 0;
        vm.ignoreMouseEvents = false;
        vm.mobileBarActive = false;

        vm.results = null;
        vm.shortcuts = [];

        vm.sortableOptions = {
            ghostClass   : 'ghost',
            forceFallback: true,
            fallbackClass: 'dragging',
            onSort       : function ()
            {
                vm.saveShortcuts();
            }
        };

        // Methods
        vm.populateResults = populateResults;
        vm.loadShortcuts = loadShortcuts;
        vm.saveShortcuts = saveShortcuts;
        vm.addShortcut = addShortcut;
        vm.removeShortcut = removeShortcut;
        vm.handleResultClick = handleResultClick;

        vm.absorbEvent = absorbEvent;
        vm.handleKeydown = handleKeydown;
        vm.handleMouseenter = handleMouseenter;
        vm.temporarilyIgnoreMouseEvents = temporarilyIgnoreMouseEvents;
        vm.ensureSelectedResultIsVisible = ensureSelectedResultIsVisible;
        vm.toggleMobileBar = toggleMobileBar;

        //////////

        init();

        function init()
        {
            // Load the shortcuts
            vm.loadShortcuts().then(
                // Success
                function (response)
                {
                    vm.shortcuts = response;

                    // Add shortcuts as results by default
                    if ( vm.shortcuts.length > 0 )
                    {
                        vm.results = response;
                    }
                }
            );

            // Watch the model changes to trigger the search
            $scope.$watch('MsShortcuts.query', function (current, old)
            {
                if ( angular.isUndefined(current) )
                {
                    return;
                }

                if ( angular.equals(current, old) )
                {
                    return;
                }

                // Show the loader
                vm.resultsLoading = true;

                // Populate the results
                vm.populateResults().then(
                    // Success
                    function (response)
                    {
                        vm.results = response;
                    },
                    // Error
                    function ()
                    {
                        vm.results = [];
                    }
                ).finally(
                    function ()
                    {
                        // Hide the loader
                        vm.resultsLoading = false;
                    }
                );
            });
        }

        /**
         * Populate the results
         */
        function populateResults()
        {
            var results = [],
                flatNavigation = msNavigationService.getFlatNavigation(),
                deferred = $q.defer();

            // Iterate through the navigation array and
            // make sure it doesn't have any groups or
            // none ui-sref items
            for ( var x = 0; x < flatNavigation.length; x++ )
            {
                if ( flatNavigation[x].uisref )
                {
                    results.push(flatNavigation[x]);
                }
            }

            // If there is a query, filter the results
            if ( vm.query )
            {
                results = results.filter(function (item)
                {
                    if ( angular.lowercase(item.title).search(angular.lowercase(vm.query)) > -1 )
                    {
                        return true;
                    }
                });

                // Iterate through one last time and
                // add required properties to items
                for ( var i = 0; i < results.length; i++ )
                {
                    // Add false to hasShortcut by default
                    results[i].hasShortcut = false;

                    // Test if the item is in the shortcuts list
                    for ( var y = 0; y < vm.shortcuts.length; y++ )
                    {
                        if ( vm.shortcuts[y]._id === results[i]._id )
                        {
                            results[i].hasShortcut = true;
                            break;
                        }
                    }
                }
            }
            else
            {
                // If the query is empty, that means
                // there is nothing to search for so
                // we will populate the results with
                // current shortcuts if there is any
                if ( vm.shortcuts.length > 0 )
                {
                    results = vm.shortcuts;
                }
            }

            // Reset the selected result
            vm.selectedResultIndex = 0;

            // Fake the service delay
            $timeout(function ()
            {
                // Resolve the promise
                deferred.resolve(results);
            }, 250);

            // Return a promise
            return deferred.promise;
        }

        /**
         * Load shortcuts
         */
        function loadShortcuts()
        {
            var deferred = $q.defer();

            // For the demo purposes, we will
            // load the shortcuts from the cookies.
            // But here you can make an API call
            // to load them from the DB.
            var shortcuts = angular.fromJson($cookies.get('FUSE.shortcuts'));

            // No cookie available. Generate one
            // for the demo purposes...
            if ( angular.isUndefined(shortcuts) )
            {
                shortcuts = [
                    {
                        'title'      : 'Chat',
                        'icon'       : 'icon-hangouts',
                        'state'      : 'app.chat',
                        'badge'      : {
                            'content': 13,
                            'color'  : '#09d261'
                        },
                        'weight'     : 5,
                        'children'   : [],
                        '_id'        : 'chat',
                        '_path'      : 'apps.chat',
                        'uisref'     : 'app.chat',
                        'hasShortcut': true
                    }, {
                        'title'      : 'Contacts',
                        'icon'       : 'icon-account-box',
                        'state'      : 'app.contacts',
                        'weight'     : 10,
                        'children'   : [],
                        '_id'        : 'contacts',
                        '_path'      : 'apps.contacts',
                        'uisref'     : 'app.contacts',
                        'hasShortcut': true
                    }, {
                        'title'      : 'Notes',
                        'icon'       : 'icon-lightbulb',
                        'state'      : 'app.notes',
                        'weight'     : 11,
                        'children'   : [],
                        '_id'        : 'notes',
                        '_path'      : 'apps.notes',
                        'uisref'     : 'app.notes',
                        'hasShortcut': true
                    }
                ];

                $cookies.put('FUSE.shortcuts', angular.toJson(shortcuts));
            }

            // Resolve the promise
            deferred.resolve(shortcuts);

            return deferred.promise;
        }

        /**
         * Save the shortcuts
         */
        function saveShortcuts()
        {
            var deferred = $q.defer();

            // For the demo purposes, we will
            // keep the shortcuts in the cookies.
            // But here you can make an API call
            // to save them to the DB.
            $cookies.put('FUSE.shortcuts', angular.toJson(vm.shortcuts));

            // Fake the service delay
            $timeout(function ()
            {
                deferred.resolve({'success': true});
            }, 250);

            return deferred.promise;
        }

        /**
         * Add item as shortcut
         *
         * @param item
         */
        function addShortcut(item)
        {
            // Update the hasShortcut status
            item.hasShortcut = true;

            // Add as a shortcut
            vm.shortcuts.push(item);

            // Save the shortcuts
            vm.saveShortcuts();
        }

        /**
         * Remove item from shortcuts
         *
         * @param item
         */
        function removeShortcut(item)
        {
            // Update the hasShortcut status
            item.hasShortcut = false;

            // Remove the shortcut
            for ( var x = 0; x < vm.shortcuts.length; x++ )
            {
                if ( vm.shortcuts[x]._id === item._id )
                {
                    // Remove the x-th item from the array
                    vm.shortcuts.splice(x, 1);

                    // If we aren't searching for anything...
                    if ( !vm.query )
                    {
                        // If all the shortcuts have been removed,
                        // null-ify the results
                        if ( vm.shortcuts.length === 0 )
                        {
                            vm.results = null;
                        }
                        // Otherwise update the selected index
                        else
                        {
                            if ( x >= vm.shortcuts.length )
                            {
                                vm.selectedResultIndex = vm.shortcuts.length - 1;
                            }
                        }
                    }
                }
            }

            // Save the shortcuts
            vm.saveShortcuts();
        }

        /**
         * Handle the result click
         *
         * @param item
         */
        function handleResultClick(item)
        {
            // Add or remove the shortcut
            if ( item.hasShortcut )
            {
                vm.removeShortcut(item);
            }
            else
            {
                vm.addShortcut(item);
            }
        }

        /**
         * Absorb the given event
         *
         * @param event
         */
        function absorbEvent(event)
        {
            event.preventDefault();
        }

        /**
         * Handle keydown
         *
         * @param event
         */
        function handleKeydown(event)
        {
            var keyCode = event.keyCode,
                keys = [38, 40];

            // Prevent the default action if
            // one of the keys are pressed that
            // we are listening
            if ( keys.indexOf(keyCode) > -1 )
            {
                event.preventDefault();
            }

            switch ( keyCode )
            {
                // Enter
                case 13:

                    // Trigger result click
                    vm.handleResultClick(vm.results[vm.selectedResultIndex]);

                    break;

                // Up Arrow
                case 38:

                    // Decrease the selected result index
                    if ( vm.selectedResultIndex - 1 >= 0 )
                    {
                        // Decrease the selected index
                        vm.selectedResultIndex--;

                        // Make sure the selected result is in the view
                        vm.ensureSelectedResultIsVisible();
                    }

                    break;

                // Down Arrow
                case 40:

                    // Increase the selected result index
                    if ( vm.selectedResultIndex + 1 < vm.results.length )
                    {
                        // Increase the selected index
                        vm.selectedResultIndex++;

                        // Make sure the selected result is in the view
                        vm.ensureSelectedResultIsVisible();
                    }

                    break;

                default:
                    break;
            }
        }

        /**
         * Handle mouseenter
         *
         * @param index
         */
        function handleMouseenter(index)
        {
            if ( vm.ignoreMouseEvents )
            {
                return;
            }

            // Update the selected result index
            // with the given index
            vm.selectedResultIndex = index;
        }

        /**
         * Set a variable for a limited time
         * to make other functions to ignore
         * the mouse events
         */
        function temporarilyIgnoreMouseEvents()
        {
            // Set the variable
            vm.ignoreMouseEvents = true;

            // Cancel the previous timeout
            $timeout.cancel(vm.mouseEventIgnoreTimeout);

            // Set the timeout
            vm.mouseEventIgnoreTimeout = $timeout(function ()
            {
                vm.ignoreMouseEvents = false;
            }, 250);
        }

        /**
         * Ensure the selected result will
         * always be visible on the results
         * area
         */
        function ensureSelectedResultIsVisible()
        {
            var resultsEl = $document.find('#ms-shortcut-add-menu').find('.results'),
                selectedItemEl = angular.element(resultsEl.find('.result')[vm.selectedResultIndex]);

            if ( resultsEl && selectedItemEl )
            {
                var top = selectedItemEl.position().top - 8,
                    bottom = selectedItemEl.position().top + selectedItemEl.outerHeight() + 8;

                // Start ignoring mouse events
                vm.temporarilyIgnoreMouseEvents();

                if ( resultsEl.scrollTop() > top )
                {
                    resultsEl.scrollTop(top);
                }

                if ( bottom > (resultsEl.height() + resultsEl.scrollTop()) )
                {
                    resultsEl.scrollTop(bottom - resultsEl.height());
                }
            }
        }

        /**
         * Toggle mobile bar
         */
        function toggleMobileBar()
        {
            vm.mobileBarActive = !vm.mobileBarActive;
        }
    }

    /** @ngInject */
    function msShortcutsDirective()
    {
        return {
            restrict        : 'E',
            scope           : {},
            require         : 'msShortcuts',
            controller      : 'MsShortcutsController as MsShortcuts',
            bindToController: {},
            templateUrl     : 'app/core/directives/ms-shortcuts/ms-shortcuts.html',
            compile         : function (tElement)
            {
                // Add class
                tElement.addClass('ms-shortcuts');

                return function postLink(scope, iElement)
                {
                    // Data

                };
            }
        };
    }
})();
(function ()
{
    'use strict';

    MsSearchBarController.$inject = ["$scope", "$element", "$timeout"];
    msSearchBarDirective.$inject = ["$document"];
    angular
        .module('app.core')
        .controller('MsSearchBarController', MsSearchBarController)
        .directive('msSearchBar', msSearchBarDirective);

    /** @ngInject */
    function MsSearchBarController($scope, $element, $timeout)
    {
        var vm = this;

        // Data
        vm.collapsed = true;
        vm.query = '';
        vm.queryOptions = {
            debounce: vm.debounce || 0
        };
        vm.resultsLoading = false;
        vm.results = null;
        vm.selectedResultIndex = 0;
        vm.ignoreMouseEvents = false;

        // Methods
        vm.populateResults = populateResults;

        vm.expand = expand;
        vm.collapse = collapse;

        vm.absorbEvent = absorbEvent;
        vm.handleKeydown = handleKeydown;
        vm.handleMouseenter = handleMouseenter;
        vm.temporarilyIgnoreMouseEvents = temporarilyIgnoreMouseEvents;
        vm.handleResultClick = handleResultClick;
        vm.ensureSelectedResultIsVisible = ensureSelectedResultIsVisible;

        //////////

        init();

        function init()
        {
            // Watch the model changes to trigger the search
            $scope.$watch('MsSearchBar.query', function (current, old)
            {
                if ( angular.isUndefined(current) )
                {
                    return;
                }

                if ( angular.equals(current, old) )
                {
                    return;
                }

                if ( vm.collapsed )
                {
                    return;
                }

                // Evaluate the onSearch function to access the
                // function itself
                var onSearchEvaluated = $scope.$parent.$eval(vm.onSearch, {query: current}),
                    isArray = angular.isArray(onSearchEvaluated),
                    isPromise = (onSearchEvaluated && !!onSearchEvaluated.then);

                if ( isArray )
                {
                    // Populate the results
                    vm.populateResults(onSearchEvaluated);
                }

                if ( isPromise )
                {
                    // Show the loader
                    vm.resultsLoading = true;

                    onSearchEvaluated.then(
                        // Success
                        function (response)
                        {
                            // Populate the results
                            vm.populateResults(response);
                        },
                        // Error
                        function ()
                        {
                            // Assign an empty array to show
                            // the no-results screen
                            vm.populateResults([]);
                        }
                    ).finally(function ()
                        {
                            // Hide the loader
                            vm.resultsLoading = false;
                        }
                    );
                }
            });
        }

        /**
         * Populate the results
         *
         * @param results
         */
        function populateResults(results)
        {
            // Before doing anything,
            // make sure the search bar is expanded
            if ( vm.collapsed )
            {
                return;
            }

            var isArray = angular.isArray(results),
                isNull = results === null;

            // Only accept arrays and null values
            if ( !isArray && !isNull )
            {
                return;
            }

            // Reset the selected result
            vm.selectedResultIndex = 0;

            // Populate the results
            vm.results = results;
        }

        /**
         * Expand
         */
        function expand()
        {
            // Set collapsed status
            vm.collapsed = false;

            // Call expand on scope
            $scope.expand();

            // Callback
            if ( vm.onExpand && angular.isFunction(vm.onExpand) )
            {
                vm.onExpand();
            }
        }

        /**
         * Collapse
         */
        function collapse()
        {
            // Empty the query
            vm.query = '';

            // Empty results to hide the results view
            vm.populateResults(null);

            // Set collapsed status
            vm.collapsed = true;

            // Call collapse on scope
            $scope.collapse();

            // Callback
            if ( vm.onCollapse && angular.isFunction(vm.onCollapse) )
            {
                vm.onCollapse();
            }
        }

        /**
         * Absorb the given event
         *
         * @param event
         */
        function absorbEvent(event)
        {
            event.preventDefault();
        }

        /**
         * Handle keydown
         *
         * @param event
         */
        function handleKeydown(event)
        {
            var keyCode = event.keyCode,
                keys = [27, 38, 40];

            // Prevent the default action if
            // one of the keys are pressed that
            // we are listening
            if ( keys.indexOf(keyCode) > -1 )
            {
                event.preventDefault();
            }

            switch ( keyCode )
            {
                // Enter
                case 13:

                    // Trigger result click
                    vm.handleResultClick(vm.results[vm.selectedResultIndex]);

                    break;

                // Escape
                case 27:

                    // Collapse the search bar
                    vm.collapse();

                    break;

                // Up Arrow
                case 38:

                    // Decrease the selected result index
                    if ( vm.selectedResultIndex - 1 >= 0 )
                    {
                        // Decrease the selected index
                        vm.selectedResultIndex--;

                        // Make sure the selected result is in the view
                        vm.ensureSelectedResultIsVisible();
                    }

                    break;

                // Down Arrow
                case 40:

                    if ( !vm.results )
                    {
                        return;
                    }

                    // Increase the selected result index
                    if ( vm.selectedResultIndex + 1 < vm.results.length )
                    {
                        // Increase the selected index
                        vm.selectedResultIndex++;

                        // Make sure the selected result is in the view
                        vm.ensureSelectedResultIsVisible();
                    }

                    break;

                default:
                    break;
            }
        }

        /**
         * Handle mouseenter
         *
         * @param index
         */
        function handleMouseenter(index)
        {
            if ( vm.ignoreMouseEvents )
            {
                return;
            }

            // Update the selected result index
            // with the given index
            vm.selectedResultIndex = index;
        }

        /**
         * Set a variable for a limited time
         * to make other functions to ignore
         * the mouse events
         */
        function temporarilyIgnoreMouseEvents()
        {
            // Set the variable
            vm.ignoreMouseEvents = true;

            // Cancel the previous timeout
            $timeout.cancel(vm.mouseEventIgnoreTimeout);

            // Set the timeout
            vm.mouseEventIgnoreTimeout = $timeout(function ()
            {
                vm.ignoreMouseEvents = false;
            }, 250);
        }

        /**
         * Handle the result click
         *
         * @param item
         */
        function handleResultClick(item)
        {
            if ( vm.onResultClick )
            {
                vm.onResultClick({item: item});
            }

            // Collapse the search bar
            vm.collapse();
        }

        /**
         * Ensure the selected result will
         * always be visible on the results
         * area
         */
        function ensureSelectedResultIsVisible()
        {
            var resultsEl = $element.find('.ms-search-bar-results'),
                selectedItemEl = angular.element(resultsEl.find('.result')[vm.selectedResultIndex]);

            if ( resultsEl && selectedItemEl )
            {
                var top = selectedItemEl.position().top - 8,
                    bottom = selectedItemEl.position().top + selectedItemEl.outerHeight() + 8;

                // Start ignoring mouse events
                vm.temporarilyIgnoreMouseEvents();

                if ( resultsEl.scrollTop() > top )
                {
                    resultsEl.scrollTop(top);
                }

                if ( bottom > (resultsEl.height() + resultsEl.scrollTop()) )
                {
                    resultsEl.scrollTop(bottom - resultsEl.height());
                }
            }
        }
    }

    /** @ngInject */
    function msSearchBarDirective($document)
    {
        return {
            restrict        : 'E',
            scope           : {},
            require         : 'msSearchBar',
            controller      : 'MsSearchBarController as MsSearchBar',
            bindToController: {
                debounce     : '=?',
                onSearch     : '@',
                onResultClick: '&?',
                onExpand     : '&?',
                onCollapse   : '&?'
            },
            templateUrl     : 'app/core/directives/ms-search-bar/ms-search-bar.html',
            compile         : function (tElement)
            {
                // Add class
                tElement.addClass('ms-search-bar');

                return function postLink(scope, iElement)
                {
                    // Data
                    var inputEl,
                        bodyEl = $document.find('body');

                    // Methods
                    scope.collapse = collapse;
                    scope.expand = expand;

                    //////////

                    // Initialize
                    init();

                    /**
                     * Initialize
                     */
                    function init()
                    {
                        // Grab the input element
                        inputEl = iElement.find('#ms-search-bar-input');
                    }

                    /**
                     * Expand action
                     */
                    function expand()
                    {
                        // Add expanded class
                        iElement.addClass('expanded');

                        // Add helper class to the body
                        bodyEl.addClass('ms-search-bar-expanded');

                        // Focus on the input
                        inputEl.focus();
                    }

                    /**
                     * Collapse action
                     */
                    function collapse()
                    {
                        // Remove expanded class
                        iElement.removeClass('expanded');

                        // Remove helper class from the body
                        bodyEl.removeClass('ms-search-bar-expanded');
                    }
                };
            }
        };
    }
})();
(function ()
{
    'use strict';

    msScrollDirective.$inject = ["$timeout", "msScrollConfig", "msUtils", "fuseConfig"];
    angular
        .module('app.core')
        .provider('msScrollConfig', msScrollConfigProvider)
        .directive('msScroll', msScrollDirective);

    /** @ngInject */
    function msScrollConfigProvider()
    {
        // Default configuration
        var defaultConfiguration = {
            wheelSpeed            : 1,
            wheelPropagation      : false,
            swipePropagation      : true,
            minScrollbarLength    : null,
            maxScrollbarLength    : null,
            useBothWheelAxes      : false,
            useKeyboard           : true,
            suppressScrollX       : false,
            suppressScrollY       : false,
            scrollXMarginOffset   : 0,
            scrollYMarginOffset   : 0,
            stopPropagationOnClick: true
        };

        // Methods
        this.config = config;

        //////////

        /**
         * Extend default configuration with the given one
         *
         * @param configuration
         */
        function config(configuration)
        {
            defaultConfiguration = angular.extend({}, defaultConfiguration, configuration);
        }

        /**
         * Service
         */
        this.$get = function ()
        {
            var service = {
                getConfig: getConfig
            };

            return service;

            //////////

            /**
             * Return the config
             */
            function getConfig()
            {
                return defaultConfiguration;
            }
        };
    }

    /** @ngInject */
    function msScrollDirective($timeout, msScrollConfig, msUtils, fuseConfig)
    {
        return {
            restrict: 'AE',
            compile : function (tElement)
            {
                // Do not replace scrollbars if
                // 'disableCustomScrollbars' config enabled
                if ( fuseConfig.getConfig('disableCustomScrollbars') )
                {
                    return;
                }

                // Do not replace scrollbars on mobile devices
                // if 'disableCustomScrollbarsOnMobile' config enabled
                if ( fuseConfig.getConfig('disableCustomScrollbarsOnMobile') && msUtils.isMobile() )
                {
                    return;
                }

                // Add class
                tElement.addClass('ms-scroll');

                return function postLink(scope, iElement, iAttrs)
                {
                    var options = {};

                    // If options supplied, evaluate the given
                    // value. This is because we don't want to
                    // have an isolated scope but still be able
                    // to use scope variables.
                    // We don't want an isolated scope because
                    // we should be able to use this everywhere
                    // especially with other directives
                    if ( iAttrs.msScroll )
                    {
                        options = scope.$eval(iAttrs.msScroll);
                    }

                    // Extend the given config with the ones from provider
                    options = angular.extend({}, msScrollConfig.getConfig(), options);

                    // Initialize the scrollbar
                    $timeout(function ()
                    {
                        PerfectScrollbar.initialize(iElement[0], options);
                    }, 0);

                    // Update the scrollbar on element mouseenter
                    iElement.on('mouseenter', updateScrollbar);

                    // Watch scrollHeight and update
                    // the scrollbar if it changes
                    scope.$watch(function ()
                    {
                        return iElement.prop('scrollHeight');
                    }, function (current, old)
                    {
                        if ( angular.isUndefined(current) || angular.equals(current, old) )
                        {
                            return;
                        }

                        updateScrollbar();
                    });

                    // Watch scrollWidth and update
                    // the scrollbar if it changes
                    scope.$watch(function ()
                    {
                        return iElement.prop('scrollWidth');
                    }, function (current, old)
                    {
                        if ( angular.isUndefined(current) || angular.equals(current, old) )
                        {
                            return;
                        }

                        updateScrollbar();
                    });

                    /**
                     * Update the scrollbar
                     */
                    function updateScrollbar()
                    {
                        PerfectScrollbar.update(iElement[0]);
                    }

                    // Cleanup on destroy
                    scope.$on('$destroy', function ()
                    {
                        iElement.off('mouseenter');
                        PerfectScrollbar.destroy(iElement[0]);
                    });
                };
            }
        };
    }
})();
(function ()
{
    'use strict';

    angular
        .module('app.core')
        .directive('msResponsiveTable', msResponsiveTableDirective);

    /** @ngInject */
    function msResponsiveTableDirective()
    {
        return {
            restrict: 'A',
            link    : function (scope, iElement)
            {
                // Wrap the table
                var wrapper = angular.element('<div class="ms-responsive-table-wrapper"></div>');
                iElement.after(wrapper);
                wrapper.append(iElement);

                //////////
            }
        };
    }
})();
(function ()
{
    'use strict';

    angular
        .module('app.core')
        .directive('msRandomClass', msRandomClassDirective);

    /** @ngInject */
    function msRandomClassDirective()
    {
        return {
            restrict: 'A',
            scope   : {
                msRandomClass: '='
            },
            link    : function (scope, iElement)
            {
                var randomClass = scope.msRandomClass[Math.floor(Math.random() * (scope.msRandomClass.length))];
                iElement.addClass(randomClass);
            }
        };
    }
})();
(function ()
{
    'use strict';

    msNavIsFoldedDirective.$inject = ["$document", "$rootScope", "msNavFoldService"];
    msNavDirective.$inject = ["$rootScope", "$mdComponentRegistry", "msNavFoldService"];
    msNavToggleDirective.$inject = ["$rootScope", "$q", "$animate", "$state"];
    angular
        .module('app.core')
        .factory('msNavFoldService', msNavFoldService)
        .directive('msNavIsFolded', msNavIsFoldedDirective)
        .controller('MsNavController', MsNavController)
        .directive('msNav', msNavDirective)
        .directive('msNavTitle', msNavTitleDirective)
        .directive('msNavButton', msNavButtonDirective)
        .directive('msNavToggle', msNavToggleDirective);

    /** @ngInject */
    function msNavFoldService()
    {
        var foldable = {};

        var service = {
            setFoldable    : setFoldable,
            isNavFoldedOpen: isNavFoldedOpen,
            toggleFold     : toggleFold,
            openFolded     : openFolded,
            closeFolded    : closeFolded
        };

        return service;

        //////////

        /**
         * Set the foldable
         *
         * @param scope
         * @param element
         */
        function setFoldable(scope, element)
        {
            foldable = {
                'scope'  : scope,
                'element': element
            };
        }

        /**
         * Is folded open
         */
        function isNavFoldedOpen()
        {
            return foldable.scope.isNavFoldedOpen();
        }

        /**
         * Toggle fold
         */
        function toggleFold()
        {
            foldable.scope.toggleFold();
        }

        /**
         * Open folded navigation
         */
        function openFolded()
        {
            foldable.scope.openFolded();
        }

        /**
         * Close folded navigation
         */
        function closeFolded()
        {
            foldable.scope.closeFolded();
        }
    }

    /** @ngInject */
    function msNavIsFoldedDirective($document, $rootScope, msNavFoldService)
    {
        return {
            restrict: 'A',
            link    : function (scope, iElement, iAttrs)
            {
                var isFolded = (iAttrs.msNavIsFolded === 'true'),
                    isFoldedOpen = false,
                    body = angular.element($document[0].body),
                    openOverlay = angular.element('<div id="ms-nav-fold-open-overlay"></div>'),
                    closeOverlay = angular.element('<div id="ms-nav-fold-close-overlay"></div>'),
                    sidenavEl = iElement.parent();

                // Initialize the service
                msNavFoldService.setFoldable(scope, iElement, isFolded);

                // Set the fold status for the first time
                if ( isFolded )
                {
                    fold();
                }
                else
                {
                    unfold();
                }

                /**
                 * Is nav folded open
                 */
                function isNavFoldedOpen()
                {
                    return isFoldedOpen;
                }

                /**
                 * Toggle fold
                 */
                function toggleFold()
                {
                    isFolded = !isFolded;

                    if ( isFolded )
                    {
                        fold();
                    }
                    else
                    {
                        unfold();
                    }
                }

                /**
                 * Fold the navigation
                 */
                function fold()
                {
                    // Add classes
                    body.addClass('ms-nav-folded');

                    // Collapse everything and scroll to the top
                    $rootScope.$broadcast('msNav::forceCollapse');
                    iElement.scrollTop(0);

                    // Append the openOverlay to the element
                    sidenavEl.append(openOverlay);

                    // Event listeners
                    openOverlay.on('mouseenter touchstart', function (event)
                    {
                        openFolded(event);
                        isFoldedOpen = true;
                    });
                }

                /**
                 * Open folded navigation
                 */
                function openFolded(event)
                {
                    if ( angular.isDefined(event) )
                    {
                        event.preventDefault();
                    }

                    body.addClass('ms-nav-folded-open');

                    // Update the location
                    $rootScope.$broadcast('msNav::expandMatchingToggles');

                    // Remove open overlay
                    sidenavEl.find(openOverlay).remove();

                    // Append close overlay and bind its events
                    sidenavEl.parent().append(closeOverlay);
                    closeOverlay.on('mouseenter touchstart', function (event)
                    {
                        closeFolded(event);
                        isFoldedOpen = false;
                    });
                }

                /**
                 * Close folded navigation
                 */
                function closeFolded(event)
                {
                    if ( angular.isDefined(event) )
                    {
                        event.preventDefault();
                    }

                    // Collapse everything and scroll to the top
                    $rootScope.$broadcast('msNav::forceCollapse');
                    iElement.scrollTop(0);

                    body.removeClass('ms-nav-folded-open');

                    // Remove close overlay
                    sidenavEl.parent().find(closeOverlay).remove();

                    // Append open overlay and bind its events
                    sidenavEl.append(openOverlay);
                    openOverlay.on('mouseenter touchstart', function (event)
                    {
                        openFolded(event);
                        isFoldedOpen = true;
                    });
                }

                /**
                 * Unfold the navigation
                 */
                function unfold()
                {
                    body.removeClass('ms-nav-folded ms-nav-folded-open');

                    // Update the location
                    $rootScope.$broadcast('msNav::expandMatchingToggles');

                    iElement.off('mouseenter mouseleave');
                }

                // Expose functions to the scope
                scope.toggleFold = toggleFold;
                scope.openFolded = openFolded;
                scope.closeFolded = closeFolded;
                scope.isNavFoldedOpen = isNavFoldedOpen;

                // Cleanup
                scope.$on('$destroy', function ()
                {
                    openOverlay.off('mouseenter touchstart');
                    closeOverlay.off('mouseenter touchstart');
                    iElement.off('mouseenter mouseleave');
                });
            }
        };
    }


    /** @ngInject */
    function MsNavController()
    {
        var vm = this,
            disabled = false,
            toggleItems = [],
            lockedItems = [];

        // Data

        // Methods
        vm.isDisabled = isDisabled;
        vm.enable = enable;
        vm.disable = disable;
        vm.setToggleItem = setToggleItem;
        vm.getLockedItems = getLockedItems;
        vm.setLockedItem = setLockedItem;
        vm.clearLockedItems = clearLockedItems;

        //////////

        /**
         * Is navigation disabled
         *
         * @returns {boolean}
         */
        function isDisabled()
        {
            return disabled;
        }

        /**
         * Disable the navigation
         */
        function disable()
        {
            disabled = true;
        }

        /**
         * Enable the navigation
         */
        function enable()
        {
            disabled = false;
        }

        /**
         * Set toggle item
         *
         * @param element
         * @param scope
         */
        function setToggleItem(element, scope)
        {
            toggleItems.push({
                'element': element,
                'scope'  : scope
            });
        }

        /**
         * Get locked items
         *
         * @returns {Array}
         */
        function getLockedItems()
        {
            return lockedItems;
        }

        /**
         * Set locked item
         *
         * @param element
         * @param scope
         */
        function setLockedItem(element, scope)
        {
            lockedItems.push({
                'element': element,
                'scope'  : scope
            });
        }

        /**
         * Clear locked items list
         */
        function clearLockedItems()
        {
            lockedItems = [];
        }
    }

    /** @ngInject */
    function msNavDirective($rootScope, $mdComponentRegistry, msNavFoldService)
    {
        return {
            restrict  : 'E',
            scope     : {},
            controller: 'MsNavController',
            compile   : function (tElement)
            {
                tElement.addClass('ms-nav');

                return function postLink(scope)
                {
                    // Update toggle status according to the ui-router current state
                    $rootScope.$broadcast('msNav::expandMatchingToggles');

                    // Update toggles on state changes
                    var stateChangeSuccessEvent = $rootScope.$on('$stateChangeSuccess', function ()
                    {
                        $rootScope.$broadcast('msNav::expandMatchingToggles');

                        // Close navigation sidenav on stateChangeSuccess
                        $mdComponentRegistry.when('navigation').then(function (navigation)
                        {
                            navigation.close();

                            if ( msNavFoldService.isNavFoldedOpen() )
                            {
                                msNavFoldService.closeFolded();
                            }
                        });
                    });

                    // Cleanup
                    scope.$on('$destroy', function ()
                    {
                        stateChangeSuccessEvent();
                    });
                };
            }
        };
    }

    /** @ngInject */
    function msNavTitleDirective()
    {
        return {
            restrict: 'A',
            compile : function (tElement)
            {
                tElement.addClass('ms-nav-title');

                return function postLink()
                {

                };
            }
        };
    }

    /** @ngInject */
    function msNavButtonDirective()
    {
        return {
            restrict: 'AE',
            compile : function (tElement)
            {
                tElement.addClass('ms-nav-button');

                return function postLink()
                {

                };
            }
        };
    }

    /** @ngInject */
    function msNavToggleDirective($rootScope, $q, $animate, $state)
    {
        return {
            restrict: 'A',
            require : '^msNav',
            scope   : true,
            compile : function (tElement, tAttrs)
            {
                tElement.addClass('ms-nav-toggle');

                // Add collapsed attr
                if ( angular.isUndefined(tAttrs.collapsed) )
                {
                    tAttrs.collapsed = true;
                }

                tElement.attr('collapsed', tAttrs.collapsed);

                return function postLink(scope, iElement, iAttrs, MsNavCtrl)
                {
                    var classes = {
                        expanded         : 'expanded',
                        expandAnimation  : 'expand-animation',
                        collapseAnimation: 'collapse-animation'
                    };

                    // Store all related states
                    var links = iElement.find('a');
                    var states = [];
                    var regExp = /\(.*\)/g;

                    angular.forEach(links, function (link)
                    {
                        var state = angular.element(link).attr('ui-sref');

                        if ( angular.isUndefined(state) )
                        {
                            return;
                        }

                        // Remove any parameter definition from the state name before storing it
                        state = state.replace(regExp, '');

                        states.push(state);
                    });

                    // Store toggle-able element and its scope in the main nav controller
                    MsNavCtrl.setToggleItem(iElement, scope);

                    // Click handler
                    iElement.children('.ms-nav-button').on('click', toggle);

                    // Toggle function
                    function toggle()
                    {
                        // If navigation is disabled, do nothing...
                        if ( MsNavCtrl.isDisabled() )
                        {
                            return;
                        }

                        // Disable the entire navigation to prevent spamming
                        MsNavCtrl.disable();

                        if ( isCollapsed() )
                        {
                            // Clear the locked items list
                            MsNavCtrl.clearLockedItems();

                            // Emit pushToLockedList event
                            scope.$emit('msNav::pushToLockedList');

                            // Collapse everything but locked items
                            $rootScope.$broadcast('msNav::collapse');

                            // Expand and then...
                            expand().then(function ()
                            {
                                // Enable the entire navigation after animations completed
                                MsNavCtrl.enable();
                            });
                        }
                        else
                        {
                            // Collapse with all children
                            scope.$broadcast('msNav::forceCollapse');
                        }
                    }

                    // Cleanup
                    scope.$on('$destroy', function ()
                    {
                        iElement.children('.ms-nav-button').off('click');
                    });

                    /*---------------------*/
                    /* Scope Events        */
                    /*---------------------*/

                    /**
                     * Collapse everything but locked items
                     */
                    scope.$on('msNav::collapse', function ()
                    {
                        // Only collapse toggles that are not locked
                        var lockedItems = MsNavCtrl.getLockedItems();
                        var locked = false;

                        angular.forEach(lockedItems, function (lockedItem)
                        {
                            if ( angular.equals(lockedItem.scope, scope) )
                            {
                                locked = true;
                            }
                        });

                        if ( locked )
                        {
                            return;
                        }

                        // Collapse and then...
                        collapse().then(function ()
                        {
                            // Enable the entire navigation after animations completed
                            MsNavCtrl.enable();
                        });
                    });

                    /**
                     * Collapse everything
                     */
                    scope.$on('msNav::forceCollapse', function ()
                    {
                        // Collapse and then...
                        collapse().then(function ()
                        {
                            // Enable the entire navigation after animations completed
                            MsNavCtrl.enable();
                        });
                    });

                    /**
                     * Expand toggles that match with the current states
                     */
                    scope.$on('msNav::expandMatchingToggles', function ()
                    {
                        var currentState = $state.current.name;
                        var shouldExpand = false;

                        angular.forEach(states, function (state)
                        {
                            if ( currentState === state )
                            {
                                shouldExpand = true;
                            }
                        });

                        if ( shouldExpand )
                        {
                            expand();
                        }
                        else
                        {
                            collapse();
                        }
                    });

                    /**
                     * Add toggle to the locked list
                     */
                    scope.$on('msNav::pushToLockedList', function ()
                    {
                        // Set expanded item on main nav controller
                        MsNavCtrl.setLockedItem(iElement, scope);
                    });

                    /*---------------------*/
                    /* Internal functions  */
                    /*---------------------*/

                    /**
                     * Is element collapsed
                     *
                     * @returns {bool}
                     */
                    function isCollapsed()
                    {
                        return iElement.attr('collapsed') === 'true';
                    }

                    /**
                     * Is element expanded
                     *
                     * @returns {bool}
                     */
                    function isExpanded()
                    {
                        return !isCollapsed();
                    }

                    /**
                     * Expand the toggle
                     *
                     * @returns $promise
                     */
                    function expand()
                    {
                        // Create a new deferred object
                        var deferred = $q.defer();

                        // If the menu item is already expanded, do nothing..
                        if ( isExpanded() )
                        {
                            // Reject the deferred object
                            deferred.reject({'error': true});

                            // Return the promise
                            return deferred.promise;
                        }

                        // Set element attr
                        iElement.attr('collapsed', false);

                        // Grab the element to expand
                        var elementToExpand = angular.element(iElement.find('ms-nav-toggle-items')[0]);

                        // Move the element out of the dom flow and
                        // make it block so we can get its height
                        elementToExpand.css({
                            'position'  : 'absolute',
                            'visibility': 'hidden',
                            'display'   : 'block',
                            'height'    : 'auto'
                        });

                        // Grab the height
                        var height = elementToExpand[0].offsetHeight;

                        // Reset the style modifications
                        elementToExpand.css({
                            'position'  : '',
                            'visibility': '',
                            'display'   : '',
                            'height'    : ''
                        });

                        // Animate the height
                        scope.$evalAsync(function ()
                        {
                            $animate.animate(elementToExpand,
                                {
                                    'display': 'block',
                                    'height' : '0px'
                                },
                                {
                                    'height': height + 'px'
                                },
                                classes.expandAnimation
                            ).then(
                                function ()
                                {
                                    // Add expanded class
                                    elementToExpand.addClass(classes.expanded);

                                    // Clear the inline styles after animation done
                                    elementToExpand.css({'height': ''});

                                    // Resolve the deferred object
                                    deferred.resolve({'success': true});
                                }
                            );
                        });

                        // Return the promise
                        return deferred.promise;
                    }

                    /**
                     * Collapse the toggle
                     *
                     * @returns $promise
                     */
                    function collapse()
                    {
                        // Create a new deferred object
                        var deferred = $q.defer();

                        // If the menu item is already collapsed, do nothing..
                        if ( isCollapsed() )
                        {
                            // Reject the deferred object
                            deferred.reject({'error': true});

                            // Return the promise
                            return deferred.promise;
                        }

                        // Set element attr
                        iElement.attr('collapsed', true);

                        // Grab the element to collapse
                        var elementToCollapse = angular.element(iElement.find('ms-nav-toggle-items')[0]);

                        // Grab the height
                        var height = elementToCollapse[0].offsetHeight;

                        // Animate the height
                        scope.$evalAsync(function ()
                        {
                            $animate.animate(elementToCollapse,
                                {
                                    'height': height + 'px'
                                },
                                {
                                    'height': '0px'
                                },
                                classes.collapseAnimation
                            ).then(
                                function ()
                                {
                                    // Remove expanded class
                                    elementToCollapse.removeClass(classes.expanded);

                                    // Clear the inline styles after animation done
                                    elementToCollapse.css({
                                        'display': '',
                                        'height' : ''
                                    });

                                    // Resolve the deferred object
                                    deferred.resolve({'success': true});
                                }
                            );
                        });

                        // Return the promise
                        return deferred.promise;
                    }
                };
            }
        };
    }
})();
(function ()
{
    'use strict';

    MsNavigationController.$inject = ["$scope", "msNavigationService"];
    msNavigationDirective.$inject = ["$rootScope", "$timeout", "$mdSidenav", "msNavigationService"];
    MsNavigationNodeController.$inject = ["$scope", "$element", "$rootScope", "$animate", "$state", "msNavigationService"];
    msNavigationHorizontalDirective.$inject = ["msNavigationService"];
    MsNavigationHorizontalNodeController.$inject = ["$scope", "$element", "$rootScope", "$state", "msNavigationService"];
    msNavigationHorizontalItemDirective.$inject = ["$mdMedia"];
    angular
        .module('app.core')
        .provider('msNavigationService', msNavigationServiceProvider)
        .controller('MsNavigationController', MsNavigationController)
        // Vertical
        .directive('msNavigation', msNavigationDirective)
        .controller('MsNavigationNodeController', MsNavigationNodeController)
        .directive('msNavigationNode', msNavigationNodeDirective)
        .directive('msNavigationItem', msNavigationItemDirective)
        //Horizontal
        .directive('msNavigationHorizontal', msNavigationHorizontalDirective)
        .controller('MsNavigationHorizontalNodeController', MsNavigationHorizontalNodeController)
        .directive('msNavigationHorizontalNode', msNavigationHorizontalNodeDirective)
        .directive('msNavigationHorizontalItem', msNavigationHorizontalItemDirective);

    /** @ngInject */
    function msNavigationServiceProvider()
    {
        // Inject $log service
        var $log = angular.injector(['ng']).get('$log');

        // Navigation array
        var navigation = [];

        var service = this;

        // Methods
        service.saveItem = saveItem;
        service.deleteItem = deleteItem;
        service.sortByWeight = sortByWeight;

        //////////

        /**
         * Create or update the navigation item
         *
         * @param path
         * @param item
         */
        function saveItem(path, item)
        {
            if ( !angular.isString(path) )
            {
                $log.error('path must be a string (eg. `dashboard.project`)');
                return;
            }

            var parts = path.split('.');

            // Generate the object id from the parts
            var id = parts[parts.length - 1];

            // Get the parent item from the parts
            var parent = _findOrCreateParent(parts);

            // Decide if we are going to update or create
            var updateItem = false;

            for ( var i = 0; i < parent.length; i++ )
            {
                if ( parent[i]._id === id )
                {
                    updateItem = parent[i];

                    break;
                }
            }

            // Update
            if ( updateItem )
            {
                angular.extend(updateItem, item);

                // Add proper ui-sref
                updateItem.uisref = _getUiSref(updateItem);
            }
            // Create
            else
            {
                // Create an empty children array in the item
                item.children = [];

                // Add the default weight if not provided or if it's not a number
                if ( angular.isUndefined(item.weight) || !angular.isNumber(item.weight) )
                {
                    item.weight = 1;
                }

                // Add the item id
                item._id = id;

                // Add the item path
                item._path = path;

                // Add proper ui-sref
                item.uisref = _getUiSref(item);

                // Push the item into the array
                parent.push(item);
            }
        }

        /**
         * Delete navigation item
         *
         * @param path
         */
        function deleteItem(path)
        {
            if ( !angular.isString(path) )
            {
                $log.error('path must be a string (eg. `dashboard.project`)');
                return;
            }

            // Locate the item by using given path
            var item = navigation,
                parts = path.split('.');

            for ( var p = 0; p < parts.length; p++ )
            {
                var id = parts[p];

                for ( var i = 0; i < item.length; i++ )
                {
                    if ( item[i]._id === id )
                    {
                        // If we have a matching path,
                        // we have found our object:
                        // remove it.
                        if ( item[i]._path === path )
                        {
                            item.splice(i, 1);
                            return true;
                        }

                        // Otherwise grab the children of
                        // the current item and continue
                        item = item[i].children;
                        break;
                    }
                }
            }

            return false;
        }

        /**
         * Sort the navigation items by their weights
         *
         * @param parent
         */
        function sortByWeight(parent)
        {
            // If parent not provided, sort the root items
            if ( !parent )
            {
                parent = navigation;
                parent.sort(_byWeight);
            }

            // Sort the children
            for ( var i = 0; i < parent.length; i++ )
            {
                var children = parent[i].children;

                if ( children.length > 1 )
                {
                    children.sort(_byWeight);
                }

                if ( children.length > 0 )
                {
                    sortByWeight(children);
                }
            }
        }

        /* ----------------- */
        /* Private Functions */
        /* ----------------- */

        /**
         * Find or create parent
         *
         * @param parts
         * @returns {Array|Boolean}
         * @private
         */
        function _findOrCreateParent(parts)
        {
            // Store the main navigation
            var parent = navigation;

            // If it's going to be a root item
            // return the navigation itself
            if ( parts.length === 1 )
            {
                return parent;
            }

            // Remove the last element from the parts as
            // we don't need that to figure out the parent
            parts.pop();

            // Find and return the parent
            for ( var i = 0; i < parts.length; i++ )
            {
                var _id = parts[i],
                    createParent = true;

                for ( var p = 0; p < parent.length; p++ )
                {
                    if ( parent[p]._id === _id )
                    {
                        parent = parent[p].children;
                        createParent = false;

                        break;
                    }
                }

                // If there is no parent found, create one, push
                // it into the current parent and assign it as a
                // new parent
                if ( createParent )
                {
                    var item = {
                        _id     : _id,
                        _path   : parts.join('.'),
                        title   : _id,
                        weight  : 1,
                        children: []
                    };

                    parent.push(item);
                    parent = item.children;
                }
            }

            return parent;
        }

        /**
         * Sort by weight
         *
         * @param x
         * @param y
         * @returns {number}
         * @private
         */
        function _byWeight(x, y)
        {
            return parseInt(x.weight) - parseInt(y.weight);
        }

        /**
         * Setup the ui-sref using state & state parameters
         *
         * @param item
         * @returns {string}
         * @private
         */
        function _getUiSref(item)
        {
            var uisref = '';

            if ( angular.isDefined(item.state) )
            {
                uisref = item.state;

                if ( angular.isDefined(item.stateParams) && angular.isObject(item.stateParams) )
                {
                    uisref = uisref + '(' + angular.toJson(item.stateParams) + ')';
                }
            }

            return uisref;
        }

        /* ----------------- */
        /* Service           */
        /* ----------------- */

        this.$get = function ()
        {
            var activeItem = null,
                navigationScope = null,
                folded = null,
                foldedOpen = null;

            var service = {
                saveItem          : saveItem,
                deleteItem        : deleteItem,
                sort              : sortByWeight,
                clearNavigation   : clearNavigation,
                setActiveItem     : setActiveItem,
                getActiveItem     : getActiveItem,
                getNavigation     : getNavigation,
                getFlatNavigation : getFlatNavigation,
                setNavigationScope: setNavigationScope,
                setFolded         : setFolded,
                getFolded         : getFolded,
                setFoldedOpen     : setFoldedOpen,
                getFoldedOpen     : getFoldedOpen,
                toggleFolded      : toggleFolded
            };

            return service;

            //////////

            /**
             * Clear the entire navigation
             */
            function clearNavigation()
            {
                // Clear the navigation array
                navigation = [];

                // Clear the vm.navigation from main controller
                if ( navigationScope )
                {
                    navigationScope.vm.navigation = navigation;
                }
            }

            /**
             * Set active item
             *
             * @param node
             * @param scope
             */
            function setActiveItem(node, scope)
            {
                activeItem = {
                    node : node,
                    scope: scope
                };
            }

            /**
             * Return active item
             */
            function getActiveItem()
            {
                return activeItem;
            }

            /**
             * Return navigation array
             *
             * @param root
             * @returns Array
             */
            function getNavigation(root)
            {
                if ( root )
                {
                    for ( var i = 0; i < navigation.length; i++ )
                    {
                        if ( navigation[i]._id === root )
                        {
                            return [navigation[i]];
                        }
                    }

                    return null;
                }

                return navigation;
            }

            /**
             * Return flat navigation array
             *
             * @param root
             * @returns Array
             */
            function getFlatNavigation(root)
            {
                // Get the correct navigation array
                var navigation = getNavigation(root);

                // Flatten the navigation object
                return _flattenNavigation(navigation);
            }

            /**
             * Store navigation's scope for later use
             *
             * @param scope
             */
            function setNavigationScope(scope)
            {
                navigationScope = scope;
            }

            /**
             * Set folded status
             *
             * @param status
             */
            function setFolded(status)
            {
                folded = status;
            }

            /**
             * Return folded status
             *
             * @returns {*}
             */
            function getFolded()
            {
                return folded;
            }

            /**
             * Set folded open status
             *
             * @param status
             */
            function setFoldedOpen(status)
            {
                foldedOpen = status;
            }

            /**
             * Return folded open status
             *
             * @returns {*}
             */
            function getFoldedOpen()
            {
                return foldedOpen;
            }


            /**
             * Toggle fold on stored navigation's scope
             */
            function toggleFolded()
            {
                navigationScope.toggleFolded();
            }

            /**
             * Flatten the given navigation
             *
             * @param navigation
             * @private
             */
            function _flattenNavigation(navigation)
            {
                var flatNav = [];

                for ( var x = 0; x < navigation.length; x++ )
                {
                    // Copy and clear the children of the
                    // navigation that we want to push
                    var navToPush = angular.copy(navigation[x]);
                    navToPush.children = [];

                    // Push the item
                    flatNav.push(navToPush);

                    // If there are child items in this navigation,
                    // do some nested function magic
                    if ( navigation[x].children.length > 0 )
                    {
                        flatNav = flatNav.concat(_flattenNavigation(navigation[x].children));
                    }
                }

                return flatNav;
            }
        };
    }

    /** @ngInject */
    function MsNavigationController($scope, msNavigationService)
    {
        var vm = this;

        // Data
        if ( $scope.root )
        {
            vm.navigation = msNavigationService.getNavigation($scope.root);
        }
        else
        {
            vm.navigation = msNavigationService.getNavigation();
        }

        // Methods
        vm.toggleHorizontalMobileMenu = toggleHorizontalMobileMenu;

        //////////

        init();

        /**
         * Initialize
         */
        function init()
        {
            // Sort the navigation before doing anything else
            msNavigationService.sort();
        }

        /**
         * Toggle horizontal mobile menu
         */
        function toggleHorizontalMobileMenu()
        {
            angular.element('body').toggleClass('ms-navigation-horizontal-mobile-menu-active');
        }
    }

    /** @ngInject */
    function msNavigationDirective($rootScope, $timeout, $mdSidenav, msNavigationService)
    {
        return {
            restrict   : 'E',
            scope      : {
                folded: '=',
                root  : '@'
            },
            controller : 'MsNavigationController as vm',
            templateUrl: 'app/core/directives/ms-navigation/templates/vertical.html',
            transclude : true,
            compile    : function (tElement)
            {
                tElement.addClass('ms-navigation');

                return function postLink(scope, iElement)
                {
                    var bodyEl = angular.element('body'),
                        foldExpanderEl = angular.element('<div id="ms-navigation-fold-expander"></div>'),
                        foldCollapserEl = angular.element('<div id="ms-navigation-fold-collapser"></div>'),
                        sidenav = $mdSidenav('navigation');

                    // Store the navigation in the service for public access
                    msNavigationService.setNavigationScope(scope);

                    // Initialize
                    init();

                    /**
                     * Initialize
                     */
                    function init()
                    {
                        // Set the folded status for the first time.
                        // First, we have to check if we have a folded
                        // status available in the service already. This
                        // will prevent navigation to act weird if we already
                        // set the fold status, remove the navigation and
                        // then re-initialize it, which happens if we
                        // change to a view without a navigation and then
                        // come back with history.back() function.

                        // If the service didn't initialize before, set
                        // the folded status from scope, otherwise we
                        // won't touch anything because the folded status
                        // already set in the service...
                        if ( msNavigationService.getFolded() === null )
                        {
                            msNavigationService.setFolded(scope.folded);
                        }

                        if ( msNavigationService.getFolded() )
                        {
                            // Collapse everything.
                            // This must be inside a $timeout because by the
                            // time we call this, the 'msNavigation::collapse'
                            // event listener is not registered yet. $timeout
                            // will ensure that it will be called after it is
                            // registered.
                            $timeout(function ()
                            {
                                $rootScope.$broadcast('msNavigation::collapse');
                            });

                            // Add class to the body
                            bodyEl.addClass('ms-navigation-folded');

                            // Set fold expander
                            setFoldExpander();
                        }
                    }

                    // Sidenav locked open status watcher
                    scope.$watch(function ()
                    {
                        return sidenav.isLockedOpen();
                    }, function (current, old)
                    {
                        if ( angular.isUndefined(current) || angular.equals(current, old) )
                        {
                            return;
                        }

                        var folded = msNavigationService.getFolded();

                        if ( folded )
                        {
                            if ( current )
                            {
                                // Collapse everything
                                $rootScope.$broadcast('msNavigation::collapse');
                            }
                            else
                            {
                                // Expand the active one and its parents
                                var activeItem = msNavigationService.getActiveItem();
                                if ( activeItem )
                                {
                                    activeItem.scope.$emit('msNavigation::stateMatched');
                                }
                            }
                        }
                    });

                    // Folded status watcher
                    scope.$watch('folded', function (current, old)
                    {
                        if ( angular.isUndefined(current) || angular.equals(current, old) )
                        {
                            return;
                        }

                        setFolded(current);
                    });

                    /**
                     * Set folded status
                     *
                     * @param folded
                     */
                    function setFolded(folded)
                    {
                        // Store folded status on the service for global access
                        msNavigationService.setFolded(folded);

                        if ( folded )
                        {
                            // Collapse everything
                            $rootScope.$broadcast('msNavigation::collapse');

                            // Add class to the body
                            bodyEl.addClass('ms-navigation-folded');

                            // Set fold expander
                            setFoldExpander();
                        }
                        else
                        {
                            // Expand the active one and its parents
                            var activeItem = msNavigationService.getActiveItem();
                            if ( activeItem )
                            {
                                activeItem.scope.$emit('msNavigation::stateMatched');
                            }

                            // Remove body class
                            bodyEl.removeClass('ms-navigation-folded ms-navigation-folded-open');

                            // Remove fold collapser
                            removeFoldCollapser();
                        }
                    }

                    /**
                     * Set fold expander
                     */
                    function setFoldExpander()
                    {
                        iElement.parent().append(foldExpanderEl);

                        // Let everything settle for a moment
                        // before registering the event listener
                        $timeout(function ()
                        {
                            foldExpanderEl.on('mouseenter touchstart', onFoldExpanderHover);
                        });
                    }

                    /**
                     * Set fold collapser
                     */
                    function setFoldCollapser()
                    {
                        bodyEl.find('#main').append(foldCollapserEl);
                        foldCollapserEl.on('mouseenter touchstart', onFoldCollapserHover);
                    }

                    /**
                     * Remove fold collapser
                     */
                    function removeFoldCollapser()
                    {
                        foldCollapserEl.remove();
                    }

                    /**
                     * onHover event of foldExpander
                     */
                    function onFoldExpanderHover(event)
                    {
                        if ( event )
                        {
                            event.preventDefault();
                        }

                        // Set folded open status
                        msNavigationService.setFoldedOpen(true);

                        // Expand the active one and its parents
                        var activeItem = msNavigationService.getActiveItem();
                        if ( activeItem )
                        {
                            activeItem.scope.$emit('msNavigation::stateMatched');
                        }

                        // Add class to the body
                        bodyEl.addClass('ms-navigation-folded-open');

                        // Remove the fold opener
                        foldExpanderEl.remove();

                        // Set fold collapser
                        setFoldCollapser();
                    }

                    /**
                     * onHover event of foldCollapser
                     */
                    function onFoldCollapserHover(event)
                    {
                        if ( event )
                        {
                            event.preventDefault();
                        }

                        // Set folded open status
                        msNavigationService.setFoldedOpen(false);

                        // Collapse everything
                        $rootScope.$broadcast('msNavigation::collapse');

                        // Remove body class
                        bodyEl.removeClass('ms-navigation-folded-open');

                        // Remove the fold collapser
                        foldCollapserEl.remove();

                        // Set fold expander
                        setFoldExpander();
                    }

                    /**
                     * Public access for toggling folded status externally
                     */
                    scope.toggleFolded = function ()
                    {
                        var folded = msNavigationService.getFolded();

                        setFolded(!folded);
                    };

                    /**
                     * On $stateChangeStart
                     */
                    scope.$on('$stateChangeStart', function ()
                    {
                        // Close the sidenav
                        sidenav.close();
                    });

                    // Cleanup
                    scope.$on('$destroy', function ()
                    {
                        foldCollapserEl.off('mouseenter touchstart');
                        foldExpanderEl.off('mouseenter touchstart');
                    });
                };
            }
        };
    }

    /** @ngInject */
    function MsNavigationNodeController($scope, $element, $rootScope, $animate, $state, msNavigationService)
    {
        var vm = this;

        // Data
        vm.element = $element;
        vm.node = $scope.node;
        vm.hasChildren = undefined;
        vm.collapsed = undefined;
        vm.collapsable = undefined;
        vm.group = undefined;
        vm.animateHeightClass = 'animate-height';

        // Methods
        vm.toggleCollapsed = toggleCollapsed;
        vm.collapse = collapse;
        vm.expand = expand;
        vm.getClass = getClass;
        vm.isHidden = isHidden;

        //////////

        init();

        /**
         * Initialize
         */
        function init()
        {
            // Setup the initial values

            // Has children?
            vm.hasChildren = vm.node.children.length > 0;

            // Is group?
            vm.group = !!(angular.isDefined(vm.node.group) && vm.node.group === true);

            // Is collapsable?
            if ( !vm.hasChildren || vm.group )
            {
                vm.collapsable = false;
            }
            else
            {
                vm.collapsable = !!(angular.isUndefined(vm.node.collapsable) || typeof vm.node.collapsable !== 'boolean' || vm.node.collapsable === true);
            }

            // Is collapsed?
            if ( !vm.collapsable )
            {
                vm.collapsed = false;
            }
            else
            {
                vm.collapsed = !!(angular.isUndefined(vm.node.collapsed) || typeof vm.node.collapsed !== 'boolean' || vm.node.collapsed === true);
            }

            // Expand all parents if we have a matching state or
            // the current state is a child of the node's state
            if ( vm.node.state === $state.current.name || $state.includes(vm.node.state) )
            {
                // If state params are defined, make sure they are
                // equal, otherwise do not set the active item
                if ( angular.isDefined(vm.node.stateParams) && angular.isDefined($state.params) && !angular.equals(vm.node.stateParams, $state.params) )
                {
                    return;
                }

                $scope.$emit('msNavigation::stateMatched');

                // Also store the current active menu item
                msNavigationService.setActiveItem(vm.node, $scope);
            }

            $scope.$on('msNavigation::stateMatched', function ()
            {
                // Expand if the current scope is collapsable and is collapsed
                if ( vm.collapsable && vm.collapsed )
                {
                    $scope.$evalAsync(function ()
                    {
                        vm.collapsed = false;
                    });
                }
            });

            // Listen for collapse event
            $scope.$on('msNavigation::collapse', function (event, path)
            {
                if ( vm.collapsed || !vm.collapsable )
                {
                    return;
                }

                // If there is no path defined, collapse
                if ( angular.isUndefined(path) )
                {
                    vm.collapse();
                }
                // If there is a path defined, do not collapse
                // the items that are inside that path. This will
                // prevent parent items from collapsing
                else
                {
                    var givenPathParts = path.split('.'),
                        activePathParts = [];

                    var activeItem = msNavigationService.getActiveItem();
                    if ( activeItem )
                    {
                        activePathParts = activeItem.node._path.split('.');
                    }

                    // Test for given path
                    if ( givenPathParts.indexOf(vm.node._id) > -1 )
                    {
                        return;
                    }

                    // Test for active path
                    if ( activePathParts.indexOf(vm.node._id) > -1 )
                    {
                        return;
                    }

                    vm.collapse();
                }
            });

            // Listen for $stateChangeSuccess event
            $scope.$on('$stateChangeSuccess', function ()
            {
                if ( vm.node.state === $state.current.name )
                {
                    // If state params are defined, make sure they are
                    // equal, otherwise do not set the active item
                    if ( angular.isDefined(vm.node.stateParams) && angular.isDefined($state.params) && !angular.equals(vm.node.stateParams, $state.params) )
                    {
                        return;
                    }

                    // Update active item on state change
                    msNavigationService.setActiveItem(vm.node, $scope);

                    // Collapse everything except the one we're using
                    $rootScope.$broadcast('msNavigation::collapse', vm.node._path);
                }

                // Expand the parents if we the current
                // state is a child of the node's state
                if ( $state.includes(vm.node.state) )
                {
                    // If state params are defined, make sure they are
                    // equal, otherwise do not set the active item
                    if ( angular.isDefined(vm.node.stateParams) && angular.isDefined($state.params) && !angular.equals(vm.node.stateParams, $state.params) )
                    {
                        return;
                    }

                    // Emit the stateMatched
                    $scope.$emit('msNavigation::stateMatched');
                }
            });
        }

        /**
         * Toggle collapsed
         */
        function toggleCollapsed()
        {
            if ( vm.collapsed )
            {
                vm.expand();
            }
            else
            {
                vm.collapse();
            }
        }

        /**
         * Collapse
         */
        function collapse()
        {
            // Grab the element that we are going to collapse
            var collapseEl = vm.element.children('ul');

            // Grab the height
            var height = collapseEl[0].offsetHeight;

            $scope.$evalAsync(function ()
            {
                // Set collapsed status
                vm.collapsed = true;

                // Add collapsing class to the node
                vm.element.addClass('collapsing');

                // Animate the height
                $animate.animate(collapseEl,
                    {
                        'display': 'block',
                        'height' : height + 'px'
                    },
                    {
                        'height': '0px'
                    },
                    vm.animateHeightClass
                ).then(
                    function ()
                    {
                        // Clear the inline styles after animation done
                        collapseEl.css({
                            'display': '',
                            'height' : ''
                        });

                        // Clear collapsing class from the node
                        vm.element.removeClass('collapsing');
                    }
                );

                // Broadcast the collapse event so child items can also be collapsed
                $scope.$broadcast('msNavigation::collapse');
            });
        }

        /**
         * Expand
         */
        function expand()
        {
            // Grab the element that we are going to expand
            var expandEl = vm.element.children('ul');

            // Move the element out of the dom flow and
            // make it block so we can get its height
            expandEl.css({
                'position'  : 'absolute',
                'visibility': 'hidden',
                'display'   : 'block',
                'height'    : 'auto'
            });

            // Grab the height
            var height = expandEl[0].offsetHeight;

            // Reset the style modifications
            expandEl.css({
                'position'  : '',
                'visibility': '',
                'display'   : '',
                'height'    : ''
            });

            $scope.$evalAsync(function ()
            {
                // Set collapsed status
                vm.collapsed = false;

                // Add expanding class to the node
                vm.element.addClass('expanding');

                // Animate the height
                $animate.animate(expandEl,
                    {
                        'display': 'block',
                        'height' : '0px'
                    },
                    {
                        'height': height + 'px'
                    },
                    vm.animateHeightClass
                ).then(
                    function ()
                    {
                        // Clear the inline styles after animation done
                        expandEl.css({
                            'height': ''
                        });

                        // Clear expanding class from the node
                        vm.element.removeClass('expanding');
                    }
                );

                // If item expanded, broadcast the collapse event from rootScope so that the other expanded items
                // can be collapsed. This is necessary for keeping only one parent expanded at any time
                $rootScope.$broadcast('msNavigation::collapse', vm.node._path);
            });
        }

        /**
         * Return the class
         *
         * @returns {*}
         */
        function getClass()
        {
            return vm.node.class;
        }

        /**
         * Check if node should be hidden
         *
         * @returns {boolean}
         */
        function isHidden()
        {
            if ( angular.isDefined(vm.node.hidden) && angular.isFunction(vm.node.hidden) )
            {
                return vm.node.hidden();
            }

            return false;
        }
    }

    /** @ngInject */
    function msNavigationNodeDirective()
    {
        return {
            restrict        : 'A',
            bindToController: {
                node: '=msNavigationNode'
            },
            controller      : 'MsNavigationNodeController as vm',
            compile         : function (tElement)
            {
                tElement.addClass('ms-navigation-node');

                return function postLink(scope, iElement, iAttrs, MsNavigationNodeCtrl)
                {
                    // Add custom classes
                    iElement.addClass(MsNavigationNodeCtrl.getClass());

                    // Add group class if it's a group
                    if ( MsNavigationNodeCtrl.group )
                    {
                        iElement.addClass('group');
                    }
                };
            }
        };
    }

    /** @ngInject */
    function msNavigationItemDirective()
    {
        return {
            restrict: 'A',
            require : '^msNavigationNode',
            compile : function (tElement)
            {
                tElement.addClass('ms-navigation-item');

                return function postLink(scope, iElement, iAttrs, MsNavigationNodeCtrl)
                {
                    // If the item is collapsable...
                    if ( MsNavigationNodeCtrl.collapsable )
                    {
                        iElement.on('click', MsNavigationNodeCtrl.toggleCollapsed);
                    }

                    // Cleanup
                    scope.$on('$destroy', function ()
                    {
                        iElement.off('click');
                    });
                };
            }
        };
    }

    /** @ngInject */
    function msNavigationHorizontalDirective(msNavigationService)
    {
        return {
            restrict   : 'E',
            scope      : {
                root: '@'
            },
            controller : 'MsNavigationController as vm',
            templateUrl: 'app/core/directives/ms-navigation/templates/horizontal.html',
            transclude : true,
            compile    : function (tElement)
            {
                tElement.addClass('ms-navigation-horizontal');

                return function postLink(scope)
                {
                    // Store the navigation in the service for public access
                    msNavigationService.setNavigationScope(scope);
                };
            }
        };
    }

    /** @ngInject */
    function MsNavigationHorizontalNodeController($scope, $element, $rootScope, $state, msNavigationService)
    {
        var vm = this;

        // Data
        vm.element = $element;
        vm.node = $scope.node;
        vm.hasChildren = undefined;
        vm.group = undefined;

        // Methods
        vm.getClass = getClass;

        //////////

        init();

        /**
         * Initialize
         */
        function init()
        {
            // Setup the initial values

            // Is active
            vm.isActive = false;

            // Has children?
            vm.hasChildren = vm.node.children.length > 0;

            // Is group?
            vm.group = !!(angular.isDefined(vm.node.group) && vm.node.group === true);

            // Mark all parents as active if we have a matching state
            // or the current state is a child of the node's state
            if ( vm.node.state === $state.current.name || $state.includes(vm.node.state) )
            {
                // If state params are defined, make sure they are
                // equal, otherwise do not set the active item
                if ( angular.isDefined(vm.node.stateParams) && angular.isDefined($state.params) && !angular.equals(vm.node.stateParams, $state.params) )
                {
                    return;
                }

                $scope.$emit('msNavigation::stateMatched');

                // Also store the current active menu item
                msNavigationService.setActiveItem(vm.node, $scope);
            }

            $scope.$on('msNavigation::stateMatched', function ()
            {
                // Mark as active if has children
                if ( vm.hasChildren )
                {
                    $scope.$evalAsync(function ()
                    {
                        vm.isActive = true;
                    });
                }
            });

            // Listen for clearActive event
            $scope.$on('msNavigation::clearActive', function ()
            {
                if ( !vm.hasChildren )
                {
                    return;
                }

                var activePathParts = [];

                var activeItem = msNavigationService.getActiveItem();
                if ( activeItem )
                {
                    activePathParts = activeItem.node._path.split('.');
                }

                // Test for active path
                if ( activePathParts.indexOf(vm.node._id) > -1 )
                {
                    $scope.$evalAsync(function ()
                    {
                        vm.isActive = true;
                    });
                }
                else
                {
                    $scope.$evalAsync(function ()
                    {
                        vm.isActive = false;
                    });
                }

            });

            // Listen for $stateChangeSuccess event
            $scope.$on('$stateChangeSuccess', function ()
            {
                if ( vm.node.state === $state.current.name || $state.includes(vm.node.state) )
                {
                    // If state params are defined, make sure they are
                    // equal, otherwise do not set the active item
                    if ( angular.isDefined(vm.node.stateParams) && angular.isDefined($state.params) && !angular.equals(vm.node.stateParams, $state.params) )
                    {
                        return;
                    }

                    // Update active item on state change
                    msNavigationService.setActiveItem(vm.node, $scope);

                    // Clear all active states except the one we're using
                    $rootScope.$broadcast('msNavigation::clearActive');
                }
            });
        }

        /**
         * Return the class
         *
         * @returns {*}
         */
        function getClass()
        {
            return vm.node.class;
        }
    }

    /** @ngInject */
    function msNavigationHorizontalNodeDirective()
    {
        return {
            restrict        : 'A',
            bindToController: {
                node: '=msNavigationHorizontalNode'
            },
            controller      : 'MsNavigationHorizontalNodeController as vm',
            compile         : function (tElement)
            {
                tElement.addClass('ms-navigation-horizontal-node');

                return function postLink(scope, iElement, iAttrs, MsNavigationHorizontalNodeCtrl)
                {
                    // Add custom classes
                    iElement.addClass(MsNavigationHorizontalNodeCtrl.getClass());

                    // Add group class if it's a group
                    if ( MsNavigationHorizontalNodeCtrl.group )
                    {
                        iElement.addClass('group');
                    }
                };
            }
        };
    }

    /** @ngInject */
    function msNavigationHorizontalItemDirective($mdMedia)
    {
        return {
            restrict: 'A',
            require : '^msNavigationHorizontalNode',
            compile : function (tElement)
            {
                tElement.addClass('ms-navigation-horizontal-item');

                return function postLink(scope, iElement, iAttrs, MsNavigationHorizontalNodeCtrl)
                {
                    iElement.on('click', onClick);

                    function onClick()
                    {
                        if ( !MsNavigationHorizontalNodeCtrl.hasChildren || $mdMedia('gt-md') )
                        {
                            return;
                        }

                        iElement.toggleClass('expanded');
                    }

                    // Cleanup
                    scope.$on('$destroy', function ()
                    {
                        iElement.off('click');
                    });
                };
            }
        };
    }

})();
(function ()
{
    'use strict';

    msMaterialColorPickerController.$inject = ["$scope", "$mdColorPalette", "$mdMenu", "fuseGenerator"];
    angular
        .module('app.core')
        .controller('msMaterialColorPickerController', msMaterialColorPickerController)
        .directive('msMaterialColorPicker', msMaterialColorPicker);

    /** @ngInject */
    function msMaterialColorPickerController($scope, $mdColorPalette, $mdMenu, fuseGenerator)
    {
        var vm = this;
        vm.palettes = $mdColorPalette; // Material Color Palette
        vm.selectedPalette = false;
        vm.selectedHues = false;
        $scope.$selectedColor = {};

        // Methods
        vm.activateHueSelection = activateHueSelection;
        vm.selectColor = selectColor;
        vm.removeColor = removeColor;

        /**
         * Initialize / Watch model changes
         */
        $scope.$watch('ngModel', setSelectedColor);

        /**
         * Activate Hue Selection
         * @param palette
         * @param hues
         */
        function activateHueSelection(palette, hues)
        {
            vm.selectedPalette = palette;
            vm.selectedHues = hues;
        }

        /**
         * Select Color
         * @type {selectColor}
         */
        function selectColor(palette, hue)
        {
            // Update Selected Color
            updateSelectedColor(palette, hue);

            // Update Model Value
            updateModel();

            // Hide The picker
            $mdMenu.hide();
        }

        function removeColor()
        {
            vm.selectedColor = {
                palette: '',
                hue    : '',
                class  : ''
            };

            activateHueSelection(false, false);

            updateModel();
        }

        /**
         * Set SelectedColor by model type
         */
        function setSelectedColor()
        {
            if ( !vm.modelCtrl.$viewValue || vm.modelCtrl.$viewValue === '' )
            {
                removeColor();
                return;
            }

            var palette, hue;

            // If ModelType Class
            if ( vm.msModelType === 'class' )
            {
                var color = vm.modelCtrl.$viewValue.split('-');
                if ( color.length >= 5 )
                {
                    palette = color[1] + '-' + color[2];
                    hue = color[3];
                }
                else
                {
                    palette = color[1];
                    hue = color[2];
                }
            }

            // If ModelType Object
            else if ( vm.msModelType === 'obj' )
            {
                palette = vm.modelCtrl.$viewValue.palette;
                hue = vm.modelCtrl.$viewValue.hue || 500;
            }

            // Update Selected Color
            updateSelectedColor(palette, hue);
        }

        /**
         * Update Selected Color
         * @param palette
         * @param hue
         */
        function updateSelectedColor(palette, hue)
        {
            vm.selectedColor = {
                palette     : palette,
                hue         : hue,
                class       : 'md-' + palette + '-' + hue + '-bg',
                bgColorValue: fuseGenerator.rgba(vm.palettes[palette][hue].value),
                fgColorValue: fuseGenerator.rgba(vm.palettes[palette][hue].contrast)
            };

            // If Model object not Equals the selectedColor update it
            // it can be happen when the model only have pallete and hue values
            if ( vm.msModelType === 'obj' && !angular.equals(vm.selectedColor, vm.modelCtrl.$viewValue) )
            {
                // Update Model Value
                updateModel();
            }

            activateHueSelection(palette, vm.palettes[palette]);

            $scope.$selectedColor = vm.selectedColor;
        }

        /**
         * Update Model Value by model type
         */
        function updateModel()
        {
            if ( vm.msModelType === 'class' )
            {
                vm.modelCtrl.$setViewValue(vm.selectedColor.class);
            }
            else if ( vm.msModelType === 'obj' )
            {
                vm.modelCtrl.$setViewValue(vm.selectedColor);
            }
        }
    }

    /** @ngInject */
    function msMaterialColorPicker()
    {
        return {
            require    : ['msMaterialColorPicker', 'ngModel'],
            restrict   : 'E',
            scope      : {
                ngModel    : '=',
                msModelType: '@?'
            },
            controller : 'msMaterialColorPickerController as vm',
            transclude : true,
            templateUrl: 'app/core/directives/ms-material-color-picker/ms-material-color-picker.html',
            link       : function (scope, element, attrs, controllers, transclude)
            {
                var ctrl = controllers[0];

                /**
                 *  Pass model controller to directive controller
                 */
                ctrl.modelCtrl = controllers[1];

                /**
                 * ModelType: 'obj', 'class'(default)
                 * @type {string|string}
                 */
                ctrl.msModelType = scope.msModelType || 'class';

                transclude(scope, function (clone)
                {
                    clone = clone.filter(function (i, el)
                    {
                        return ( el.nodeType === 1 ) ? true : false;
                    });

                    if ( clone.length )
                    {
                        element.find('ms-color-picker-button').replaceWith(clone);
                    }
                });
            }
        };
    }
})();
(function ()
{
    'use strict';

    msInfoBarDirective.$inject = ["$document"];
    angular
        .module('app.core')
        .directive('msInfoBar', msInfoBarDirective);

    /** @ngInject */
    function msInfoBarDirective($document)
    {
        return {
            restrict   : 'E',
            scope      : {},
            transclude : true,
            templateUrl: 'app/core/directives/ms-info-bar/ms-info-bar.html',
            link       : function (scope, iElement)
            {
                var body = $document.find('body'),
                    bodyClass = 'ms-info-bar-active';

                // Add body class
                body.addClass(bodyClass);

                /**
                 * Remove the info bar
                 */
                function removeInfoBar()
                {
                    body.removeClass(bodyClass);
                    iElement.remove();
                    scope.$destroy();
                }

                // Expose functions
                scope.removeInfoBar = removeInfoBar;
            }
        };
    }
})();
(function ()
{
    'use strict';

    msMasonryController.$inject = ["$scope", "$window", "$mdMedia", "$timeout"];
    msMasonry.$inject = ["$timeout"];
    angular
        .module('app.core')
        .controller('msMasonryController', msMasonryController)
        .directive('msMasonry', msMasonry)
        .directive('msMasonryItem', msMasonryItem);

    /** @ngInject */
    function msMasonryController($scope, $window, $mdMedia, $timeout)
    {
        var vm = this,
            defaultOpts = {
                columnCount     : 5,
                respectItemOrder: false,
                reLayoutDebounce: 400,
                responsive      : {
                    md: 3,
                    sm: 2,
                    xs: 1
                }
            },
            reLayoutTimeout = true;

        vm.options = null;
        vm.container = [];
        vm.containerPos = '';
        vm.columnWidth = '';
        vm.items = [];

        // Methods
        vm.reLayout = reLayout;
        vm.initialize = initialize;
        vm.waitImagesLoaded = waitImagesLoaded;

        function initialize()
        {
            vm.options = !vm.options ? defaultOpts : angular.extend(defaultOpts, vm.options);


            watchContainerResize();
        }

        $scope.$on('msMasonry:relayout', function ()
        {
            reLayout();
        });

        function waitImagesLoaded(element, callback)
        {
            if ( typeof imagesLoaded !== 'undefined' )
            {
                var imgLoad = $window.imagesLoaded(element);

                imgLoad.on('done', function ()
                {
                    callback();
                });
            }
            else
            {
                callback();
            }
        }

        function watchContainerResize()
        {
            $scope.$watch(
                function ()
                {
                    return vm.container.width();
                },
                function (newValue, oldValue)
                {
                    if ( newValue !== oldValue )
                    {
                        reLayout();
                    }
                }
            );
        }

        function reLayout()
        {
            // Debounce for relayout
            if ( reLayoutTimeout )
            {
                $timeout.cancel(reLayoutTimeout);
            }

            reLayoutTimeout = $timeout(function ()
            {
                start();

                $scope.$broadcast('msMasonry:relayoutFinished');

            }, vm.options.reLayoutDebounce);

            // Start relayout
            function start()
            {
                vm.containerPos = vm.container[0].getBoundingClientRect();

                updateColumnOptions();

                $scope.$broadcast('msMasonry:relayoutStarted');

                vm.items = vm.container.find('ms-masonry-item');

                //initialize lastRowBottomArr
                var referenceArr = Array.apply(null, new Array(vm.columnCount)).map(function ()
                {
                    return 0;
                });

                // set item positions
                for ( var i = 0; i < vm.items.length; i++ )
                {
                    var item = vm.items[i],
                        xPos, yPos, column, refTop;

                    item = angular.element(item);

                    if ( item.scope() )
                    {
                        item.scope().$broadcast('msMasonryItem:startReLayout');
                    }

                    item.css({'width': vm.columnWidth});

                    if ( vm.options.respectItemOrder )
                    {
                        column = i % vm.columnCount;
                        refTop = referenceArr[column];
                    }
                    else
                    {
                        refTop = Math.min.apply(Math, referenceArr);
                        column = referenceArr.indexOf(refTop);
                    }

                    referenceArr[column] = refTop + item[0].getBoundingClientRect().height;

                    xPos = Math.round(column * vm.columnWidth);
                    yPos = refTop;

                    item.css({'transform': 'translate3d(' + xPos + 'px,' + yPos + 'px,0px)'});
                    item.addClass('placed');

                    if ( item.scope() )
                    {
                        item.scope().$broadcast('msMasonryItem:finishReLayout');
                    }
                }
            }
        }

        function updateColumnOptions()
        {
            vm.columnCount = vm.options.columnCount;

            if ( $mdMedia('gt-md') )
            {
                vm.columnCount = vm.options.columnCount;
            }
            else if ( $mdMedia('md') )
            {
                vm.columnCount = (vm.columnCount > vm.options.responsive.md ? vm.options.responsive.md : vm.columnCount);
            }
            else if ( $mdMedia('sm') )
            {
                vm.columnCount = (vm.columnCount > vm.options.responsive.sm ? vm.options.responsive.sm : vm.columnCount);
            }
            else
            {
                vm.columnCount = vm.options.responsive.xs;
            }

            vm.columnWidth = vm.containerPos.width / vm.columnCount;

        }
    }

    /** @ngInject */
    function msMasonry($timeout)
    {
        return {
            restrict  : 'AEC',
            controller: 'msMasonryController',
            compile   : compile
        };
        function compile(element, attributes)
        {
            return {
                pre : function preLink(scope, iElement, iAttrs, controller)
                {
                    controller.options = angular.fromJson(attributes.options || '{}');
                    controller.container = element;
                },
                post: function postLink(scope, iElement, iAttrs, controller)
                {
                    $timeout(function ()
                    {
                        controller.initialize();
                    });
                }
            };
        }
    }

    /** @ngInject */
    function msMasonryItem()
    {
        return {
            restrict: 'AEC',
            require : '^msMasonry',
            priority: 1,
            link    : link
        };

        function link(scope, element, attributes, controller)
        {
            controller.waitImagesLoaded(element, function ()
            {
                controller.reLayout();

            });

            scope.$on('msMasonryItem:finishReLayout', function ()
            {
                scope.$watch(function ()
                {
                    return element.height();
                }, function (newVal, oldVal)
                {
                    if ( newVal !== oldVal )
                    {
                        controller.reLayout();
                    }
                });
            });

            element.on('$destroy', function ()
            {
                controller.reLayout();
            });
        }
    }
})();
(function ()
{
    'use strict';

    angular
        .module('app.core')
        .controller('MsFormWizardController', MsFormWizardController)
        .directive('msFormWizard', msFormWizardDirective)
        .directive('msFormWizardForm', msFormWizardFormDirective);

    /** @ngInject */
    function MsFormWizardController()
    {
        var vm = this;

        // Data
        vm.forms = [];
        vm.selectedIndex = 0;

        // Methods
        vm.registerForm = registerForm;

        vm.previousStep = previousStep;
        vm.nextStep = nextStep;
        vm.firstStep = firstStep;
        vm.lastStep = lastStep;

        vm.totalSteps = totalSteps;
        vm.isFirstStep = isFirstStep;
        vm.isLastStep = isLastStep;

        vm.currentStepInvalid = currentStepInvalid;
        vm.previousStepInvalid = previousStepInvalid;
        vm.formsIncomplete = formsIncomplete;
        vm.resetForm = resetForm;

        //////////

        /**
         * Register form
         *
         * @param form
         */
        function registerForm(form)
        {
            vm.forms.push(form);
        }

        /**
         * Go to previous step
         */
        function previousStep()
        {
            if ( isFirstStep() )
            {
                return;
            }

            vm.selectedIndex--;
        }

        /**
         * Go to next step
         */
        function nextStep()
        {
            if ( isLastStep() )
            {
                return;
            }

            vm.selectedIndex++;
        }

        /**
         * Go to first step
         */
        function firstStep()
        {
            vm.selectedIndex = 0;
        }

        /**
         * Go to last step
         */
        function lastStep()
        {
            vm.selectedIndex = totalSteps() - 1;
        }

        /**
         * Return total steps
         *
         * @returns {int}
         */
        function totalSteps()
        {
            return vm.forms.length;
        }

        /**
         * Is first step?
         *
         * @returns {boolean}
         */
        function isFirstStep()
        {
            return vm.selectedIndex === 0;
        }

        /**
         * Is last step?
         *
         * @returns {boolean}
         */
        function isLastStep()
        {
            return vm.selectedIndex === totalSteps() - 1;
        }

        /**
         * Is current step invalid?
         *
         * @returns {boolean}
         */
        function currentStepInvalid()
        {
            return angular.isDefined(vm.forms[vm.selectedIndex]) && vm.forms[vm.selectedIndex].$invalid;
        }

        /**
         * Is previous step invalid?
         *
         * @returns {boolean}
         */
        function previousStepInvalid()
        {
            return vm.selectedIndex > 0 && angular.isDefined(vm.forms[vm.selectedIndex - 1]) && vm.forms[vm.selectedIndex - 1].$invalid;
        }

        /**
         * Check if there is any incomplete forms
         *
         * @returns {boolean}
         */
        function formsIncomplete()
        {
            for ( var x = 0; x < vm.forms.length; x++ )
            {
                if ( vm.forms[x].$invalid )
                {
                    return true;
                }
            }

            return false;
        }

        /**
         * Reset form
         */
        function resetForm()
        {
            // Go back to the first step
            vm.selectedIndex = 0;

            // Make sure all the forms are back in the $pristine & $untouched status
            for ( var x = 0; x < vm.forms.length; x++ )
            {
                vm.forms[x].$setPristine();
                vm.forms[x].$setUntouched();
            }
        }
    }

    /** @ngInject */
    function msFormWizardDirective()
    {
        return {
            restrict  : 'E',
            scope     : true,
            controller: 'MsFormWizardController as msWizard',
            compile   : function (tElement)
            {
                tElement.addClass('ms-form-wizard');

                return function postLink()
                {

                };
            }
        };
    }

    /** @ngInject */
    function msFormWizardFormDirective()
    {
        return {
            restrict: 'A',
            require : ['form', '^msFormWizard'],
            compile : function (tElement)
            {
                tElement.addClass('ms-form-wizard-form');

                return function postLink(scope, iElement, iAttrs, ctrls)
                {
                    var formCtrl = ctrls[0],
                        MsFormWizardCtrl = ctrls[1];

                    MsFormWizardCtrl.registerForm(formCtrl);
                };
            }
        };
    }

})();
(function ()
{
    'use strict';

    angular
        .module('app.core')
        .directive('msCard', msCardDirective);

    /** @ngInject */
    function msCardDirective()
    {
        return {
            restrict: 'E',
            scope   : {
                templatePath: '=template',
                card        : '=ngModel',
                vm          : '=viewModel'
            },
            template: '<div class="ms-card-content-wrapper" ng-include="templatePath" onload="cardTemplateLoaded()"></div>',
            compile : function (tElement)
            {
                // Add class
                tElement.addClass('ms-card');

                return function postLink(scope, iElement)
                {
                    // Methods
                    scope.cardTemplateLoaded = cardTemplateLoaded;

                    //////////

                    /**
                     * Emit cardTemplateLoaded event
                     */
                    function cardTemplateLoaded()
                    {
                        scope.$emit('msCard::cardTemplateLoaded', iElement);
                    }
                };
            }
        };
    }
})();
(function ()
{
    'use strict';

    msDatepickerFix.$inject = ["msDatepickerFixConfig"];
    angular
        .module('app.core')
        .provider('msDatepickerFixConfig', msDatepickerFixConfigProvider)
        .directive('msDatepickerFix', msDatepickerFix);

    /** @ngInject */
    function msDatepickerFixConfigProvider()
    {
        var service = this;

        // Default configuration
        var defaultConfig = {
            // To view
            formatter: function (val)
            {
                if ( !val )
                {
                    return '';
                }

                return val === '' ? val : new Date(val);
            },
            // To model
            parser   : function (val)
            {
                if ( !val )
                {
                    return '';
                }

                return moment(val).add(moment(val).utcOffset(), 'm').toDate();
            }
        };

        // Methods
        service.config = config;

        //////////

        /**
         * Extend default configuration with the given one
         *
         * @param configuration
         */
        function config(configuration)
        {
            defaultConfig = angular.extend({}, defaultConfig, configuration);
        }

        /**
         * Service
         */
        service.$get = function ()
        {
            return defaultConfig;
        };
    }

    /** @ngInject */
    function msDatepickerFix(msDatepickerFixConfig)
    {
        return {
            require : 'ngModel',
            priority: 1,
            link    : function (scope, elem, attrs, ngModel)
            {
                ngModel.$formatters.push(msDatepickerFixConfig.formatter); // to view
                ngModel.$parsers.push(msDatepickerFixConfig.parser); // to model
            }
        };
    }
})();
(function ()
{
    'use strict';

    config.$inject = ["$stateProvider"];
    angular
        .module('app.settings', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider)
    {
        $stateProvider.state('app.settings', {
            url    : '/settings',
            views  : {

                'content@app': {
                    templateUrl: 'app/main/settings/settings.html',
                    controller : 'SettingsController as vm'
                }
            }
        });

      

     

    }

})();

(function ()
{
    'use strict';

    SettingsController.$inject = ["$scope", "api", "$mdToast"];
    angular
        .module('app.settings')
        .controller('SettingsController', SettingsController);

    /** @ngInject */
    function SettingsController($scope, api, $mdToast){
      	
      	var checkedArray = {};
      	api.getUserSettings.settings.get({},function(res){
          try{
              var checked = JSON.parse(res.response.value);
              angular.forEach(checked, function(v,k){
                checkedArray[k] = v;
              });
          }catch(e){

          }
      		
      	});
      	$scope.user = checkedArray;

      	$scope.saveSettings = function(user){

      		var formData = new FormData();
      		formData.append('user_settings',JSON.stringify(user));
      		api.postMethod.saveUserSettings(formData).then(function(res){
      			$mdToast.show(
			             $mdToast.simple()
			                .textContent('Settings Saved Successfully!')
			                .position('top right')
			                .hideDelay(4000)
			            );
      		})
      	}
    }
})();




(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "$translatePartialLoaderProvider", "msApiProvider"];
    angular
        .module('app.register', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider, $translatePartialLoaderProvider, msApiProvider)
    {
        $stateProvider.state('app.register', {
            url    : '/register',
            views  : {

                'content@app': {
                    templateUrl: 'app/main/register/register.html',
                    controller : 'RegisterController as vm'
                }
            }
        });

        // Translation
        $translatePartialLoaderProvider.addPart('app/main/register');

    }

})();

(function ()
{
    'use strict';

    RegisterController.$inject = ["$scope", "api", "$state", "$mdToast"];
    angular
        .module('app.register')
        .directive('ngFiles', ['$parse', function ($parse) {

            function fn_link(scope, element, attrs) {
                var onChange = $parse(attrs.ngFiles);
                element.on('change', function (event) {
                    onChange(scope, { $files: event.target.files });
                });
            };
            return {
                link: fn_link
            }
        } ])
        .controller('RegisterController', RegisterController);

    /** @ngInject */
    function RegisterController($scope,api,$state, $mdToast){
         /*if(checkLogined($state) == false){
             return false;
         }*/
         var vm = this;
         $scope.isVisible = false;
         $scope.serverError = false;
         $scope.isDisabled = false;
        
         api.organization.list.get({},function(res){
            
             // console.log(res.records);
             $scope.orgs = res.records;
             
         });
         var sendData = new FormData();
         $scope.other_org = false;
         $('.org').change(function(){
            if($(this).val() == 'others'){
                $scope.other_org = true;
            }else{
                $scope.other_org = false;
            }
         });
         $scope.userRegister = function(){
         	 
             
             
             $scope.serverErrorMessage = '';
             $scope.isLoading = true;
             $scope.isDisabled = true;
             
             if($scope.files.length == 0){
                sendData.append('profile_pic','');   
             }else{
                sendData.append('profile_pic',$scope.files[0].lfFile);
             }
             //console.log(sendData.get('profile_pic'));
             sendData.append('name',vm.loginForm.name);
             sendData.append('email',vm.loginForm.email);
             sendData.append('password',vm.loginForm.password);
             sendData.append('departments',vm.loginForm.departmentsList);
             sendData.append('designation',vm.loginForm.designationList);
             sendData.append('organization',vm.loginForm.ministryList);
             sendData.append('phone',vm.loginForm.phone);
             sendData.append('address',vm.loginForm.address);
             sendData.append('organization_name',vm.loginForm.other_org);
             api.postMethod.registerUser(sendData).then(function(res){
                 //console.log(res);
                 if(res.data.status == 'successful'){
                     $scope.isLoading = false;
                     $scope.isDisabled = false;
                     //sessionStorage.api_token = res.data.token;
                     $mdToast.show(
                      $mdToast.simple()
                         .textContent('Registered Successfully!')
                         .position('top right')
                         .hideDelay(5000)
                     );
                     $state.go('app.page',{'slug':'register_success'});
                 }else{
                     $scope.serverError = true;
                     $scope.isDisabled = false;
                     $scope.isLoading = false;
                     $scope.serverErrorMessage = res.data.message;
                 }
             });
         }

        /*$scope.goHome = function(){
            $state.go('app.goal_list');
        }*/
    }

    function checkLogined($state){
        if(sessionStorage.api_token != ''){

            $state.go('app.profile');
            return false;
        }
    }

})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "$translatePartialLoaderProvider", "msApiProvider"];
    angular
        .module('app.page', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider, $translatePartialLoaderProvider, msApiProvider)
    {
        $stateProvider.state('app.page', {
            url    : '/page/:slug',
            views  : {
              
                'content@app': {
                    templateUrl: 'app/main/page/page.html',
                    controller : 'PageController as vm'
                }
            }
        });
    }

})();

(function ()
{
    'use strict';

    PageController.$inject = ["$scope", "$state", "api", "$sce"];
    angular
        .module('app.page')
        .directive('bindHtmlCompile', ['$compile', function ($compile) {
          return {
            restrict: 'A',
            link: function (scope, element, attrs) {
              scope.$watch(function () {
                return scope.$eval(attrs.bindHtmlCompile);
              }, function (value) {
                element.html(value && value.toString());
                var compileScope = scope;
                if (attrs.bindHtmlScope) {
                  compileScope = scope.$eval(attrs.bindHtmlScope);
                }
                $compile(element.contents())(compileScope);
              });
            }
          };
        }])
        .controller('PageController', PageController);

    /** @ngInject */
    function PageController($scope, $state, api, $sce){

        var vm = this;
        $scope.isLoading = true;
        api.pages.getBySlug.get({'slug': $state.params.slug}, function(res){
            // console.log(res);
            if(res.records.pages.page_status == 2){
                if(sessionStorage.api_token == '' || sessionStorage.api_token == undefined){
                    $state.go('app.new-login');
                }else{
                    $scope.isLoading = false;
                    $scope.title      = res.records.pages.page_title;
                    $scope.sub_title  = res.records.pages.page_subtitle;
                    $scope.content    = res.records.pages.page_content;
                }
            }else if(res.status == 'success'){
                $scope.isLoading = false;
                $scope.title      = res.records.pages.page_title;
                $scope.sub_title  = res.records.pages.page_subtitle;
                $scope.content    = res.records.pages.page_content;
            }else{
                $state.go('app.page',{'slug':'404'});
            }
        });
    }

})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "$translatePartialLoaderProvider", "msApiProvider", "msNavigationServiceProvider"];
    angular
        .module('app.login', ['app.login.profile','app.login.change-password','app.login.forgot-password','app.login.new-login','app.login.edit-profile','app.login.new-password'
           ])
        .config(config);

    /** @ngInject */
    function config($stateProvider, $translatePartialLoaderProvider, msApiProvider, msNavigationServiceProvider)
    {
        $stateProvider.state('app.login', {
            url      : '/oldlogin',
            views    : {
                'main@'                          : {
                    templateUrl: 'app/core/layouts/content-only.html',
                    controller : 'MainController as vm'
                },
                'content@app.login': {
                    templateUrl: 'app/main/login/login.html',
                    controller : 'LoginController as vm'
                }
            },

        });
        $stateProvider.state('app.logout', {
            url      : '/logout',
            views    : {
                'content@app': {
                    templateUrl: 'app/main/login/new-login/new-login.html',
                    controller : 'LoginController as vm'
                }
            },

        });
        if(sessionStorage.api_token != undefined && sessionStorage.api_token != ''){

            // Navigation
         /*   msNavigationServiceProvider.saveItem('profile', {
                title : 'Profile',
                group: true,
                weight: 18,
                state: 'app.profile'
            });
            msNavigationServiceProvider.saveItem('profile.myprofile', {
                title : 'My Profile',
                state: 'app.profile'
            });
            msNavigationServiceProvider.saveItem('profile.changepass', {
                title : 'Change Password',
                state: 'app.change_password'
            });
            msNavigationServiceProvider.saveItem('profile.logout', {
                title : 'Logout',
                state: 'app.logout'
            });*/
        }else{

            // Navigation
            msNavigationServiceProvider.saveItem('login', {
                title : 'Login',
                icon  : 'icon-lock-unlocked',
                weight: 13,
                state: 'app.new-login'
            });
            // Navigation
            msNavigationServiceProvider.saveItem('register', {
                title : 'Register',
                icon  : 'icon-account',
                weight: 14,
                state: 'app.register'
            });
            msNavigationServiceProvider.saveItem('forgot-password', {
                title : 'Forgot Password',
                icon  : 'icon-key-variant',
                weight: 15,
                state: 'app.forgot_password'
            });
        }
        // Translation
        $translatePartialLoaderProvider.addPart('app/main/login');

    }

})();

(function ()
{
    'use strict';

    LoginController.$inject = ["api", "$http", "$scope", "$state", "$location"];
    angular
        .module('app.login')
        .controller('LoginController', LoginController);

    /** @ngInject */
    function LoginController(api, $http, $scope, $state, $location)
    {

        if($state.current.name == 'app.logout'){
            sessionStorage.api_token = '';
            window.location.href= 'login';
        }
    	$scope.user_error = 'true';
    	var vm = this;
    	var SendData = new FormData();
    	$scope.userLogin = function(){

    		SendData.append('email',vm.form.email);
    		SendData.append('password',vm.form.password);
    		$http.defaults.headers.post['Content-Type'] = undefined;
	    	$http({

	    		url: api.localUrl+'auth',
	    		method: 'POST',
	    		data: SendData
	    	}).then(function(res){
	    		if(res.data.status == 'error'){
	    			vm.user_error = 'false';
	    			$scope.error_user_login = res.data.message;
	    		}else{
	    			sessionStorage.api_token = res.data.user_detail.api_token;
                    window.location.href='forgotpass';
	    			//$state.go('app.goal_list',{}, {reload: true});
	    		}
	    	});
    	}

        $scope.goHome = function(){
            $state.go('app.goal_list');
        }


    	//console.log(api_token);

		/*console.log('Test');
    	api.auth.login.get({email: 'test@gmail.com',pass: '123456'},function(res){

    		console.log(res);
    	});*/
    }

})();

(function ()
{
    'use strict';

    config.$inject = ["$stateProvider"];
    angular
        .module('app.embed', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider)
    {
        $stateProvider.state('app.embed', {
            url    : '/embed/:id',
            views  : {

                'main@'  : {
                    templateUrl: 'app/core/layouts/content-only.html',
                    controller : 'MainController as vm'
                },

                'content@app.embed': {
                    templateUrl: 'app/main/embed/embed.html',
                    controller : 'EmbedController as vm'
                }
            }
        });

      

     

    }

})();

(function ()
{
    'use strict';

    EmbedController.$inject = ["$scope", "$timeout", "$mdSidenav", "$state", "api", "$compile", "$mdDialog"];
    angular
        .module('app.embed')
        .controller('EmbedController', EmbedController);

    /** @ngInject */
    function EmbedController($scope, $timeout, $mdSidenav, $state, api, $compile, $mdDialog)
    {
      	var vm = this;
        window.chartWrapper = {};
        window.newTempSettings = {};
        $scope.showFirst = true;
        $scope.embedCss = 'test';
        $scope.embedJS = '';
        var chartAnimation;
        $scope.slider = {};
        google.charts.load('current'); // Don't need to specify chart libraries!
        google.charts.setOnLoadCallback(drawVisualization);

        $scope.drawVisual = function(action){
            
            if(vm.visFilters == undefined && jQuery.isEmptyObject($scope.slider) == true && vm.visFiltersMulti == undefined){
                return false;
            }
            $scope.procReq = true;
            $scope.pr = true;

            var formData = new FormData();
            formData.append('id',$state.params.id);
            if(action == 'filter'){
                formData.append('type','filter');
            }else{
                $scope.vm.visFilters = '';
                $scope.vm.visFiltersMulti = '';
                formData.append('type','non-filter');
            }
            if(jQuery.isEmptyObject($scope.slider) != true){
                var rangesList = {};
                angular.forEach($scope.slider, function(val, key){
                    var sliderRange = {};
                    if(val.min != undefined){
                        sliderRange['min'] = val.min;
                        sliderRange['max'] = val.max;
                        rangesList[key] = sliderRange;
                    }
                });                
                formData.append('range_filters',JSON.stringify(rangesList));
            }
            formData.append('filter_array',JSON.stringify(vm.visFilters));
            formData.append('filter_array_multi',JSON.stringify(vm.visFiltersMulti));
            api.postMethod.getVisualEmbed(formData).then(function(res){
                res = res.data;
                // console.log(res);
                $scope.filters = res.filters;
                $("#data_wrapper").text(JSON.stringify(res));
                $scope.charts = [];
                $scope.charts = res;
                
                setTimeout(function(){
                    var index = 1;
                    angular.forEach(res.chart_data, function(ind,val){
                        var chartTypes = JSON.parse(res.chart_types);
                        var options = res.settings;
                        if(chartTypes[val] != 'CustomMap'){
                            chartWrapper['chart_'+index] = new google.visualization.ChartWrapper({
                                chartType: chartTypes[val],
                                dataTable: res.chart_data[val],
                                options: JSON.parse(options[val][0]),
                                containerId: 'chart_wrapper_'+index,
                            });
                            //var chartSetoptions = JSON.parse(options[val][0]);
                            chartWrapper['chart_'+index].draw();
                        }else{
                            $scope.chart_cont = false;
                            // $scope.map_cont = true;
                            var chrtData = res.chart_data['chart_'+index];
                            var settings = res.settings['chart_'+index];
                            var chartHeaderArray = chrtData[0];
                            settings = JSON.parse(settings[0]);
                            try{
                                var haxColor = settings['chartColor']['colors']
                            }catch(e){
                                var haxColor = '#ED6F1D';
                            }
                            
                            var hex = haxColor.replace('#','');
                            var r = parseInt(hex.substring(0,2), 16);
                            var g = parseInt(hex.substring(2,4), 16);
                            var b = parseInt(hex.substring(4,6), 16);
                            if(res.map_display_val != null){
                                var stateCode = res.map_display_val;
                                var columnHeaderForSort = res.map_display_val[0];
                                var stateCode_Loop = res.map_display_val;
                            }
                            
                            if($.inArray(columnHeaderForSort, chartHeaderArray) !== -1){
                                var HeaderIndex = chartHeaderArray.indexOf(columnHeaderForSort);
                                var sortedArray = chrtData.sort(function(a, b){
                                    return a[HeaderIndex] - b[HeaderIndex]; 
                                });
                            }else{
                                angular.forEach(res.map_display_val, function(v,k){
                                    if($.isArray(chrtData[k])){
                                        chrtData[k].push(v);
                                    }else{
                                        var tempArray = [];
                                        tempArray.push(chrtData[k]);
                                        tempArray.push(v);
                                        chrtData[k] = tempArray;
                                    }
                                });
                                if(res.map_display_val == null){
                                    var arrayDataForSort = chrtData;
                                    delete arrayDataForSort[0];
                                    var sortedArray = arrayDataForSort.sort(function(a, b){
                                        return a[1] - b[1];
                                    });
                                    var putHeader = [];
                                    putHeader.push('String');
                                    putHeader.push('Frequecy');
                                    sortedArray.unshift(putHeader);
                                }else{
                                    var HeaderIndex = chartHeaderArray.indexOf(columnHeaderForSort);
                                    var sortedArray = chrtData.sort(function(a, b){
                                        return a[HeaderIndex] - b[HeaderIndex]; 
                                    });
                                }
                            }
                            
                            var highest_value = Math.max.apply(Math, stateCode);
                            
                            $('#chart_wrapper_'+index).html($compile(res.maps[val])($scope));
                            var stateInd = 0;
                            var leagend = "<div class='map-leagend'>";
                            angular.forEach(sortedArray, function(val,ind){

                                if(ind != 0){
                                    var currentClass = $('#'+val[0]).attr('class');
                                    
                                    /*$('#'+val[0])
                                    .css({'fill': 'rgba('+r+','+g+','+b+','+(stateCode_Loop[stateInd]/highest_value) +')'}).attr('class','mapArea '+currentClass);*/
                                    var colorVal = stateInd/sortedArray.length;
                                    var leagendWidth = (1/(sortedArray.length-1))*100;
                                    var colorCode = getColor(colorVal);

                                    $('#chart_wrapper_'+index+' #'+val[0])
                                    .css({'fill': colorCode }).attr('class','mapArea '+currentClass);
                                    angular.forEach(val, function(v,i_nd){
                                        if(i_nd > 0){
                                            $('#chart_wrapper_'+index+' #'+val[0]).attr(chrtData[0][i_nd].replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, "_"),v);
                                        }
                                    });
                                    if(res.map_display_val == null){
                                        leagend += '<div data-value="'+val[1]+'" style="width:'+leagendWidth+'%; background-color:'+colorCode+'"></div>';
                                    }else{
                                        leagend += '<div data-value="'+val[HeaderIndex]+'" style="width:'+leagendWidth+'%; background-color:'+colorCode+'"></div>';
                                    }
                                    stateInd++;
                                }
                            });
                            leagend += "</div>";
							leagend += "<div class='smaart-watermark'>Created with <a href='http://smaartframework.com' target='_blank'>SMAART™ Framework</a></div>";
                            $('#chart_wrapper_'+index).append(leagend);
                            //$(leagend).appendTo('body');
                            
                            $('#chart_wrapper_'+index+' .mapArea').mouseover(function (e) {
                                var elm = $(this);
                                var title=$(this).attr('title');
                                var html = '';
                                html += '<div class="inf">';
                                html += '<span class="title">'+title + '</span>';
                                angular.forEach(chrtData[0], function(v, k_in){
                                    if(k_in > 0){
                                        var atr_id = v.replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, "_");
                                        html += '<span class="data">'+v+': '+ elm.attr(atr_id)+'</span>';
                                    }
                                });
                                html += '</div>';
                                $(html).appendTo('body');
                            })
                            .mouseleave(function () {
                                $('.inf').remove();
                            }).mousemove(function(e) {
                                var mouseX = e.pageX, //X coordinates of mouse
                                    mouseY = e.pageY; //Y coordinates of mouse

                                $('.inf').css({
                                    'top': mouseY-($('.inf').height()+30),
                                    'left': mouseX
                                });
                            });
                        }
                        index++;
                    });
                },1);
                try{
                    $scope.embedCss = res.css_js.css;
                    eval(res.css_js.js);

                }catch(e){
                    $scope.embedCss = '';
                    $scope.embedJS = '';
                }
            });
        }
        
        $scope.showFirst = false;
            
        vm.chart_types = [{
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
            {
                "value": "BubbleChart",
                "label": "Bubble Chart"
            }
        ];

        $scope.edit = function(){
            $state.go('app.genvisuals_edit',{'id':$state.params.id});
        }

        function drawVisualization(){
            var formData = new FormData();
            formData.append('id',$state.params.id);
            formData.append('type','non-filter');
            api.postMethod.getVisualEmbed(formData).then(function(res){
                
                window.res = res;
                $scope.viz_name = res.data.visual_name;
                window.redrawSetting = res;
                res = res.data;
                $scope.showFilter = true;
                $scope.showCharts = true;
                if(res.status == 'error'){
                    $scope.showFilter = false;
                    $scope.filterNotFound = true;
                    $scope.showCharts = false;
                    $scope.noCharts = true;
                    return false;
                }
                $scope.filters = res.filters;
                $("#data_wrapper").text(JSON.stringify(res));
                
                $scope.charts = res;
                //console.log(res);
                setTimeout(function(){

                    var index = 1;
                    angular.forEach(res.chart_data, function(ind,val){
                        var chartTypes = JSON.parse(res.chart_types);
                        var options = res.settings;
                        var titles = res.titles;
                        if(chartTypes[val] != 'CustomMap'){
                            $scope.chart_cont = true;
                            $scope.map_cont = false;
                            chartWrapper['chart_'+index] = new google.visualization.ChartWrapper({
                                chartType: chartTypes[val],
                                dataTable: res.chart_data[val],
                                options: JSON.parse(options[val][0]),
                                containerId: 'chart_wrapper_'+index,
                            });
                            // console.log(JSON.parse(options[val][0]));
                            /*chartWrapper['chart_'+index].setOption('chartArea', {'left': 80, 'top': 40, 'right': 0, 'bottom': 40, 'width':700, 'height': 300});
                            chartWrapper['chart_'+index].setOption('width', 800);
                            chartWrapper['chart_'+index].setOption('height',400);*/
                            //var chartSetoptions = JSON.parse(options[val][0]);
                            chartWrapper['chart_'+index].draw();
                        }else{
                            var chrtData = res.chart_data['chart_'+index];
                            var settings = res.settings['chart_'+index];
                            var chartHeaderArray = chrtData[0];
                            settings = JSON.parse(settings[0]);
                            try{
                                var haxColor = settings['chartColor']['colors']
                            }catch(e){
                                var haxColor = '#ED6F1D';
                            }
                            
                            var hex = haxColor.replace('#','');
                            var r = parseInt(hex.substring(0,2), 16);
                            var g = parseInt(hex.substring(2,4), 16);
                            var b = parseInt(hex.substring(4,6), 16);
                            
                            if(res.map_display_val != null){
                                var stateCode = res.map_display_val;
                                var columnHeaderForSort = res.map_display_val[0];
                                var stateCode_Loop = res.map_display_val;;
                            }
                            
                            if($.inArray(columnHeaderForSort, chartHeaderArray) !== -1){
                                var HeaderIndex = chartHeaderArray.indexOf(columnHeaderForSort);
                                var sortedArray = chrtData.sort(function(a, b){
                                    return a[HeaderIndex] - b[HeaderIndex]; 
                                });
                            }else{
                                angular.forEach(res.map_display_val, function(v,k){
                                    if($.isArray(chrtData[k])){
                                        chrtData[k].push(v);
                                    }else{
                                        var tempArray = [];
                                        tempArray.push(chrtData[k]);
                                        tempArray.push(v);
                                        chrtData[k] = tempArray;
                                    }
                                });
                                if(res.map_display_val == null){
                                    var arrayDataForSort = chrtData;
                                    delete arrayDataForSort[0];
                                    var sortedArray = arrayDataForSort.sort(function(a, b){
                                        return a[1] - b[1];
                                    });
                                    var putHeader = [];
                                    putHeader.push('String');
                                    putHeader.push('Frequecy');
                                    sortedArray.unshift(putHeader);
                                }else{
                                    var HeaderIndex = chartHeaderArray.indexOf(columnHeaderForSort);
                                    var sortedArray = chrtData.sort(function(a, b){
                                        return a[HeaderIndex] - b[HeaderIndex]; 
                                    });
                                }
                            }
                            
                            var highest_value = Math.max.apply(Math, stateCode);
                            
                            /*$scope.chart_cont = false;
                            $scope.map_cont = true;*/
                            $('#chart_wrapper_'+index).html($compile(res.maps[val])($scope));
                            var stateInd = 0;
                            var leagend = "<div class='map-leagend'>";
                            angular.forEach(sortedArray, function(val,ind){

                                if(ind != 0){
                                    var currentClass = $('#'+val[0]).attr('class');
                                    
                                    /*$('#'+val[0])
                                    .css({'fill': 'rgba('+r+','+g+','+b+','+(stateCode_Loop[stateInd]/highest_value) +')'}).attr('class','mapArea '+currentClass);*/
                                    var colorVal = stateInd/sortedArray.length;
                                    var leagendWidth = (1/(sortedArray.length-1))*100;
                                    var colorCode = getColor(colorVal);

                                    $('#chart_wrapper_'+index+' #'+val[0])
                                    .css({'fill': colorCode }).attr('class','mapArea '+currentClass);
                                    angular.forEach(val, function(v,i_nd){
                                        if(i_nd > 0){
                                            $('#chart_wrapper_'+index+' #'+val[0]).attr(chrtData[0][i_nd].replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, "_"),v);
                                        }
                                    });
									stateInd++;
                                    if(res.map_display_val == null){
                                        leagend += '<div data-value="'+val[1]+'" style="width:'+leagendWidth+'%; background-color:'+colorCode+'"></div>';
                                    }else{
                                        leagend += '<div data-value="'+val[HeaderIndex]+'" style="width:'+leagendWidth+'%; background-color:'+colorCode+'"></div>';
                                    }
                                    
                                }
                            });
                            leagend += "</div>";
							leagend += "<div class='smaart-watermark'>Created with <a href='http://smaartframework.com' target='_blank'>SMAART™ Framework</a></div>";
                            $('#chart_wrapper_'+index).append(leagend);
                            //$(leagend).appendTo('body');
                            $('#chart_wrapper_'+index+' .mapArea').mouseover(function (e) {
                                var elm = $(this);
                                var title=$(this).attr('title');
                                var html = '';
                                html += '<div class="inf">';
                                html += '<span class="title">'+title + '</span>';
                                angular.forEach(chrtData[0], function(v, k_in){
                                    if(k_in > 0){
                                        var atr_id = v.replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, "_");
                                        html += '<span class="data">'+v+': '+ elm.attr(atr_id)+'</span>';
                                    }
                                });
                                html += '</div>';
                                $(html).appendTo('body');
                            })
                            .mouseleave(function () {
                                $('.inf').remove();
                            }).mousemove(function(e) {
                                var mouseX = e.pageX, //X coordinates of mouse
                                    mouseY = e.pageY; //Y coordinates of mouse

                                $('.inf').css({
                                    'top': mouseY-($('.inf').height()+30),
                                    'left': mouseX
                                });
                            });

                        }
                        index++;
                    });
                },5);
                try{
                    $scope.embedCss = res.css_js.css;
                    eval(res.css_js.js);

                }catch(e){
                    $scope.embedCss = '';
                    $scope.embedJS = '';
                }
            });
            
        }
        function getColor(value){
            //value from 0 to 1
            var hue=((1-value)*50).toString(10);
            return ["hsl(",hue,",100%,50%)"].join("");
        }
        $scope.showCustom = function(event, chart, ind) {
            $mdDialog.show({
              clickOutsideToClose: true,
              scope: $scope,        
              preserveScope: true,           
              templateUrl: 'app/main/visualizations/generated/edit/dialogs/visual-setting.html',
              controller: ["$scope", "$mdDialog", "$state", "api", function DialogController($scope, $mdDialog, $state, api) {
                 $scope.closeDialog = function() {
                    $mdDialog.hide();
                 }
                 setTimeout(function(){
                    if(newTempSettings[chart] == '' || newTempSettings[chart] == undefined){
                        $scope.visualSettings.chart_settings = JSON.parse(window.res.data.settings[chart][0]);
                    }else{
                        $scope.visualSettings.chart_settings = newTempSettings[chart];
                    }
                 },1);
                 var chartSetting = JSON.parse(window.res.data.default_settings);
                 $scope.settings = chartSetting;
                 $scope.saveVisualSettings = function(){
                    if($scope.visualSettings.chart_settings.colors == undefined || $scope.visualSettings.chart_settings.colors == ''){
                        delete $scope.visualSettings.chart_settings.colors;
                    }else{
                        $scope.visualSettings.chart_settings.colors = ($scope.visualSettings.chart_settings.colors).split(',');
                    }
                    newTempSettings[chart] = $scope.visualSettings.chart_settings;
                    var formData = new FormData();
                    formData.append('chart',chart);
                    formData.append('settings',JSON.stringify($scope.visualSettings.chart_settings));
                    formData.append('visual_id',$state.params.id);
                    api.postMethod.saveSettings(formData).then(function(res){
                        if(res.data.status == 'success'){
                            chartWrapper['chart_'+ind].setOptions($scope.visualSettings.chart_settings);
                            chartWrapper['chart_'+ind].draw();
                            $mdDialog.hide();
                        }
                    });
                 }
              }]
           });
        };

        $scope.setVisualizationType = function() {
            var chart_type = $scope.visualization.charttype;
            chartWrapper.setChartType(chart_type);
            chartWrapper.draw();
        }

    }
})();




(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "msNavigationServiceProvider"];
    angular
        .module('app.dashboardfront', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider,msNavigationServiceProvider )
    {
        $stateProvider.state('app.dashboardfront', {
            url    : '/dashboard',
            views  : {

                'content@app': {
                    templateUrl: 'app/main/dashboardfront/dashboardfront.html',
                    controller : 'DashboardfrontController as vm'
                }
            }
        });

      msNavigationServiceProvider.saveItem('dashboardfront', {
                title : 'Dashboard',
                group : true,
                // state : 'app.dashboardfront',
                cache: false,
                weight: 1
            });
      msNavigationServiceProvider.saveItem('dashboardfront.dashboard', {
                title : 'Dashboard',
                icon  : 'icon-camera-timer',
                state : 'app.dashboardfront',
                cache: false,
               
            });

     

    }

})();

(function ()
{
    'use strict';

    DashboardfrontController.$inject = ["$scope", "$state", "api", "$mdToast"];
    angular
        .module('app.dashboardfront')
        .controller('DashboardfrontController', DashboardfrontController);

    /** @ngInject */
    function DashboardfrontController($scope, $state, api,$mdToast)
    {
        if(sessionStorage.api_token != undefined && sessionStorage.api_token != ''){
            $scope.dashboard = true;
            $scope.loginLinks = false;
        	api.dashboard.getDetails.get({}, function(res){
        		
                api.getUserSettings.settings.get({}, function(resDet){
                    console.log(resDet);
                    $scope.DatasetsCount = res.dataset_count;
                    $scope.DatasetsList = res.dataset_list;
                   /* console.log(res.dataset_list);*/
                    $scope.VizCount = res.visual_count;
                    $scope.VizList = res.visual_list;
                     // console.log(res);
                    $scope.surveyCount = res.survey_count;
                    $scope.surveyList = res.survey_list;

                    $scope.organization_detail = res.organization_detail;
                    // console.log(res);
                    $scope.profilePic = res.user_meta.profile_pic;
                    
                    $scope.userProfile = res.user_profile;
                    
                    $scope.userCount = res.user_count;
                     // console.log(res.user_list);
                    $scope.userList = res.user_list;

                    try{
                        //Show Hide Settings
                        angular.forEach(JSON.parse(resDet.response.value), function(v,k){
                            $scope[k] = v;
                        });
                    }catch(e){

                    }
                    
                    $scope.twt = true;
                });

        	});
        }else{
            $scope.dashboard = false;
            $scope.loginLinks = true;
        }

        $scope.allViz = function(){
            $state.go('app.genvisuals_list');
        }
        
         $scope.unapprove = function(id,ind){
            $('.loader_'+ind).show();
            api.userAction.unapprove.get({
                'id': id
            },function(res){
                if(res.status == 'success'){
                    $mdToast.show(
                       $mdToast.simple()
                        .textContent('User disapproved successfully!')
                        .position('top right')
                        .hideDelay(5000)
                    );
                    $state.go($state.current, {}, {
                        reload: true
                    });
                }
            });
        }

        $scope.approve = function(id,ind){
            $('.loader_'+ind).show();
            api.userAction.approve.get({
                'id': id
            },function(res){
                if(res.status == 'success'){
                    $mdToast.show(
                       $mdToast.simple()
                        .textContent('User approved successfully!')
                        .position('top right')
                        .hideDelay(5000)
                    );
                    $state.go($state.current, {}, {
                        reload: true
                    });
                }
            });
        }
        $scope.visitSettings = function(){
            $state.go('app.settings');
        }
    }
})();




(function ()
{
    'use strict';

    config.$inject = ["$stateProvider", "msNavigationServiceProvider"];
    angular
        .module('app.dashboard', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider,msNavigationServiceProvider)
    {
        $stateProvider.state('app.dashboard', {
            url    : '/users',
            views  : {
              
                'content@app': {
                    templateUrl: 'app/main/dashboard/dashboard.html',
                    controller : 'DashboardController as vm'
                }
            }
        });

         if(sessionStorage.api_token != undefined && sessionStorage.api_token != ''){

             msNavigationServiceProvider.saveItem('manageUser', {
                title : 'Manage Users',
                group : true,
                // state : 'app.genvisuals_list',
                cache: false,
                weight: 29
            });

            msNavigationServiceProvider.saveItem('manageUser.manageUser', {
                title : 'Users',
                icon  : 'icon-account-multiple',
                state : 'app.dashboard',
                cache: false,
            });
        }
    }

})();

(function() {
    'use strict';

    DashboardController.$inject = ["$scope", "$mdBottomSheet", "api", "$mdDialog", "$state", "$mdToast"];
    angular.module('app.dashboard').filter('pagination', function() {
        return function(input, start) {
            start = +start;
            return input.slice(start);
        };
    });
    angular
        .module('app.dashboard')
        .controller('DashboardController', DashboardController);

    /** @ngInject **/
    function DashboardController($scope, $mdBottomSheet, api, $mdDialog, $state, $mdToast) {
        
        var vm = this;
        api.listuser.list.get({}, function(res) {
           
            $scope.userlist = res.user_list;
           
        });

        $scope.openMenu = function($mdOpenMenu, ev) {
            originatorEv = ev;
            $mdOpenMenu(ev);
        };
        $scope.openBottomSheet = function() {
            $mdBottomSheet.show({
                template: '<md-bottom-sheet>Hello!</md-bottom-sheet>'
            });
        };
		vm.dtOptions = {
				dom       : '<"top"<"left"<"length"l>><"right"<"search"f>>>rt<"bottom"<"left"<"info"i>><"right"<"pagination"p>>>',
				pagingType: 'full_numbers',
				order: [[ 0, "desc" ]],
				autoWidth : false,
				responsive: true
		};
        $scope.isDisabled = false;

        $scope.selected = [1];

        $scope.toggle = function(column, list) {
            var idx = list.indexOf(column);
            if (idx > -1) {
                list.splice(idx, 1);
            } else {
                list.push(column);
            }
        };
        $scope.exists = function(column, list) {
            return list.indexOf(column) > -1;
        };



        $scope.toggleAll = function() {
            if ($scope.selected.length === $scope.columns.length) {
                $scope.selected = [];
            } else if ($scope.selected.length === 0 || $scope.selected.length > 0) {
                $scope.selected = $scope.columns.slice(0);
            }
        };
        $scope.deleteUser = function(userId, ev) {

            var confirm = $mdDialog.confirm({

                    onComplete: function afterShowAnimation() {
                        var $dialog = angular.element(document.querySelector('md-dialog'));
                        var $actionsSection = $dialog.find('md-dialog-actions');
                        var $cancelButton = $actionsSection.children()[0];
                        var $confirmButton = $actionsSection.children()[1];
                        angular.element($confirmButton).addClass('md-raised md-warn ph-15');
                        angular.element($cancelButton).addClass('md-raised ph-15');
                    }

                })
                .title('Would you like to delete this user?')
                .textContent('That user will be deleted permanently and no longer available.')
                .ariaLabel('Delete User')
                .targetEvent(ev)
                .ok('Yes, delete it!')
                .cancel('No, don\'t delete');


            $mdDialog.show(confirm).then(function() {
                api.userAction.deleteUser.get({
                    'id': userId
                }, function(res) {
                    if (res.status == 'success') {
                        $state.go($state.current, {}, {
                            reload: true
                        });
                    }
                });
            }, function() {

            });
        }

        $scope.unapprove = function(id,ind){
        	$('.loader_'+ind).show();
        	api.userAction.unapprove.get({
        		'id': id
        	},function(res){
        		if(res.status == 'success'){
        			$mdToast.show(
	                   $mdToast.simple()
                      	.textContent('User disapproved successfully!')
                      	.position('top right')
                      	.hideDelay(5000)
	                );
        			$state.go($state.current, {}, {
                        reload: true
                    });
        		}
        	});
        }

        $scope.approve = function(id,ind){
        	$('.loader_'+ind).show();
        	api.userAction.approve.get({
        		'id': id
        	},function(res){
        		if(res.status == 'success'){
        			$mdToast.show(
	                   $mdToast.simple()
                      	.textContent('User approved successfully!')
                      	.position('top right')
                      	.hideDelay(5000)
	                );
        			$state.go($state.current, {}, {
                        reload: true
                    });
        		}
        	});
        }

        $scope.editUser = function(event,userId) {
        	
            $mdDialog.show({
                clickOutsideToClose: true,
                scope: $scope,
                preserveScope: true,
                templateUrl: '/app/main/dashboard/dialogs/edit-dialog.html',
                controller: ["$scope", "$mdDialog", "api", function DialogController($scope, $mdDialog, api) {
                	var vm = this;
                	api.userAction.editUser.get({
                		'id': userId
                	},function(res){
                		
                		$scope.email = res.user_data.email;
                		$scope.username = res.user_data.name;
                        
                		$scope.phone = res.user_data.phone;
                		
                		$scope.userid = res.user_data.id;
                		var path = api.baseUrl;
                		path = path.split('api');
                		$scope.imagePath = path[0];
                		$scope.imageName = res.user_data.profile_pic;
                	});
                    $scope.closeDialog = function() {
                        $mdDialog.hide();
                    }

                    $scope.saveEdituser = function(id){

        				var formdata = new FormData();
        				var minisID = [];
        				var departID = [];
        				
        				formdata.append('name',$scope.username);
        				formdata.append('phone',$scope.phone);
        				formdata.append('email',$scope.email);
        				
        				formdata.append('id',id);
                        if($scope.files[0] !== undefined){
                            formdata.append('profile_pic',$scope.files[0].lfFile);
                        }
        				api.postMethod.saveEditUser(formdata).then(function(res){
        					
                             $mdDialog.hide();
                             $state.go($state.current, {}, {reload: true});
        				});
        			}
                }]
            });
        };

        $scope.updateUser = function(){
              
        };
    }

})();
(function ()
{
    'use strict';

    config.$inject = ["$stateProvider"];
    angular
        .module('app.activity-log', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider)
    {
        $stateProvider.state('app.activity_log', {
            url    : '/activity-log',
            views  : {
              
                'content@app': {
                    templateUrl: 'app/main/activity-log/activity-log.html',
                    controller : 'ActivityLogController as vm'
                }
            }
        });
    }

})();

(function() {
    'use strict';
    ActivityLogController.$inject = ["$scope", "api"];
    angular
        .module('app.activity-log')
        .controller('ActivityLogController', ActivityLogController);

    /** @ngInject **/
    function ActivityLogController($scope,api) {
        
        api.logs.list.get({},function(res){
        	console.log(res);
        	$scope.logs = res.log;
        });

        $scope.emailName = function(data, type){
        	var data = JSON.parse(data);
        	return data[type];
        }

        $scope.dtOptions = {
						dom       : '<"top"<"left"<"length"l>><"right"<"search"f>>>rt<"bottom"<"left"<"info"i>><"right"<"pagination"p>>>',
						pagingType: 'full_numbers',
						order: [[ 0, "desc" ]],
						autoWidth : false,
						responsive: true
				};
        
    }

})();
(function ()
{
    'use strict';

    fuseThemingService.$inject = ["$cookies", "$log", "$mdTheming"];
    angular
        .module('app.core')
        .service('fuseTheming', fuseThemingService);

    /** @ngInject */
    function fuseThemingService($cookies, $log, $mdTheming)
    {
        var service = {
            getRegisteredPalettes: getRegisteredPalettes,
            getRegisteredThemes  : getRegisteredThemes,
            setActiveTheme       : setActiveTheme,
            setThemesList        : setThemesList,
            themes               : {
                list  : {},
                active: {
                    'name' : '',
                    'theme': {}
                }
            }
        };

        return service;

        //////////

        /**
         * Get registered palettes
         *
         * @returns {*}
         */
        function getRegisteredPalettes()
        {
            return $mdTheming.PALETTES;
        }

        /**
         * Get registered themes
         *
         * @returns {*}
         */
        function getRegisteredThemes()
        {
            return $mdTheming.THEMES;
        }

        /**
         * Set active theme
         *
         * @param themeName
         */
        function setActiveTheme(themeName)
        {
            // If theme does not exist, fallback to the default theme
            if ( angular.isUndefined(service.themes.list[themeName]) )
            {
                // If there is no theme called "default"...
                if ( angular.isUndefined(service.themes.list.default) )
                {
                    $log.error('You must have at least one theme named "default"');
                    return;
                }

                $log.warn('The theme "' + themeName + '" does not exist! Falling back to the "default" theme.');

                // Otherwise set theme to default theme
                service.themes.active.name = 'default';
                service.themes.active.theme = service.themes.list.default;
                $cookies.put('selectedTheme', service.themes.active.name);

                return;
            }

            service.themes.active.name = themeName;
            service.themes.active.theme = service.themes.list[themeName];
            $cookies.put('selectedTheme', themeName);
        }

        /**
         * Set available themes list
         *
         * @param themeList
         */
        function setThemesList(themeList)
        {
            service.themes.list = themeList;
        }
    }
})();

(function ()
{
    'use strict';

    config.$inject = ["$mdThemingProvider", "fusePalettes", "fuseThemes"];
    angular
        .module('app.core')
        .config(config);

    /** @ngInject */
    function config($mdThemingProvider, fusePalettes, fuseThemes)
    {
        // Inject Cookies Service
        var $cookies;
        angular.injector(['ngCookies']).invoke([
            '$cookies', function (_$cookies)
            {
                $cookies = _$cookies;
            }
        ]);

        // Check if custom theme exist in cookies
        var customTheme = $cookies.getObject('customTheme');
        if ( customTheme )
        {
            fuseThemes['custom'] = customTheme;
        }

        $mdThemingProvider.alwaysWatchTheme(true);

        // Define custom palettes
        angular.forEach(fusePalettes, function (palette)
        {
            $mdThemingProvider.definePalette(palette.name, palette.options);
        });

        // Register custom themes
        angular.forEach(fuseThemes, function (theme, themeName)
        {
            $mdThemingProvider.theme(themeName)
                .primaryPalette(theme.primary.name, theme.primary.hues)
                .accentPalette(theme.accent.name, theme.accent.hues)
                .warnPalette(theme.warn.name, theme.warn.hues)
                .backgroundPalette(theme.background.name, theme.background.hues);
        });
    }

})();
(function ()
{
    'use strict';

    var fuseThemes = {
        default  : {
            primary   : {
                name: 'fuse-paleblue',
                hues: {
                    'default': '700',
                    'hue-1'  : '500',
                    'hue-2'  : '600',
                    'hue-3'  : '400'
                }
            },
            accent    : {
                name: 'light-blue',
                hues: {
                    'default': '600',
                    'hue-1'  : '400',
                    'hue-2'  : '700',
                    'hue-3'  : 'A100'
                }
            },
            warn      : {
                name: 'red'
            },
            background: {
                name: 'grey',
                hues: {
                    'default': 'A100',
                    'hue-1'  : 'A100',
                    'hue-2'  : '100',
                    'hue-3'  : '300'
                }
            }
        },
        'pinkTheme': {
            primary   : {
                name: 'blue-grey',
                hues: {
                    'default': '800',
                    'hue-1'  : '600',
                    'hue-2'  : '400',
                    'hue-3'  : 'A100'
                }
            },
            accent    : {
                name: 'pink',
                hues: {
                    'default': '400',
                    'hue-1'  : '300',
                    'hue-2'  : '600',
                    'hue-3'  : 'A100'
                }
            },
            warn      : {
                name: 'blue'
            },
            background: {
                name: 'grey',
                hues: {
                    'default': 'A100',
                    'hue-1'  : 'A100',
                    'hue-2'  : '100',
                    'hue-3'  : '300'
                }
            }
        },
        'tealTheme': {
            primary   : {
                name: 'fuse-blue',
                hues: {
                    'default': '900',
                    'hue-1'  : '600',
                    'hue-2'  : '500',
                    'hue-3'  : 'A100'
                }
            },
            accent    : {
                name: 'teal',
                hues: {
                    'default': '500',
                    'hue-1'  : '400',
                    'hue-2'  : '600',
                    'hue-3'  : 'A100'
                }
            },
            warn      : {
                name: 'deep-orange'
            },
            background: {
                name: 'grey',
                hues: {
                    'default': 'A100',
                    'hue-1'  : 'A100',
                    'hue-2'  : '100',
                    'hue-3'  : '300'
                }
            }
        }
    };

    angular
        .module('app.core')
        .constant('fuseThemes', fuseThemes);
})();
(function () {
    'use strict';

    var fusePalettes = [
        {
            name: 'fuse-blue',
            options: {
                '50': '#ebf1fa',
                '100': '#c2d4ef',
                '200': '#9ab8e5',
                '300': '#78a0dc',
                '400': '#5688d3',
                '500': '#3470ca',
                '600': '#2e62b1',
                '700': '#275498',
                '800': '#21467e',
                '900': '#1a3865',
                'A100': '#c2d4ef',
                'A200': '#9ab8e5',
                'A400': '#5688d3',
                'A700': '#275498',
                'contrastDefaultColor': 'light',
                'contrastDarkColors': '50 100 200 A100',
                'contrastStrongLightColors': '300 400'
            }
        },
        {
            name: 'fuse-paleblue',
            options: {
                '50': '#ececee',
                '100': '#c5c6cb',
                '200': '#9ea1a9',
                '300': '#7d818c',
                '400': '#5c616f',
                '500': '#3c4252',
                '600': '#353a48',
                '700': '#2d323e',
                '800': '#262933',
                '900': '#1e2129',
                'A100': '#c5c6cb',
                'A200': '#9ea1a9',
                'A400': '#5c616f',
                'A700': '#2d323e',
                'contrastDefaultColor': 'light',
                'contrastDarkColors': '50 100 200 A100',
                'contrastStrongLightColors': '300 400'
            }
        }
    ];

    angular
        .module('app.core')
        .constant('fusePalettes', fusePalettes);
})();
(function ()
{
    'use strict';

    fuseGeneratorService.$inject = ["$cookies", "$log", "fuseTheming"];
    angular
        .module('app.core')
        .factory('fuseGenerator', fuseGeneratorService);

    /** @ngInject */
    function fuseGeneratorService($cookies, $log, fuseTheming)
    {
        // Storage for simplified themes object
        var themes = {};

        var service = {
            generate: generate,
            rgba    : rgba
        };

        return service;

        //////////

        /**
         * Generate less variables for each theme from theme's
         * palette by using material color naming conventions
         */
        function generate()
        {
            // Get registered themes and palettes and copy
            // them so we don't modify the original objects
            var registeredThemes = angular.copy(fuseTheming.getRegisteredThemes());
            var registeredPalettes = angular.copy(fuseTheming.getRegisteredPalettes());

            // First, create a simplified object that stores
            // all registered themes and their colors

            // Iterate through registered themes
            angular.forEach(registeredThemes, function (registeredTheme)
            {
                themes[registeredTheme.name] = {};

                // Iterate through color types (primary, accent, warn & background)
                angular.forEach(registeredTheme.colors, function (colorType, colorTypeName)
                {
                    themes[registeredTheme.name][colorTypeName] = {
                        'name'  : colorType.name,
                        'levels': {
                            'default': {
                                'color'    : rgba(registeredPalettes[colorType.name][colorType.hues.default].value),
                                'contrast1': rgba(registeredPalettes[colorType.name][colorType.hues.default].contrast, 1),
                                'contrast2': rgba(registeredPalettes[colorType.name][colorType.hues.default].contrast, 2),
                                'contrast3': rgba(registeredPalettes[colorType.name][colorType.hues.default].contrast, 3),
                                'contrast4': rgba(registeredPalettes[colorType.name][colorType.hues.default].contrast, 4)
                            },
                            'hue1'   : {
                                'color'    : rgba(registeredPalettes[colorType.name][colorType.hues['hue-1']].value),
                                'contrast1': rgba(registeredPalettes[colorType.name][colorType.hues['hue-1']].contrast, 1),
                                'contrast2': rgba(registeredPalettes[colorType.name][colorType.hues['hue-1']].contrast, 2),
                                'contrast3': rgba(registeredPalettes[colorType.name][colorType.hues['hue-1']].contrast, 3),
                                'contrast4': rgba(registeredPalettes[colorType.name][colorType.hues['hue-1']].contrast, 4)
                            },
                            'hue2'   : {
                                'color'    : rgba(registeredPalettes[colorType.name][colorType.hues['hue-2']].value),
                                'contrast1': rgba(registeredPalettes[colorType.name][colorType.hues['hue-2']].contrast, 1),
                                'contrast2': rgba(registeredPalettes[colorType.name][colorType.hues['hue-2']].contrast, 2),
                                'contrast3': rgba(registeredPalettes[colorType.name][colorType.hues['hue-2']].contrast, 3),
                                'contrast4': rgba(registeredPalettes[colorType.name][colorType.hues['hue-2']].contrast, 4)
                            },
                            'hue3'   : {
                                'color'    : rgba(registeredPalettes[colorType.name][colorType.hues['hue-3']].value),
                                'contrast1': rgba(registeredPalettes[colorType.name][colorType.hues['hue-3']].contrast, 1),
                                'contrast2': rgba(registeredPalettes[colorType.name][colorType.hues['hue-3']].contrast, 2),
                                'contrast3': rgba(registeredPalettes[colorType.name][colorType.hues['hue-3']].contrast, 3),
                                'contrast4': rgba(registeredPalettes[colorType.name][colorType.hues['hue-3']].contrast, 4)
                            }
                        }
                    };
                });
            });

            // Process themes one more time and then store them in the service for external use
            processAndStoreThemes(themes);

            // Iterate through simplified themes
            // object and create style variables
            var styleVars = {};

            // Iterate through registered themes
            angular.forEach(themes, function (theme, themeName)
            {
                styleVars = {};
                styleVars['@themeName'] = themeName;

                // Iterate through color types (primary, accent, warn & background)
                angular.forEach(theme, function (colorTypes, colorTypeName)
                {
                    // Iterate through color levels (default, hue1, hue2 & hue3)
                    angular.forEach(colorTypes.levels, function (colors, colorLevelName)
                    {
                        // Iterate through color name (color, contrast1, contrast2, contrast3 & contrast4)
                        angular.forEach(colors, function (color, colorName)
                        {
                            styleVars['@' + colorTypeName + ucfirst(colorLevelName) + ucfirst(colorName)] = color;
                        });
                    });
                });

                // Render styles
                render(styleVars);
            });
        }

        // ---------------------------
        //  INTERNAL HELPER FUNCTIONS
        // ---------------------------

        /**
         * Process and store themes for global use
         *
         * @param _themes
         */
        function processAndStoreThemes(_themes)
        {
            // Here we will go through every registered theme one more time
            // and try to simplify their objects as much as possible for
            // easier access to their properties.
            var themes = angular.copy(_themes);

            // Iterate through themes
            angular.forEach(themes, function (theme)
            {
                // Iterate through color types (primary, accent, warn & background)
                angular.forEach(theme, function (colorType, colorTypeName)
                {
                    theme[colorTypeName] = colorType.levels;
                    theme[colorTypeName].color = colorType.levels.default.color;
                    theme[colorTypeName].contrast1 = colorType.levels.default.contrast1;
                    theme[colorTypeName].contrast2 = colorType.levels.default.contrast2;
                    theme[colorTypeName].contrast3 = colorType.levels.default.contrast3;
                    theme[colorTypeName].contrast4 = colorType.levels.default.contrast4;
                    delete theme[colorTypeName].default;
                });
            });

            // Store themes and set selected theme for the first time
            fuseTheming.setThemesList(themes);

            // Remember selected theme.
            var selectedTheme = $cookies.get('selectedTheme');

            if ( selectedTheme )
            {
                fuseTheming.setActiveTheme(selectedTheme);
            }
            else
            {
                fuseTheming.setActiveTheme('default');
            }
        }


        /**
         * Render css files
         *
         * @param styleVars
         */
        function render(styleVars)
        {
            var cssTemplate = '/* Content hack because they wont fix */\n/* https://github.com/angular/material/pull/8067 */\n[md-theme="@themeName"] md-content.md-hue-1,\nmd-content.md-@themeName-theme.md-hue-1 {\n    color: @backgroundHue1Contrast1;\n    background-color: @backgroundHue1Color;\n}\n\n[md-theme="@themeName"] md-content.md-hue-2,\nmd-content.md-@themeName-theme.md-hue-2 {\n    color: @backgroundHue2Contrast1;\n    background-color: @backgroundHue2Color;\n}\n\n[md-theme="@themeName"] md-content.md-hue-3,\n md-content.md-@themeName-theme.md-hue-3 {\n    color: @backgroundHue3Contrast1;\n    background-color: @backgroundHue3Color;\n}\n\n/* Text Colors */\n[md-theme="@themeName"] a {\n    color: @accentDefaultColor;\n}\n\n[md-theme="@themeName"] .secondary-text,\n[md-theme="@themeName"] .icon {\n    color: @backgroundDefaultContrast2;\n}\n\n[md-theme="@themeName"] .hint-text,\n[md-theme="@themeName"] .disabled-text {\n    color: @backgroundDefaultContrast3;\n}\n\n[md-theme="@themeName"] .fade-text,\n[md-theme="@themeName"] .divider {\n    color: @backgroundDefaultContrast4;\n}\n\n/* Primary */\n[md-theme="@themeName"] .md-primary-bg {\n    background-color: @primaryDefaultColor;\n    color: @primaryDefaultContrast1;\n}\n\n[md-theme="@themeName"] .md-primary-bg .secondary-text,\n[md-theme="@themeName"] .md-primary-bg .icon {\n    color: @primaryDefaultContrast2;\n}\n\n[md-theme="@themeName"] .md-primary-bg .hint-text,\n[md-theme="@themeName"] .md-primary-bg .disabled-text {\n    color: @primaryDefaultContrast3;\n}\n\n[md-theme="@themeName"] .md-primary-bg .fade-text,\n[md-theme="@themeName"] .md-primary-bg .divider {\n    color: @primaryDefaultContrast4;\n}\n\n/* Primary, Hue-1 */\n[md-theme="@themeName"] .md-primary-bg.md-hue-1 {\n    background-color: @primaryHue1Color;\n    color: @primaryHue1Contrast1;\n}\n\n[md-theme="@themeName"] .md-primary-bg.md-hue-1 .secondary-text,\n[md-theme="@themeName"] .md-primary-bg.md-hue-1 .icon {\n    color: @primaryHue1Contrast2;\n}\n\n[md-theme="@themeName"] .md-primary-bg.md-hue-1 .hint-text,\n[md-theme="@themeName"] .md-primary-bg.md-hue-1 .disabled-text {\n    color: @primaryHue1Contrast3;\n}\n\n[md-theme="@themeName"] .md-primary-bg.md-hue-1 .fade-text,\n[md-theme="@themeName"] .md-primary-bg.md-hue-1 .divider {\n    color: @primaryHue1Contrast4;\n}\n\n/* Primary, Hue-2 */\n[md-theme="@themeName"] .md-primary-bg.md-hue-2 {\n    background-color: @primaryHue2Color;\n    color: @primaryHue2Contrast1;\n}\n\n[md-theme="@themeName"] .md-primary-bg.md-hue-2 .secondary-text,\n[md-theme="@themeName"] .md-primary-bg.md-hue-2 .icon {\n    color: @primaryHue2Contrast2;\n}\n\n[md-theme="@themeName"] .md-primary-bg.md-hue-2 .hint-text,\n[md-theme="@themeName"] .md-primary-bg.md-hue-2 .disabled-text {\n    color: @primaryHue2Contrast3;\n}\n\n[md-theme="@themeName"] .md-primary-bg.md-hue-2 .fade-text,\n[md-theme="@themeName"] .md-primary-bg.md-hue-2 .divider {\n    color: @primaryHue2Contrast4;\n}\n\n/* Primary, Hue-3 */\n[md-theme="@themeName"] .md-primary-bg.md-hue-3 {\n    background-color: @primaryHue3Color;\n    color: @primaryHue3Contrast1;\n}\n\n[md-theme="@themeName"] .md-primary-bg.md-hue-3 .secondary-text,\n[md-theme="@themeName"] .md-primary-bg.md-hue-3 .icon {\n    color: @primaryHue3Contrast1;\n}\n\n[md-theme="@themeName"] .md-primary-bg.md-hue-3 .hint-text,\n[md-theme="@themeName"] .md-primary-bg.md-hue-3 .disabled-text {\n    color: @primaryHue3Contrast3;\n}\n\n[md-theme="@themeName"] .md-primary-bg.md-hue-3 .fade-text,\n[md-theme="@themeName"] .md-primary-bg.md-hue-3 .divider {\n    color: @primaryHue3Contrast4;\n}\n\n/* Primary foreground */\n[md-theme="@themeName"] .md-primary-fg {\n    color: @primaryDefaultColor !important;\n}\n\n/* Primary foreground, Hue-1 */\n[md-theme="@themeName"] .md-primary-fg.md-hue-1 {\n    color: @primaryHue1Color !important;\n}\n\n/* Primary foreground, Hue-2 */\n[md-theme="@themeName"] .md-primary-fg.md-hue-2 {\n    color: @primaryHue2Color !important;\n}\n\n/* Primary foreground, Hue-3 */\n[md-theme="@themeName"] .md-primary-fg.md-hue-3 {\n    color: @primaryHue3Color !important;\n}\n\n/* Accent */\n[md-theme="@themeName"] .md-accent-bg {\n    background-color: @accentDefaultColor;\n    color: @accentDefaultContrast1;\n}\n\n[md-theme="@themeName"] .md-accent-bg .secondary-text,\n[md-theme="@themeName"] .md-accent-bg .icon {\n    color: @accentDefaultContrast2;\n}\n\n[md-theme="@themeName"] .md-accent-bg .hint-text,\n[md-theme="@themeName"] .md-accent-bg .disabled-text {\n    color: @accentDefaultContrast3;\n}\n\n[md-theme="@themeName"] .md-accent-bg .fade-text,\n[md-theme="@themeName"] .md-accent-bg .divider {\n    color: @accentDefaultContrast4;\n}\n\n/* Accent, Hue-1 */\n[md-theme="@themeName"] .md-accent-bg.md-hue-1 {\n    background-color: @accentHue1Color;\n    color: @accentHue1Contrast1;\n}\n\n[md-theme="@themeName"] .md-accent-bg.md-hue-1 .secondary-text,\n[md-theme="@themeName"] .md-accent-bg.md-hue-1 .icon {\n    color: @accentHue1Contrast2;\n}\n\n[md-theme="@themeName"] .md-accent-bg.md-hue-1 .hint-text,\n[md-theme="@themeName"] .md-accent-bg.md-hue-1 .disabled-text {\n    color: @accentHue1Contrast3;\n}\n\n[md-theme="@themeName"] .md-accent-bg.md-hue-1 .fade-text,\n[md-theme="@themeName"] .md-accent-bg.md-hue-1 .divider {\n    color: @accentHue1Contrast4;\n}\n\n/* Accent, Hue-2 */\n[md-theme="@themeName"] .md-accent-bg.md-hue-2 {\n    background-color: @accentHue2Color;\n    color: @accentHue2Contrast1;\n}\n\n[md-theme="@themeName"] .md-accent-bg.md-hue-2 .secondary-text,\n[md-theme="@themeName"] .md-accent-bg.md-hue-2 .icon {\n    color: @accentHue2Contrast2;\n}\n\n[md-theme="@themeName"] .md-accent-bg.md-hue-2 .hint-text,\n[md-theme="@themeName"] .md-accent-bg.md-hue-2 .disabled-text {\n    color: @accentHue2Contrast3;\n}\n\n[md-theme="@themeName"] .md-accent-bg.md-hue-2 .fade-text,\n[md-theme="@themeName"] .md-accent-bg.md-hue-2 .divider {\n    color: @accentHue2Contrast4;\n}\n\n/* Accent, Hue-3 */\n[md-theme="@themeName"] .md-accent-bg.md-hue-3 {\n    background-color: @accentHue3Color;\n    color: @accentHue3Contrast1;\n}\n\n[md-theme="@themeName"] .md-accent-bg.md-hue-3 .secondary-text,\n[md-theme="@themeName"] .md-accent-bg.md-hue-3 .icon {\n    color: @accentHue3Contrast1;\n}\n\n[md-theme="@themeName"] .md-accent-bg.md-hue-3 .hint-text,\n[md-theme="@themeName"] .md-accent-bg.md-hue-3 .disabled-text {\n    color: @accentHue3Contrast3;\n}\n\n[md-theme="@themeName"] .md-accent-bg.md-hue-3 .fade-text,\n[md-theme="@themeName"] .md-accent-bg.md-hue-3 .divider {\n    color: @accentHue3Contrast4;\n}\n\n/* Accent foreground */\n[md-theme="@themeName"] .md-accent-fg {\n    color: @accentDefaultColor !important;\n}\n\n/* Accent foreground, Hue-1 */\n[md-theme="@themeName"] .md-accent-fg.md-hue-1 {\n    color: @accentHue1Color !important;\n}\n\n/* Accent foreground, Hue-2 */\n[md-theme="@themeName"] .md-accent-fg.md-hue-2 {\n    color: @accentHue2Color !important;\n}\n\n/* Accent foreground, Hue-3 */\n[md-theme="@themeName"] .md-accent-fg.md-hue-3 {\n    color: @accentHue3Color !important;\n}\n\n/* Warn */\n[md-theme="@themeName"] .md-warn-bg {\n    background-color: @warnDefaultColor;\n    color: @warnDefaultContrast1;\n}\n\n[md-theme="@themeName"] .md-warn-bg .secondary-text,\n[md-theme="@themeName"] .md-warn-bg .icon {\n    color: @warnDefaultContrast2;\n}\n\n[md-theme="@themeName"] .md-warn-bg .hint-text,\n[md-theme="@themeName"] .md-warn-bg .disabled-text {\n    color: @warnDefaultContrast3;\n}\n\n[md-theme="@themeName"] .md-warn-bg .fade-text,\n[md-theme="@themeName"] .md-warn-bg .divider {\n    color: @warnDefaultContrast4;\n}\n\n/* Warn, Hue-1 */\n[md-theme="@themeName"] .md-warn-bg.md-hue-1 {\n    background-color: @warnHue1Color;\n    color: @warnHue1Contrast1;\n}\n\n[md-theme="@themeName"] .md-warn-bg.md-hue-1 .secondary-text,\n[md-theme="@themeName"] .md-warn-bg.md-hue-1 .icon {\n    color: @warnHue1Contrast2;\n}\n\n[md-theme="@themeName"] .md-warn-bg.md-hue-1 .hint-text,\n[md-theme="@themeName"] .md-warn-bg.md-hue-1 .disabled-text {\n    color: @warnHue1Contrast3;\n}\n\n[md-theme="@themeName"] .md-warn-bg.md-hue-1 .fade-text,\n[md-theme="@themeName"] .md-warn-bg.md-hue-1 .divider {\n    color: @warnHue1Contrast4;\n}\n\n/* Warn, Hue-2 */\n[md-theme="@themeName"] .md-warn-bg.md-hue-2 {\n    background-color: @warnHue2Color;\n    color: @warnHue2Contrast1;\n}\n\n[md-theme="@themeName"] .md-warn-bg.md-hue-2 .secondary-text,\n[md-theme="@themeName"] .md-warn-bg.md-hue-2 .icon {\n    color: @warnHue2Contrast2;\n}\n\n[md-theme="@themeName"] .md-warn-bg.md-hue-2 .hint-text,\n[md-theme="@themeName"] .md-warn-bg.md-hue-2 .disabled-text {\n    color: @warnHue2Contrast3;\n}\n\n[md-theme="@themeName"] .md-warn-bg.md-hue-2 .fade-text,\n[md-theme="@themeName"] .md-warn-bg.md-hue-2 .divider {\n    color: @warnHue2Contrast4;\n}\n\n/* Warn, Hue-3 */\n[md-theme="@themeName"] .md-warn-bg.md-hue-3 {\n    background-color: @warnHue3Color;\n    color: @warnHue3Contrast1;\n}\n\n[md-theme="@themeName"] .md-warn-bg.md-hue-3 .secondary-text,\n[md-theme="@themeName"] .md-warn-bg.md-hue-3 .icon {\n    color: @warnHue3Contrast1;\n}\n\n[md-theme="@themeName"] .md-warn-bg.md-hue-3 .hint-text,\n[md-theme="@themeName"] .md-warn-bg.md-hue-3 .disabled-text {\n    color: @warnHue3Contrast3;\n}\n\n[md-theme="@themeName"] .md-warn-bg.md-hue-3 .fade-text,\n[md-theme="@themeName"] .md-warn-bg.md-hue-3 .divider {\n    color: @warnHue3Contrast4;\n}\n\n/* Warn foreground */\n[md-theme="@themeName"] .md-warn-fg {\n    color: @warnDefaultColor !important;\n}\n\n/* Warn foreground, Hue-1 */\n[md-theme="@themeName"] .md-warn-fg.md-hue-1 {\n    color: @warnHue1Color !important;\n}\n\n/* Warn foreground, Hue-2 */\n[md-theme="@themeName"] .md-warn-fg.md-hue-2 {\n    color: @warnHue2Color !important;\n}\n\n/* Warn foreground, Hue-3 */\n[md-theme="@themeName"] .md-warn-fg.md-hue-3 {\n    color: @warnHue3Color !important;\n}\n\n/* Background */\n[md-theme="@themeName"] .md-background-bg {\n    background-color: @backgroundDefaultColor;\n    color: @backgroundDefaultContrast1;\n}\n\n[md-theme="@themeName"] .md-background-bg .secondary-text,\n[md-theme="@themeName"] .md-background-bg .icon {\n    color: @backgroundDefaultContrast2;\n}\n\n[md-theme="@themeName"] .md-background-bg .hint-text,\n[md-theme="@themeName"] .md-background-bg .disabled-text {\n    color: @backgroundDefaultContrast3;\n}\n\n[md-theme="@themeName"] .md-background-bg .fade-text,\n[md-theme="@themeName"] .md-background-bg .divider {\n    color: @backgroundDefaultContrast4;\n}\n\n/* Background, Hue-1 */\n[md-theme="@themeName"] .md-background-bg.md-hue-1 {\n    background-color: @backgroundHue1Color;\n    color: @backgroundHue1Contrast1;\n}\n\n[md-theme="@themeName"] .md-background-bg.md-hue-1 .secondary-text,\n[md-theme="@themeName"] .md-background-bg.md-hue-1 .icon {\n    color: @backgroundHue1Contrast2;\n}\n\n[md-theme="@themeName"] .md-background-bg.md-hue-1 .hint-text,\n[md-theme="@themeName"] .md-background-bg.md-hue-1 .disabled-text {\n    color: @backgroundHue1Contrast3;\n}\n\n[md-theme="@themeName"] .md-background-bg.md-hue-1 .fade-text,\n[md-theme="@themeName"] .md-background-bg.md-hue-1 .divider {\n    color: @backgroundHue1Contrast4;\n}\n\n/* Background, Hue-2 */\n[md-theme="@themeName"] .md-background-bg.md-hue-2 {\n    background-color: @backgroundHue2Color;\n    color: @backgroundHue2Contrast1;\n}\n\n[md-theme="@themeName"] .md-background-bg.md-hue-2 .secondary-text,\n[md-theme="@themeName"] .md-background-bg.md-hue-2 .icon {\n    color: @backgroundHue2Contrast2;\n}\n\n[md-theme="@themeName"] .md-background-bg.md-hue-2 .hint-text,\n[md-theme="@themeName"] .md-background-bg.md-hue-2 .disabled-text {\n    color: @backgroundHue2Contrast3;\n}\n\n[md-theme="@themeName"] .md-background-bg.md-hue-2 .fade-text,\n[md-theme="@themeName"] .md-background-bg.md-hue-2 .divider {\n    color: @backgroundHue2Contrast4;\n}\n\n/* Background, Hue-3 */\n[md-theme="@themeName"] .md-background-bg.md-hue-3 {\n    background-color: @backgroundHue3Color;\n    color: @backgroundHue3Contrast1;\n}\n\n[md-theme="@themeName"] .md-background-bg.md-hue-3 .secondary-text,\n[md-theme="@themeName"] .md-background-bg.md-hue-3 .icon {\n    color: @backgroundHue3Contrast1;\n}\n\n[md-theme="@themeName"] .md-background-bg.md-hue-3 .hint-text,\n[md-theme="@themeName"] .md-background-bg.md-hue-3 .disabled-text {\n    color: @backgroundHue3Contrast3;\n}\n\n[md-theme="@themeName"] .md-background-bg.md-hue-3 .fade-text,\n[md-theme="@themeName"] .md-background-bg.md-hue-3 .divider {\n    color: @backgroundHue3Contrast4;\n}\n\n/* Background foreground */\n[md-theme="@themeName"] .md-background-fg {\n    color: @backgroundDefaultColor !important;\n}\n\n/* Background foreground, Hue-1 */\n[md-theme="@themeName"] .md-background-fg.md-hue-1 {\n    color: @backgroundHue1Color !important;\n}\n\n/* Background foreground, Hue-2 */\n[md-theme="@themeName"] .md-background-fg.md-hue-2 {\n    color: @backgroundHue2Color !important;\n}\n\n/* Background foreground, Hue-3 */\n[md-theme="@themeName"] .md-background-fg.md-hue-3 {\n    color: @backgroundHue3Color !important;\n}';

            var regex = new RegExp(Object.keys(styleVars).join('|'), 'gi');
            var css = cssTemplate.replace(regex, function (matched)
            {
                return styleVars[matched];
            });

            var headEl = angular.element('head');
            var styleEl = angular.element('<style type="text/css"></style>');
            styleEl.html(css);
            headEl.append(styleEl);
        }

        /**
         * Convert color array to rgb/rgba
         * Also apply contrasts if needed
         *
         * @param color
         * @param _contrastLevel
         * @returns {string}
         */
        function rgba(color, _contrastLevel)
        {
            var contrastLevel = _contrastLevel || false;

            // Convert 255,255,255,0.XX to 255,255,255
            // According to Google's Material design specs, white primary
            // text must have opacity of 1 and we will fix that here
            // because Angular Material doesn't care about that spec
            if ( color.length === 4 && color[0] === 255 && color[1] === 255 && color[2] === 255 )
            {
                color.splice(3, 4);
            }

            // If contrast level provided, apply it to the current color
            if ( contrastLevel )
            {
                color = applyContrast(color, contrastLevel);
            }

            // Convert color array to color string (rgb/rgba)
            if ( color.length === 3 )
            {
                return 'rgb(' + color.join(',') + ')';
            }
            else if ( color.length === 4 )
            {
                return 'rgba(' + color.join(',') + ')';
            }
            else
            {
                $log.error('Invalid number of arguments supplied in the color array: ' + color.length + '\n' + 'The array must have 3 or 4 colors.');
            }
        }

        /**
         * Apply given contrast level to the given color
         *
         * @param color
         * @param contrastLevel
         */
        function applyContrast(color, contrastLevel)
        {
            var contrastLevels = {
                'white': {
                    '1': '1',
                    '2': '0.7',
                    '3': '0.3',
                    '4': '0.12'
                },
                'black': {
                    '1': '0.87',
                    '2': '0.54',
                    '3': '0.26',
                    '4': '0.12'
                }
            };

            // If white
            if ( color[0] === 255 && color[1] === 255 && color[2] === 255 )
            {
                color[3] = contrastLevels.white[contrastLevel];
            }
            // If black
            else if ( color[0] === 0 && color[1] === 0 && color[2] === 0 )
            {
                color[3] = contrastLevels.black[contrastLevel];
            }

            return color;
        }

        /**
         * Uppercase first
         */
        function ucfirst(string)
        {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    }

})();
(function ()
{
    'use strict';

    MsThemeOptionsController.$inject = ["$cookies", "fuseTheming"];
    msThemeOptions.$inject = ["$mdSidenav"];
    angular
        .module('app.core')
        .controller('MsThemeOptionsController', MsThemeOptionsController)
        .directive('msThemeOptions', msThemeOptions);

    /** @ngInject */
    function MsThemeOptionsController($cookies, fuseTheming)
    {
        var vm = this;

        // Data
        vm.themes = fuseTheming.themes;

        vm.layoutModes = [
            {
                label: 'Boxed',
                value: 'boxed'
            },
            {
                label: 'Wide',
                value: 'wide'
            }
        ];
        vm.layoutStyles = [
            {
                label : 'Vertical Navigation',
                value : 'verticalNavigation',
                figure: '/assets/images/theme-options/vertical-nav.jpg'
            },
            {
                label : 'Vertical Navigation with Fullwidth Toolbar',
                value : 'verticalNavigationFullwidthToolbar',
                figure: '/assets/images/theme-options/vertical-nav-with-full-toolbar.jpg'
            },
            {
                label : 'Vertical Navigation with Fullwidth Toolbar 2',
                value : 'verticalNavigationFullwidthToolbar2',
                figure: '/assets/images/theme-options/vertical-nav-with-full-toolbar-2.jpg'
            },
            {
                label : 'Horizontal Navigation',
                value : 'horizontalNavigation',
                figure: '/assets/images/theme-options/horizontal-nav.jpg'
            },
            {
                label : 'Content with Toolbar',
                value : 'contentWithToolbar',
                figure: '/assets/images/theme-options/content-with-toolbar.jpg'
            },
            {
                label : 'Content Only',
                value : 'contentOnly',
                figure: '/assets/images/theme-options/content-only.jpg'
            },
        ];

        vm.layoutMode = 'wide';
        vm.layoutStyle = $cookies.get('layoutStyle') || 'verticalNavigation';

        // Methods
        vm.setActiveTheme = setActiveTheme;
        vm.getActiveTheme = getActiveTheme;
        vm.updateLayoutMode = updateLayoutMode;
        vm.updateLayoutStyle = updateLayoutStyle;

        //////////

        /**
         * Set active theme
         *
         * @param themeName
         */
        function setActiveTheme(themeName)
        {
            fuseTheming.setActiveTheme(themeName);
        }

        /**
         * Get active theme
         *
         * @returns {service.themes.active|{name, theme}}
         */
        function getActiveTheme()
        {
            return fuseTheming.themes.active;
        }

        /**
         * Update layout mode
         */
        function updateLayoutMode()
        {
            var bodyEl = angular.element('body');

            // Update class on body element
            bodyEl.toggleClass('boxed', (vm.layoutMode === 'boxed'));
        }

        /**
         * Update layout style
         */
        function updateLayoutStyle()
        {
            // Update the cookie
            $cookies.put('layoutStyle', vm.layoutStyle);

            // Reload the page to apply the changes
            location.reload();
        }
    }

    /** @ngInject */
    function msThemeOptions($mdSidenav)
    {
        return {
            restrict   : 'E',
            scope      : {},
            controller : 'MsThemeOptionsController as vm',
            templateUrl: 'app/core/theme-options/theme-options.html',
            compile    : function (tElement)
            {
                tElement.addClass('ms-theme-options');

                return function postLink(scope)
                {
                    /**
                     * Toggle options sidenav
                     */
                    function toggleOptionsSidenav()
                    {
                        // Toggle the fuse theme options panel
                        $mdSidenav('fuse-theme-options').toggle();
                    }

                    // Expose the toggle function
                    scope.toggleOptionsSidenav = toggleOptionsSidenav;
                };
            }
        };
    }
})();
(function ()
{
    'use strict';

    msUtils.$inject = ["$window"];
    angular
        .module('app.core')
        .factory('msUtils', msUtils);

    /** @ngInject */
    function msUtils($window)
    {
        // Private variables
        var mobileDetect = new MobileDetect($window.navigator.userAgent),
            browserInfo = null;

        var service = {
            exists       : exists,
            detectBrowser: detectBrowser,
            guidGenerator: guidGenerator,
            isMobile     : isMobile,
            toggleInArray: toggleInArray
        };

        return service;

        //////////

        /**
         * Check if item exists in a list
         *
         * @param item
         * @param list
         * @returns {boolean}
         */
        function exists(item, list)
        {
            return list.indexOf(item) > -1;
        }

        /**
         * Returns browser information
         * from user agent data
         *
         * Found at http://www.quirksmode.org/js/detect.html
         * but modified and updated to fit for our needs
         */
        function detectBrowser()
        {
            // If we already tested, do not test again
            if ( browserInfo )
            {
                return browserInfo;
            }

            var browserData = [
                {
                    string       : $window.navigator.userAgent,
                    subString    : 'Edge',
                    versionSearch: 'Edge',
                    identity     : 'Edge'
                },
                {
                    string   : $window.navigator.userAgent,
                    subString: 'Chrome',
                    identity : 'Chrome'
                },
                {
                    string       : $window.navigator.userAgent,
                    subString    : 'OmniWeb',
                    versionSearch: 'OmniWeb/',
                    identity     : 'OmniWeb'
                },
                {
                    string       : $window.navigator.vendor,
                    subString    : 'Apple',
                    versionSearch: 'Version',
                    identity     : 'Safari'
                },
                {
                    prop    : $window.opera,
                    identity: 'Opera'
                },
                {
                    string   : $window.navigator.vendor,
                    subString: 'iCab',
                    identity : 'iCab'
                },
                {
                    string   : $window.navigator.vendor,
                    subString: 'KDE',
                    identity : 'Konqueror'
                },
                {
                    string   : $window.navigator.userAgent,
                    subString: 'Firefox',
                    identity : 'Firefox'
                },
                {
                    string   : $window.navigator.vendor,
                    subString: 'Camino',
                    identity : 'Camino'
                },
                {
                    string   : $window.navigator.userAgent,
                    subString: 'Netscape',
                    identity : 'Netscape'
                },
                {
                    string       : $window.navigator.userAgent,
                    subString    : 'MSIE',
                    identity     : 'Explorer',
                    versionSearch: 'MSIE'
                },
                {
                    string       : $window.navigator.userAgent,
                    subString    : 'Trident/7',
                    identity     : 'Explorer',
                    versionSearch: 'rv'
                },
                {
                    string       : $window.navigator.userAgent,
                    subString    : 'Gecko',
                    identity     : 'Mozilla',
                    versionSearch: 'rv'
                },
                {
                    string       : $window.navigator.userAgent,
                    subString    : 'Mozilla',
                    identity     : 'Netscape',
                    versionSearch: 'Mozilla'
                }
            ];

            var osData = [
                {
                    string   : $window.navigator.platform,
                    subString: 'Win',
                    identity : 'Windows'
                },
                {
                    string   : $window.navigator.platform,
                    subString: 'Mac',
                    identity : 'Mac'
                },
                {
                    string   : $window.navigator.platform,
                    subString: 'Linux',
                    identity : 'Linux'
                },
                {
                    string   : $window.navigator.platform,
                    subString: 'iPhone',
                    identity : 'iPhone'
                },
                {
                    string   : $window.navigator.platform,
                    subString: 'iPod',
                    identity : 'iPod'
                },
                {
                    string   : $window.navigator.platform,
                    subString: 'iPad',
                    identity : 'iPad'
                },
                {
                    string   : $window.navigator.platform,
                    subString: 'Android',
                    identity : 'Android'
                }
            ];

            var versionSearchString = '';

            function searchString(data)
            {
                for ( var i = 0; i < data.length; i++ )
                {
                    var dataString = data[i].string;
                    var dataProp = data[i].prop;

                    versionSearchString = data[i].versionSearch || data[i].identity;

                    if ( dataString )
                    {
                        if ( dataString.indexOf(data[i].subString) !== -1 )
                        {
                            return data[i].identity;

                        }
                    }
                    else if ( dataProp )
                    {
                        return data[i].identity;
                    }
                }
            }

            function searchVersion(dataString)
            {
                var index = dataString.indexOf(versionSearchString);

                if ( index === -1 )
                {
                    return;
                }

                return parseInt(dataString.substring(index + versionSearchString.length + 1));
            }

            var browser = searchString(browserData) || 'unknown-browser';
            var version = searchVersion($window.navigator.userAgent) || searchVersion($window.navigator.appVersion) || 'unknown-version';
            var os = searchString(osData) || 'unknown-os';

            // Prepare and store the object
            browser = browser.toLowerCase();
            version = browser + '-' + version;
            os = os.toLowerCase();

            browserInfo = {
                browser: browser,
                version: version,
                os     : os
            };

            return browserInfo;
        }

        /**
         * Generates a globally unique id
         *
         * @returns {*}
         */
        function guidGenerator()
        {
            var S4 = function ()
            {
                return (((1 + Math.random()) * 0x10000) || 0).toString(16).substring(1);
            };
            return (S4() + S4() + S4() + S4() + S4() + S4());
        }

        /**
         * Return if current device is a
         * mobile device or not
         */
        function isMobile()
        {
            return mobileDetect.mobile();
        }

        /**
         * Toggle in array (push or splice)
         *
         * @param item
         * @param array
         */
        function toggleInArray(item, array)
        {
            if ( array.indexOf(item) === -1 )
            {
                array.push(item);
            }
            else
            {
                array.splice(array.indexOf(item), 1);
            }
        }
    }
}());
(function ()
{
    'use strict';

    angular
        .module('app.core')
        .provider('msApi', msApiProvider);

    /** @ngInject **/
    function msApiProvider()
    {
        /* ----------------- */
        /* Provider          */
        /* ----------------- */
        var provider = this;

        // Inject the $log service
        var $log = angular.injector(['ng']).get('$log');

        // Data
        var baseUrl = '';
        var api = [];

        // Methods
        provider.setBaseUrl = setBaseUrl;
        provider.getBaseUrl = getBaseUrl;
        provider.getApiObject = getApiObject;
        provider.register = register;

        //////////

        /**
         * Set base url for API endpoints
         *
         * @param url {string}
         */
        function setBaseUrl(url)
        {
            baseUrl = url;
        }

        /**
         * Return the base url
         *
         * @returns {string}
         */
        function getBaseUrl()
        {
            return baseUrl;
        }

        /**
         * Return the api object
         *
         * @returns {object}
         */
        function getApiObject()
        {
            return api;
        }

        /**
         * Register API endpoint
         *
         * @param key
         * @param resource
         */
        function register(key, resource)
        {
            if ( !angular.isString(key) )
            {
                $log.error('"path" must be a string (eg. `dashboard.project`)');
                return;
            }

            if ( !angular.isArray(resource) )
            {
                $log.error('"resource" must be an array and it must follow $resource definition');
                return;
            }

            // Store the API object
            api[key] = {
                url          : baseUrl + (resource[0] || ''),
                paramDefaults: resource[1] || [],
                actions      : resource[2] || [],
                options      : resource[3] || {}
            };
        }

        /* ----------------- */
        /* Service           */
        /* ----------------- */
        this.$get = ["$log", "$q", "$resource", "$rootScope", function ($log, $q, $resource, $rootScope)
        {
            // Data

            // Methods
            var service = {
                setBaseUrl: setBaseUrl,
                getBaseUrl: getBaseUrl,
                register  : register,
                resolve   : resolve,
                request   : request
            };

            return service;

            //////////

            /**
             * Resolve an API endpoint
             *
             * @param action {string}
             * @param parameters {object}
             * @returns {promise|boolean}
             */
            function resolve(action, parameters)
            {
                // Emit an event
                $rootScope.$broadcast('msApi::resolveStart');
                
                var actionParts = action.split('@'),
                    resource = actionParts[0],
                    method = actionParts[1],
                    params = parameters || {};

                if ( !resource || !method )
                {
                    $log.error('msApi.resolve requires correct action parameter (resourceName@methodName)');
                    return false;
                }

                // Create a new deferred object
                var deferred = $q.defer();

                // Get the correct resource definition from api object
                var apiObject = api[resource];

                if ( !apiObject )
                {
                    $log.error('Resource "' + resource + '" is not defined in the api service!');
                    deferred.reject('Resource "' + resource + '" is not defined in the api service!');
                }
                else
                {
                    // Generate the $resource object based on the stored API object
                    var resourceObject = $resource(apiObject.url, apiObject.paramDefaults, apiObject.actions, apiObject.options);

                    // Make the call...
                    resourceObject[method](params,

                        // Success
                        function (response)
                        {
                            deferred.resolve(response);

                            // Emit an event
                            $rootScope.$broadcast('msApi::resolveSuccess');
                        },

                        // Error
                        function (response)
                        {
                            deferred.reject(response);

                            // Emit an event
                            $rootScope.$broadcast('msApi::resolveError');
                        }
                    );
                }

                // Return the promise
                return deferred.promise;
            }

            /**
             * Make a request to an API endpoint
             *
             * @param action {string}
             * @param [parameters] {object}
             * @param [success] {function}
             * @param [error] {function}
             *
             * @returns {promise|boolean}
             */
            function request(action, parameters, success, error)
            {
                // Emit an event
                $rootScope.$broadcast('msApi::requestStart');
                
                var actionParts = action.split('@'),
                    resource = actionParts[0],
                    method = actionParts[1],
                    params = parameters || {};

                if ( !resource || !method )
                {
                    $log.error('msApi.resolve requires correct action parameter (resourceName@methodName)');
                    return false;
                }

                // Create a new deferred object
                var deferred = $q.defer();

                // Get the correct resource definition from api object
                var apiObject = api[resource];

                if ( !apiObject )
                {
                    $log.error('Resource "' + resource + '" is not defined in the api service!');
                    deferred.reject('Resource "' + resource + '" is not defined in the api service!');
                }
                else
                {
                    // Generate the $resource object based on the stored API object
                    var resourceObject = $resource(apiObject.url, apiObject.paramDefaults, apiObject.actions, apiObject.options);

                    // Make the call...
                    resourceObject[method](params,

                        // SUCCESS
                        function (response)
                        {
                            // Emit an event
                            $rootScope.$broadcast('msApi::requestSuccess');
                            
                            // Resolve the promise
                            deferred.resolve(response);

                            // Call the success function if there is one
                            if ( angular.isDefined(success) && angular.isFunction(success) )
                            {
                                success(response);
                            }
                        },

                        // ERROR
                        function (response)
                        {
                            // Emit an event
                            $rootScope.$broadcast('msApi::requestError');
                            
                            // Reject the promise
                            deferred.reject(response);

                            // Call the error function if there is one
                            if ( angular.isDefined(error) && angular.isFunction(error) )
                            {
                                error(response);
                            }
                        }
                    );
                }

                // Return the promise
                return deferred.promise;
            }
        }];
    }
})();
(function ()
{
    'use strict';

    apiResolverService.$inject = ["$q", "$log", "api"];
    angular
        .module('app.core')
        .factory('apiResolver', apiResolverService);

    /** @ngInject */
    function apiResolverService($q, $log, api)
    {
        var service = {
            resolve: resolve
        };

        return service;

        //////////
        /**
         * Resolve api
         * @param action
         * @param parameters
         */
        function resolve(action, parameters)
        {
            var actionParts = action.split('@'),
                resource = actionParts[0],
                method = actionParts[1],
                params = parameters || {};

            if ( !resource || !method )
            {
                $log.error('apiResolver.resolve requires correct action parameter (ResourceName@methodName)');
                return false;
            }

            // Create a new deferred object
            var deferred = $q.defer();

            // Get the correct api object from api service
            var apiObject = getApiObject(resource);

            if ( !apiObject )
            {
                $log.error('Resource "' + resource + '" is not defined in the api service!');
                deferred.reject('Resource "' + resource + '" is not defined in the api service!');
            }
            else
            {
                apiObject[method](params,

                    // Success
                    function (response)
                    {
                        deferred.resolve(response);
                    },

                    // Error
                    function (response)
                    {
                        deferred.reject(response);
                    }
                );
            }

            // Return the promise
            return deferred.promise;
        }

        /**
         * Get correct api object
         *
         * @param resource
         * @returns {*}
         */
        function getApiObject(resource)
        {
            // Split the resource in case if we have a dot notated object
            var resourceParts = resource.split('.'),
                apiObject = api;

            // Loop through the resource parts and go all the way through
            // the api object and return the correct one
            for ( var l = 0; l < resourceParts.length; l++ )
            {
                if ( angular.isUndefined(apiObject[resourceParts[l]]) )
                {
                    $log.error('Resource part "' + resourceParts[l] + '" is not defined!');
                    apiObject = false;
                    break;
                }

                apiObject = apiObject[resourceParts[l]];
            }

            if ( !apiObject )
            {
                return false;
            }

            return apiObject;
        }
    }

})();
(function () {
    'use strict';

    hljsDirective.$inject = ["$timeout", "$q", "$interpolate"];
    angular
        .module('app.core')
        .directive('hljs', hljsDirective);

    /** @ngInject */
    function hljsDirective($timeout, $q, $interpolate) {
        return {
            restrict: 'E',
            compile : function (element, attr) {
                var code;
                //No attribute? code is the content
                if (!attr.code) {
                    code = element.html();
                    element.empty();
                }

                return function (scope, element, attr) {

                    if (attr.code) {
                        // Attribute? code is the evaluation
                        code = scope.$eval(attr.code);
                    }
                    var shouldInterpolate = scope.$eval(attr.shouldInterpolate);

                    $q.when(code).then(function (code) {
                        if (code) {
                            if (shouldInterpolate) {
                                code = $interpolate(code)(scope);
                            }
                            var contentParent = angular.element(
                                '<pre><code class="highlight" ng-non-bindable></code></pre>'
                            );
                            element.append(contentParent);
                            // Defer highlighting 1-frame to prevent GA interference...
                            $timeout(function () {
                                render(code, contentParent);
                            }, 34, false);
                        }
                    });

                    function render(contents, parent) {

                        var codeElement = parent.find('code');
                        var lines = contents.split('\n');

                        // Remove empty lines
                        lines = lines.filter(function (line) {
                            return line.trim().length;
                        });

                        // Make it so each line starts at 0 whitespace
                        var firstLineWhitespace = lines[0].match(/^\s*/)[0];
                        var startingWhitespaceRegex = new RegExp('^' + firstLineWhitespace);
                        lines = lines.map(function (line) {
                            return line
                                .replace(startingWhitespaceRegex, '')
                                .replace(/\s+$/, '');
                        });

                        var highlightedCode = hljs.highlight(attr.language || attr.lang, lines.join('\n'), true);
                        highlightedCode.value = highlightedCode.value
                            .replace(/=<span class="hljs-value">""<\/span>/gi, '')
                            .replace('<head>', '')
                            .replace('<head/>', '');
                        codeElement.append(highlightedCode.value).addClass('highlight');
                    }
                };
            }
        };
    }
})();
(function ()
{
    'use strict';

    angular
        .module('app.core')
        .filter('filterByTags', filterByTags)
        .filter('filterSingleByTags', filterSingleByTags);

    /** @ngInject */
    function filterByTags()
    {
        return function (items, tags)
        {
            if ( items.length === 0 || tags.length === 0 )
            {
                return items;
            }

            var filtered = [];

            items.forEach(function (item)
            {
                var match = tags.every(function (tag)
                {
                    var tagExists = false;

                    item.tags.forEach(function (itemTag)
                    {
                        if ( itemTag.name === tag.name )
                        {
                            tagExists = true;
                            return;
                        }
                    });

                    return tagExists;
                });

                if ( match )
                {
                    filtered.push(item);
                }
            });

            return filtered;
        };
    }

    /** @ngInject */
    function filterSingleByTags()
    {
        return function (itemTags, tags)
        {
            if ( itemTags.length === 0 || tags.length === 0 )
            {
                return;
            }

            if ( itemTags.length < tags.length )
            {
                return [];
            }

            var filtered = [];

            var match = tags.every(function (tag)
            {
                var tagExists = false;

                itemTags.forEach(function (itemTag)
                {
                    if ( itemTag.name === tag.name )
                    {
                        tagExists = true;
                        return;
                    }
                });

                return tagExists;
            });

            if ( match )
            {
                filtered.push(itemTags);
            }

            return filtered;
        };
    }

})();
(function ()
{
    'use strict';

    angular
        .module('app.core')
        .filter('filterByPropIds', filterByPropIds);

    /** @ngInject */
    function filterByPropIds()
    {
        return function (items, parameter, ids)
        {
            if ( items.length === 0 || !ids || ids.length === 0 )
            {
                return items;
            }

            var filtered = [];

            for ( var i = 0; i < items.length; i++ )
            {
                var item = items[i];
                var match = false;

                for ( var j = 0; j < ids.length; j++ )
                {
                    var id = ids[j];
                    if ( item[parameter].indexOf(id) > -1 )
                    {
                        match = true;
                        break;
                    }
                }

                if ( match )
                {
                    filtered.push(item);
                }

            }

            return filtered;

        };
    }

})();
(function ()
{
    'use strict';

    angular
        .module('app.core')
        .filter('filterByIds', filterByIds);

    /** @ngInject */
    function filterByIds()
    {
        return function (items, ids)
        {

            if ( items.length === 0 || !ids )
            {
                return items;
            }

            if ( ids.length === 0 )
            {
                return [];
            }

            var filtered = [];

            for ( var i = 0; i < items.length; i++ )
            {
                var item = items[i];
                var match = false;

                for ( var j = 0; j < ids.length; j++ )
                {
                    var id = ids[j];
                    if ( item.id === id )
                    {
                        match = true;
                        break;
                    }
                }

                if ( match )
                {
                    filtered.push(item);
                }

            }

            return filtered;

        };
    }

})();
(function ()
{
    'use strict';

    toTrustedFilter.$inject = ["$sce"];
    angular
        .module('app.core')
        .filter('toTrusted', toTrustedFilter)
        .filter('htmlToPlaintext', htmlToPlainTextFilter)
        .filter('nospace', nospaceFilter)
        .filter('humanizeDoc', humanizeDocFilter);

    /** @ngInject */
    function toTrustedFilter($sce)
    {
        return function (value)
        {
            return $sce.trustAsHtml(value);
        };
    }

    /** @ngInject */
    function htmlToPlainTextFilter()
    {
        return function (text)
        {
            return String(text).replace(/<[^>]+>/gm, '');
        };
    }

    /** @ngInject */
    function nospaceFilter()
    {
        return function (value)
        {
            return (!value) ? '' : value.replace(/ /g, '');
        };
    }

    /** @ngInject */
    function humanizeDocFilter()
    {
        return function (doc)
        {
            if ( !doc )
            {
                return;
            }
            if ( doc.type === 'directive' )
            {
                return doc.name.replace(/([A-Z])/g, function ($1)
                {
                    return '-' + $1.toLowerCase();
                });
            }
            return doc.label || doc.name;
        };
    }

})();
(function ()
{
    'use strict';

    angular
        .module('app.core')
        .filter('altDate', altDate);

    /** @ngInject */
    function altDate()
    {
        return function (value)
        {
            var diff = Date.now() - new Date(value);

            /**
             * If in a hour
             * e.g. "2 minutes ago"
             */
            if ( diff < (60 * 60 * 1000) )
            {
                return moment(value).fromNow();
            }
            /*
             * If in the day
             * e.g. "11:23"
             */
            else if ( diff < (60 * 60 * 24 * 1000) )
            {
                return moment(value).format('HH:mm');
            }
            /*
             * If in week
             * e.g "Tuesday"
             */
            else if ( diff < (60 * 60 * 24 * 7 * 1000) )
            {
                return moment(value).format('dddd');
            }
            /*
             * If more than a week
             * e.g. 03/29/2016
             */
            else
            {
                return moment(value).calendar();
            }

        };
    }

})();
(function ()
{
    'use strict';

    angular
        .module('app.core')
        .provider('fuseConfig', fuseConfigProvider);

    /** @ngInject */
    function fuseConfigProvider()
    {
        // Default configuration
        var fuseConfiguration = {
            'disableCustomScrollbars'        : false,
            'disableMdInkRippleOnMobile'     : true,
            'disableCustomScrollbarsOnMobile': true
        };

        // Methods
        this.config = config;

        //////////

        /**
         * Extend default configuration with the given one
         *
         * @param configuration
         */
        function config(configuration)
        {
            fuseConfiguration = angular.extend({}, fuseConfiguration, configuration);
        }

        /**
         * Service
         */
        this.$get = function ()
        {
            var service = {
                getConfig: getConfig,
                setConfig: setConfig
            };

            return service;

            //////////

            /**
             * Returns a config value
             */
            function getConfig(configName)
            {
                if ( angular.isUndefined(fuseConfiguration[configName]) )
                {
                    return false;
                }

                return fuseConfiguration[configName];
            }

            /**
             * Creates or updates config object
             *
             * @param configName
             * @param configValue
             */
            function setConfig(configName, configValue)
            {
                fuseConfiguration[configName] = configValue;
            }
        };
    }

})();
(function ()
{
    'use strict';

    /**
     * Main module of the Fuse
     */
    angular
        .module('fuse', [

            // Common 3rd Party Dependencies
           
            'textAngular',
            'xeditable',
            'angAccordion',

            // Core
            'app.core',

            // Navigation
            'app.navigation',

            // Toolbar
            'app.toolbar',

            

            // Apps
            //'app.dashboards',
            

			// Dataset
            'app.dataset',


            //Login
            'app.login',
            'app.login.forgot-password',
            'app.login.new-login',
            'app.login.edit-profile',

            //Register
            'app.register',

            //initiatives
            //'app.initiatives',

            'app.visualizations',
            

            'app.page',

            'app.dashboard',
            'app.dashboardfront',
            'app.survey',
            'app.settings',
            'app.embed',
            'app.activity-log',
            'app.svg-maps'
            

        ]);
})();

(function ()
{
    'use strict';

    MainController.$inject = ["$scope", "$rootScope"];
    angular
        .module('fuse')
        .controller('MainController', MainController);

    /** @ngInject */
    function MainController($scope, $rootScope)
    {
        // Data

        //////////

        // Remove the splash screen
        $scope.$on('$viewContentAnimationEnded', function (event)
        {
            if ( event.targetScope.$id === $scope.$id )
            {
                $rootScope.$broadcast('msSplashScreen::remove');
            }
        });
    }
})();
(function ()
{
    'use strict';

    config.$inject = ["$translatePartialLoaderProvider"];
    angular
        .module('app.toolbar', [])
        .config(config);

    /** @ngInject */
    function config($translatePartialLoaderProvider)
    {
        $translatePartialLoaderProvider.addPart('app/toolbar');
    }
})();

(function ()
{
    'use strict';

    ToolbarController.$inject = ["$scope", "$rootScope", "$q", "$state", "$timeout", "$mdSidenav", "$translate", "$mdToast", "msNavigationService", "api"];
    angular
        .module('app.toolbar')
        .controller('ToolbarController', ToolbarController);

    /** @ngInject */
    function ToolbarController($scope,$rootScope, $q, $state, $timeout, $mdSidenav, $translate, $mdToast, msNavigationService,api)
    {
        
        var vm = this;
        vm.bodyEl = angular.element('body');
        $scope.isLogined = false;
        if(sessionStorage.api_token != '' && sessionStorage.api_token != undefined){
            
            $scope.isLogined = true;
            api.profile.details.get(function(res){
                $scope.details = res.details;
            });
        }
        vm.toggleHorizontalMobileMenu = toggleHorizontalMobileMenu;
        vm.toggleSidenav = toggleSidenav;

        // var vm = this;
        // vm.bodyEl = angular.element('body');
        // $scope.isLogined = false;
        // if(sessionStorage.api_token != '' && sessionStorage.api_token != undefined){
            
        //     $scope.isLogined = true;
        //     $scope.userName = sessionStorage.userName;
        //     $scope.profile_pic = sessionStorage.profile_pic;
        // }
        // vm.toggleHorizontalMobileMenu = toggleHorizontalMobileMenu;
        // vm.toggleSidenav = toggleSidenav;
        
        function toggleHorizontalMobileMenu()
        {
            vm.bodyEl.toggleClass('ms-navigation-horizontal-mobile-menu-active');
        }
        function toggleSidenav(sidenavId){

            $mdSidenav(sidenavId).toggle();
        }



        
    }

})();
(function ()
{
    'use strict';

    angular
        .module('app.navigation', [])
        .config(config);

    /** @ngInject */
    function config()
    {
        
    }

})();
(function ()
{
    'use strict';

    NavigationController.$inject = ["$scope"];
    angular
        .module('app.navigation')
        .controller('NavigationController', NavigationController);

    /** @ngInject */
    function NavigationController($scope)
    {
        var vm = this;

        // Data
        vm.bodyEl = angular.element('body');
        vm.folded = true;
        vm.msScrollOptions = {
            suppressScrollX: true
        };

        // Methods
        vm.toggleMsNavigationFolded = toggleMsNavigationFolded;

        //////////

        /**
         * Toggle folded status
         */
        function toggleMsNavigationFolded()
        {
            vm.folded = !vm.folded;
        }

        // Close the mobile menu on $stateChangeSuccess
        $scope.$on('$stateChangeSuccess', function ()
        {
            vm.bodyEl.removeClass('ms-navigation-horizontal-mobile-menu-active');
        });
    }

})();
(function ()
{
    'use strict';

    config.$inject = ["$translatePartialLoaderProvider"];
    angular
        .module('app.footer', [])
        .config(config);

    /** @ngInject */
    function config($translatePartialLoaderProvider)
    {
        $translatePartialLoaderProvider.addPart('app/toolbar');
    }
})();

(function ()
{
    'use strict';

    ToolbarController.$inject = ["$rootScope", "$q", "$state", "$timeout", "$mdSidenav", "$translate", "$mdToast", "msNavigationService"];
    angular
        .module('app.footer')
        .controller('ToolbarController', ToolbarController);

    /** @ngInject */
    function ToolbarController($rootScope, $q, $state, $timeout, $mdSidenav, $translate, $mdToast, msNavigationService)
    {
        var vm = this;

        // Data
        $rootScope.global = {
            search: ''
        };

        vm.bodyEl = angular.element('body');
        vm.userStatusOptions = [
            {
                'title': 'Online',
                'icon' : 'icon-checkbox-marked-circle',
                'color': '#4CAF50'
            },
            {
                'title': 'Away',
                'icon' : 'icon-clock',
                'color': '#FFC107'
            },
            {
                'title': 'Do not Disturb',
                'icon' : 'icon-minus-circle',
                'color': '#F44336'
            },
            {
                'title': 'Invisible',
                'icon' : 'icon-checkbox-blank-circle-outline',
                'color': '#BDBDBD'
            },
            {
                'title': 'Offline',
                'icon' : 'icon-checkbox-blank-circle-outline',
                'color': '#616161'
            }
        ];
        vm.languages = {
            en: {
                'title'      : 'English',
                'translation': 'TOOLBAR.ENGLISH',
                'code'       : 'en',
                'flag'       : 'us'
            },
            es: {
                'title'      : 'Spanish',
                'translation': 'TOOLBAR.SPANISH',
                'code'       : 'es',
                'flag'       : 'es'
            },
            tr: {
                'title'      : 'Turkish',
                'translation': 'TOOLBAR.TURKISH',
                'code'       : 'tr',
                'flag'       : 'tr'
            }
        };

        // Methods
        vm.toggleSidenav = toggleSidenav;
        vm.logout = logout;
        vm.changeLanguage = changeLanguage;
        vm.setUserStatus = setUserStatus;
        vm.toggleHorizontalMobileMenu = toggleHorizontalMobileMenu;
        vm.toggleMsNavigationFolded = toggleMsNavigationFolded;
        vm.search = search;
        vm.searchResultClick = searchResultClick;

        //////////

        init();

        /**
         * Initialize
         */
        function init()
        {
            // Select the first status as a default
            vm.userStatus = vm.userStatusOptions[0];

            // Get the selected language directly from angular-translate module setting
            vm.selectedLanguage = vm.languages[$translate.preferredLanguage()];
        }


        /**
         * Toggle sidenav
         *
         * @param sidenavId
         */
        function toggleSidenav(sidenavId)
        {
            $mdSidenav(sidenavId).toggle();
        }

        /**
         * Sets User Status
         * @param status
         */
        function setUserStatus(status)
        {
            vm.userStatus = status;
        }

        /**
         * Logout Function
         */
        function logout()
        {
            // Do logout here..
        }

        /**
         * Change Language
         */
        function changeLanguage(lang)
        {
            vm.selectedLanguage = lang;

            /**
             * Show temporary message if user selects a language other than English
             *
             * angular-translate module will try to load language specific json files
             * as soon as you change the language. And because we don't have them, there
             * will be a lot of errors in the page potentially breaking couple functions
             * of the template.
             *
             * To prevent that from happening, we added a simple "return;" statement at the
             * end of this if block. If you have all the translation files, remove this if
             * block and the translations should work without any problems.
             */
            if ( lang.code !== 'en' )
            {
                var message = 'Fuse supports translations through angular-translate module, but currently we do not have any translations other than English language. If you want to help us, send us a message through ThemeForest profile page.';

                $mdToast.show({
                    template : '<md-toast id="language-message" layout="column" layout-align="center start"><div class="md-toast-content">' + message + '</div></md-toast>',
                    hideDelay: 7000,
                    position : 'top right',
                    parent   : '#content'
                });

                return;
            }

            // Change the language
            $translate.use(lang.code);
        }

        /**
         * Toggle horizontal mobile menu
         */
        function toggleHorizontalMobileMenu()
        {
            vm.bodyEl.toggleClass('ms-navigation-horizontal-mobile-menu-active');
        }

        /**
         * Toggle msNavigation folded
         */
        function toggleMsNavigationFolded()
        {
            msNavigationService.toggleFolded();
        }

        /**
         * Search action
         *
         * @param query
         * @returns {Promise}
         */
        function search(query)
        {
            var navigation = [],
                flatNavigation = msNavigationService.getFlatNavigation(),
                deferred = $q.defer();

            // Iterate through the navigation array and
            // make sure it doesn't have any groups or
            // none ui-sref items
            for ( var x = 0; x < flatNavigation.length; x++ )
            {
                if ( flatNavigation[x].uisref )
                {
                    navigation.push(flatNavigation[x]);
                }
            }

            // If there is a query, filter the navigation;
            // otherwise we will return the entire navigation
            // list. Not exactly a good thing to do but it's
            // for demo purposes.
            if ( query )
            {
                navigation = navigation.filter(function (item)
                {
                    if ( angular.lowercase(item.title).search(angular.lowercase(query)) > -1 )
                    {
                        return true;
                    }
                });
            }

            // Fake service delay
            $timeout(function ()
            {
                deferred.resolve(navigation);
            }, 1000);

            return deferred.promise;
        }

        /**
         * Search result click action
         *
         * @param item
         */
        function searchResultClick(item)
        {
            // If item has a link
            if ( item.uisref )
            {
                // If there are state params,
                // use them...
                if ( item.stateParams )
                {
                    $state.go(item.state, item.stateParams);
                }
                else
                {
                    $state.go(item.state);
                }
            }
        }
    }

})();
(function ()
{
    'use strict';

    runBlock.$inject = ["msUtils", "fuseGenerator", "fuseConfig"];
    angular
        .module('app.core')
        .run(runBlock);

    /** @ngInject */
    function runBlock(msUtils, fuseGenerator, fuseConfig)
    {
        /**
         * Generate extra classes based on registered themes so we
         * can use same colors with non-angular-material elements
         */
        fuseGenerator.generate();

        /**
         * Disable md-ink-ripple effects on mobile
         * if 'disableMdInkRippleOnMobile' config enabled
         */
        if ( fuseConfig.getConfig('disableMdInkRippleOnMobile') && msUtils.isMobile() )
        {
            var bodyEl = angular.element('body');
            bodyEl.attr('md-no-ink', true);
        }

        /**
         * Put isMobile() to the html as a class
         */
        if ( msUtils.isMobile() )
        {
            angular.element('html').addClass('is-mobile');
        }

        /**
         * Put browser information to the html as a class
         */
        var browserInfo = msUtils.detectBrowser();
        if ( browserInfo )
        {
            var htmlClass = browserInfo.browser + ' ' + browserInfo.version + ' ' + browserInfo.os;
            angular.element('html').addClass(htmlClass);
        }
    }
})();
(function ()
{
    'use strict';

    config.$inject = ["$ariaProvider", "$logProvider", "msScrollConfigProvider", "fuseConfigProvider"];
    angular
        .module('app.core')
        .config(config);

    /** @ngInject */
    function config($ariaProvider, $logProvider, msScrollConfigProvider, fuseConfigProvider)
    {
        // Enable debug logging
        $logProvider.debugEnabled(true);

        /*eslint-disable */

        // ng-aria configuration
        $ariaProvider.config({
            tabindex: false
        });

        // Fuse theme configurations
        fuseConfigProvider.config({
            'disableCustomScrollbars'        : false,
            'disableCustomScrollbarsOnMobile': true,
            'disableMdInkRippleOnMobile'     : true
        });

        // msScroll configuration
        msScrollConfigProvider.config({
            wheelPropagation: true
        });

        /*eslint-enable */
    }
})();
(function ()
{
    'use strict';

    runBlock.$inject = ["$rootScope", "$timeout", "$state", "editableThemes"];
    angular
        .module('fuse')
        .run(runBlock);

    /** @ngInject */
    function runBlock($rootScope, $timeout, $state, editableThemes)
    {
        // 3rd Party Dependencies
        editableThemes.default.submitTpl = '<md-button class="md-icon-button" type="submit" aria-label="save"><md-icon md-font-icon="icon-checkbox-marked-circle" class="md-accent-fg md-hue-1"></md-icon></md-button>';
        editableThemes.default.cancelTpl = '<md-button class="md-icon-button" ng-click="$form.$cancel()" aria-label="cancel"><md-icon md-font-icon="icon-close-circle" class="icon-cancel"></md-icon></md-button>';

        // Activate loading indicator
        var stateChangeStartEvent = $rootScope.$on('$stateChangeStart', function ()
        {
            $rootScope.loadingProgress = true;
        });

        // De-activate loading indicator
        var stateChangeSuccessEvent = $rootScope.$on('$stateChangeSuccess', function ()
        {
            $timeout(function ()
            {
                $rootScope.loadingProgress = false;
            });
        });

        // Store state in the root scope for easy access
        $rootScope.state = $state;

        // Cleanup
        $rootScope.$on('$destroy', function ()
        {
            stateChangeStartEvent();
            stateChangeSuccessEvent();
        });
    }
})();

(function ()
{
    'use strict';

    routeConfig.$inject = ["$stateProvider", "$urlRouterProvider", "$locationProvider"];
    angular
        .module('fuse')
        .config(routeConfig);

    /** @ngInject */
    function routeConfig($stateProvider, $urlRouterProvider, $locationProvider)
    {
        $locationProvider.html5Mode(true);

        $urlRouterProvider.otherwise('/dashboard');

        /**
         * Layout Style Switcher
         *
         * This code is here for demonstration purposes.
         * If you don't need to switch between the layout
         * styles like in the demo, you can set one manually by
         * typing the template urls into the `State definitions`
         * area and remove this code
         */
        // Inject $cookies
        var $cookies;

        angular.injector(['ngCookies']).invoke([
            '$cookies', function (_$cookies)
            {
                $cookies = _$cookies;
            }
        ]);

        // Get active layout
        var layoutStyle = 'verticalNavigation';//$cookies.get('layoutStyle') || 'horizontalNavigation';

        var layouts = {
            verticalNavigation  : {
                main      : 'app/core/layouts/vertical-navigation.html',
                toolbar   : 'app/toolbar/layouts/vertical-navigation/toolbar.html',
                navigation: 'app/navigation/layouts/vertical-navigation/navigation.html'
            },
            verticalNavigationFullwidthToolbar  : {
                main      : 'app/core/layouts/vertical-navigation-fullwidth-toolbar.html',
                toolbar   : 'app/toolbar/layouts/vertical-navigation-fullwidth-toolbar/toolbar.html',
                navigation: 'app/navigation/layouts/vertical-navigation/navigation.html'
            },
            verticalNavigationFullwidthToolbar2  : {
                main      : 'app/core/layouts/vertical-navigation-fullwidth-toolbar-2.html',
                toolbar   : 'app/toolbar/layouts/vertical-navigation-fullwidth-toolbar-2/toolbar.html',
                navigation: 'app/navigation/layouts/vertical-navigation-fullwidth-toolbar-2/navigation.html'
            },
            horizontalNavigation: {
                main      : 'app/core/layouts/horizontal-navigation.html',
                toolbar   : 'app/toolbar/layouts/horizontal-navigation/toolbar.html',
                navigation: 'app/navigation/layouts/horizontal-navigation/navigation.html'
            },
            contentOnly         : {
                main      : 'app/core/layouts/content-only.html',
                toolbar   : '',
                navigation: ''
            },
            contentWithToolbar  : {
                main      : 'app/core/layouts/content-with-toolbar.html',
                toolbar   : 'app/toolbar/layouts/content-with-toolbar/toolbar.html',
                navigation: ''
            }
        };
        // END - Layout Style Switcher

        // State definitions
        $stateProvider
            .state('app', {
                abstract: true,
                views   : {
                    'main@'         : {
                        templateUrl: layouts[layoutStyle].main,
                        controller : 'MainController as vm'
                    },
                    'toolbar@app'   : {
                        templateUrl: layouts[layoutStyle].toolbar,
                        controller : 'ToolbarController as vm'
                    },
                    'navigation@app': {
                        templateUrl: layouts[layoutStyle].navigation,
                        controller : 'NavigationController as vm'
                    }
                }
            });
    }

})();
(function ()
{
    'use strict';

    IndexController.$inject = ["fuseTheming"];
    angular
        .module('fuse')
        .controller('IndexController', IndexController);

    /** @ngInject */
    function IndexController(fuseTheming)
    {
        var vm = this;

        // Data
        vm.themes = fuseTheming.themes;

        //////////
    }
})();
(function ()
{
    'use strict';

    angular
        .module('fuse');
})();

(function ()
{
    'use strict';

    config.$inject = ["$translateProvider", "$provide"];
    angular
        .module('fuse')
        .config(config);

    /** @ngInject */
    function config( $translateProvider, $provide)
    {
        
        // Put your common app configurations here

        // uiGmapgoogle-maps configuration
       

        // angular-translate configuration
        $translateProvider.useLoader('$translatePartialLoader', {
            urlTemplate: '{part}/i18n/{lang}.json'
        });
        $translateProvider.preferredLanguage('en');
        $translateProvider.useSanitizeValueStrategy('sanitize');

        // Text Angular options
        $provide.decorator('taOptions', [
            '$delegate', function (taOptions)
            {
                taOptions.toolbar = [
                    ['bold', 'italics', 'underline', 'ul', 'ol', 'quote']
                ];

                taOptions.classes = {
                    focussed           : 'focussed',
                    toolbar            : 'ta-toolbar',
                    toolbarGroup       : 'ta-group',
                    toolbarButton      : 'md-button',
                    toolbarButtonActive: 'active',
                    disabled           : '',
                    textEditor         : 'form-control',
                    htmlEditor         : 'form-control'
                };

                return taOptions;
            }
        ]);

        // Text Angular tools
        $provide.decorator('taTools', [
            '$delegate', function (taTools)
            {
                taTools.quote.iconclass = 'icon-format-quote';
                taTools.bold.iconclass = 'icon-format-bold';
                taTools.italics.iconclass = 'icon-format-italic';
                taTools.underline.iconclass = 'icon-format-underline';
                taTools.strikeThrough.iconclass = 'icon-format-strikethrough';
                taTools.ul.iconclass = 'icon-format-list-bulleted';
                taTools.ol.iconclass = 'icon-format-list-numbers';
                taTools.redo.iconclass = 'icon-redo';
                taTools.undo.iconclass = 'icon-undo';
                taTools.clear.iconclass = 'icon-close-circle-outline';
                taTools.justifyLeft.iconclass = 'icon-format-align-left';
                taTools.justifyCenter.iconclass = 'icon-format-align-center';
                taTools.justifyRight.iconclass = 'icon-format-align-right';
                taTools.justifyFull.iconclass = 'icon-format-align-justify';
                taTools.indent.iconclass = 'icon-format-indent-increase';
                taTools.outdent.iconclass = 'icon-format-indent-decrease';
                taTools.html.iconclass = 'icon-code-tags';
                taTools.insertImage.iconclass = 'icon-file-image-box';
                taTools.insertLink.iconclass = 'icon-link';
                taTools.insertVideo.iconclass = 'icon-filmstrip';

                return taTools;
            }
        ]);
    }

})();

(function() {
    'use strict';

    apiService.$inject = ["$resource", "$http"];
    angular
        .module('fuse')
        .factory('api', apiService);

    /** @ngInject */
    function apiService($resource, $http) {

        var api = {};

        // Base Url
		
		/*api.baseUrl     =   'http://smaartframework.com/smaartadmin/public/api/v1/';
		api.surveyEmbed = 'http://smaartframework.com/smaartadmin/public/';
		api.siteUrl = 'http://smaartframework.com/smaartadmin/public/v';*/
		
        
		api.baseUrl = 'http://smaart.oxosolutions.com/api/v1/';
        api.surveyEmbed = 'http://smaart.oxosolutions.com/';
		api.siteUrl = 'http://smaart.oxosolutions.com/v/';
        
		
	   
	   
        api.apiToken = sessionStorage.api_token;
        
        
        //survey part
        api.survey = {
            delSurveyById: $resource(api.baseUrl + 'surrvey/del/:id?api_token=' + api.apiToken, {
                id: '@id'
            }),
            
            changeStatus: $resource(api.baseUrl + 'surrvey/enableDisable/:id?api_token=' + api.apiToken, {
                id: '@id'
            }),
            surveyList: $resource(api.baseUrl + 'surrvey/list?api_token='+api.apiToken),
            surveyEditList: $resource(api.baseUrl + 'surrvey/edit/:id?api_token='+api.apiToken, {
                id: '@id'
            }),
            getSurveyDataById: $resource(api.baseUrl + 'view_survey_saved_data/:id?api_token='+api.apiToken,{
                id: '@id'
            }),
            getThemes: $resource(api.baseUrl+'getsurveythemes?api_token='+api.apiToken),
            // getSurveyById: $resource(api.baseUrl + 'surrvey/edit/:id?api_token'+api.apiToken,{
            //     id: '@id'
            // }),
            createClone: $resource(api.baseUrl+'create/clone/:id?api_token='+api.apiToken, {
                id: '@id'
            })
        }

        api.dataset = {
            getById: $resource(api.baseUrl + 'dataset/view/:id/:skip?api_token=' + api.apiToken, {
                id: '@id',
                skip: '@skip'
            }),
            getcolumnsById: $resource(api.baseUrl + 'dataset/columns/:id?api_token=' + api.apiToken, {
                id: '@id'
            }),
            getLastColumns: $resource(api.baseUrl + 'dataset/define/columns/:id?api_token=' + api.apiToken, {
                id: '@id'
            }),
            deleteDataset: $resource(api.baseUrl + 'dataset/delete/:id?api_token=' + api.apiToken, {
                id: '@id'
            }),
            exportDataset: $resource(api.baseUrl + 'dataset/export/:id?api_token=' + api.apiToken, {
                id: '@id'
            }),

            /*******/
            get420Columns: $resource(api.baseUrl + 'dataset/static/dataset?api_token=' + api.apiToken),
            /*******/
            downloadDataset: $resource(api.baseUrl + 'export/dataset/:id', {
                id: '@id'
            }),
            columnValidate: $resource(api.baseUrl + 'dataset/validate/columns/:id?api_token=' + api.apiToken, {
                id: '@id'
            }),
            getByDate: $resource('http://api.example.com/blog/:date', {
                id: '@date'
            }, {
                get: {
                    method: 'GET',
                    params: {
                        getByDate: true
                    }
                }
            }),
            getColumnsOfSelectedDataset: $resource(api.baseUrl+'getColumnOfDataset/:dataset_id?api_token='+api.apiToken,{
                dataset_id: '@dataset_id'
            }),
            getAnsweredSurvey: $resource(api.baseUrl+'answeredSurveysList?api_token='+api.apiToken),

            createClone: $resource(api.baseUrl+'create/dataset/clone/:id?api_token='+api.apiToken, {
                id: '@id'
            })
        };

        api.listdataset = {
            list: $resource(api.baseUrl + 'dataset/list?api_token=' + api.apiToken),
            getById: $resource(api.baseUrl + 'dataset/view/:id?api_token=' + api.apiToken, {
                id: '@id'
            }),
            getByDate: $resource('http://api.example.com/blog/:date', {
                id: '@date'
            }, {
                get: {
                    method: 'GET',
                    params: {
                        getByDate: true
                    }
                }
            })
        };

        // userRoles
        api.roles = {
            list: $resource(api.baseUrl + 'role/list?api_token=' + api.apiToken),
        };

       
        api.userAction = {
            deleteUser: $resource(api.baseUrl+'deleteUser/:id?api_token='+api.apiToken,{
                id: '@id'
            }),
            unapprove:  $resource(api.baseUrl+'user/unapprove/:id?api_token='+api.apiToken, {
                id: '@id'
            }),
            approve:    $resource(api.baseUrl+'user/approve/:id?api_token='+api.apiToken, {
                id: '@id'
            }),
            editUser:   $resource(api.baseUrl+'editUser/:id?api_token='+api.apiToken, {
                id: '@id'
            }),
        };

        api.dashboard = {
            getDetails: $resource(api.baseUrl+'dashboard?api_token='+api.apiToken)
        };

        api.organization = {
            list: $resource(api.baseUrl + 'organizationList')
           
        };

        


        api.listuser = {
            list: $resource(api.baseUrl + 'userlists?api_token=' + api.apiToken),
            getById: $resource(api.baseUrl + 'dataset/view/:id?api_token=' + api.apiToken, {
                id: '@id'
            }),
            deleteUser: $resource(api.baseUrl + 'deleteUser/:id?api_token=' + api.apiTOken, {
                id: '@id'
            }),
            getByDate: $resource('http://api.example.com/blog/:date', {
                id: '@date'
            }, {
                get: {
                    method: 'GET',
                    params: {
                        getByDate: true
                    }
                }
            })
        };

        api.auth = {
            login: $resource(api.baseUrl + 'auth', {
                data: '@email',
                password: '@pass'
            }, {
                get: {
                    method: 'POST',
                    params: {
                        getByDate: true
                    }
                }
            })
        };

        api.goals = {
            
            list: $resource(api.baseUrl + 'goals/list', {
                id: '@id'
            }),
            get: {
                method: 'GET'
            }
        };

        api.goaldata = {

            list: $resource(api.baseUrl + 'goalData/:id', {
                id: '@id'
            }),
            get: {
                method: 'GET'
            }
        };

        api.goal = {
            list: $resource(api.baseUrl + 'goals/:id?api_token=' + api.apiToken, {
                id: '@id'
            }),
            get: {
                method: 'GET'
            }
        };

        api.visualizations = {
            list: $resource(api.baseUrl + 'visual/list?api_token=' + api.apiToken),
            getById: $resource(api.baseUrl + 'visual/:id?api_token=' + api.apiToken, {
                id: '@id'
            }),
            deleteVisualization: $resource(api.baseUrl + 'visualization/delete/:id?api_token=' + api.apiToken, {
                id: '@id'
            }),
            get: {
                method: 'GET'
            }
        };
        api.visualizationData = {
            list: $resource(api.baseUrl + 'visual/list?api_token=' + api.apiToken),
            getById: $resource(api.baseUrl + 'dataset/chartdata/:id?api_token=' + api.apiToken, {
                id: '@id'
            }),
            get: {
                method: 'GET'
            }
        };

        api.visual = {
            list: $resource(api.baseUrl + 'visualization/list?api_token=' + api.apiToken),
            getCols: $resource(api.baseUrl +'datsetColumns/:id?api_token=' + api.apiToken, {
                id: '@id'
            }),
            visualDetails: $resource(api.baseUrl +'visualization/details/:id?api_token=' + api.apiToken,{
                id: '@id'
            }),
            visualByDatset: $resource(api.baseUrl +'visualization/bydataset/:id?api_token=' + api.apiToken,{
                id: '@id'
            }),
            visualEmbedCode: $resource(api.baseUrl+'getembedcode/:visual_id?api_token='+api.apiToken,{
                visual_id: '@visual_id'
            }),
            mapsList: $resource(api.baseUrl+'maps?api_token='+api.apiToken),
            get: {
                method: 'GET'
            },
            deleteMaps: $resource(api.baseUrl +'deleteMap/:id?api_token=' + api.apiToken,{
                id: '@id'
            }),
            singleMap: $resource(api.baseUrl +'singelMap/:id?api_token=' + api.apiToken,{
                id: '@id'
            }),
            createClone: $resource(api.baseUrl+'create/visualization/clone/:id?api_token='+api.apiToken, {
                id: '@id'
            })
        };

        api.pages = {
            pages: $resource(api.baseUrl + 'pages'),
            getBySlug: $resource(api.baseUrl + 'pages/:slug', {
                slug: '@slug'
            }),
            get: {
                method: 'GET'
            }
        };

        api.editQuestions = {
            questions: $resource(api.baseUrl+ 'survey/view/:id?api_token=' + api.apiToken, {
                id: '@id'
            })
        },

        api.editFields = {
            fields : $resource(api.baseUrl+'getfields/:survey_id/:group_id?api_token=' + api.apiToken,{
                survey_id: '@survey_id',
                group_id: '@group_id'
            })
        },

        api.profile = {
            details: $resource(api.baseUrl + 'profile?api_token=' + api.apiToken),
            get: {
                method: 'GET'
            }
        };

        api.resources = {
            list: $resource(api.baseUrl + 'resources/list?api_token=' + api.apiToken),
            get: {
                method: 'GET'
            }
        };

        api.getUserSettings = {

            settings: $resource(api.baseUrl+'usersettings/get?api_token='+api.apiToken)
        };

        api.getallFiles = {

            filesList: $resource(api.baseUrl+'sharedFile?api_token='+api.apiToken)
        }

        api.ministries = {
            list: $resource(api.baseUrl + 'profile/ministries'),
            get: {
                method: 'GET'
            }
        };

        api.designation = {
            list: $resource(api.baseUrl + 'designation/list'),
        };

        api.logs = {
            list: $resource(api.baseUrl+'logs?api_token='+api.apiToken)
        }

        api.departments = {
            list: $resource(api.baseUrl + 'departments'),
        };

        api.downloadFile = {

            downloadDatasetFile: function(id,type) {
                return api.baseUrl + 'dataset/file/'+id+'/'+type+'?api_token='+api.apiToken;
            }
        };

        api.forgetPass = {
            validate: $resource(api.baseUrl + 'validateForgetToken/:token', {
                token: '@token'
            }),
        };

        api.postMethod = {
           
            insertDatasetRecord: function(formData) {
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'insert/dataset/record?api_token=' + api.apiToken,
                    data: formData
                });
            },

            registerUser: function(formData) {
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'register',
                    data: formData
                });
            },

            createMap: function(formData) {
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'saveMap?api_token=' + api.apiToken,
                    data: formData
                });
            },

            updateMap: function(formData) {
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'updateMap?api_token=' + api.apiToken,
                    data: formData
                });
            },

            renameDataset: function(formData) {
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'datasetname/update?api_token=' + api.apiToken,
                    data: formData
                });
            },

            getVisual: function(formData){
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'singlevisual?api_token=' + api.apiToken,
                    data: formData
                });
            },

            getVisualEmbed: function(formData){
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'singlevisualEmbed?api_token=' + api.apiToken,
                    data: formData
                });
            },

            importDataset: function(formData, $scope) {
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'dataset/import?api_token=' + api.apiToken,
                    data: formData,
                    uploadEventHandlers: {
                        progress: function(e) {
                            $scope.percent = Math.round((e.loaded / e.total) * 100);
                            // console.log("Current : " + Math.round((e.loaded / e.total) * 100) + '%');

                        }
                    }
                });
            },

            uploadFile: function(formData, $scope){

                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'uplaodFile?api_token=' + api.apiToken,
                    data: formData,
                    uploadEventHandlers: {
                        progress: function(e) {
                            $scope.percent = Math.round((e.loaded / e.total) * 100);
                            // console.log("Current : " + Math.round((e.loaded / e.total) * 100) + '%');

                        }
                    }
                });
            },

            saveDatasetColumns: function(formData) {
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'dataset/savevalidatecolumns?api_token=' + api.apiToken,
                    data: formData
                });
            },

            saveNewVisual: function(formData) {
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'visualization/create?api_token=' + api.apiToken,
                    data: formData
                });
            },

            changePassword: function(formData) {
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'profile/changepass?api_token=' + api.apiToken,
                    data: formData
                });
            },

            userLogin: function(formData) {
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'auth',
                    data: formData
                });
            },

            saveEditedDatset: function(formData) {
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'dataset/saveEditedDatset?api_token=' + api.apiToken,
                    data: formData
                });
            },

            saveSubset: function(formData) {
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'dataset/saveSubset?api_token=' + api.apiToken,
                    data: formData
                });
            },

            saveProfile: function(formData) {
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'update/profile?api_token=' + api.apiToken,
                    data: formData
                });
            },

            profilePicUpdate: function(formData) {
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'update/profilePic?api_token=' + api.apiToken,
                    data: formData
                });
            },

            forgetPass: function(formData) {
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'forget',
                    data: formData
                });
            },

            resetPass: function(formData) {
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'resetpass',
                    data: formData
                });
            },

            postColumns: function(formData) {
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'dataset/visual/gen?api_token=' + api.apiToken,
                    data: formData
                });
            },

            saveEditUser: function(formData){
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'user/update?api_token=' + api.apiToken,
                    data: formData
                });
            },

            saveVisual: function(formData){
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'visualization/update?api_token=' + api.apiToken,
                    data: formData
                });
            },

            saveNewDataset: function(formData){
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'create_dataset?api_token=' + api.apiToken,
                    data: formData 
                });
            },
            saveNewSurvey: function(formData){
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'surrvey/save?api_token=' + api.apiToken,
                    data: formData 
                });
            },
            saveSection: function(formData){
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'save_section?api_token=' + api.apiToken,
                    data: formData 
                });
            },
            saveFields: function(formData){
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'save/fields?api_token=' + api.apiToken,
                    data: formData 
                });
            },
            updateSurvey: function(formData){
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'survey/update?api_token=' + api.apiToken,
                    data: formData 
                });
            },

            saveSettings: function(formData){
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'saveVisualSettings?api_token=' + api.apiToken,
                    data: formData 
                });
            },

            generateEmbed: function(formData){
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'generateEmbedToken?api_token=' + api.apiToken,
                    data: formData 
                });
            },

            saveSurveyQuest: function(formData){
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'survey/data?api_token=' + api.apiToken,
                    data: formData 
                });
            },

            saveUserSettings: function(formData){
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'usersettings/save?api_token=' + api.apiToken,
                    data: formData 
                });
            },

            generateSurveyEmbed: function(formData){
                $http.defaults.headers.post['Content-Type'] = undefined;
                return $http({
                    method: 'POST',
                    url: api.baseUrl + 'survey_embeds?api_token=' + api.apiToken,
                    data: formData 
                });
            },


        }

        return api;
    }

})();
(function ()
{
    'use strict';

    config.$inject = ["$stateProvider"];
    angular
        .module('app.survey.edit', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider )
    {
        $stateProvider.state('app.survey_edit', {
            url    : '/survey/edit/:id',
            views  : {

                'content@app': {
                    templateUrl: 'app/main/survey/edit/edit.html',
                    controller : 'EditSurveyController as vm'
                }
            }
        });

     

     

    }

})();

(function ()
{
    'use strict';

    config.$inject = ["msNavigationServiceProvider"];
    angular
        .module('app.visualizations', [
			'app.visualizations.list',
            'app.visualizations.view',
            'app.visualizations.add',
            'app.visualizations.edit',
            
            
            'rzModule',
            'ui.select2',
            'ui.ace'
        ])
        .config(config);

    /** @ngInject */
    function config(msNavigationServiceProvider)
    {
        if(sessionStorage.api_token != undefined && sessionStorage.api_token != ''){
            // Navigation
            msNavigationServiceProvider.saveItem('visualizations', {
                title : 'Visualizations',
                group : true,
    			// state : 'app.genvisuals_list',
                cache: false,
                weight: 12
            });


            msNavigationServiceProvider.saveItem('visualizations.all', {
                title : 'All Visualizations',
                icon  : 'icon-chart-bar',
                state : 'app.genvisuals_list',
                cache: false,
            });

            /*

            msNavigationServiceProvider.saveItem('visualizations.list', {
                title : 'All Visualizations',
                icon  : 'icon-grid',
                state : 'app.visualizations_list',
                cache: false,
            });
            */
    		
         /*   msNavigationServiceProvider.saveItem('visualizations.view', {
                title : 'View Visualization',
                icon  : 'icon-monitor',
                state : 'app.visualizations_view',
                cache: false,
                stateParams: {
                    id: '1',
                    dataset:'9'
                }
            });*/
            msNavigationServiceProvider.saveItem('visualizations.add', {
                title : 'Add Visualization',
                icon  : 'icon-plus',
    			state : 'app.visualizations_add',
                cache: false,
                stateParams: {
                    dataset:''
                }
            });
            /* msNavigationServiceProvider.saveItem('visualizations.edit', {
                title : 'Edit Visualization',
                icon  : 'icon-pencil',
                state : 'app.visualizations_edit',
                cache: false
            });*/

        }

		
		
		
    }
})();
(function ()
{
    'use strict';

    config.$inject = ["msNavigationServiceProvider"];
    angular
        .module('app.svg-maps', ['app.create-map','app.list-maps','app.edit-map','app.view-map'])
        .config(config);

    /** @ngInject */
    function config(msNavigationServiceProvider)
    {
         if(sessionStorage.api_token != undefined && sessionStorage.api_token != ''){
                 /* msNavigationServiceProvider.saveItem('svg-maps', {
                            title : 'Svg Maps',
                            group : true,
                            // state : 'app.dashboardfront',
                            cache: false,
                            weight: 2
                        });*/
                 
                  msNavigationServiceProvider.saveItem('visualizations.list', {
                            title : 'Custom Maps',
                            icon  : 'icon-view-list',
                            state : 'app.list_maps',
                            cache: false,
                           
                        });
                   msNavigationServiceProvider.saveItem('visualizations.create', {
                            title : 'Add Custom Map',
                            icon  : 'icon-map',
                            state : 'app.create_map',
                            cache: false,
                           
                        });
        }

    }

})();
(function ()
{
    'use strict';

    config.$inject = ["msNavigationServiceProvider"];
    angular
        .module('app.survey', ['app.survey.list','app.survey.add','app.survey.addQuestion','app.survey.edit','app.survey.view','app.survey.fields','ui.bootstrap.contextMenu','app.survey.preview','ngMaterialDatePicker','textAngular','ui.ace'])
        .config(config);

    /** @ngInject */
    function config(msNavigationServiceProvider )
    {
        // $stateProvider.state('app.survey', {
        //     url    : '/survey',
        //     views  : {

        //         'content@app': {
        //             templateUrl: 'app/main/survey/survey.html',
        //             controller : 'SurveyController as vm'
        //         }
        //     }
        // });
        
          if(sessionStorage.api_token != undefined && sessionStorage.api_token != ''){
                  msNavigationServiceProvider.saveItem('survey', {
                            title : 'Survey',
                            group : true,
                            // state : 'app.dashboardfront',
                            cache: false,
                            weight: 2
                        });
                  msNavigationServiceProvider.saveItem('survey.list', {
                            title : 'All Survey',
                            icon  : 'icon-content-paste',
                            state : 'app.survey_list',
                            cache: false,
                           
                        });
                  msNavigationServiceProvider.saveItem('survey.add', {
                            title : 'Add Survey',
                            icon  : 'icon-plus',
                            state : 'app.survey_add',
                            cache: false,
                           
                        });
        }
        

     

    }

})();

(function ()
{
    'use strict';

    config.$inject = ["msNavigationServiceProvider"];
    angular
        .module('app.dataset', [
			'app.dataset.list-dataset',
            'app.dataset.create-dataset',
			//'app.dataset.add-dataset',
			'app.dataset.edit-dataset',
			'app.dataset.view-dataset',
			'app.dataset.import-dataset',
			'app.dataset.export-dataset',
			'app.dataset.column-validate',
            'app.dataset.validate-dataset',
            'ngHandsontable',
            'app.dataset.data-filtration',
            'lfNgMdFileInput'
        ])
        .config(config);

    
    /** @ngInject */
    function config(msNavigationServiceProvider)
    {  
        
        if(sessionStorage.api_token != undefined && sessionStorage.api_token != ''){
                // Navigation
                msNavigationServiceProvider.saveItem('dataset', {
                    title : 'Datasets',
                    group : true,
                    // state : 'app.dataset_list',
                    weight: 10
                });

                msNavigationServiceProvider.saveItem('dataset.list-dataset', {
                    title : 'All Datasets',
                    icon  : 'icon-format-list-bulleted',
                    state : 'app.dataset_list',
                });
                msNavigationServiceProvider.saveItem('dataset.create-dataset', {
                    title : 'Create Dataset',
                    icon  : 'icon-grid',
                    state : 'app.dataset_create',
                });

                /*

                msNavigationServiceProvider.saveItem('dataset.add-dataset', {
                    title : 'Add New Dataset',
                    icon  : 'icon-plus',
                    state : 'app.dataset_add',
                });


                msNavigationServiceProvider.saveItem('dataset.view-dataset', {
                    title : 'View Dataset',
                    icon  : 'icon-monitor',
                    state : 'app.dataset_view',
                    stateParams: {
                        id: '3'
                    }
                });

                msNavigationServiceProvider.saveItem('dataset.edit-dataset', {
                    title : 'Edit Dataset',
                    icon  : 'icon-pencil',
                    state : 'app.dataset_edit',
                });
                */


                msNavigationServiceProvider.saveItem('dataset.import-dataset', {
                    title : 'Import Dataset',
                    icon  : 'icon-arrow-left',
                    state : 'app.dataset_import',
                });

                
               /* msNavigationServiceProvider.saveItem('dataset.export-dataset', {
                    title : 'Export Dataset',
                    icon  : 'icon-arrow-right',
                    state : 'app.dataset_export',
                });*/
                
                

                msNavigationServiceProvider.saveItem('dataset.wizard', {
                    title : 'Start Wizard',
                    icon  : 'icon-auto-fix',
                    state : 'app.dataset_import_wizard',
                    stateParams: {
                        wizard: 'wizard'
                    }
                });

        }

    }


})();
