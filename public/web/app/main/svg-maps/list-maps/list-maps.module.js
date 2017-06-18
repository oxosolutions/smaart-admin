(function ()
{
    'use strict';

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