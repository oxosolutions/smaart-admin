<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\DatasetsList as DL;
use App\GeneratedVisual as GV;
use Auth;
use Yajra\Datatables\Datatables;
use Session;
class VisualController extends Controller
{
    function index(){
    	$plugins = [
        			'css' => ['datatables'],
        			'js'  => ['datatables', 'custom' => ['gen-datatables']]
    	           ];
    	return view('visual.index',$plugins);
    }

    public function getData(){
    	$model = GV::orderBy('id','desc')->get();

    	return Datatables::of($model)

            ->editColumn('columns',function($model){

                    return substr($model->columns, 0,20).'....';
                })

    			->editColumn('dataset_id',function($model){

                    try{
                        return $model->datasetName->dataset_name;
                    }catch(\Exception $e){
                        return '<i style=\'color:red;\'>No dataset found</i>';
                        throw $e;
                    }

    			})
    			->editColumn('created_by', function($model){
    				return $model->createdBy->name;
    			})
    		   	->addColumn('actions', function($model){
                    return view('visual._actions', ['model'=>$model])->render();
               	})->make(true);
    }

    public function create(){
    	$plugins = [
    		'js'  => ['select2','icheck','custom'=>['visual-create']],
    		'css' => ['select2','icheck']
    	];

    	return view('visual.create',$plugins);
    }

    public function getDatasetColumns($id,$type = 'column', $chart = ''){

    	$model = DL::find($id);
    	$datasetTable = $model->dataset_table;
    	$model = DB::table($datasetTable)->first();
    	unset($model->id);
        if($type == 'clone'){
            return view('visual._clone', ['columns'=>$model,'chart'=>'chart_'.$chart])->render();
        }else{
            return view('visual._columns', ['columns'=>$model])->render();
        }
    }

    public function saveVisualColumns(Request $request){

        $model = new GV();
        $model->visual_name = $request->visual_name;
        $model->dataset_id = $request->dataset_id;
        $model->columns = json_encode(
                                        [   'title'=>$request->title,
                                            'column_one'=>$request->columns_one,
                                            'columns_two'=>$request->columns_two,
                                            'visual_settings'=>$request->visual_settings,
                                            'count'=> @$request->count
                                        ]);
        $model->filter_columns = json_encode(@$request->filter_cols);
        $model->chart_type = json_encode($request->chartType);
        $model->created_by = Auth::user()->id;
        $model->save();
        Session::flash('success','Successfully created!');
        return redirect()->route('list.visual');

    	/*$this->validateRequest($request);
    	$model = DL::find($request->dataset_id);
    	$datasetTable = $model->dataset_table;
    	$resultArray = [];
    	foreach($request->columns as $key => $column){
    		$model = DB::table($datasetTable)->select([DB::raw('COUNT(id) as count'),$column])->groupBy($column)->get();
    		unset($model[0]);
    		$resultArray[$column] = $model;
    	}
        $filterResultArray = [];
        foreach($request->filter_cols as $key => $column){
            $model = DB::table($datasetTable)->select([DB::raw('COUNT(id) as count'),$column])->groupBy($column)->get();
            unset($model[0]);
            $filterResultArray[$column] = $model;
        }
    	$jsonData = json_encode($resultArray);
    	$model = new GV();
    	$model->visual_name = $request->visual_name;
    	$model->dataset_id = $request->dataset_id;
        $model->columns = json_encode($request->columns);
        $model->filter_columns = json_encode($request->filter_cols);
    	$model->filter_counts = json_encode($filterResultArray);
    	$model->query_result = $jsonData;
        $mode->visual_settings = $request->visual_settings;
    	$model->created_by = Auth::user()->id;
    	$model->save();
    	Session::flash('success','Successfully created!');
        return redirect()->route('list.visual');*/
    }

    protected function validateRequest($request){
    	$rules = [
    			'dataset_id' => 'required',
    			'visual_name' => 'required',
    			'columns' => 'required'
    	];

    	$this->validate($request,$rules);
    }

    public function deleteVisual($id){

        $model = GV::find($id);
        $model->delete();
        Session::flash('success','Successfully deleted!');
        return redirect()->route('list.visual');
    }

    public function edit($id){

        $model = GV::find($id);
        $dataList = DL::find($model->dataset_id);
        $dbModel = DB::table($dataList->dataset_table)->first();
        unset($dbModel->id);
        $columnData = json_decode($model->columns,true);

       // dd( $columnData);
        $selectedFilterCol = json_decode($model->filter_columns);
        $plugins = [
            'js'  => ['select2','custom'=>['visual-create']],
            'css' => ['select2'],
            'model' => $model,
            'columns' => $dbModel,
            'preFilled' => $columnData,
            'prefilledFilter' => $selectedFilterCol,
            'chartTypes' => json_decode($model->chart_type,true)
        ];
        return view('visual.edit',$plugins);
    }

    public function update(Request $request, $id){

        $model = GV::find($id);
        $model->visual_name = $request->visual_name;
        $model->dataset_id = $request->dataset_id;
        $model->columns = json_encode(
                                        [   'title'=>$request->title,
                                            'column_one'=>$request->columns_one,
                                            'columns_two'=>$request->columns_two,
                                            'visual_settings'=>$request->visual_settings,
                                            'count'=> @$request->count
                                        ]);
        $model->filter_columns = json_encode(@$request->filter_cols);
        $model->chart_type = json_encode($request->chartType);
        $model->created_by = Auth::user()->id;
        $model->save();
        Session::flash('success','Successfully update!');
        return redirect()->route('list.visual');

        /*$this->validateRequest($request);
        $model = DL::find($request->dataset_id);
        $datasetTable = $model->dataset_table;
        $resultArray = [];
        foreach($request->columns as $key => $column){
            $model = DB::table($datasetTable)->select([DB::raw('COUNT(id) as count'),$column])->groupBy($column)->get();
            unset($model[0]);
            $resultArray[$column] = $model;
        }
        $filterResultArray = [];
        foreach($request->filter_cols as $key => $column){
            $model = DB::table($datasetTable)->select([DB::raw('COUNT(id) as count'),$column])->groupBy($column)->get();
            unset($model[0]);
            $filterResultArray[$column] = $model;
        }
        $jsonData = json_encode($resultArray);
        $model = GV::find($id);
        $model->visual_name = $request->visual_name;
        $model->dataset_id = $request->dataset_id;
        $model->columns = json_encode($request->columns);
        $model->filter_columns = json_encode($request->filter_cols);
        $model->filter_counts = json_encode($filterResultArray);
        $model->query_result = $jsonData;
        $model->visual_settings = $request->visual_settings;
        $model->created_by = Auth::user()->id;
        $model->save();
        Session::flash('success','Successfully updated!');
        return redirect()->route('list.visual');*/
    }
}
