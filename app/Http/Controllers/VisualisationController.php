<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Visualisation;
use Auth;
use App\DatasetsList as DL;
use App\GeneratedVisual as GV;
use Session;
use DB;
use App\Embed;
use App\LogSystem as LOG;
use Carbon\Carbon AS TM;
use App\Http\Controllers\Services\VisualApiController;
use Lava;
use App\Map;
use App\GMap;
use Excel;
use App\VisualizationMeta as VisualMeta;
use App\VisualizationChart;
use App\VisualizationChartMeta;
use App\GlobalSetting;

class VisualisationController extends Controller
{
	protected $ipAdress;
	protected $errors_list = [];
	public function __construct(Request $request)
	{ 
	  $this->ipAdress =  $request->ip();
	  DB::enableQueryLog();  
	}
	function index(){

		$plugin = [
					'css'  =>  ['datatables'],
					'js'   =>  ['datatables','custom'=>['gen-datatables']]
				   ];

		return view('visualisation.index',$plugin);
	}

	public function indexData(){

		$model = VS::orderBy('id','desc')->get();
		return Datatables::of($model)
			->addColumn('actions',function($model){
				return view('visualisation._actions',['model' => $model])->render();
			})->editColumn('dataset_id',function($model){
				try{
					return $model->dataset->dataset_name;
				}catch(\Exception $e){
					return '';
				}
			})->editColumn('created_by',function($model){
				try{
						return $model->createdBy->name;
					}catch(\Exception $e)
					{
						return '';
					}

			})->make(true);
	}

	public function create(){
		return view('visualisation.create');
	}

	public function store(Request $request){

		$this->modelValidate($request);
		DB::beginTransaction();
		try{

			$model = new VS();
			$model->fill($request->except(['_token']));
			$model->created_by = Auth::user()->id;
			$model->save();
			DB::commit();
			Session::flash('success','Successfully created!');
			return redirect()->route('visualisation.list');
		}catch(\Exception $e){

			DB::rollback();

			throw $e;
		}
	}


	protected function modelValidate($request){

		$rules = [
				'dataset_id'  => 'required',
				'visual_name' => 'required',
				'settings'    => 'required',
				'options'     => 'required'
		];

		$this->validate($request,$rules);
	}


	public function edit($id){
		try{
		   $model = VS::findOrFail($id);
		   return view('visualisation.edit',['model'=>$model]);
		}catch(\Exception $e)
		{
			Session::flash('error','No data found for this.');
			return redirect()->route('visualisation.list');
		}
	}

	public function update(Request $request, $id){

		$model = VS::findOrFail($id);

		$this->modelValidate($request);

		DB::beginTransaction();

		try{

			$model->fill($request->except(['_token']));
			$model->save();
			DB::commit();
			Session::flash('success','Successfully update!!');
			return redirect()->route('visualisation.list');
		}catch(\Exception $e){

			DB::rollback();
			throw $e;
		}
	}

	public function destroy($id){

		$model = VS::findOrFail($id);

		try{

			$model->delete();
			Session::flash('success','Successfully deleted!');
			return redirect()->route('visualisation.list');
		}catch(\Exception $e){

			throw $e;
		}
	}
	public function __destruct() {
	   // parent::__destruct();
		// $uid = Auth::user()->id;          

		// foreach (DB::getQueryLog() as $key => $value){ 

		//   if($value['query'] =="insert into `log_systems` (`user_id`, `type`, `text`, `ip_address`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?, ?)" || $value['query'] =="select * from `log_systems` where `user_id` = ? order by `id` desc limit 1" || $value['query']=="select * from `users` where `users`.`id` = ? limit 1")
		//   {  //Not put in log
		//   }else{
		//         $log    = LOG::orderBy('id','desc')->where('user_id',$uid)->first();
		//         $logAr  = json_decode($log->text,true);
		//         $insertTime = $log->created_at;
		//         $currentTime = TM::now();
		//         $addSecond = $insertTime->addSeconds(10);
		//         if(array_key_exists('query', $logAr))
		//         {
		//           if($addSecond > $currentTime  && $logAr['query'] == $value['query'])
		//           {
		//           // dump('not insert log forthis');
		//           }else{
		//             $Lg             =   new LOG;
		//             $Lg->user_id    =   $uid;
		//             $Lg->type       =   "model";            
		//             $Lg->text       =   json_encode(['query'=>$value['query'] , 'value'=>$value['bindings'] ,'time'=> $value['time']]);
		//             $Lg->ip_address =   $this->ipAdress;
		//             $Lg->save(); 
		//           }
		//         }else{
		//             $Lg             =   new LOG;
		//             $Lg->user_id    =   $uid;
		//             $Lg->type       =   "model";            
		//             $Lg->text       =   json_encode(['query'=>$value['query'] , 'value'=>$value['bindings'] ,'time'=> $value['time']]);
		//             $Lg->ip_address =   $this->ipAdress;
		//             $Lg->save(); 
		//         }
		//   }

		// }    

	  }






