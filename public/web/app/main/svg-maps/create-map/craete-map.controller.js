(function ()
{
    'use strict';

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