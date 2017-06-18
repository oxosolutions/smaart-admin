(function ()
{
    'use strict';

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