	/************************************************************ Complete Visualization Work ********************************************************/

	/*
	* Called internally from craeteVisualization to validate requqest
	* @param $request
	* return JSON to API
	*/
	protected function validateRequest($request){

        if($request->has('dataset') && $request->has('visual_name')){
            return ['status'=>'true','errors'=>''];
        }else{
            return ['status'=>'false','error'=>'Fill required fields!'];
        }
    }

    /*
    * Used in Smaart Framework Api index.api.js to create new visualization
    * @param $request (posted request)
    * return JSON to API
    */
	public function createVisualization(Request $request)
	{
        $validate = $this->validateRequest($request);
        if($validate['status'] == 'false'){

            $response = ['status'=>'error','error'=>$validate['error']];
            return $response;
        }
        try{
            $model = new Visualisation();
            $model->dataset_id = $request->dataset;
            $model->name = $request->visual_name;
            $model->description = $request->visual_description;
            $model->created_by = Auth::User()->id;
            $model->save();
        }catch(\Exception $e){

            if($e instanceOf \Illuminate\Database\QueryException){
                return ['status'=>'error','message'=>'No dataset found!'];
            }else{
                return ['status'=>'error','message'=>'something went wrong!'];
            }
        }
		return ['status'=>'success','message'=>'Successfully created!','visual_id'=>$model->id];
	}

	/*
	* Used to update visualization details like chart data and meta
	* @param $request (posted request)
	* return JSON to API
	*/
	public function updateVisualization(Request $request){
		try{
			$charts = json_decode($request->charts); //if charts not null
		}catch(\Exception $e){
			$charts = [];
		}
		try{
			if(!empty($charts)){
				VisualizationChart::where('visualization_id',$request->visualization_id)->forceDelete(); // deleting old entries for update new values
				VisualizationChartMeta::where('visualization_id',$request->visualization_id)->forceDelete();
				foreach($charts as $key => $chart){
					$visualization_chart = new VisualizationChart();
					$visualization_chart->visualization_id = $request->visualization_id;
					$visualization_chart->chart_title = $chart->title;
					$visualization_chart->primary_column = $chart->column_one;
					$visualization_chart->secondary_column = json_encode($chart->columns_two);
					$visualization_chart->chart_type = $chart->chartType;
					$visualization_chart->status = 'true';
					$visualization_chart->save();
					unset($chart->title);			// unset all those values which one we
					unset($chart->column_one);		// don't want to store in meta table
					unset($chart->columns_two);		
					unset($chart->chartType);
					foreach ($chart as $chart_meta_key => $chart_meta_value) {
						$visualization_chart_meta = new VisualizationChartMeta();
						$visualization_chart_meta->visualization_id = $request->visualization_id;
						$visualization_chart_meta->chart_id = $visualization_chart->id;
						$visualization_chart_meta->key = $chart_meta_key;
						if(is_array($chart_meta_value)){
							$chart_meta_value = json_encode($chart_meta_value);
						}
						$visualization_chart_meta->value = $chart_meta_value;
						$visualization_chart_meta->save();
					}
				}
			}
			/*
			* insert visualization meta values
			* inserting filters in visualization meta
			 */
			$filters = [];
			VisualMeta::where('visualization_id',$request->visualization_id)->forceDelete();
			$filters = json_decode(@$request->filters);
			if(!empty($filters)){
				$visualization_meta = new VisualMeta;
				$visualization_meta->visualization_id = $request->visualization_id;
				$visualization_meta->key = 'filters';
				$visualization_meta->value = json_encode($filters);
				$visualization_meta->save();
			}
			/*
			* Inserting visualization settings in 
			* visualization meta table
			 */
			$settings = [];
			$settings = json_decode($request->settings);
			foreach($settings as $setting_key => $setting_value) {
				$visualization_meta = new VisualMeta;
				$visualization_meta->visualization_id = $request->visualization_id;
				$visualization_meta->key = $setting_key;
				$visualization_meta->value = $setting_value;
				$visualization_meta->save();
			}
		}catch(\Exception $e){
			// throw $e;
			return ['status'=>'error','message'=>$e->getMessage()];
		}

		return ['status'=>'success','message'=>'Successfully updated!'];
	}

