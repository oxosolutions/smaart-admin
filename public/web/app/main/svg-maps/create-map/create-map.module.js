(function ()
{
    'use strict';

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