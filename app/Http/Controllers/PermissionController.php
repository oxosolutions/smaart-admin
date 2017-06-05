<?php

namespace App\Http\Controllers;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
Use App\Permisson;
use App\PermissonRouteMapping as PRM;
use App\PermissonRole as PR;
use DB;
use Session;

class PermissionController extends Controller
{
    //
	public function index()
    {
    		$plugins = [ 'css'=> ['datatables'],
    					 'js'=>['datatables', 'custom'=>['gen-datatables'] ]	];

    		return view('permisson.index', $plugins);

    }

    public function list_permisson()
    {
			$model = Permisson::get();
			return Datatables::of($model)
			->addColumn('actions',function($model){
					return view('permisson._actions',['model'=>$model])->render();
			    })
			->make(true);
		}


    public function create(){  

    		// $roles = 'App\Role'::rolePermisson(1);
    		// dump($roles->permisson);
    		// foreach ($roles->permisson as  $value) {
    			
    		// 	dump($value->permissons);
    		// }
    		//dd($roles->permisson);
      // $data = 'App\Permisson'::allRoute(2);
      // dd($data->routeMapping);
      // echo "<pre>";
      //   foreach ($data as  $value) {
      //     # code...
      //    print_r($value->routeMapping);
      //   }  

        //die;        
		// 	$data = Permisson::all();//->routeMapping;
			
		// 	foreach ($data as  $value) {
		// 		# code...
		// 		dump($value->routeMapping);
		// 	}
		// dd($data);
    		//dd($data);

			return view('permisson.create');
    }

    public function store(Request $request)
    {
    		$this->modelValidation($request);
			DB::beginTransaction();
			try{
	    		$permisson = new Permisson($request->except(['_token','route']));
	    		$permisson->save();
	    		$permisson_id =  $permisson->id;
	     		
	    		$routeSize = count($request->route);
    	for($i=0; $i<$routeSize; $i++)
	    	{	
	    		$pr_map = new PRM($request->except(['_token','updated_at']));
	    		$pr_map->permisson_id = $permisson_id;
	    		$pr_map->route = $request->route[$i];
	    		$pr_map->route_for =  $request->routeFor[$i];
	    		$pr_map->route_name =  $request->route_name[$i];
				$pr_map->save(); 
	    	}

			DB::commit();

	    	Session::flash('success',"Permisson Successful Created!");

	    	}catch(\Exception $e)
	    	{
                throw $e;
	    		if($e instanceOf \Illuminate\Database\QueryException){

	    				Session::flash('error','Permisson name already Created!');

					}else{
					    Session::flash('error','Try again!');

					}

	    		DB::rollback();
	    	}

	         return redirect()->route('permisson.list');
    }

    public function edit($id)
    {
			$permisson = Permisson::findOrFail($id);
			// foreach ($permisson->routeMapping as  $value) {
			// 	# code...
			// 	$route[$value->id]['route'] =  $value->route;
			// }
			// dd($route);
			return view('permisson.edit',['model'=>$permisson]);

    }

    public function update(Request $request, $id)
    {	
    	// $newRouteSize = count($request->route_new);
		 		// if($newRouteSize>0)
		 		// {
		 		// 	for($j=0; $j<$newRouteSize; $j++)
		 		// 	{	
		 		// 		echo $request->route_new[$j];
		 		// 		echo $request->route_name_new[$j];
		 		// 	//$newRouteObj 				=	new PRM();
		 		// 	// echo $newRouteObj->permisson_id  = 	$id;
		 		// 	// echo $newRouteObj->route  		=  	$request->route_new[$j];
		 		// 	// //$newRouteObj->route_for 	=	$request->routeFor_new[$i];
		 		// 	// echo $newRouteObj->route_name 	=	$request->route_name_new[$i];
		 		// 	//$newRouteObj->save();
		 		// 	}

		 		// }
    	
    	
    	// dd($request);
			$permisson = Permisson::findOrFail($id);
			$this->modelValidation($request);
			DB::beginTransaction();
			try{
				$permisson->fill($request->except(['_token']));
				//$role->name = $request->name;
				$permisson->save();

				$sizeRoutes =	count($request->route_map_id);
		    	for($i=0; $i<$sizeRoutes; $i++)
		    	{	
		    		$routeObj = PRM::findOrFail($request->route_map_id[$i]);
		       		$routeObj->route = $request->route[$i];
		       		$routeObj->route_for = $request->routeFor[$i];
		       		$routeObj->route_name = $request->route_name[$i];
		       		$routeObj->save();
		 		}

		 		$newRouteSize = count($request->route_new);
		 		if($newRouteSize>0)
		 		{
		 			for($j=0; $j<$newRouteSize; $j++)
		 			{	
		 			$newRouteObj 				=	new PRM();
		 			$newRouteObj->permisson_id  = 	$id;
		 			$newRouteObj->route  		=  	$request->route_new[$j];
		 			$newRouteObj->route_for 	=	'other';//$request->routeFor_new[$j];
		 			$newRouteObj->route_name 	=	$request->route_name_new[$j];
		 			$newRouteObj->save();
		 			}

		 		}

		 	// 		die;
			// dd($request);

				 DB::commit();
				Session::flash('success',"Updated Permisson Successful Created!");

			}catch(\Exception $e)
			{
				echo "exception"; 
				die;
				if($e instanceOf \Illuminate\Database\QueryException){

					Session::flash('error','Permisson name already Created!');

				}else{
				Session::flash('error','Try again!');

				}
			}

     	return redirect()->route('permisson.list');
 	}

	 public function destroy($id)
	 {

	 	PR::where('permisson_id',$id)->delete();
	 	PRM::where('permisson_id',$id)->delete();
		
		$model  = Permisson::findOrFail($id);
	 	try{
	 			$model->delete();
		 	}catch(\Exception $e)
		 {
		 	throw $e;
		 }
		 	return redirect()->route('permisson.list');

	 }

    protected function modelValidation($request)
    {

    	$rules =['name'=>'required'];

    	$this->validate($request, $rules);

    }

    public function delete_permisson_route($id)
    {

    	$model  = PRM::findOrFail($id);
	 	try{
	 			$model->delete();
		 	}catch(\Exception $e)
		 {
		 	throw $e;
		 }
		 	
		 	return redirect()->route('permisson.list');    		
    }



}
