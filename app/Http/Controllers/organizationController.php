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

        $this->modelValidate($request);

        DB::beginTransaction();
        try{
            $model = new Page($request->except(['_token']));
            $model->created_by = Auth::user()->id;
            $path = 'pages_data';
            if($request->hasFile('page_image')){

                $filename = date('Y-m-d-H-i-s')."-".$request->file('page_image')->getClientOriginalName();

                $request->file('page_image')->move($path, $filename);

                $model->page_image = $filename;
            }
            $model->save();
            DB::commit();
            Session::flash('success','Successfully created!');
            return redirect()->route('pages.list');
        }catch(\Exception $e){

            DB::rollback();
            throw $e;
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
              $model = Page::findOrFail($id);
              $plugins = [
                          'css' => ['wysihtml5','fileupload'],
                          'js'  => ['wysihtml5','fileupload','ckeditor','custom'=>['page-create']],
                          'model' => $model
                        ];

          return view('pages.edit',$plugins);
        }catch(\Exception $e)
        {
              Session::flash('error','Data not found.');
              return redirect()->route('pages.list');          
        }
    }

    public function update(Request $request, $id){

        $model = Page::findOrFail($id);
        $this->modelValidate($request);
        DB::beginTransaction();
        try{
            $model->fill($request->except(['_token']));
            $model->created_by = Auth::user()->id;
            $path = 'pages_data';
          if($request->hasFile('page_image')){

              $filename = date('Y-m-d-H-i-s')."-".$request->file('page_image')->getClientOriginalName();
              $request->file('page_image')->move($path, $filename);
              $model->page_image = $filename;
            }
            $model->save();
            DB::commit();
            Session::flash('success','Successfully updated!');


            $model = Page::findOrFail($id);
              $plugins = [
                          'css' => ['wysihtml5','fileupload'],
                          'js'  => ['wysihtml5','fileupload','ckeditor','custom'=>['page-create']],
                          'model' => $model
                        ];

          return view('pages.edit',$plugins);
           // return redirect()->route('pages.edit');
          }catch(\Exception $e){
            DB::rollback(); 
            throw $e;
      }
    }

    public function destroy($id){

        $model = Page::findOrFail($id);
      try{
          $model->delete();
          Session::flash('success','Successfully deleted!');
          return redirect()->route('pages.list');
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
