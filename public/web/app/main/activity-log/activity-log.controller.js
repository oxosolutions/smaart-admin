(function() {
    'use strict';
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