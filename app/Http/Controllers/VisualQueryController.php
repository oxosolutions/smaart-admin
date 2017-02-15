<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Yajra\Datatables\Datatables;
use Session;
use App\GeneratedVisualQuerie as GVQ;
use App\GeneratedVisual as GV;
use App\DatasetsList as DL;
use DB;
class VisualQueryController extends Controller
{
    public function index(){

    	$plugins = [

    			'css'	=>	['datatables'],
    			'js'	=>	['datatables','custom'=>['gen-datatables']]
    	];
    	return view('visual.queries.index',$plugins);
    }

    public function getQueryList(){

    	$model = GVQ::orderBy('id','desc')->get();

    	return Datatables::of($model)
    			->editColumn('visual_id',function($model){
    				return $model->visualId->visual_name;
    			})
    			->editColumn('created_by', function($model){
    				return $model->createdBy->name;
    			})
    		   	->addColumn('actions', function($model){
                    return view('visual.queries._actions', ['model'=>$model])->render();
               	})->make(true);
    }

    public function create($visual_id = null){

    	$plugins = [
    		'css' 	=> ['select2'],
    		'js' 	=> ['select2','custom'=>['visual-queries']]

    	];
    	if(!is_null($visual_id)){
    		$plugins['visual_id'] = $visual_id;
    		$model = GV::find($visual_id);
    		$datasetModel = DL::find($model->dataset_id);
    		$datasetTable = $datasetModel->dataset_table;
    		$DBModel = DB::table($datasetTable)->where('id',1)->first();
    		unset($DBModel->id);
    		$plugins['columns'] = $DBModel;
    		$plugins['db_table'] = $datasetTable;
    	}

    	return view('visual.queries.create',$plugins);
    }

    public function store(Request $request){

    	$query = $request->columns;
    	$visual_id = $request->visual_id;
    	$GVData = GV::find($visual_id);

    	$visualColumns = json_decode($GVData->columns);
    	$withFilterData = [];
    	foreach($visualColumns as $key => $col){
    		$QueryModel = DB::table($request->db_table)->select([DB::raw('COUNT(id) as count'),$col])->where($query)->groupBy($col)->get();
    		$withFilterData[$col] = $QueryModel;
    	}
    	$saveModel = new GVQ;
    	$saveModel->query = json_encode($query);
    	$saveModel->query_result = json_encode($withFilterData);
    	$saveModel->visual_id = $visual_id;
    	$saveModel->created_by = Auth::user()->id;
    	$saveModel->save();
    	Session::flash('success','Successfully created!');
        return redirect()->route('visual.queries');
    }

    public function getColData(Request $request){

    	$colDataArray = [];
    	$columns = json_decode($request->columns);
    	foreach($columns as $key => $column){
    		$model = DB::table($request->db_table)->select($column)->groupBy($column)->get()->toArray();
    		array_shift($model);
    		$colDataArray[] = $model;
    	}
    	return view('visual.queries._columns',['model'=>$colDataArray])->render();
    }
}
