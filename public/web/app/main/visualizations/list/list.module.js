(function ()
{
    'use strict';

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