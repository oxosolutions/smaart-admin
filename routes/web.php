<?php



		Route::get('/viewemail',function(){
			return view('mail.layout.email',
			['username' => 'sgssandhu'],
			['user_name' => 'SGS Sandhu']
			); 
		});
		Route::get('/survey/auth',['as'=>'survey.auth','uses'=>'DrawSurveyController@login']);
		Route::post('/survey/do_auth',['as'=>'survey.do_auth','uses'=>'DrawSurveyController@do_auth']);
		Route::get('/correctCsv',['as'=>'datasets.list','uses'=>'DataSetsController@correctCsv']);


		Route::get ('/export/dataset/{id}',['as'=>'export.dataset', 'uses'=>'DataSetsController@apiExportDataset']);

		Route::get('checkLog',['as' => 'log' , 'uses' => 'LogsystemController@CheckForLog']);

		Route::group(['middleware'=>['auth','approve']], function(){
			Route::get('/view_log',['as'=>'log.view','uses'=>'LogsystemController@viewLog']);	
			Route::get('/search_log',['as'=>'log.search','uses'=>'LogsystemController@search_log']);	

		});

		Route::get('s/{id}',['as'=>'survey.draw', 'uses'=>'DrawSurveyController@draw_survey']);

		Route::get('v/{id}',['as'=>'draw.visualisation','uses'=>'VisualisationController@embedVisualization']);

		Route::post('survey/filled',['as'=>'survey.store', 'uses'=>'DrawSurveyController@survey_store']);


		Route::group(['middleware'=>['auth','approve','log']], function(){
		
		Route::get('view_map/{id}',['as'=>'map.view' , 'uses'=>'MapController@view_map' ]);
		Route::get('maps',['as'=>'map.list' , 'uses'=>'MapController@index' ]);
		Route::get('mapData',['as'=>'map.data' , 'uses'=>'MapController@indexData' ]);
		Route::get('map/edit/{id}',['as'=>'map.edit' , 'uses'=>'MapController@edit' ]);
		Route::post('/map/update/{id}',['as'=>'map.update' , 'uses'=>'MapController@update' ]);
		Route::get('/map/enable/{id}',['as'=>'map.enable' , 'uses'=>'MapController@statusEnable' ]);
		Route::get('/map/disable/{id}',['as'=>'map.disable' , 'uses'=>'MapController@statusDisable' ]);
		Route::get('map/create',['as'=>'map.create' , 'uses'=>'MapController@create' ]);
		Route::post('map/save',['as'=>'map.save' , 'uses'=>'MapController@save' ]);
		Route::get('map/del/{id}',['as'=>'map.delete' , 'uses'=>'MapController@map_delete' ]);

		

		//dashboard
		Route::get('/', ['as'=>'home', 'uses'=>'DashboardController@index' ,'route_name'=> 'View Dashboard']);
		//Api user
		Route::get('/api_users/del_all', ['as'=>'user.del', 'uses'=>'ApiusersController@delAllUser' ,'route_name'=>  'Delete User']);

		Route::get('/api_users', ['as'=>'api.users', 'uses'=>'ApiusersController@index']);
		Route::get('/api_users/create', ['as'=>'api.create_users', 'uses'=>'ApiusersController@create']);
		Route::get('/api_users/edit/{id}', ['as'=>'api.edit_users', 'uses'=>'ApiusersController@edit']);
		Route::post('/api_users/update/{id}', ['as'=>'apiuser.update', 'uses'=>'ApiusersController@update']);
		Route::get('/api_users/approved/{id}', ['as'=>'apiuser.approved', 'uses'=>'ApiusersController@approved']);
		Route::get('/api_users/unapproved/{id}', ['as'=>'apiuser.unapproved', 'uses'=>'ApiusersController@unapproved']);
		Route::get('/api_users/editmeta/{id}', ['as'=>'apiuser.editmeta', 'uses'=>'ApiusersController@editmeta']);
		Route::post('/api_users/updatemeta/{id}', ['as'=>'apiuser.updatemeta', 'uses'=>'ApiusersController@updatemeta']);
		Route::get('/api_users/delete/{id}', ['as'=>'apiuser.delete', 'uses'=>'ApiusersController@delete']);



//userPages
		
		Route::get('/userpages/deleteall',['middleware'=>'log','as'=>'userpages.del','uses'=>'UserPagesController@delAllPages']);

		Route::get('/userpages',['middleware'=>'log','as'=>'userpages.list','uses'=>'UserPagesController@index']);
		Route::get('/userpages/create',['middleware'=>'log','as'=>'userpages.create','uses'=>'UserPagesController@create']);
		Route::get('/userpages/delete/{id}',['middleware'=>'log','as'=>'userpages.delete', 'uses'=>'UserPagesController@destroy']);
		Route::get('/userpages_list',['middleware'=>'log','as'=>'userpages.list.ajax','uses'=>'UserPagesController@indexData']);
		Route::post('/userpages/store',['middleware'=>'log','as'=>'userpages.store','uses'=>'UserPagesController@store']);
		Route::get('/userpages/edit/{id}',['middleware'=>'log','as'=>'userpages.edit', 'uses'=>'UserPagesController@edit']);
		Route::patch('/userpages/update/{id}',['middleware'=>'log','as'=>'userpages.update', 'uses'=>'UserPagesController@update']);
	//Pages
		
		Route::get('/pages/deleteall',['middleware'=>'log','as'=>'pages.del','uses'=>'PagesController@delAllPages']);

		Route::get('/pages',['middleware'=>'log','as'=>'pages.list','uses'=>'PagesController@index']);
		Route::get('/pages/create',['middleware'=>'log','as'=>'pages.create','uses'=>'PagesController@create']);
		Route::get('/pages/delete/{id}',['middleware'=>'log','as'=>'pages.delete', 'uses'=>'PagesController@destroy']);
		Route::get('/pages_list',['middleware'=>'log','as'=>'pages.list.ajax','uses'=>'PagesController@indexData']);
		Route::post('/pages/store',['middleware'=>'log','as'=>'pages.store','uses'=>'PagesController@store']);
		Route::get('/pages/edit/{id}',['middleware'=>'log','as'=>'pages.edit', 'uses'=>'PagesController@edit']);
		Route::patch('/pages/update/{id}',['middleware'=>'log','as'=>'pages.update', 'uses'=>'PagesController@update']);
	
	//Dataset
		Route::get('/export/{type}/table/{table}',['as'=>'export.data' , 'uses'=>'DataSetsController@exportTable']);
		Route::get('/dataset',['as'=>'datasets.list','uses'=>'DataSetsController@index']);
		Route::get('/dataset/create',['as'=>'dataset.create','uses'=>'DataSetsController@create']);
		Route::get('/dataset/delete/{id}',['as'=>'datasets.delete', 'uses'=>'DataSetsController@destroy']);
	

	//organization
		Route::get('/organization',					['as'=>'organization.list',			'uses'=>'organizationController@index']);
		Route::get('/organization/create',			['as'=>'organization.create',		'uses'=>'organizationController@create']);
		Route::get('/organization_list',			['as'=>'organization.list.ajax',	'uses'=>'organizationController@indexData']);
		Route::post('/organization/store',			['as'=>'organization.store',		'uses'=>'organizationController@store']);
		Route::get('/organization/delete/{id}',		['as'=>'organization.delete', 		'uses'=>'organizationController@destroy']);
		Route::get('/organization/edit/{id}',		['as'=>'organization.edit', 		'uses'=>'organizationController@edit']);
		Route::patch('/organization/update/{id}',	['as'=>'organization.update', 		'uses'=>'organizationController@update']);
	//role
		Route::get('/roles', ['as'=>'role.list', 'uses'=>'RoleController@index']);
		Route::get('/role/create',['as'=>'role.create', 'uses'=>'RoleController@create']);
		Route::get('/role/delete/{id}', ['as'=>'role.delete', 'uses'=>'RoleController@destroy']);
	//Permisson
		Route::get('/permisson/create',['as'=>'permisson.create', 'uses'=>'PermissionController@create']);
		Route::get('/permisson', ['as'=>'permisson.list', 'uses'=>'PermissionController@index']);
		Route::get('/permisson/delete/{id}', ['as'=>'permisson.delete', 'uses'=>'PermissionController@destroy']);
	//visualisation
		Route::get('/visualisation',['as'=>'visualisation.list','uses'=>'VisualisationController@index']);
		Route::get('/visualisation/create',['as'=>'visualisation.create','uses'=>'VisualisationController@create']);
		Route::get('/visualisation/delete/{id}',['as'=>'visualisation.delete', 'uses'=>'VisualisationController@destroy']);

		/*Routes For indicators resources*/
		Route::get('/visualisation_list',['as'=>'visualisation.list.ajax','uses'=>'VisualisationController@indexData']);
		Route::post('/visualisation/store',['as'=>'visualisation.store','uses'=>'VisualisationController@store']);
		Route::get('/visualisation/edit/{id}',['as'=>'visualisation.edit', 'uses'=>'VisualisationController@edit']);
		Route::patch('/visualisation/update/{id}',['as'=>'visualisation.update', 'uses'=>'VisualisationController@update']);

	/*API Config Routes*/
		Route::get('/config',['as'=>'api.config','uses'=>'ApiConfigController@index']);

	// permisson
		Route::get('/permisson/delete_route/{id}', ['as'=>'permisson.delete_route', 'uses'=>'PermissionController@delete_permisson_route']);

		Route::post('/permisson/store', ['as'=>'permisson.store', 'uses'=>'PermissionController@store']);
		Route::get('/list_permisson', ['as'=>'permisson.list_role.ajax', 'uses'=>'PermissionController@list_permisson']);
		Route::get('/permisson/edit/{id}',['as'=>'permisson.edit', 'uses'=>'PermissionController@edit']);
		Route::patch('/permisson/update/{id}', ['as'=>'permisson.update', 'uses'=>'PermissionController@update']);

	//Role for user
		Route::post('/role/store', ['as'=>'role.store', 'uses'=>'RoleController@store']);
		Route::get('/list_roles', ['as'=>'role.list_role.ajax', 'uses'=>'RoleController@list_role']);
		Route::get('/role/edit/{id}',['as'=>'role.edit', 'uses'=>'RoleController@edit']);
		Route::patch('/role/update/{id}', ['as'=>'role.update', 'uses'=>'RoleController@update']);

	//Role permisson Setting  'middleware' => 'roles',
		Route::get('/setting/create',['as'=>'setting.create', 'uses'=>'SettingController@create']);
		Route::post('/setting/store', ['as'=>'setting.store', 'uses'=>'SettingController@store']);
		Route::get('/setting', ['as'=>'setting.list', 'uses'=>'SettingController@index']);
		Route::get('/list_setting', ['as'=>'setting.list_setting', 'uses'=>'SettingController@list_setting']);
		Route::get('/setting/view/{id}', ['as'=>'setting.view', 'uses'=>'SettingController@view']);
		Route::get('/setting/edit/{id}',['as'=>'setting.edit', 'uses'=>'SettingController@edit']);
		Route::post('/setting/update', ['as'=>'setting.update', 'uses'=>'SettingController@update']);

	/*Routes of API users*/
		Route::get('/get_users', ['as'=>'api.get_users', 'uses'=>'ApiusersController@get_users']);
		Route::post('/api_users/store', ['as'=>'api.store_users', 'uses'=>'ApiusersController@store']);
		Route::get('/api_users_meta/create/{id}', ['as'=>'api.create_users_meta', 'uses'=>'ApiusersController@createUserMeta']);
		Route::post('/api_users_meta/store', ['as'=>'api.store_users_meta', 'uses'=>'ApiusersController@storeUserMeta']);
		Route::get('user_detail/{id}',['as'=>'api.user_detail', 'uses'=>'ApiusersController@userDetail']);
		Route::get('editUserDetails/{id}',['as'=>'api.editUserDetails', 'uses'=>'ApiusersController@editUserDetails']);
		Route::POST('/updateProfile', ['as' => 'updateProfile' , 'uses' => 'ApiusersController@updateProfile']);


	/*Routes For datasets resources*/
		Route::get('/dataset_list',['as'=>'datasets.list.ajax','uses'=>'DataSetsController@indexData']);
		Route::post('/dataset/store',['as'=>'dataset.store','uses'=>'DataSetsController@store']);
		Route::get('/dataset/edit/{id}',['as'=>'datasets.edit', 'uses'=>'DataSetsController@edit']);
		Route::patch('/dataset/update/{id}',['as'=>'datasets.update', 'uses'=>'DataSetsController@update']);

	

	//Global Settings
		Route::get('/settings',['as'=>'global.settings','uses'=>'GlobalSettingsController@index']);
		Route::patch('/settings/store/register',['as'=>'register.settings','uses'=>'GlobalSettingsController@saveNewUserRegisterSettings']);
		Route::patch('/settings/store/forget',['as'=>'forget.settings','uses'=>'GlobalSettingsController@saveForgetEmailSettings']);
		Route::patch('/settings/store/adminreg',['as'=>'adminreg.settings','uses'=>'GlobalSettingsController@saveAdminRegEmailSettings']);
		Route::patch('/settings/store/userapprove',['as'=>'aprroveuser.settings','uses'=>'GlobalSettingsController@saveApproveUserSettings']);
		Route::patch('/settings/store/datasetNumRow',['as'=>'dataset.settings','uses'=>'GlobalSettingsController@datasetNumRowSetting']);
		Route::patch('/settings/store/sitevalue',['as'=>'sitevalue.settings','uses'=>'GlobalSettingsController@siteValue']);

	//Create Visual
		Route::get('/visual', ['as'=>'list.visual','uses'=>'VisualController@index']);
		Route::get('/visual/create', ['as'=>'create.visual','uses'=>'VisualController@create']);
		Route::get('/dataset/columns/{id}/{type?}/{chart?}', ['as'=>'dataset.columns','uses'=>'VisualController@getDatasetColumns']);
		Route::post('/visual/savecolumns',['as'=>'save.dataset.columns','uses'=>'VisualController@saveVisualColumns']);
		Route::get('/getVisual',['as'=>'visual.ajax','uses'=>'VisualController@getData']);
		Route::get('/delete/visual/{id}',['as'=>'visual.delete','uses'=>'VisualController@deleteVisual']);
		Route::get('/visual/edit/{id}',['as'=>'visual.edit','uses'=>'VisualController@edit']);
		Route::patch('/visual/update/{id}',['as'=>'visual.update','uses'=>'VisualController@update']);


//DRAW SURVEY 
	Route::get('survey/view/{sid}/{uid}',['as'=>'survey.views', 'uses'=>'DrawSurveyController@view_filled_survey']);

		
	//Form Builder 

		Route::get('surrvey_setting/{id}',['as'=>'surrvey.setting', 'uses'=>'FormBuilderController@surrvey_setting']);
		Route::post('surrvey_setting/save/{id}',['as'=>'setting.save', 'uses'=>'FormBuilderController@save_setting']);

		Route::get('surrveys', ['as'=>'surrvey.index', 'uses'=>'FormBuilderController@index']);
		Route::get('surrvey/userList/{surrvey_table}', ['as'=>'surrvey.user', 'uses'=>'FormBuilderController@surrveyUserList']);
		Route::get('surrveyUserListData/{surrvey_table}', ['as'=>'surrvey.userData', 'uses'=>'FormBuilderController@surrveyUserListData']);
		
		Route::get('filledSurrveyData/{user_id}/{table}', ['as'=>'surrvey.filldata', 'uses'=>'FormBuilderController@filledSurrveyData']);

		Route::get('surrveyData', ['as'=>'surrvey.data', 'uses'=>'FormBuilderController@index_data']);
		Route::get('surrvey/add', ['as'=>'surrvey.add', 'uses'=>'FormBuilderController@create_surrvey']);
		Route::get('surrvey_edit/{id}', ['as'=>'surrvey.edit', 'uses'=>'FormBuilderController@surrvey_edit']);
		Route::post('surrvey_update/{id}', ['as'=>'surrvey.update', 'uses'=>'FormBuilderController@surrvey_update']);
		Route::get('surrvey_del/{id}', ['as'=>'surrvey.del', 'uses'=>'FormBuilderController@surrvey_del']);

		Route::post('surrvey/surrvey_save', ['as'=>'surrvey.surrvey_save', 'uses'=>'FormBuilderController@surrvey_save']);

		Route::get('surrvey/create/{id}', ['as'=>'surrvey.create', 'uses'=>'FormBuilderController@create']);
		Route::post('surrvey/save', ['as'=>'surrvey.save', 'uses'=>'FormBuilderController@save']);
		Route::get('surrvey/ques/{sid}', ['as'=>'surrvey.ques', 'uses'=>'FormBuilderController@surrvey_ques']);
		Route::get('ques/{id}', ['as'=>'ques.single', 'uses'=>'FormBuilderController@get_ques']);

	//Generated Visuals Queries
		Route::get('/visual/queries',['as'=>'visual.queries','uses'=>'VisualQueryController@index']);
		Route::get('/getQueries',['as'=>'query.ajax','uses'=>'VisualQueryController@getQueryList']);
		Route::get('/visual/query/create/{id?}',['as'=>'visual.query.create','uses'=>'VisualQueryController@create']);
		Route::post('/visual/query/getColValue',['as'=>'visual.query.ajax','uses'=>'VisualQueryController@getColData']);
		Route::post('/visual/query/store',['as'=>'store.visual.query','uses'=>'VisualQueryController@store']);

	//Ajax Routes
		Route::get('survey/field',['as'=>'survey.fields','uses'=>'FormBuilderController@addField']);
		Route::get('map/svg/{id}',['as'=>'map.svg','uses'=>'MapController@loadSVG']);
		Route::post('map/saveSVG',['as'=>'save.svg','uses'=>'MapController@saveSVG']);
	});

Auth::routes();
Route::group(['middleware'=>['log']], function(){
		Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

});

Route::get('/approve/{from?}/{api_token?}', ['as'=>'approve','uses'=>'ApiusersController@approveUser']);
