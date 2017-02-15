<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use DB;
use App\User;
use App\UserMeta as UM;
use App\Designation as DES;
use Session;
use App\Ministrie as MIN;
use App\Department as DEP;
use Hash;
use Auth;
use App\GlobalSetting as GS;
use Illuminate\Support\Facades\Mail;
use App\Mail\AfterApproveUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\LogSystem as LOG;
use Carbon\Carbon AS TM;

class ApiusersController extends Controller
{
    public $ipAdress;
    public function __construct(Request $request)
    { 
       $this->ipAdress =  $request->ip();
      DB::enableQueryLog();  
    }

    public function index(){
      $plugins = [
                	'css' => ['datatables'],
                	'js'  => ['datatables','custom'=>['gen-datatables']]
               ];
	     return view('apiusers.index',$plugins);
    }

    public function get_users(){
        $model = Auth::user()->id;
        $model = User::where('id','!=',$model)->orderBy('id','desc')->get();
             return Datatables::of($model)
             ->addColumn('selector', '<input type="checkbox" name="items[]" class="icheckbox_minimal-blue item-selector" value="{{$id}}" >')
              ->addColumn('actions',function($model){
            return view('apiusers._actions',['model'=>$model])->render();
        })
        ->make(true);
    }

    public function create(){
      $plugins = [
                  'css' => ['fileupload','select2'],
                  'js'  => ['fileupload','select2','custom'=>['api-user']]
                ];
      return view('apiusers.create',$plugins);
    }

    public function store(Request $request){
       
          $role_id = (int) $request->role_id[0];
          $this->modelValidate($request);

         DB::beginTransaction();
          try{
                User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role_id' => $role_id,
                    'api_token' => $request->token
                ]);
                DB::commit();
                Session::flash('success','Successfully created!');