	/*
	* To display pre-filled details in edit visualization 
	* $param $id (visualization id)
	* return JSON to API
	*/
	public function visualization_details($id){
		try{
			$model = Visualisation::with(['dataset','charts','meta','chart_meta'])->find($id);
			$dataset_table = $model->dataset->dataset_table; //get dataset table name from visualization table with relation (with('dataset'))
			$dataset_model = DB::table($dataset_table)->first();
        	unset($dataset_model->id);
        	$responseArray = [];
        	$responseArray['dataset_columns'] = (array)$dataset_model;
        	$charts = [];
        	$chartIndex = 0;
        	if(!$model->charts->isEmpty()){
        		foreach($model->charts as $chart_key => $chart_value){
        			$charts[$chartIndex]['title'] = $chart_value->chart_title;
        			$charts[$chartIndex]['column_one'] = $chart_value->primary_column;
        			$charts[$chartIndex]['columns_two'] = json_decode($chart_value->secondary_column);
        			$charts[$chartIndex]['chartType'] = $chart_value->chart_type;
        			$chart_meta = VisualizationChartMeta::where('chart_id',$chart_value->id)->get();
        			if(!$chart_meta->isEmpty()){
        				foreach($chart_meta as $meta_key => $chart_meta_value){
        					json_decode($chart_meta_value->value);
        					if(json_last_error() == JSON_ERROR_NONE){
        						$charts[$chartIndex][$chart_meta_value->key] = json_decode($chart_meta_value->value);
        					}else{
        						$charts[$chartIndex][$chart_meta_value->key] = $chart_meta_value->value;
        					}
        				}
        			}
        			$chartIndex++;
        		}
        	}
        	
        	$responseArray['charts'] = $charts; // gettings all chart of this visualizaton with 'hasMany' eloquent relation
        	$responseArray['visualization_meta'] = [];
        	if(!$model->meta->isEmpty()){
        		foreach($model->meta as $key => $meta_data){
        			$responseArray['visualization_meta'][$meta_data->key] = $meta_data->value; //get all visualization_meta data from relation
        		}
        	}
        	$responseArray['maps'] = [
        								'organization_maps'=>Map::select(['id','title'])->where('status','enable')->get(),
        								'global_maps'=>GMap::select(['id','title'])->where('status','enable')->get()
        							];
        	$responseArray['chart_settings'] = GlobalSetting::where('meta_key','visual_setting')->first()->meta_value;
        	return ['status'=>'success','data'=>$responseArray];
		}catch(\Exception $e){
			return ['status'=>'error','message'=>$e->getMessage()];
		}
	}

	public function visualization_list(){

		$responseArray = [];
		$model = Visualisation::with('dataset')->get();
		foreach ($model as $key => $value) {
			$responseArray['visuals'][] = $value;
			$responseArray['dataset'][] = $value->dataset;
		}
		return ['status'=>'success','list'=>$responseArray];
	}

	public function generateEmbed(Request $request){
        $user = Auth::user();
        $org_id = $user->organization_id;
        $exist = Embed::where(['user_id'=>$user->id,'visual_id'=>$request->visual_id])->first();
        if($exist == null){
            $model = new Embed;
            $embed_token = str_random(20);
            $model->visual_id = $request->visual_id;
            $model->org_id  = $org_id;
            $model->user_id = $user->id;
            $model->embed_token = $embed_token;
            $model->save();
        }else{
            $embed_token = $exist->embed_token;
        }

        return ['status'=>'success','message'=>'Successfully generated!','token'=>$embed_token];
    }

	protected function put_in_errors_list($error, $break = false){
		array_push($this->errors_list, $error);
		if($break){
			return view(); // load error view
			dd();
		}
		return true;
	}

