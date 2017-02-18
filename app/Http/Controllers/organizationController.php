<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\organization as ORG;
use Session;
use DB;
use Yajra\Datatables\Datatables;

class organizationController extends Controller
{
	public function index(){

        $plugins = [
                  'css'  => ['datatables'],
                  'js'   => ['datatables','custom'=>['gen-datatables']],
              ];

        return view('organization.index',$plugins);
    }

    public function indexData(){

      $model = ORG::orderBy('id','desc')->get();
      return Datatables::of($model)
         ->addColumn('selector', '<input type="checkbox" name="items[]" class="icheckbox_minimal-blue item-selector" value="{{$id}}" >')
            ->addColumn('actions',function($model){
                return view('organization._actions',['model' => $model])->render();
            })->make(true);
    }

    public function create(){

      $plugins = [
                        'css' => ['wysihtml5','fileupload'],
                        'js'  => ['wysihtml5','fileupload','ckeditor','custom'=>['page-create']]
                    ];
      return view('organization.create',$plugins);
    }

    public function store(Request $request){
      $data = array('organization_name' => $request->organization_name);  
      $inserted = ORG::create($data);
      if ($inserted){
        Session::flash('success','Successfully created!');
        return redirect()->route('organization.list'); // view('organization.create');
      }else{
        Session::flash('error','Some thing goes wrong Try again!');
      }
    }

    public function modelValidate($request){

          $rules = [
                    'page_title'  => 'required',
                    'status'      => 'required',
                    'page_slug'   => 'required',
                    'page_image'  => 'image|mimes:jpeg,png,jpg' 
                  ];

          $this->validate($request, $rules);
    }

    public function edit($id){
          try{
              $model = ORG::findOrFail($id);
              $plugins = [
                          'css' => ['wysihtml5','fileupload'],
                          'js'  => ['wysihtml5','fileupload','ckeditor','custom'=>['page-create']],
                          'model' => $model
                        ];

          return view('organization.edit',$plugins);
        }catch(\Exception $e)
        {
              Session::flash('error','Data not found.');
              return redirect()->route('pages.list');          
        }
    }

    public function update(Request $request, $id){

       $model = ORG::where('id',$id)->update(['organization_name'=>$request->organization_name]);
      return redirect()->route('organization.list');  
    }

    public function destroy($id){

      $model = ORG::findOrFail($id);
      try{
          $model->delete();
          Session::flash('success','Successfully deleted!');
          return redirect()->route('organization.list');
        }catch(\Exception $e){
            throw $e;
        }
    }

    public function delAllPages(Request $request){

     

        $sizeOfId = count($request->check);
        for($i=0; $i<$sizeOfId; $i++)
        {
            $id = $request->check[$i];
            $model = Page::findOrFail($id);
            $model->delete();               
        }
            Session::flash('success','Successfully deleted!');

            return 1;// redirect()->route('goals.list');

    }



}
