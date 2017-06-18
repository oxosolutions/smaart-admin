(function ()
{
    'use strict';

    angular
        .module('app.survey.fields', [])
        .config(config);

    /** @ngInject */
    function config($stateProvider )
    {
        $stateProvider.state('app.survey_fields', {
            url    : '/survey/fields/:survey_id/:group_id',
            views  : {

                'content@app': {
                    templateUrl: 'app/main/survey/fields/fields.html',
                    controller : 'FieldsController as vm'
                }
            }
        });

     

     

    }

})();