	protected function getMetaValue($metaArray, $metaKey){
		$metaArray = collect($metaArray);
		$metaData = $metaArray->where('key',$metaKey);
		$metaValue = false;
		foreach($metaData as $key => $value){
			$metaValue = $value->value;
		}
		return $metaValue;
	}

	protected function get_meta_in_correct_format($visualMetas){
		$visualMetas = json_decode(json_encode($visualMetas),true);
		$valueColumns = array_column($visualMetas, 'value');
		$keyColumns = array_column($visualMetas, 'key');
		return array_combine($keyColumns,$valueColumns);
	}

	public function getFIlters($table, $columns, $columnNames){
        
        $columnsWithType = $columns;
        $columns = (array)$columns;
        $columns = array_column($columns, 'column');
        $resultArray = [];
        $model = DB::table($table)->select($columns)->where('id','!=',1)->get()->toArray();
        $tmpAry = [];
        $max =0;
        foreach($model as $k => $v){
            
            $tmpAry[] = (array)$v;
        }
        
        
        $index = 1;
        foreach($columns as $key => $value){           
            $filter = [];
            if($columnsWithType['filter_'.$index]['type'] == 'range'){
               
                $allData = array_column($tmpAry, $value);
                $min = min($allData);
                $max = max($allData);
                $filter['column_name'] = $columnNames[$value];
                $filter['column_min'] = (int)$min;
                $filter['column_max'] = (int)$max;
                $filter['column_type'] = $columnsWithType['filter_'.$index]['type'];
            }else{
                $filter['column_name'] = $columnNames[$value];
                $filter['column_data'] = array_unique(array_column($tmpAry, $value));
                $filter['column_type'] = $columnsWithType['filter_'.$index]['type'];
            }
            
            $index++;
            $data[$value] = $filter;
        }
     
        return $data;
    }

    protected function apply_filters($request, $dataset_table, $columns){
    	/* 
    	* Sample data array of filters
    	* 
    	*array:2 [▼
		*  "singledrop" => array:1 [▼
		*    0 => array:1 [▼
		*      "column_3" => array:1 [▼
		*        0 => "Designing"
		*      ]
		*    ]
		*  ]
		*  "multipledrop" => array:1 [▼
		*    0 => array:1 [▼
		*      "column_4" => array:2 [▼
		*        0 => "Senior"
		*        1 => "Junior"
		*      ]
		*    ]
		*  ]
		*]
		*/
    	$filterColumns = [];
    	$requested_filters = $request->except(['_token','applyFilter']);
    	$filterKeys = ['dropdown','mdropdown','checkbox','radio'];
    	foreach ($filterKeys as $key) { // $key contains filters key --> singledrop, multidrop etc
    		if(array_key_exists($key, $requested_filters)){ // check if the specific key exist in requested filters
    			foreach ($requested_filters[$key] as $k => $column) { // if key exist in request filter then get that all columns of that key
    				foreach($column as $columnName => $columnValue){
    					// array_filter removing all empty values from $columnValue, in case if user selected "All" in filters
    					$filterColumns[$columnName] = array_filter($columnValue); // create a single array of all selected filters columns
    				}
    			}
    		}
    	}
    	$with_whereIn = false; // with_whereId for check the status if whereIn added in query or not
    	$db = DB::table($dataset_table);
    	foreach($filterColumns as $columnName => $columnsData){
    		if(!empty($columnsData)){ // if $columnData array is empty that means user selected "All" in this filter, so we do not need to add in "whereIn" clause
    			$db->whereIn($columnName, $columnsData); // will create multiple "where in" clause in query 
    			$with_whereIn = true; // set status true if query have where in clause
    		}
    	}
    	if($with_whereIn == true){ // if there is whereIn clause then we need to get the id row also, otherwise select all data from table 
    		$db->orWhere('id',1); // for also get the columns header we need to get first record from datatable
    	}

    	// Finaly it will generate query: "select * from `126_data_table_1495705270` where `column_3` in (?) and `column_4` in (?, ?) or `id` = ?"
    	//dd($db->toSql());
    	
    	return $db->select($columns)->get()->toArray(); // return final query result in the form or array
    	
    }

   

    protected function getSVGMaps($chartMeta){
    	$map = '';
    	$mapId = $this->getMetaValue($chartMeta,'mapArea');
    	$maps_table_and_id = explode('-',$mapId);
    	if($maps_table_and_id[0] == 'globalmaps'){
    		$map = GMap::find($maps_table_and_id[1]);
    	}else{
    		$map = Map::find($maps_table_and_id[1]);
    	}
    	if($map != null && $map != ''){
    		return $map->map_data;
    	}else{
    		return $map;
    	}
    }

