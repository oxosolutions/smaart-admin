 <?php

namespace App\Http\Controllers;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
Use App\Role;
Use DB;
Use Session;
Use App\PermissonRole as PR;

class RoleController extends Controller
{
    //

    public function index()
    {
    		$plugins = [ 'css'=> ['datatables'],
    					 'js'=>['datatables', 'custom'=>['gen-datatables'] ]	];

    		return view('role.index', $plugins);
    }

    public function list_role()
    {

    	   $model = Role::get();
			return Datatables::of($model)
			->addColumn('actions',function($model){                 
					return view('role._actions',['model'=>$model])->render(); 
			    })
			->make(true);     
	}

    public function create()
    {   
    	return view('role.create');
    }

    public function store(Request $request)
    {
		$this->modelValidation($request);
		DB::beginTransaction();
		try{
    		$role = new Role($request->except(['_token']));
    		
    		$role->save();
    		DB::commit();
    	Session::flash('success',"Role Successful Created!");

    	}catch(\Exception $e)
    	{
    		echo "roll back";
    		DB::rollback();	
    	}

         return redirect()->route('role.list');
    }

    public function edit($id)
    {
		  $role = Role::findOrFail($id);
		  return view('role.edit',['model'=>$role]);
    }

    public function update(Request $request, $id)
    {
    		$role = Role::findOrFail($id);
            $this->modelValidation($request);
    		DB::beginTransaction();
    		try{
        		$role->fill($request->except(['_token']));
        		$role->save();
        		DB::commit();
                Session::flash('success',"Updated Role Successful Created!");
            }catch(\Exception $e)
        	{
        		DB::rollback();
        	}

            return redirect()->route('role.list');
    }

    public function destroy($id)
    {
            $model  = Role::findOrFail($id);
     	      try{
                    PR::where('role_id',$id)->delete();
     	              $model->delete();
    	        }catch(\Exception $e)
    	       {
    	 	     throw $e;
    	       }
    	 	return redirect()->route('role.list');
    }

    protected function modelValidation($request)
    {
            $rules =['name'=>'required'];
            $this->validate($request, $rules);
    }

}
