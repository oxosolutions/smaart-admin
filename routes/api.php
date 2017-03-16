<?php

use Illuminate\Http\Request;
	
Route::get('/sql','Services\ImportdatasetController@runSqlFile');
Route::group(['prefix' => 'v1'], function () {

	//get organization list without api_token
	Route::get('organizationList',['as'=> 'organization' , 'uses'=>'Services\organization@allOrganization']);
	Route::group(['middleware'=>['cors','log']], function(){
	Route::post('/dataset/save',['as'=>'dataset.save','uses'=>'Services\SaveServeController@saveDataset']);

Route::get('/userpages',						['as'=>'pages.list','uses'=>'Services\UserPagesApiController@getAllPages' , 'route_name'=>  'View Pages']);
Route::get('/userpages/{page_slug}',			['as'=>'pages.by_slug','uses'=>'Services\UserPagesApiController@getPageBySlug']);


	Route::post('/logs_filter','Services\LogApiController@logFilter');
	Route::post('/auth','Services\ApiauthController@Authenicates');
	Route::post('/register',					['as'=>'register','uses'=>'Services\ApiauthController@Register']);
	Route::get('/goals/list',					['as'=>'goals.list','uses'=>'Services\GoalApiController@goalsList' ,'route_name'=>  'View Goal List']);
	Route::get('/goalData/{id}',			    'Services\GoalApiController@goalData');
	Route::get('/pages',						['as'=>'pages.list','uses'=>'Services\PagesApiController@getAllPages' , 'route_name'=>  'View Pages']);
	Route::get('/pages/{page_slug}',			['as'=>'pages.by_slug','uses'=>'Services\PagesApiController@getPageBySlug']);
	Route::get('/profile/ministries',			['as'=>'ministries','uses'=>'Services\MinistryApiController@Ministries']);
	Route::get('/departments',					['as'=>'departments','uses'=>'Services\DepartmentApiController@departments' ]);
	Route::get('/designation/list',				['as'=>'Designation.list','uses'=>'Services\DesignationApiController@DesignitionList' ,'route_name'=>  'View  Designation List']);
	Route::get('/department/list',				['as'=>'department.list','uses'=>'Services\DepartmentApiController@departmentList' ,'route_name'=>  'View Department List']);
	Route::get('/department/{id}',				['as'=>'department.single','uses'=>'Services\DepartmentApiController@singleDepartment']);
	Route::get('/resources/list',				['as'=>'Resources.list','uses'=>'Services\ResourcesApiController@ResourcesList' , 'route_name'=>  'View Resources List']);
	Route::get('/ministry/list',				['as'=>'ministry.list','uses'=>'Services\MinistryApiController@ministryList' , 'route_name'=>  'View Ministries List']);
	Route::get('/ministry/{id}',				['as'=>'ministry.single','uses'=>'Services\MinistryApiController@singleMinistry']);
	Route::get('/goals/{id}',					['as'=>'goal.single','uses'=>'Services\GoalApiController@singleGoal']);
	Route::get('/schema',						['as'=>'Services\SchemaApiController','uses'=>'Services\SchemaApiController@allSchema' , 'route_name'=>  'View Schemes List']);
	Route::get('/indicators',					['as'=>'indicators','uses'=>'Services\IndicatorsController@indicators' , 'route_name'=>  'View Indicators List']);
	Route::post('/forget',						['as'=>'forget.password','uses'=>'Services\ApiauthController@forgetPassword']);
	Route::get('/validateForgetToken/{token}',	['as'=>'forget.token.validate','uses'=>'Services\ApiauthController@validateForgetPassToken']);
	Route::post('/resetpass',					['as'=>'forget.token.validate','uses'=>'Services\ApiauthController@resetUserPassword']);
});

Route::group(['middleware'=>['auth:api']], function(){	
	Route::get ('/dataset/file/{id}/{type}',['as'=>'export.dataset', 'uses'=>'Services\ExportDatasetController@export' , 'route_name'=>  'Download Dataset']);

	Route::get('/dataset/download/{fileName}',  ['as'=>'dataset.download','uses'=>'Services\ExportDatasetController@downloadFile']);
});



// VISUAL   API END  HERE
Route::post('/singlevisualEmbed', ['as'=>'single.visual','uses'=>'Services\VisualApiController@EmbedVisualById']);

Route::group(['middleware'=>['auth:api','cors','log']], function(){


	//UserSetting
Route::post('/usersettings/save', ['as' => 'usersettings.save' , 'uses' => 'Services\ApiauthController@UserSettingSave']);
Route::get('/usersettings/get', ['as' => 'usersettings.get' , 'uses' => 'Services\ApiauthController@UserSettingGet']);
Route::get('/usersettings/edit', ['as' => 'usersettings.edit' , 'uses' => 'Services\ApiauthController@UserSettingEdit']);
Route::post('/usersettings/update', ['as' => 'usersettings.update' , 'uses' => 'Services\ApiauthController@UserSettingUpdate']);


	//dashboard 
		Route::get('/dashboard', ['as' => 'dashboard' , 'uses' => 'Services\DashboardController@DashboardData']);
	// VISUAL API START HERE
	Route::post('/singlevisual', ['as'=>'single.visual','uses'=>'Services\VisualApiController@visualById']);
	
	//surrvey
	Route::post('surrvey/save', ['as'=>'surrvey.surrvey_save', 'uses'=>'Services\SurrveyApiController@surrvey_save', 'route_name'=> 'Survey Created']);
	Route::get('surrvey/list', ['as'=>'apisurrvey.list', 'uses'=>'Services\SurrveyApiController@surrvey_list' , 'route_name'=> 'Survey List View']);
	Route::get('surrvey/enableDisable/{id}', ['as'=>'apisurrvey.status', 'uses'=>'Services\SurrveyApiController@enableDisable']);
	Route::get('surrvey/del/{id}', ['as'=>'apisurrvey.del', 'uses'=>'Services\SurrveyApiController@delSurrvey']);
	Route::get('surrvey/edit/{id}', ['as'=>'apisurrvey.edit', 'uses'=>'Services\SurrveyApiController@surrvey_edit']);
	Route::post('survey/update', ['as'=>'apisurrvey.update', 'uses'=>'Services\SurrveyApiController@survey_update' , 'route_name'=> 'Survey Update']);
	//SURRVEY GROUP 
	Route::post('survey/data', ['as'=>'apisurrvey.data', 'uses'=>'Services\SurrveyApiController@save_survey_data' , 'route_name'=> 'Created Question']);

	Route::get('survey/view/{id}', ['as'=>'apisurrvey.data', 'uses'=>'Services\SurrveyApiController@view_survey_data']);
	Route::get('generate_survey/{id}', ['as'=>'apisurrvey.data', 'uses'=>'Services\SurrveyApiController@generate_survey']);
	
//role list 
	Route::get('role/list', ['as'=>'role.list', 'uses'=>'Services\ApiauthController@roleList']);
	
	Route::get('surrveyData/{id}',['as'=>'surrvey.data','uses'=>'Services\SurrveyApiController@surrveyData']);

	Route::get('maps',['as'=>'map.list','uses'=>'Services\MapApiController@mapList']);
	Route::get('singelMap/{id}',['as'=>'map.single','uses'=>'Services\MapApiController@singleMap']);
	Route::get('/logs','Services\LogApiController@logActivity');

	Route::post('/create_dataset',['as'=>'dataset.create', 'uses'=>'Services\DatasetsController@create_dataset']);


	Route::get('/users', function (Request $request) {
	    return $request->user();
	});
	Route::get('/userlists',					['as'=>"user.list", 'uses'=>'Services\ApiauthController@listUser']);
	Route::get('/editUser/{id}',				['as'=>"user.edit", 'uses'=>'Services\ApiauthController@editUser']);
	Route::get('/deleteUser/{id}',				['as'=>"user.delete", 'uses'=>'Services\ApiauthController@deleteUser']);
	Route::get('/user/approve/{id}',			['as'=>"user.approve", 'uses'=>'Services\ApiauthController@approveUser']);
	Route::get('/user/unapprove/{id}',			['as'=>"user.unapprove", 'uses'=>'Services\ApiauthController@unApproveUser']);
	Route::post('/user/update',					['as'=>"user.update", 'uses'=>'Services\ApiauthController@updateUser' ,'route_name'=>  'Update User Profile']);

	Route::get('users/list',					['as' => 'users' , 'uses' => 'Services\ApiauthController@UserList']);
	Route::post('/dataset/import',				['as'=>'import','uses'=>'Services\ImportdatasetController@uploadDataset' , 'route_name'=>  'Import Data Set']);
	Route::get('/dataset/list',					['as'=>'list','uses'=>'Services\DatasetsController@getDatasetsList'  , 'route_name'=>  'View Dataset']);
	Route::post('/datasetname/update',		['as'=>'list','uses'=>'Services\DatasetsController@updateDataSetName']);
	
	Route::get('/dataset/view/{id}/{skip}',		['as'=>'list','uses'=>'Services\DatasetsController@getDatasets']);
	Route::get('/dataset/columns/{id}',			['as'=>'list','uses'=>'Services\DatasetsController@getDatasetsColumnsForSubset']);
	Route::get('/dataset/export/{id}',			['as'=>'dataset.export','uses'=>'Services\ExportDatasetController@export'  , 'route_name'=>  'Export Dataset']);
	Route::post('/store/visual',				['as'=>'visualization.store','uses'=>'Services\VisualizationController@store'  , 'route_name'=>  'Store Visual']);
	Route::get('/visual/list',					['as'=>'visualization.list','uses'=>'Services\VisualizationController@visualList']);
	Route::get('/visual/{id}',					['as'=>'visualization.single','uses'=>'Services\VisualizationController@visualByID']);
	Route::get('/dataset/chartdata/{id}',		['as'=>'list','uses'=>'Services\DatasetsController@getFormatedDataset']);
	Route::get('/dataset/define/columns/{id}',	['as'=>'validate.columns','uses'=>'Services\ImportdatasetController@getColumns']);
	Route::post('/visual/settings',				['as'=>'store.visual.settings','uses'=>'Services\VisualizationController@storeVisualOptionsAndSettings']);
	Route::post('/dataset/savevalidatecolumns',	['as'=>'validate.columns','uses'=>'Services\DatasetsController@SavevalidateColumns']);
	Route::get('/dataset/delete/{id}',			['as'=>'validate.columns','uses'=>'Services\DatasetsController@deleteDataset'  , 'route_name'=>  'Delete Data Set']);
	Route::get('/visual/delete/{id}',			['as'=>'validate.columns','uses'=>'Services\VisualizationController@deleteVisual']);
	//User Profile API
	 Route::get('/profile',						['as'=>'user.profile','uses'=>'Services\ProfileApiController@getUserProfile']);
	Route::post('/profile/changepass',			['as'=>'change.password','uses'=>'Services\ProfileApiController@changePassword' , 'route_name'=>  'Change Password']);
	Route::post('dataset/saveEditedDatset',		['as'=>'dataset.save_edited','uses'=>'Services\DatasetsController@saveEditedDatset'  , 'route_name'=>  'Update Data Set']);
	Route::post('dataset/saveSubset',			['as'=>'dataset.save_subset','uses'=>'Services\DatasetsController@saveNewSubset']);
	Route::post('update/profile',				['as'=>'profile.update','uses'=>'Services\ProfileApiController@saveProfile','route_name'=> 'Update Profile']);
	Route::post('update/profilePicUpdate',			['as'=>'profilePic.update','uses'=>'Services\ProfileApiController@profilePicUpdate', 'route_name'=>  'Change Profile Picture ']);
	Route::post('editProfile',			['as'=>'profile.edit','uses'=>'Services\ProfileApiController@editProfile']);
	Route::get('dataset/validate/columns/{id}', ['as'=>'dataset.column.validate', 'uses'=>'Services\DatasetsController@validateColums']);
	Route::get('dataset/static/dataset', 		['as'=>'dataset.column.validate', 'uses'=>'Services\DatasetsController@staticDatsetFunction']);

	Route::get('/generatedVisual/list',			['as'=>'visual.list','uses'=>'Services\VisualApiController@visualList' , 'route_name'=>  'View Visualisation']);
	Route::get('/datsetColumns/{id}',			['as'=>'columns.list','uses'=>'Services\VisualApiController@getColumnByDataset']);
	Route::get('/getVisualdetails/{id}',		['as'=>'visual.details','uses'=>'Services\VisualApiController@getVisualDetails']);
	Route::post('/updatevisual', 				['as'=>'update.visual','uses'=>'Services\VisualApiController@saveVisualData' ,'route_name'=>  'Update Visual']);
	Route::get('/visualChartList',				['as'=>'visual.chartList','uses'=>'Services\VisualApiController@visualList']);
	Route::get('/calculate/visual/{id}',		['as'=>'calc.visual','uses'=>'Services\VisualApiController@calculateVisuals']);
	Route::post('/saveVisualSettings',			['as'=>'save.visual.settings','uses'=>'Services\VisualApiController@saveVisualSettings' ,'route_name'=>  'Save Visual Setting']);
	Route::get('/getVisualList/{dataset_id}',	['as'=>'visual.list.byDataset','uses'=>'Services\VisualApiController@getVisualsFromDatsetID' ]);
	Route::post('/generateEmbedToken',			['as'=>'generate.embed','uses'=>'Services\VisualApiController@generateEmbed']);
		
	});
});