    protected function apply_formula($records, $formula){
    	$records_array = [];
		foreach(json_decode(json_encode($records),true) as $record){ // convert associative array into indexd array
			$records_array[] = array_values($record);
		}
    	if($formula == 'count'){
    		
    		$collection = collect($records_array); // convert simple array to laravel collection
    		$countedArray = [];
    		$index = 0;
    		foreach($collection->groupBy(0) as $key => $value){ // getting data from collection with group by first column or primary column
    			$countedArray[$index][] = $key;
    			$countedArray[$index][] = $value->count();
    			$index++;
    		}
    		return $countedArray;
    	}
    	if($formula == 'addition'){
    		$collection = collect($records_array); // convert simple array to laravel collection
    		$recordsToSum = count(json_decode(json_encode($records[0]),true))-1;
			$preparedArray = [];
			$headers = $collection->pull(0); // get headers from collection
			$index = 0;
			foreach ($collection->groupBy(0) as $key => $value) {
				for($i = 1; $i <= $recordsToSum; $i++){ // recordsToSum contain that how much secondory columns we have selected 
					if($i == 1){
						$preparedArray[$index][] = $key;
					}
					$preparedArray[$index][] = $value->sum($i);
				}
				$index++;
			}
    		$records_array = collect($preparedArray);
    		$records_array->prepend($headers);
    		return $records_array;
    	}

    	if($formula == 'percent'){
    		$columns = count($records_array[0])-1;
    		$collection = collect($records_array);
    		$records_total_array = [];
    		for($i = 1; $i <= $columns; $i++){
    			$records_total_array[$i] = $collection->sum($i);
    		}
    		$headers = $collection->pull(0);
    		$records_array = [];
    		foreach($collection as $key => $value){
    			$tempArray = [];
    			foreach($value as $k => $v){
    				if(array_key_exists($k, $records_total_array)){
    					$tempArray[] = ($v*100)/$records_total_array[$k];
    				}else{
    					$tempArray[] = $v;
    				}
    			}
    			$records_array[] = $tempArray;
    		}
    		$records_array = collect($records_array);
    		$records_array->prepend($headers);
    		return $records_array;
    	}
    }


