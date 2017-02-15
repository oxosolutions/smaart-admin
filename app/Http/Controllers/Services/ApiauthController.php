<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
// use App\ApiUsers ;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\UserMeta;
use App\Mail\ForgetPassword;
use App\Mail\AdminRegister;
USE App\Mail\RegisterNewUser;
USE App\Mail\AfterApproveUser;

use Illuminate\Support\Facades\Mail;
use App\GlobalSetting as GS;
use App\Designation as DES;
use App\Ministrie   as min;
use App\Department  as DEP;

class ApiauthController extends Controller
{
   public  function Authenicates(Request $request)
    {
        if(empty ( $request->email )){
            return ['status'=>'error','message'=>'We need to know your e-mail address!'];
        }
        else if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            return ['status'=>'error','message'=>'Invalid email format!'];
        }
        else if($request->password==""){
            return ['status'=>'error','message'=>'We need to know your Password!'];

        }
        else if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            if($user->approved == 0){
                return ['status'=>'error','message'=>'Your account is yet not approved!'];
            }
            $model = UserMeta::select('value')->where(['user_id'=>$user->id,'key'=>'profile_pic'])->first();
            $image = (!empty($model))?$model->value:'';
           
            /*$api_token    =   str_random(20);
            $updateToken  =   User::findOrfail($user->id);
            $updateToken->api_token = $api_token;
            $updateToken->save(); */
            //$user->api_token = $api_token;
            return ['status'=>'success', 'user_detail'=>$user, 'profile_pic'=>asset('profile_pic/'.$image)];
        }else{
            return ['status'=>'error','message'=>'Invalid email or password!'];
        }
 
    }
    public function listUser()
    {   
      $i =0;
      if(Auth::user()->id ==1)
      {
        foreach(User::where('api_token','!=',Auth::user()->api_token)->get() as  $val)
        {
            $arr[$i]['id']=  $val->id;
            $arr[$i]['name']=$val->name;
            $arr[$i]['email']=$val->email;
            $arr[$i]['api_token']=$val->api_token;
            $arr[$i]['role_id']=$val->role_id;
            $arr[$i]['approved']=$val->approved;
            if($val->meta)
            {
               foreach ($val->meta as  $metaValue) {
                    if($metaValue->key == "designation")
                       {
                        $arr[$i]["designation"] = DES::getDesignation($metaValue->value); 
                       }
                    if($metaValue->key == "ministry")
                       {
                            $json = json_decode($metaValue->value);
                            if(min::ministryName($json[0])!=false)
                               {         
                                 $arr[$i]["ministry"] = min::ministryName($json[0]);
                                }
                       }
                       if($metaValue->key == "phone")
                       {
                        $arr[$i]["phone"] = $metaValue->value;
                       }
                       if($metaValue->key == "address")
                       {
                        $arr[$i]["address"] = $metaValue->value;
                       }
                       if($metaValue->key == "department")
                       {
                        $dep = json_decode($metaValue->value);  
                            if(DEP::getDepName($dep[0])!=false)
                            {
                                $arr[$i]["department"] = DEP::getDepName($dep[0]);
                            }
                       }
                       if($metaValue->key == "profile_pic")
                       {
                        $arr[$i]["profile_pic"] = $metaValue->value;
                       }
                }//end meta loop
                $arr[$i]['created_at']=$val->created_at;
            }
        $i++;
        }//end main loop
        return ['status'=>'success','user_list'=>$arr];
      }else{
        return ['status'=>'success','Have not permisson to view'];
      }
    }

    public function editUser($id)
    {
        $user =  User::findOrfail($id);
        $arr['id']=  $user->id;
        $arr['name']=$user->name;
        $arr['email']=$user->email;
        $arr['api_token']=$user->api_token;
        $arr['role_id']=$user->role_id;
        $arr['approved']=$user->approved;
        
        if(count($user->meta) != 0){
            
            foreach ($user->meta as  $metaValue) {

                if($metaValue->key == "designation"){

                    $arr["designation"] = DES::getDesignation($metaValue->value); 
                }
                if($metaValue->key == "ministry"){
                    $json = json_decode($metaValue->value);
                    if(min::ministryName($json[0])!=false){    

                        $arr["ministry"] = min::ministryName($json[0]);
                    }
                }
                if($metaValue->key == "phone"){

                    $arr["phone"] = $metaValue->value;
                }
                if($metaValue->key == "address"){

                    $arr["address"] = $metaValue->value;
                }
                if($metaValue->key == "department"){

                    $dep = json_decode($metaValue->value);  
                    if(DEP::getDepName($dep[0])!=false){

                        $arr["department"] = DEP::getDepName($dep[0]);
                    }
                }
                if($metaValue->key == "profile_pic"){

                    $arr["profile_pic"] = $metaValue->value;
                }
                foreach (DES::all() as $key => $value) {
                   $arr['designationList'] = array('id' => $value->id, 'title' => $value->designation);
                }

            }

        }

        $arr_list['des_list'] = DES::all();
        $arr_list['mi_list'] = min::all();
        $arr_list['de_list'] = DEP::all();
        
        return ['status'=>'success','user_data' => $arr,'data_list' => $arr_list]; 
    }
    public function approveUser($id)
    {      
        try{
                $approved =User::findOrFail($id);
                DB::beginTransaction();
                $approved->approved = 1;
                $approved->save();
                DB::commit();
              Mail::to($approved->email)->send(new AfterApproveUser($approved));

                return ['status'=>'success','message'=>'User Approved.']; 

              }catch(\Exception $e)
                {
                  DB::rollback();
                 return ['error'=>'error','message'=>'Some thing goes wrong. Try Again']; 
                 throw $e;
                }
    } 
    public function unApproveUser($id)
    {
        
            try{
                  $approved = User::findOrfail($id);
                  DB::beginTransaction();
                  $approved->approved = 0;
                  $approved->save();
                  DB::commit();
                return ['status'=>'success','message'=>'User Un-Approve.']; 

                }catch(\Exception $e)
                {
                  DB::rollback();
                  return ['error'=>'error','message'=>'Some thing goes wrong.Try Again']; 
                  throw $e;

                }
    }


    public function updateUser(Request $request)
    {

        if($request->name && $request->email)
        {
            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {

                return ['status'=>'error','message'=>'Invalid email format!'];
             }
             try{
                  $id   = $request->id;
                  DB::beginTransaction();
                  $user = User::find($id);

                  $user->name = $request->name;
                  $user->email = $request->email;
                   if($request->role_id)
                   {         
                     $user->role_id = $request->role_id;
                    }
                  $user->save();
                //meta update script



                $uMeta = UserMeta::where('user_id',$id);
                 $old_profile_pic_val ="";
                if($uMeta->count()>0){
                   
                     foreach ($uMeta->get() as $key => $value) {
                        if($value->key=="profile_pic")
                        {
                            $old_profile_pic_val = $value->value;
                        }
                     } 

                     $uMeta->delete();             
                }

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
                            $designationMeta  = new UserMeta();
                            $designationMeta->user_id = $request->user_list;
                            $designationMeta->key =     "designation";
                            $designationMeta->value   =  $request->designation;
                            $designationMeta->save();
                    }

                    
                    if($request->ministry)   
                        {
                               foreach (json_decode($request->ministry) as $key => $value){

                                    $ministryMetaVal[] = $value;
                                }

                                $minMetaVal = json_encode($ministryMetaVal);
                                $ministryMeta = new UserMeta();
                                $ministryMeta->key = "ministry";
                                $ministryMeta->user_id = $request->user_list;
                                $ministryMeta->value = $minMetaVal;
                                $ministryMeta->save();
                        }  
                     if($request->phone !="")
                        {
                                $phoneMeta = new UserMeta();
                                $phoneMeta->key = "phone";
                                $phoneMeta->user_id = $request->user_list;
                                $phoneMeta->value  =  $request->phone;
                                $phoneMeta->save();
                        }
                        if($request->address !="")
                        {
                                $adrsMeta = new UserMeta();
                                $adrsMeta->key = "address";
                                $adrsMeta->user_id = $request->user_list;
                                $adrsMeta->value  =  $request->address;
                                $adrsMeta->save();
                        }
                    if($request->department)
                        {
                                foreach(json_decode($request->department) as $key => $value){

                                  $depValues[] =  $value;
                                }

                                $depJsonVal =     json_encode($depValues);
                                $departmentMeta  = new UserMeta();
                                $departmentMeta->user_id = $request->user_list;
                                $departmentMeta->key = "department";
                                $departmentMeta->value   =  $depJsonVal;
                                $departmentMeta->save();
                        }  

                         $proPic  = new UserMeta();
                         $path = 'profile_pic';
                    if($request->hasFile('profile_pic')){
                        $filename = date('Y-m-d-H-i-s')."-".$request->file('profile_pic')->getClientOriginalName();
                        $request->file('profile_pic')->move($path, $filename);
                        $proPic->key = "profile_pic";
                        $proPic->user_id = $request->user_list;
                        $proPic->value = $filename;
                        $proPic->save();
                    }
                    elseif($old_profile_pic_val!=""){
                        $proPic->key = "profile_pic";
                        $proPic->user_id  = $request->user_list;
                        $proPic->value    = $old_profile_pic_val;
                        $proPic->save();
                    }    
                  DB::commit();
                return ['status'=>'success','message'=>'Update User detail.']; 

                }catch(\Exception $e)
                {

                  DB::rollback();
                  // return ['status'=>'error','message'=>'Not Update .Try Again']; 
                  throw $e;
                }                
        }
       else{
            return ['status'=>'error','message'=>'fill all required fields!'];
        }   
    }

     protected function modelValidate($request){

        $rules = [
                'name'  => 'min:5|regex:/^[A-Z a-z]+$/',
                'email' => 'required|email|unique:users,email',
                'password' => 'min:6|required',
                'token' => 'required'
        ];

        $this->validate($request, $rules);;
    }


    public function Register(Request $request)
    {
        $api_token    = str_random(20);
        $validate   = $this->validateUserMeta($request);
        if(!$validate){
            return ['status'=>'error','message'=>'Required fields are missing!'];
        }
        if($request->name && $request->email && $request->password )
        {
            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {

                return ['status'=>'error','message'=>'Invalid email format!'];
             }
                try{
                    $user = User::create([
                            'name' => $request->name,
                            'email' => $request->email,
                            'password' => Hash::make($request->password),
                            'api_token' => $api_token
                            ]);

                    $MetaData = [];
                    $path = 'profile_pic';
                    if($request->hasFile('profile_pic')){
                        $filename = date('Y-m-d-H-i-s')."-".$request->file('profile_pic')->getClientOriginalName();
                        $request->file('profile_pic')->move($path, $filename);
                        $MetaData[0]['key'] = 'profile_pic';
                        $MetaData[0]['value'] = $filename;
                        $MetaData[0]['user_id'] = $user->id;
                    }else{
                        $MetaData[0]['key'] = 'profile_pic';
                        $MetaData[0]['value'] = 'user5.ico';
                        $MetaData[0]['user_id'] = $user->id;
                    }
                    $MetaData[1]['key'] = 'phone';
                    $MetaData[1]['value'] = $request->phone;
                    $MetaData[1]['user_id'] = $user->id;
                    $MetaData[2]['key'] = 'address';
                    $MetaData[2]['value'] = $request->address;
                    $MetaData[2]['user_id'] = $user->id;
                    $Departments    = explode(',',$request->departments);
                    $Ministries     = explode(',',$request->ministries);
                    $MetaData[3]['key']     = 'department';
                    $MetaData[3]['value']   = json_encode($Departments);
                    $MetaData[3]['user_id'] = $user->id;
                    $MetaData[4]['key'] = 'ministry';
                    $MetaData[4]['value'] = json_encode($Ministries);
                    $MetaData[4]['user_id'] = $user->id;
                    $MetaData[5]['key'] = 'designation';
                    $MetaData[5]['value'] = $request->designation;
                    $MetaData[5]['user_id'] = $user->id;
                    UserMeta::insert($MetaData);

                    $model = GS::where('meta_key','adminreg_settings')->first();
                    if(!empty($model) && json_decode($model->meta_value)->activate == 'true' && json_decode($model->meta_value)->admin_email != ''){
                        $userDetails['subject'] = json_decode($model->meta_value)->subject;
                        $userDetails['description'] = json_decode($model->meta_value)->description;
                        $userDetails['api_token'] = $api_token;
                        $userDetails['name'] = $request->name;
                        $userDetails['email'] = $request->email;
                        $userDetails['phone'] = $request->phone;
                        $userDetails['ministries'] = $request->ministries;
                        $userDetails['designation'] = $request->designation;
                        $userDetails['department'] = $request->departments;
                        Mail::to(json_decode($model->meta_value)->admin_email)->send(new AdminRegister($userDetails));
                        Mail::to($request->email)->send(new RegisterNewUser($request->name));

                    }
                    return ['status'=>'successful','message'=>'successfully registered!', "token"=>$api_token];
                }catch(\Exception $e){
                    if($e instanceOf \Illuminate\Database\QueryException){
                        return ['status'=>'error','message'=>'Email already in use!'];
                    }else{
                        throw $e;
                        return ['status'=>'error','message'=>'Some thing go wrong!'];
                    }
                }
        }
       else{
            return ['status'=>'error','message'=>'fill all required fields!'];
        }
   }
   public function UserList()
   {
        return User::all();   
   }

   protected function validateUserMeta($request){

       if($request->has('departments') && $request->has('ministries')  && $request->has('designation')){

           return true;
       }else{
           return false;
       }
   }

   public function forgetPassword(Request $request){

        if(!$request->has('email_id')){
            return ['status'=>'error','message'=>'Required fields are missing!'];
        }
        $model = User::where('email',$request->email_id)->first();
        if(empty($model)){
            return ['status'=>'error','message'=>'Email id not found!'];
        }
        $userName = $model->name;
        $token = str_random(60);
        $model->reset_token = $token;
        $model->save();
        $userDetails['name'] = $userName;
        $userDetails['token'] = $token;

        $model = GS::where('meta_key','forget_settings')->first();
        if(empty($model)){
            return ['status'=>'success','message'=>'Password Changed, But email not configured yet!'];
        }elseif(json_decode($model->meta_value)->activate != 'true'){
            return ['status'=>'success','message'=>'Password Changed, But email not configured yet!'];
        }else{
            $userDetails['subject'] = json_decode($model->meta_value)->subject;
            $userDetails['description'] = json_decode($model->meta_value)->description;
            Mail::to($request->email_id)->send(new ForgetPassword($userDetails));
        }
        return ['status'=>'success','message'=>'New password sent on your email id!'];
   }

   public function validateForgetPassToken($token){

        $model = User::where('reset_token',$token)->first();

        if(empty($model)){
            return ['status'=>'error','message'=>'Invalid token!'];
        }else{
            return ['status'=>'success','message'=>'Valid token!'];
        }
   }

   public function resetUserPassword(Request $request){

        $validate = $this->validateChangePassword($request);
        if(!$validate){
            return ['status'=>'error','message'=>'Required fields are missing!'];
        }

        $model = User::where('reset_token',$request->reset_token)->first();
        $model->password = Hash::make($request->newpassword);
        $model->reset_token = '';
        $model->save();
        return ['status'=>'success','message'=>'Password chnaged successly!'];
   }

   protected function validateChangePassword($request){

        if($request->has('newpassword') && $request->has('confpassword') && $request->has('reset_token')){

            if($request->newpassword == $request->confpassword){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
   }
   public function deleteUser($id){
        if(empty($id) || $id == '' ){
            return ['status'=>'error','message'=>'Id not found'];
        } else{
            $deluser = User::where('id',$id)->delete();
            if ($deluser){
                return ['status' => 'success' , 'message' => 'User Delete successfully'];
            }
        }
   }

 
}