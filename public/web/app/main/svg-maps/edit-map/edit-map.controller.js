(function ()
{
    'use strict';

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