	public function embedVisualization(Request $request){
		
		$embedModel = Embed::where('embed_token',$request->id)->first();
		if($embedModel == null){
			$this->put_in_errors_list('Wrong embed token!', true);
		}
		Session::put('org_id',$embedModel->org_id); // putting organization id into session for get the data from models

		$visualization = Visualisation::with([

		'dataset','charts'=>function($query){

				$query->with('meta');

		},'meta','chart_meta'])->find($embedModel->visual_id); //getting dataset, visualization charts and meta from eloquent relations

		if($visualization->charts->isEmpty()){ //if there is not chart exist in generated visualization

			$this->put_in_errors_list('No charts found!', true);
		}

		$dataset_table = $visualization->dataset->dataset_table; //getting dataset table name from visualization query
		$drawer_array = [];
		$chartTitles = [];
		$javascript = [];
		$drawer_array['visualization_name'] = $visualization->name;
		$drawer_array['visualization_id'] = $visualization->id;
		$drawer_array['visualization_meta'] = $this->get_meta_in_correct_format($visualization->meta);
		$drawer_array['visualizations'] = [];
		foreach ($visualization->charts as $key => $chart) {
			$columns = [];
			$columns[] = $chart->primary_column;
			// dd($columns);
			foreach(json_decode($chart->secondary_column) as $column){
				$columns[] = $column;
			}
			if($chart->chart_type == 'CustomMap'){

				$viewData_meta = $this->getMetaValue($chart->meta,'viewData');
				$customData_meta = json_decode($this->getMetaValue($chart->meta,'customData'));
				dd($columns);
				$columns[] = $viewData_meta;
				if(!empty($customData_meta)){
					foreach ($customData_meta as $customColumn) {
						$columns[] = $customColumn;
					}
				}
				$columns = array_unique($columns);
			}
			try{
				/*
				*	if request has any filter
				*/
				if($request->has('applyFilter')){
					$dataset_records = $this->apply_filters($request, $dataset_table, $columns);
				}else{
					$dataset_records = DB::table($dataset_table)->select($columns)->get()->toArray(); //getting records with selected columns from dataset table
				}

				$formula = $this->getMetaValue($chart->meta,'formula');
				if($formula != 'no'){
					$dataset_records = $this->apply_formula($dataset_records, $formula);
				}

				$dataset_records = json_decode(json_encode($dataset_records),true); // generating pure array from colection of stdClass object
				$headers = array_shift($dataset_records);
				if($chart->chart_type != 'CustomMap'){

					$lavaschart = lava::DataTable();
					$index = 0;
					foreach ($headers as $header) { // to add headers into lavacharts datatable
						if($index == 0){
							$lavaschart->addStringColumn($header); //for string header
						}else{
							if($chart->chart_type == 'TableChart'){
								$lavaschart->addStringColumn($header); //for string header
							}else{
								$lavaschart->addNumberColumn($header); //for all numeric headers
							}
						}
						$index++;
					}
				}

				$records_array = [];
				foreach($dataset_records as $record){ // convert associative array into indexd array
					$records_array[] = array_values($record);
				}

				if(!empty($records_array)){ // if after filter or without filter there is no data in records list
					if($chart->chart_type != 'CustomMap'){
						$lavaschart->addRows($records_array); // lavachart add only indexed array of arrays (inserting multiple rows in to lavacharts datatable)

						lava::{$chart->chart_type}('chart_'.$key,$lavaschart)->setOptions([
										'title' => $chart->chart_title
									]);
					}else{
						$drawer_array['visualizations']['chart_'.$key]['map'] = $this->getSVGMaps($chart->meta); // get svg maps global or local
						$headers = array_values($headers);
						$this->create_map_array($records_array, $headers);
						$javascript['chart_'.$key] = ['type'=>$chart->chart_type,'id'=>'chart_'.$key,'data'=>$records_array,'headers'=>$headers];
					}
					/*
					* Prepare data for draw visualization
					* on front
					*/
					$chartTitles['chart_'.$key] = $chart->chart_title; //collect all chart titles in single array
					$drawer_array['visualizations']['chart_'.$key]['chart_type'] = $chart->chart_type;
					$drawer_array['visualizations']['chart_'.$key]['title'] = $chart->chart_title;
					$drawer_array['visualizations']['chart_'.$key]['enableDisable'] = $this->getMetaValue($chart->meta,'enableDisable');
				}else{
					$this->put_in_errors_list('No records found with selected filters');
				}

			}catch(\Exception $e){

				$this->put_in_errors_list($e->getMessage());
				throw $e;
			}
		}
		/*
		* Prepare filters for front view
		 */
		$datasetColumns = (array)DB::table($dataset_table)->where('id',1)->first();
		$filter_columns = $this->getMetaValue($visualization->meta,'filters');
		$filters = [];
		if(!empty(json_decode($filter_columns,true))){
			$filters = $this->getFIlters($dataset_table, json_decode($filter_columns, true),$datasetColumns);
		}
		
		// adding selected values of filters in filters array
		$selectedFilters = $request->except(['_token','applyFilter']);
		foreach ($selectedFilters as $type => $array) {
			foreach($array as $indexedkey => $columnNames){
				foreach($columnNames as $colKey => $colArray){
					if($filters[$colKey]['column_type'] == $type){
						$filters[$colKey]['selected_value'] = $colArray;
					}
				}
			}
		}
		// dd($javascript);
		//Finaly load view
		return view('web_visualization.visualization',
								[
									'filters'=>$filters, // contain all filters
									'titles'=>$chartTitles, // contains all titles 
									'visualizations'=>$drawer_array, // data for draw all charts from lava charts
									'javascript'=>$javascript, //data for custom map popup details
									'custom_map_data'=>[] //data for pop click event
								]
					);
	}

	public function create_map_array($records, $headers){
		$records = collect($records);
		$records = $records->groupBy(0)->toArray();
		foreach($records as $key => $record){
			foreach($record as $k => $value){
				$records[$key][$k] = array_combine($headers, $value);
			}
		}

		//Don't remove this, this is working code
		/*$records_array = [];
		foreach ($records as $key => $record) {
			foreach($record as $k => $value){
				if($k != 0){
					$records_array[$record[0]][$headers[$k]][] = $value;
				}
			}
		}*/

		dd($records);
	}

}