                return redirect()->route('api.users');
            }catch(\Exception $e){

                DB::rollback();
                throw $e;
              }
    }

    public function delete($id)
    { 
      try{
        $model  = User::findOrFail($id);
        UM::where('user_id',$id)->delete();
        $model->delete();
      }catch(\Exception $e){
        throw $e;
      }
        return redirect()->route('api.users');
    }

    protected function modelValidate($request){

            $rules = [

                'name'  => 'min:5|regex:/^[A-Z a-z]+$/',
                'email' => 'required|email|unique:users,email',
                'password' => 'min:6|required',
                'token' => 'required'
                    ];

        $this->validate($request, $rules);
    }

     protected function metaValidate($request){

             $rules = ['phone'=>'min:10|max:12'];

        // $rules = [               
        //           'address'=> 'required',
        //           'ministry'=>'required',
        //           'department'=>'required',
        //           'designation'=>'required',
        //           'phone'=>'required|min:10|max:12',
        //           'profile_pic'=>'required'
        //       ];

        $this->validate($request, $rules);
    }

    protected function editmetaValidate($request){

        $rules = [               
                  'address'=> 'required',
                  'ministry'=>'required',
                  'department'=>'required',
                  'designation'=>'required',
                  'phone'=>'required|min:10|max:12',
                ];

        $this->validate($request, $rules);
    }

    public function  createUserMeta($user_id)
    {
        try{
            User::findOrFail($user_id);
             $plugins = [
                        'css' => ['fileupload','select2'],
                        'js'  => ['fileupload','select2','custom'=>['api-user']],
                        'user_id' => $user_id,
                        ];
            return view('apiusers.fill_user_meta',$plugins);
          }catch(ModelNotFoundException $e)
            {
                Session::flash('error','No data found for this.');
                return redirect()->route('api.users');

            }

    }


    public function storeUserMeta( Request $request){

            $this->metaValidate($request);
            if(!$request->ministry && !$request->hasFile('profile_pic') && !$request->designation && !$request->department && $request->phone =="" &&  $request->address =="" )
            {
                    return redirect()->route('api.create_users_meta',$request->user_list);
            }

            
          DB::beginTransaction();
            try{//create new designation if not exist

              if($request->designation)
              {
                 if(!is_numeric($request->designation))
                 {
                       $chkDes = DES::where(['designation'=>$request->designation])->get()->count();
                       if($chkDes==0){
                          $newDes =  new DES();
                          $newDes->designation = $request->designation;
                          $newDes->save();
                          $request->designation = $newDes->id;
                       }
                  }
                $designationMeta  = new UM();
                $designationMeta->user_id = $request->user_list;
                $designationMeta->key =     "designation";
                $designationMeta->value   =  $request->designation;
                $designationMeta->save();
              }

              if($request->ministry)
              {
                foreach ($request->ministry as $key => $value){

                      $ministryMetaVal[] = $value;
                  }

                $minMetaVal = json_encode($ministryMetaVal);
                $ministryMeta = new UM();
                $ministryMeta->key = "ministry";
                $ministryMeta->user_id = $request->user_list;
                $ministryMeta->value = $minMetaVal;
                $ministryMeta->save();
              }

              if($request->phone!="")
              {
                $phoneMeta = new UM();
                $phoneMeta->key = "phone";
                $phoneMeta->user_id = $request->user_list;
                $phoneMeta->value  =  $request->phone;
                $phoneMeta->save();
              }
              if($request->address)
              {
                $adrsMeta = new UM();
                $adrsMeta->key = "address";
                $adrsMeta->user_id = $request->user_list;
                $adrsMeta->value  =  $request->address;
                $adrsMeta->save();
              }

              if($request->department)
              {
                foreach($request->department as $key => $value){

                   $depValues[] =  $value;
                }

                $depJsonVal =     json_encode($depValues);
                $departmentMeta  = new UM();
                $departmentMeta->user_id = $request->user_list;
                $departmentMeta->key = "department";
                $departmentMeta->value   =  $depJsonVal;
                $departmentMeta->save();
              }
                

                $proPic  = new UM();
                $path = 'profile_pic';
                if($request->hasFile('profile_pic')){

                    $filename = date('Y-m-d-H-i-s')."-".$request->file('profile_pic')->getClientOriginalName();
                    $request->file('profile_pic')->move($path, $filename);
                    $proPic->key = "profile_pic";
                    $proPic->user_id = $request->user_list;
                    $proPic->value = $filename;
                    $proPic->save();
                }
                DB::commit();
                Session::flash('success','Successfully created User Meta!');
                return redirect()->route('api.users');

            }catch(\Exception $e){

                DB::rollback();
                throw $e;
            }
        }

        public function userDetail($id)
        {   
          try{
              $userDetail =  User::findOrfail($id);
              $userMeta =  UM::where('user_id' , $id)->get();
              return view('apiusers.user_detail', ['user_detail'=>$userDetail, 'user_meta' => $userMeta]); 
            }catch(\Exception $e)
            {

              Session::flash('error','No data found for this.');
              return redirect()->route('api.users');
              
            }
                  
        }
        public function editUserDetails($id)
        { 
          try{
              $userDetail =  User::where('id' , $id)->get();
              $userMeta =  UM::where('user_id' , $id)->get();
              return view('apiusers.editProfile', ['user_detail'=>$userDetail, 'user_meta' => $userMeta]); 
            }catch(\Exception $e)
            {

              Session::flash('error','No data found for this.');
              return redirect()->route('api.users');
              
            }
                  
        }

        public function edit($id) {
          try{
                $model = User::findOrfail($id);
                return view('apiusers.edit',['model'=>$model]);
              }catch(\Exception $e)
              {
                 Session::flash('error','No data found for this.');              
                 return redirect()->route('api.users');

                throw $e;
              }
        }
        public function update(Request $request, $id)
        {
            $role_id = (int) $request->role_id[0];
            
            try{
                  $user = User::findOrfail($id);
                  DB::beginTransaction();
                  $user->name = $request->name;
                  $user->email = $request->email;
                   if(!empty($request->new_password))
                   {
                       $user->password = Hash::make($request->new_password);
                   }            
                  $user->role_id = $role_id;
                  $user->api_token = $request->token;
                  $user->save();
                  DB::commit();
                  Session::flash('success','User updated Successfully');
                }catch(\Exception $e)
                {
                  DB::rollback();
                  throw $e;
                }

           return redirect()->route('api.users');
        }

        public function approved($id)
        {
            
            try{
                $approved = User::findOrfail($id);
                DB::beginTransaction();
                $approved->approved = 1;
                $approved->save();
                DB::commit();
              Mail::to($approved->email)->send(new AfterApproveUser($approved));

              }catch(\Exception $e)
                {
                  DB::rollback();
                  throw $e;
                }
                return redirect()->route('api.users');
                
        }
        public function unapproved($id)
        {
            
            try{
                  $approved = User::findOrfail($id);
                  DB::beginTransaction();
                  $approved->approved = 0;
                  $approved->save();
                  DB::commit();
                }catch(\Exception $e)
                {
                  DB::rollback();
                  throw $e;
                }
            return redirect()->route('api.users');
        }
        public function editmeta($id)
        { 
          try{     
              $chkmeta =  UM::where('user_id',$id)->count(); 
              if($chkmeta ==0)
              {   
                  Session::flash('error','No data found for this.');
                  return redirect()->route('api.users');
              }
              $meta = UM::select('id','key','value')->where('user_id',$id)->get();//->where();
             $depChk = $minChk = $desChk = $picChk = $phChk = $adrsChk =0;
             foreach ($meta as  $value) {
                    if($value->key == "address")
                      { 
                        $adrsChk =1;
                        $address = $value->value;
                      }elseif($adrsChk ==0){ $address ="";}
                    if($value->key == "phone")
                      {
                        $phChk = 1;
                        $phone = $value->value;
                      }elseif($phChk ==0){ $phone ="";}
                    if($value->key == "designation")
                      {
                        $desChk =1;
                        $designation = $value->value;
                      }elseif($desChk ==0){ $designation =""; }
                   if($value->key == "profile_pic")
                      { 
                        $picChk =1;
                        $profile_pic = $value->value;
                      }elseif($picChk ==0){ $profile_pic ="";}             
                    if($value->key == "ministry")
                      { 
                        $minData =json_decode($value->value);
                        $minCount = count($minData);
                        
                            for($i=0; $i<$minCount; $i++)
                            {
                                $minChk  =1;
                                $mdata[$minData[$i]]=$minData[$i]; 
                            }
                          
                        }elseif($minChk==0){ $mdata['']=''; }
                      if($value->key == "department")
                      { 
                        $depData =json_decode($value->value);
                        $depCount = count($depData);
                        for($j=0; $j<$depCount; $j++)
                        { 
                            $depChk =1;
                            $dep[$depData[$j]]=$depData[$j]; 
                        }
                      }elseif($depChk==0){ $dep[''] ='';  }
             }
                 $plugins = [
                              'css'     =>  ['fileupload','select2'],
                              'js'      =>  ['fileupload','select2','custom'=>['api-user']],
                              'model'   =>  @$meta,
                              'id'      =>  $id,
                              'minData' =>  @$mdata,
                              'address' =>  @$address,
                              'department' => @$dep,
                              'phone'   =>    @$phone,
                              'designation' =>  @$designation,
                              'profile_pic' =>  @$profile_pic
                            ];
                return view('apiusers.editmeta',$plugins);
            }catch(\Exception $e)
            {
                Session::flash('error','No data found for this.');
                return redirect()->route('api.users');

            }
        }
        public function updatemeta(Request $request , $id)
        {
         $this->metaValidate($request);
            if(!$request->ministry && !$request->hasFile('profile_pic') && !$request->designation && !$request->department && $request->phone =="" &&  $request->address =="" )
            {
                    UM::where('user_id',$id)->delete(); 
                  return redirect()->route('api.create_users_meta',$id);
            }
          DB::beginTransaction();
          try{
              UM::where('user_id',$id)->delete();
              $request->user_list = $id;
            if($request->designation)
            {
              if(!is_numeric($request->designation))
                   {
                         $chkDes = DES::where(['designation'=>$request->designation])->get()->count();
                         if($chkDes==0){
                            $newDes =  new DES();
                            $newDes->designation = $request->designation;
                            $newDes->save();
                            $request->designation = $newDes->id;
                         }
                    }
                    $designationMeta  = new UM();
                    $designationMeta->user_id = $request->user_list;
                    $designationMeta->key =     "designation";
                    $designationMeta->value   =  $request->designation;
                    $designationMeta->save();
            }
            if($request->ministry)   
            {
                   foreach ($request->ministry as $key => $value){

                        $ministryMetaVal[] = $value;
                    }

                    $minMetaVal = json_encode($ministryMetaVal);
                    $ministryMeta = new UM();
                    $ministryMeta->key = "ministry";
                    $ministryMeta->user_id = $request->user_list;
                    $ministryMeta->value = $minMetaVal;
                    $ministryMeta->save();
            }
            if($request->phone !="")
            {
                    $phoneMeta = new UM();
                    $phoneMeta->key = "phone";
                    $phoneMeta->user_id = $request->user_list;
                    $phoneMeta->value  =  $request->phone;
                    $phoneMeta->save();
            }
            if($request->address !="")
            {
                    $adrsMeta = new UM();
                    $adrsMeta->key = "address";
                    $adrsMeta->user_id = $request->user_list;
                    $adrsMeta->value  =  $request->address;
                    $adrsMeta->save();
            }

            if($request->department)
            {
                    foreach($request->department as $key => $value){

                       $depValues[] =  $value;
                    }

                    $depJsonVal =     json_encode($depValues);
                    $departmentMeta  = new UM();
                    $departmentMeta->user_id = $request->user_list;
                    $departmentMeta->key = "department";
                    $departmentMeta->value   =  $depJsonVal;
                    $departmentMeta->save();
            }
                
                    $proPic  = new UM();
                    $path = 'profile_pic';
                    if($request->hasFile('profile_pic')){

                        $filename = date('Y-m-d-H-i-s')."-".$request->file('profile_pic')->getClientOriginalName();
                        $request->file('profile_pic')->move($path, $filename);
                        $proPic->key = "profile_pic";
                        $proPic->user_id = $request->user_list;
                        $proPic->value = $filename;
                        $proPic->save();
                    }
                    else{
                        $proPic->key = "profile_pic";
                        $proPic->user_id  = $request->user_list;
                        $proPic->value    = $request->current_pic;
                        $proPic->save();
                    }
                    DB::commit();
                    Session::flash('success','User Meta Updated Successfully.');
                }catch(\Exception $e)
                {
                  DB::rollback();

                }    
                return redirect()->route('api.users');

        }

        public function approveUser($from = 0, $api_token = null){
            if($from == 'email' && $api_token != null){
               
                $gs_model = GS::where('meta_key','user_approvel_settings')->first();
                $settings = json_decode($gs_model->meta_value);
                if($settings->activate == 'true'){
                    $userModel = User::where('api_token',$api_token)->first();
                    $userDetails = [];
                    $userDetails['email'] = $userModel->email;
                    $userDetails['name'] = $userModel->name;
                    $userDetails['subject'] = $settings->subject;
                    $userDetails['desc'] = $settings->description;
                    Mail::to($userModel->email)->send(new AfterApproveUser($userDetails));
                }
                $model = User::where('api_token',$api_token)->update(['approved'=>1]);
                if($model){
                    return view('approvel.approved');
                }else{
                    return view('approvel.approved');
                }
            }else{
                
                return view('approvel.not-approved');
            }
        }

  
    public function updateProfile(Request $request)
    {
      User::where('id',Auth::user()->id)->update(["name"=>$request->name]);
      $user_meta = UM::where('user_id',Auth::user()->id)->get();
      $data[] = "";
      foreach ($user_meta as $key => $value) {
        $data[] = $value->key;
      }
     
       if ($request->password != "" || !empty($request->password)){
        User::where('id',Auth::user()->id)->update(['password' => Hash::make($request->password)] );
       }
       if (in_array('phone',$data)){
          UM::where(['key'=> 'phone','user_id'=>Auth::user()->id])->update(["value"=>$request->phone]);
       }else{
          UM::create(['key'=> 'phone','user_id'=>Auth::user()->id,"value"=>$request->phone]);
       }
       
       if (in_array('address',$data)){
          UM::where(['key'=> 'address','user_id'=>Auth::user()->id])->update(["value"=>$request->address]);
       }else{
          UM::create(['key'=> 'address','user_id'=>Auth::user()->id,"value"=>$request->address]);
       }
       
       if (in_array('ministry',$data)){
          UM::where(['key'=> 'ministry','user_id'=>Auth::user()->id])->update(["value"=>json_encode($request->ministry)]);
       }else{
          UM::create(['key'=> 'ministry','user_id'=>Auth::user()->id,"value"=>json_encode($request->ministry)]);
       }
       
       if (in_array('department',$data)){
          UM::where(['key'=> 'department','user_id'=>Auth::user()->id])->update(["value"=>json_encode($request->department)]);
       }else{
          UM::create(['key'=> 'department','user_id'=>Auth::user()->id,"value"=>json_encode($request->department)]);
       }
       
       if (in_array('designation',$data)){
          UM::where(['key'=> 'designation','user_id'=>Auth::user()->id])->update(["value"=>$request->designation]);
       }else{
          UM::create(['key'=> 'designation','user_id'=>Auth::user()->id,"value"=>$request->designation]);
       }
       
      return redirect()->route('home');
  }

  public function delAllUser(Request $request){

     

        $sizeOfId = count($request->check);
        for($i=0; $i<$sizeOfId; $i++)
        {
            $id = $request->check[$i];
            $model = User::findOrFail($id);
            $model->delete();               
        }
            Session::flash('success','Successfully deleted!');

            return 1;// redirect()->route('goals.list');

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
