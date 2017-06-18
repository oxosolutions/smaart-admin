(function ()
{
    'use strict';

    angular
        .module('app.activity-log', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider)
    {
        $stateProvider.state('app.activity_log', {
            url    : '/activity-log',
            views  : {
              
                'content@app': {
                    templateUrl: 'app/main/activity-log/activity-log.html',
                    controller : 'ActivityLogController as vm'
                }
            }
        });
    }

})();
