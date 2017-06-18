(function ()
{
    'use strict';

    angular
        .module('app.svg-maps', ['app.create-map','app.list-maps','app.edit-map','app.view-map'])
        .config(config);

    /** @ngInject */
    function config(msNavigationServiceProvider)
    {
         if(sessionStorage.api_token != undefined && sessionStorage.api_token != ''){
                 /* msNavigationServiceProvider.saveItem('svg-maps', {
                            title : 'Svg Maps',
                            group : true,
                            // state : 'app.dashboardfront',
                            cache: false,
                            weight: 2
                        });*/
                 
                  msNavigationServiceProvider.saveItem('visualizations.list', {
                            title : 'Custom Maps',
                            icon  : 'icon-view-list',
                            state : 'app.list_maps',
                            cache: false,
                           
                        });
                   msNavigationServiceProvider.saveItem('visualizations.create', {
                            title : 'Add Custom Map',
                            icon  : 'icon-map',
                            state : 'app.create_map',
                            cache: false,
                           
                        });
        }

    }

})();