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
        parent::__destruct();
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

        //$data->getFIlters();
        $model = Embed::where('embed_token',$request->id)->first();
        Session::put('org_id',$model->org_id);
        //dump($model->visual_id);
        $visual = GV::find($model->visual_id);
       // dump($visual);
        $filter =  json_decode($visual->filter_columns,true);
        //dump($filter);
        $chart_type = json_decode($visual->chart_type,true);
        //dump($chart_type);
        foreach ($chart_type as $ckey => $cvalue) {
            //dump($ckey);
           $array['visualizations'][$ckey]['chart_type'] = $cvalue;
        }
        $i = 0;
        foreach ($filter as $fkey => $fvalue) {
            $array['filters'][$i]['filter_column'] =$fvalue['column']; 
            $array['filters'][$i]['filter_type'] =$fvalue['type'];
            $i++;
        }
       
        $dataset_id = $visual->dataset_id;
        $columns = json_decode($visual->columns, true);
        $chartType = json_decode($visual->chart_type, true);
        $embedCss = @$columns['embedCss'];
        $embedJS = @$columns['embedJS'];
        $dataset_data = DL::find($dataset_id);
        $keys = array_keys($columns);
        $charts = array_keys($columns[$keys[0]]);
        //dump($dataset_data);
        $dataset_table_data = DB::table($dataset_data->dataset_table)->get();
        //dump($dataset_table_data);
        $obj = new VisualApiController;
        $datasetColumns = (array)DB::table($dataset_data->dataset_table)->where('id',1)->first();
        $filters = $obj->getFIlters($dataset_data->dataset_table, json_decode($visual->filter_columns, true),$datasetColumns);

        $array['visualization_id'] =  $visual->id;
        $array['visualization_name'] =  $visual->visual_name;
        $array['dataset_id'] =  $visual->dataset_id;
        $array['dataset_name'] = $dataset_data->dataset_name;
        $array['filters'] = $filters;
        $array['custom_code'] = ['custom_css'=>$embedCss,'custom_js'=>$embedJS];
        $array['data'] = $dataset_table_data;


        foreach ($charts as $key => $val) {
            foreach($columns as $colKey => $colVal){
                if(is_array($colVal)){
                    if(array_key_exists($val, $colVal)){
                        $array['visualizations'][$val][$colKey] = $colVal[$val];
                    }else{
                        $array['visualizations'][$val][$colKey] = [];
                    }
                }
            }
        }

       dd($array);
               //return view('web_visualization.visualization');

//dump($kkey);
       // dump($value[$charts[0]]);
         //  $array[$charts[0]][$key] = $value[$charts[0]];
           // dump(array_keys($value));
            //$kkey = $key;
           // dump($kkey);
          //  foreach ($value as $nKey => $nValue) {
           
          // //  dump($nKey);
          //  // dump($nValue);

          //  }
       
        return view('embedVisual.index')->with('data',$array_data);
    }
}