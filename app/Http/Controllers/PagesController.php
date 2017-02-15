<?php

  namespace App\Http\Controllers;

  use Illuminate\Http\Request;
  use Yajra\Datatables\Datatables;
  use App\Page;
  use Session;
  use DB;
  use Auth;
  use App\LogSystem as LOG;
  use Carbon\Carbon AS TM;

   
  class PagesController extends Controller
  {
    protected $ipAdress;
    public function __construct(Request $request)
    { 
      $this->ipAdress =  $request->ip();
      DB::enableQueryLog();  
    }
    public function index(){

        $plugins = [
                  'css'  => ['datatables'],
                  'js'   => ['datatables','custom'=>['gen-datatables']],
              ];

        return view('pages.index',$plugins);
    }

    public function indexData(){

      $model = Page::with('createdBy')->orderBy('id','desc')->get();
      return Datatables::of($model)
         ->addColumn('selector', '<input type="checkbox" name="items[]" class="icheckbox_minimal-blue item-selector" value="{{$id}}" >')
            ->addColumn('actions',function($model){
                return view('pages._actions',['model' => $model])->render();
            })->editColumn('created_by',function($model){
              return $model->createdBy->name;
            })->editColumn('page_image', function($model){
              return view('pages._image',['model'=>$model])->render();
            })->make(true);
    }

    public function create(){

      $plugins = [
                        'css' => ['wysihtml5','fileupload'],
                        'js'  => ['wysihtml5','fileupload','ckeditor','custom'=>['page-create']]
                    ];
      return view('pages.create',$plugins);
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

    public function __destruct() {
      parent::__destruct();
      // $user = Auth::user();
      //   $uid = $user->id;          

      //   foreach (DB::getQueryLog() as $key => $value){ 

      //     if($value['query'] =="insert into `log_systems` (`user_id`, `type`, `text`, `ip_address`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?, ?)" || $value['query'] =="select * from `log_systems` where `user_id` = ? order by `id` desc limit 1" || $value['query']=="select * from `users` where `users`.`id` = ? limit 1")
      //     {  //Not put in log
      //     }else{
      //           $log    = LOG::orderBy('id','desc')->where('user_id',$uid)->first();
      //           $logAr  = json_decode($log->text,true);
      //           $insertTime = $log->created_at;
      //           $currentTime = TM::now();
      //           $addSecond = $insertTime->addSeconds(30);
      //           if(array_key_exists('query', $logAr))
      //           {
      //             if($addSecond > $currentTime  && $logAr['query'] == $value['query'])
      //             {
      //             // dump('not insert log forthis');
      //             }else{
      //               $Lg             =   new LOG;
      //               $Lg->user_id    =   $uid;
      //               $Lg->type       =   "model";            
      //               $Lg->text       =   json_encode(['query'=>$value['query'] , 'value'=>$value['bindings'] ,'time'=> $value['time'],'email'=>$user->email]);
      //               $Lg->ip_address =   $this->ipAdress;
      //               $Lg->save(); 
      //             }
      //           }else{
      //               $Lg             =   new LOG;
      //               $Lg->user_id    =   $uid;
      //               $Lg->type       =   "model";            
      //               $Lg->text       =   json_encode(['query'=>$value['query'] , 'value'=>$value['bindings'] ,'time'=> $value['time'],'email'=>$user->email]);
      //               $Lg->ip_address =   $this->ipAdress;
      //               $Lg->save(); 
      //           }
      //     }

      //   }    

      }




  }
