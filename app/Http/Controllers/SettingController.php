<?php

namespace App\Http\Controllers;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\RolePermissonMapping as RP;
use App\Role;
use App\Permisson;
use App\PermissonRole;
use Session;
Use DB;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    //

    public function test()
    {
    	// $user = Auth::user();
    	// return $user;
    	echo "hello its working";
    }
    public function index()
    {
    		
    	$plugins = [ 'css'=> ['datatables'],
    					 'js'=>['datatables', 'custom'=>['gen-datatables'] ]	];
    	return view('setting.index', $plugins);

    }

    public function list_setting()
    {
		$model = Role::get();
    	// $model =	DB::table('roles as r')->select('r.*')
    	// 	->rightJoin('permisson_roles as pr','pr.role_id','=','r.id')->get();
		return Datatables::of($model)
		->addColumn('actions',function($model){                 
		return view('setting._actions',['model'=>$model])->render(); 
		})
		->make(true);

    }

    public function edit($id)
    {

		$data = DB::table('roles as r')
    	->select('prm.route_for','prm.route_name','pr.id as prid','r.id as rid','r.name as rname','pr.read','pr.write','pr.delete','pr.other','p.name as pname','p.id as pid')
		->leftJoin('permisson_roles as pr','r.id','=','pr.role_id')
		->leftJoin('permissons as p','p.id','=','pr.permisson_id')
		->leftJoin('permisson_route_mappings as prm', 'prm.permisson_id','=','p.id')
		->where('r.id',$id)->get();



		$role = Role::findOrFail($id);
		return view('setting.edit',['model'=>$role,'role_permisson'=>$data]);
		

    }
    public function update(Request $request)
    {
		//dd($request);	
		PermissonRole::where('role_id',$request->rid)->delete();

		if($request->permisson_id)
		{

				foreach ($request->permisson_id as $key => $value) {
						$permissonId[] = $key;
					$pr =	new PermissonRole($request->except(['_token','read']));
					$pr->role_id =  $request->rid;
					 $pr->permisson_id = $key;
					$pr->read=0;
					$pr->write=0;
					$pr->delete=0;
					$pr->other=0;
					$permSize = count($value);
					for($i=0; $i<$permSize; $i++){
					$field = $value[$i];
					$pr->$field =1;
					}
					$pr->save();
				}
					$perId = Permisson::select('id')->whereNotIn('id', $permissonId)->get();

		}
		else{
				$perId =	Permisson::select('id')->get();
		}
					
			foreach ($perId as  $value) {
					 $value->id;
					$pr =	new PermissonRole($request->except(['_token','read']));
					$pr->role_id =  $request->rid;
					$pr->permisson_id = $value->id;
					$pr->read=0;
					$pr->write=0;
					$pr->delete=0;
					$pr->other=0;
					$pr->save();	
			}
		
				return redirect()->route('setting.list');
    	
    }

    public function view($id)
    {
    	
		$countRole = DB::table('permisson_roles as pr')->where('pr.role_id',$id)->count();
		if($countRole==0)
		{

			Session::flash('error','No Permisson set yet');
			return redirect()->route('setting.list');
		}

		$data = DB::table('roles as r')->select('r.id as rid','r.name as rname','pr.read','pr.write','pr.delete','p.name as pname')
		->leftJoin('permisson_roles as pr','r.id','=','pr.role_id')
		->leftJoin('permissons as p','p.id','=','pr.permisson_id')
		->where('r.id',$id)->get();
		return view('setting.view', ['role_permisson'=> $data]);

	}

    public function create()
    {
    return	view('setting.create');
    }

    public function store(Request $request)
    {
		$this->modelValidation($request);
		if($request->permisson_id)
		{
			DB::beginTransaction();

			try{
				foreach ($request->permisson_id as $key => $value) {

					$pr =	new PermissonRole($request->except(['_token','read']));
					$pr->role_id =  $request->role_id;
					$pr->permisson_id = $key;
					$pr->read=0;
					$pr->write=0;
					$pr->delete=0;
					$permSize = count($value);
					for($i=0; $i<$permSize; $i++){
					$field = $value[$i];
					$pr->$field =1;
					}
					$pr->save();
					DB::Commit();

				}
			}catch(\Exception $e)
			{
			throw $e;
			}
		}
		else{
		Session::flash('error','Must Assign Permisson. ');
		return redirect()->route('setting.create');
		}
				return redirect()->route('setting.list');
	
    }
    

    public function modelValidation($request)
    {

    	$rules = ['role_id'=>'required'];
		$this->validate($request, $rules);
    }
}
