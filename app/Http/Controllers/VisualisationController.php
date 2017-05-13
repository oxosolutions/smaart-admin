<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Visualisation as VS;
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

class VisualisationController extends Controller
{
	protected $ipAdress;
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

	public function embedVisualization(Request $request){
	    
		$model = Embed::where('embed_token',$request->id)->first();
		if($model == null){

			echo "Error";

		}else{
			Session::put('org_id',$model->org_id);
			$visual = GV::find($model->visual_id);
			$chart_type = json_decode($visual->chart_type,true);
			if($chart_type == null){

				echo "Error";	

			}else{
				
				foreach ($chart_type as $ckey => $cvalue) {
				   $array['visualizations'][$ckey]['chart_type'] = $cvalue;
				   $chart_type = $cvalue;
				}
			   	
				$dataset_id = $visual->dataset_id;
				$columns = json_decode($visual->columns, true);
				$chartType = json_decode($visual->chart_type, true);
				$embedCss = @$columns['embedCss'];
				$embedJS = @$columns['embedJS'];

				$keys = array_keys($columns);
				$charts = array_keys($columns[$keys[0]]);

				foreach ($charts as $key => $val) {
					foreach($columns as $colKey => $colVal){
						if(is_array($colVal)){
							
							if($colKey=="title")
								{
									$title = $colVal[$val];
								}
							 if(array_key_exists($val, $colVal)){
								$array['visualizations'][$val][$colKey] = $colVal[$val];
							}else{
								$array['visualizations'][$val][$colKey] = [];
							}
						}
					}
				}

				foreach($array['visualizations'] as $k => $value){
					$columns = [];
					$columns[] = $value['column_one'];
					if(!empty($value['columns_two'])){
						foreach($value['columns_two'] as $key => $columnName){
							$columns[] = $columnName;

						}
					}
					$dataset_data = DL::find($dataset_id);

					/************* if request has filters ***************/

					if($request->has('applyFilter')){
						$dataset_table_data = DB::table($dataset_data->dataset_table);
						if($request->has('multipledrop')){
							$filters = $request->except(['_token','applyFilter']);
							foreach($filters['multipledrop'] as $key => $dropdown){
								foreach($dropdown as $columnKey => $filter){
									$dataset_table_data->where(function($query) use ($filter, $columnKey){
										foreach ($filter as $filterValue) {
											$query->orWhere($columnKey, $filterValue);
										}
									});
								}
							}
						}
						if($request->has('singledrop')){
							$filters = $request->except(['_token','applyFilter']);
							foreach($filters['singledrop'] as $key => $dropdown){
								foreach($dropdown as $columnKey => $filter){
									$dataset_table_data->where(function($query) use ($filter, $columnKey){
										foreach ($filter as $filterValue) {
											$query->orWhere($columnKey, $filterValue);
										}
									});
								}
							}
						}
						if($request->has('range')){
							$filters = $request->except(['_token','applyFilter']);
							foreach($filters['range'] as $key => $range){
								$dataset_table_data->where(function($query) use ($range, $key){
									foreach ($range as $columnKey => $filterValue) {
										$explodeRange = explode(',',$filterValue);
										$query->whereBetween($columnKey,[$explodeRange[0],$explodeRange[1]]);
									}
								});
							}
						}
						if($value['formula'] == 'count'){
							$collected_records = [];
							foreach($columns as $colKey => $column){
								$records = $dataset_table_data->select([$column, DB::raw('COUNT(id) as count')])->groupBy($column)->get()->toArray();
								if(!empty($dataset_table_data)){
									array_walk($records, function($value) use (&$collected_records){
										array_push($collected_records,$value);
									});
								}else{
									$collected_records = $records;
								}
							}
							$dataset_table_data = $collected_records;
						}elseif($value['formula'] == 'addition'){
							$collected_records = [];
							foreach($columns as $colKey => $column){
								$records = $dataset_table_data->selectRaw('SUM('.$column.') as SUM')->first();
								$totalAddition[] = $records->SUM;
								$header = DB::table($dataset_data->dataset_table)->select($column)->where('id',1)->first();
								$columnHeader[] = $header->{$column};
							}
							$dataset_table_data = [];
							$dataset_table_data[] = $columnHeader;
							$dataset_table_data[] = $totalAddition;
						}else{
							$dataset_table_data = $dataset_table_data->orWhere('id',1)->select($columns)->get()->toArray();
						}
						
					}else{
						if($value['formula'] == 'count'){
							$dataset_table_data = [];
							foreach($columns as $colKey => $column){
								$records = DB::table($dataset_data->dataset_table)->select([$column,DB::raw('COUNT(id) as count')])->groupBy($column)->get()->toArray();
								if(!empty($dataset_table_data)){
									array_walk($records, function($value) use (&$dataset_table_data){
										array_push($dataset_table_data,$value);
									});
								}else{
									$dataset_table_data = $records;
								}
							}
						}elseif($value['formula'] == 'addition'){
							$dataset_table_data = [];
							foreach($columns as $colKey => $column){
								$records = DB::table($dataset_data->dataset_table)->selectRaw('SUM('.$column.') as SUM')->first();
								$totalAddition[] = $records->SUM;
								$header = DB::table($dataset_data->dataset_table)->select($column)->where('id',1)->first();
								$columnHeader[] = $header->{$column};
							}
							$dataset_table_data[] = $columnHeader;
							$dataset_table_data[] = $totalAddition;
						}elseif($value['formula'] == 'percent'){

						}else{
							$dataset_table_data = DB::table($dataset_data->dataset_table)->select($columns)->get()->toArray();
						}
					}

					/***************************************************/
					$dataset_table_data = json_decode(json_encode($dataset_table_data),true);
					$prepareDataArray = [];
					foreach($dataset_table_data as $dateKey => $dataVal){
						$prepareDataArray[] = array_values($dataVal);
					}
					$headers = array_shift($prepareDataArray);
					$array['visualizations'][$k]['headers'] = $headers;
		            $array['visualizations'][$k]['data'] = $prepareDataArray;
				}
				//dd($array);
				$obj = new VisualApiController;
				$datasetColumns = (array)DB::table($dataset_data->dataset_table)->where('id',1)->first();
				$filters = [];
				try{       
					$filters = $obj->getFIlters($dataset_data->dataset_table, json_decode($visual->filter_columns, true),$datasetColumns);
				}catch(\Exception $e){
					// throw $e;
				}
				// dd($filters);
				$visualMetas = VisualMeta::where('visualization_id',$visual->id)->get()->toArray();
				
				$valueColumns = array_column($visualMetas, 'value');
				$keyColumns = array_column($visualMetas, 'key');

				$array['visualization_meta'] = array_combine($keyColumns,$valueColumns);
				$array['visualization_id'] =  $visual->id;
				$array['visualization_name'] =  $visual->visual_name;
				$array['dataset_id'] =  $visual->dataset_id;
				$array['dataset_name'] = $dataset_data->dataset_name;
				$array['filters'] = @$filters;
				$array['custom_code'] = ['custom_css'=>$embedCss,'custom_js'=>$embedJS];
				$array['data'] = $dataset_table_data;

				$chartDetailsForJquery = [];
				foreach($array['visualizations'] as $key => $value){
					if($value['chart_type'] != 'CustomMap'){
						$lavaschart = lava::DataTable();
						foreach($value['headers'] as $index => $header){
							if($index == 0){

								$lavaschart->addStringColumn($header);
							}else{
								$lavaschart->addNumberColumn($header);
							}
						}
						$lavaschart->addRows($value['data']);
						$chartDetailsForView[$key] = [
														'type'=>$value['chart_type'],
														'id'=>$key,
														'data'=>$value['data'],
														'chartWidth' => @$value['chartWidth']
													];
						lava::{$value['chart_type']}($key,$lavaschart)->setOptions([
								'title' => $value['title']
							]);
						$chartTitles[$key] = $value['title'];	
					}elseif($value['chart_type'] == 'CustomMap'){

						$SVGContent = Map::find($value['mapArea']);
						if($SVGContent == null){
							$SVGContent = GMap::find($value['mapArea']);
						}
						$array['visualizations'][$key]['map'] = $SVGContent['map_data'];
						$chartDetailsForView[$key] = [
														'type'=>$value['chart_type'],
														'id'=>$key,
														'data'=>$value['data'],
														'map'=>$SVGContent['map_data'],
														'data'=>$value['data'],
														'chartWidth'=> @$value['chartWidth']
													];
						$chartTitles[$key] = $value['title'];
						$chartDetailsForJquery[$key] = ['type'=>$value['chart_type'],'id'=>$key,'data'=>$value['data'],'headers'=>$value['headers']];
					}
				}
				
				if($request->has('downloadData')){
					$chartsData = $value['data'];
					array_unshift($chartsData,$headers);
					Excel::create('Generated-Chart-Data', function($excel) use ($chartsData) {

					    $excel->sheet('Records', function($sheet) use ($chartsData) {

					        $sheet->fromArray($chartsData);

					    });

					})->download('xls');
				}

				//dd($chartDetailsForJquery); 
		 		return view('web_visualization.visualization',['details'=>$chartDetailsForView,'filters'=>$filters,'titles'=>$chartTitles,'javascript'=>$chartDetailsForJquery,'customeCode'=>$array['custom_code'],'visualizations'=>$array]);
			}
		}
	}
}