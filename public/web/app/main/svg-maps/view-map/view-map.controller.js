(function ()
{
    'use strict';

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