<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\VirtualTableGenController;
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
use App\organization as org;
use Session;
use App\UserMeta as um;
use App\Role;

class ApiauthController extends VirtualTableGenController
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
            Session::put('org_id', $user->organization_id);
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
//role list
   public function roleList()
    {
      $roles = Role::role_list();
      return ['status'=>'success', 'roles'=>$roles];
    }
    public function listUser()
    {   
      $i =0;
      $role_id = Auth::user()->role_id;
      if($role_id == 1)
      {
        $org_id  = Auth::user()->organization_id;
        $org_user  = User::orderBy('id','Desc')->where(['organization_id'=>$org_id,'role_id'=>2])->get();
      }
      else if($role_id == 3){
        $org_user  = User::orderBy('id','Desc')->whereNotIn('role_id',[3])->get();
      }
      else{
        return ['status'=>'error','Have not permisson to view'];
      }
        $arr = array();
        foreach($org_user as  $val)
        {
            $arr[$i]['id']                =   $val->id;
            $arr[$i]['name']              =   $val->name;
            $arr[$i]['email']             =   $val->email;
            $arr[$i]['api_token']         =   $val->api_token;
            $arr[$i]['role_id']           =   $val->role_id;
            $arr[$i]['approved']          =   $val->approved;
            if($role_id == 1)
            {
              $arr[$i]['organization_name'] =   $val->organization->organization_name;
            }

            if($val->meta)
            {
               foreach ($val->meta as  $metaValue) {
                   
                       if($metaValue->key == "phone")
                       {
                        $arr[$i]["phone"] = $metaValue->value;
                       }
                       if($metaValue->key == "address")
                       {
                        $arr[$i]["address"] = $metaValue->value;
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
      // }else{
      //   return ['status'=>'error','Have not permisson to view'];
      // }
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
        $arr['organization_id']=$user->organization_id;
        if(count($user->meta) != 0){
            foreach ($user->meta as  $metaValue) {
             if($metaValue->key == "phone"){

                    $arr["phone"] = $metaValue->value;
                }
                if($metaValue->key == "address"){

                    $arr["address"] = $metaValue->value;
                }
               
                if($metaValue->key == "profile_pic"){

                    $arr["profile_pic"] = $metaValue->value;
                }
                
            }
      }
      return ['status'=>'success','user_data' => $arr]; 
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
                 // $user->organization_id = $request->organization_id;
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
                  throw $e;
                }                
        }
       else{
            return ['status'=>'error','message'=>'fill all required fields !'];
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
      //dd($request->request);
        $role = 2;
        $org_status ="used";

try{
      DB::beginTransaction();
      if($request->organization =="others")
      {
       //  $checkOrg = UserMeta::where(['key'=>'organization','value'=>$request->organization_name]);//->count();
         // User::where('organization_id',)
        $org = org::where('organization_name',$request->organization_name);
          if($org->count()==0)
          {
            $data = array('organization_name' => $request->organization_name, 'activation_code'=>rand(15,100000));  
            $inserted = org::create($data);
            $organization_id = $inserted->id;
            $role = 1;
             $org_status ="new";
          }
          else{
                $organization_id  = $org->first()->id;
                $org_status ="used";
          }
      }else{
        $organization_id  = $request->organization;
            $org_status ="used";
      }  
        
        $api_token    = str_random(20);
        // $validate   = $this->validateUserMeta($request);
        // if(!$validate){
        //     return ['status'=>'error','message'=>'Required fields are missing!'];
        // }
        if($request->name && $request->email && $request->password )
        {
            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {

                return ['status'=>'error','message'=>'Invalid email format!'];
             }
                //try{
                    $user = User::create([
                            'name' => $request->name,
                            'email' => $request->email,
                            'password' => Hash::make($request->password),
                            'app_password' => $request->password,
                            'role_id'=>$role,
                            'organization_id'=>$organization_id,
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

                     // $MetaData[3]['key'] = 'organization';
                     // $MetaData[3]['value'] = $organization_id;
                     // $MetaData[3]['user_id'] = $user->id;
                    
                    UserMeta::insert($MetaData);

                    DB::commit();
                    if($org_status=="new")
                    {
                      $this->generateTable($organization_id);
                    }

                    $model = GS::where('meta_key','adminreg_settings')->first();
                    if(!empty($model) && json_decode($model->meta_value)->activate == 'true' && json_decode($model->meta_value)->admin_email != ''){
                        $userDetails['subject'] = json_decode($model->meta_value)->subject;
                        $userDetails['description'] = json_decode($model->meta_value)->description;
                        $userDetails['api_token'] = $api_token;
                        $userDetails['name'] = $request->name;
                        $userDetails['email'] = $request->email;
                        $userDetails['phone'] = $request->phone;
                       
                        Mail::to(json_decode($model->meta_value)->admin_email)->send(new AdminRegister($userDetails));
                        Mail::to($request->email)->send(new RegisterNewUser($request->name));

                    }
                    return ['status'=>'successful','message'=>'successfully registered!', "token"=>$api_token];
                // }catch(\Exception $e){
                //     if($e instanceOf \Illuminate\Database\QueryException){
                //         return ['status'=>'error','message'=>'Email already in use!'];
                //     }else{
                //         throw $e;
                //         return ['status'=>'error','message'=>'Some thing go wrong!'];
                //     }
                // }
        }
       else{
            return ['status'=>'error','message'=>'fill all required fields!'];
        }
      
}catch(\Exception $e)
{
   if($e instanceOf \Illuminate\Database\QueryException){
                        return ['status'=>'error','message'=>'Email already in use!'];
                    }else{
                        throw $e;
                        return ['status'=>'error','message'=>'Some thing go wrong!'];
                    }
  
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

   public function UserSettingSave(Request $request)
   {

    try{
      $user_id = Auth::user()->id;
      $query = um::where(['user_id'=>$user_id,'key'=>'user_settings']);
     if($query->count()==0)
     {
      $setting = new um();
      $setting->key = 'user_settings';
      $setting->value = $request['user_settings'];
      $setting->user_id = $user_id;
      $setting->save();
      return ['status'=>"success", "message"=>"user setting saved successfully"];
    }
    else{ 
         $query->update(['value'=>$request['user_settings']]);

        return ['status'=>"success", "message"=>"user setting updated successfully"];
    }
    
    }catch(\Exception $e)
    {
      throw $e;
      //return ['status'=>"error", "message"=>"Something went wrong"];
    }

      
    }
   public function UserSettingGet()
   {
      try{
          $uId = Auth::user()->id;
          $settings = um::select(['id','key','value','user_id'])->where(['user_id'=>$uId, 'key'=>'user_settings'])->first();
          return ['status'=>"success", "response"=> $settings];
     }catch(\Exception $e)
     {
        throw $e;     
     }
   }

   
  public function UserSettingUpdate()
  {
    um::where(['user_id'=>Auth::user()->id,'key'=>'user_settings'])->update(['value'=>$request['user_settings']]);

  }
   

 
}