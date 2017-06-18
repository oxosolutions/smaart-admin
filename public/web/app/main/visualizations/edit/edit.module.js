(function ()
{
    'use strict';

    angular
        .module('app.visualizations.edit', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider, msApiProvider)
    {
        $stateProvider.state('app.genvisuals_edit', {
            url    : '/visual/edit/:id',
            views  : {
                'content@app': {
                    templateUrl: 'app/main/visualizations/edit/edit.html',
                    controller : 'EditVisualController as vm'
                }
            }
        });

    }

})();