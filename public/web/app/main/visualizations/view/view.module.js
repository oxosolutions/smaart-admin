(function ()
{
    'use strict';

    angular
        .module('app.visualizations.view', ['svgMaps'])
        .config(config);

    /** @ngInject */
    function config($stateProvider, msApiProvider)
    {
        $stateProvider.state('app.genvisual_view', {
            url    : '/visual/view/:id',
            views  : {
                'content@app': {
                    templateUrl: 'app/main/visualizations/view/view.html',
                    controller : 'ViewVisualController as vm'
                }
            }

        });

    }

})();