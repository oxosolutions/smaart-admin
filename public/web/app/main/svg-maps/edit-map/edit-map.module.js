(function ()
{
    'use strict';

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