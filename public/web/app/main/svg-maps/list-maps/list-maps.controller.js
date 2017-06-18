(function ()
{
    'use strict';

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