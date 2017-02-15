<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Visualisation as VS;
use Auth;
use Session;
use DB;
use App\LogSystem as LOG;
use Carbon\Carbon AS TM;
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

    		$model = new VS($request->except(['_token']));
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
}
