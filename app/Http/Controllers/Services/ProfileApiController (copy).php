<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Ministrie as MIN;
use App\Department as DP;
use App\Designation as DS;
use Hash;
use Auth;
use App\UserMeta;
class ProfileApiController extends Controller
{
    public function getUserProfile(Request $request){


        $model = $request->user();        
        $responseArray = [];
        $responseArray['departments'] = [];
        $responseArray['ministries'] = [];
        $responseArray['name']  = $model->name;
        $responseArray['email'] = $model->email;
        $responseArray['phone'] = $model->phone;

        $responseArray['token'] = $model->api_token;
        $responseArray['profile_pic'] = asset('profile_pic/profile.jpg');//'profile.jpg';
        if($model->meta != null){

            foreach ($model->meta as $metaKey => $metaValue) {
                switch($metaValue->key){
                    case'ministry':
                        $ministries = json_decode($metaValue->value);

                        $index = 0;
                        try{
                            foreach($ministries as $minKey => $minVal){
                                $MinisModel = MIN::find($minVal);
                                $responseArray['ministries'][$index]['id'] = $MinisModel->id;
                                $responseArray['ministries'][$index]['ministry_title'] = $MinisModel->ministry_title;
                                $index++;
                            }
                        }catch(\Exception $e){
                            $responseArray['ministries'][0]['id'] = '';
                            $responseArray['ministries'][0]['ministry_title'] = '';
                        }
                    break;
                    case'department':
                        $departments = json_decode($metaValue->value);
                        $index = 0;
                        try{
                            foreach($departments as $depKey => $depVal){
                                $DepModel = DP::find($depVal);
                                $responseArray['departments'][$index]['id'] = $DepModel->id;
                                $responseArray['departments'][$index]['department_name'] = $DepModel->dep_name;
                                $index++;
                            }
                        }catch(\Exception $e){

                            $responseArray['departments'][0]['id'] = '';
                            $responseArray['departments'][0]['department_name'] = '';
                        }
                    break;
                    case'designation':
                        $DesgModel = DS::find($metaValue->value);
                        try{
                            $responseArray['designation_name'] = $DesgModel->designation;
                            $responseArray['designation_id'] = $DesgModel->id;
                        }catch(\Exception $e){
                            $responseArray['designation_name'] = '';
                            $responseArray['designation_id'] = '';
                        }
                       
                    break;
                    case'profile_pic': 
                        if($metaValue->value == '' || $metaValue->value == null){
                           $responseArray[$metaValue->key] = asset('profile_pic/profile.jpg');
                        }else{


                            $responseArray[$metaValue->key] = asset('profile_pic/'.$metaValue->value);
                        }
                    break;
                    default:
                    $responseArray[$metaValue->key] = $metaValue->value;
                }
            }
        }
        return ['status'=>'success','details'=>$responseArray];
    }


    public function changePassword(Request $request){

        $validate = $this->validateModel($request);
        if(!$validate){

            return ['status'=>'error','message'=>'required fields are missing!'];
        }
        $model = $request->user();
        $result = Hash::check($request->old_pass, $model->password);
        if(!$result){
            return ['status'=>'error','message'=>'old password not correct!'];
        }

        if ($request->old_pass == $request->new_pass){
            return ['status' => 'error' , 'message' => 'your old and new password should not be same'];
        }

        if($request->new_pass != $request->conf_pass){
            return ['status'=>'error','message'=>'password not match!'];
        }

        $model->password = Hash::make($request->new_pass);
        $model->save();
        return ['status'=>'success','message'=>'Password changed successfully!'];

    }

    protected function validateModel($request){

        if($request->has('old_pass') && $request->has('new_pass') && $request->has('conf_pass')){
            return true;
        }else{
            return false;
        }
    }

    public function forgetPassword(Request $request){

    }

    public function saveProfile(Request $request){

        $validate = $this->validateProfile($request);
        if(!$validate){
            return ['status'=>'error','message'=>'Required fields are missing!'];
        }

        $userId = $request->user()->id;
        $model = User::find($userId);
        if($request->name != 'undefined'){
            $model->name = $request->name;
        }
        $model->save();
        $ministries = explode(',',$request->ministry);
        $departments = explode(',',$request->department);

        if($request->ministry != 'undefined' && $request->department != 'undefined' || $request->phone != 'undefined' || $request->designation != 'undefined' || $request->designation == 'address'){

            $UserMetaMinistry = UserMeta::where(['key'=>'ministry', 'user_id' => $userId])->get();
            if(count($UserMetaMinistry) < 1){
                $sendData = 'ministry';
                $this->checkUserDetails( $request ,$sendData);
            }else{
                UserMeta::where(['key'=>'ministry', 'user_id' => $userId])->update(['value'=>json_encode($ministries)]);
            }

            $UserMetaPhone = UserMeta::where(['key'=>'phone', 'user_id' => $userId])->get();
            if(count($UserMetaPhone) < 1){
                $sendData = 'phone';
                $this->checkUserDetails( $request ,$sendData);
            }else{
                UserMeta::where(['key'=>'phone', 'user_id' => $userId])->update(['value' => $request->phone]);
            }


            $UserMetaDesignation = UserMeta::where(['key'=>'designation', 'user_id' => $userId])->get();
            if(count($UserMetaDesignation) < 1){
                $sendData = 'designation';
                $this->checkUserDetails( $request ,$sendData);
            }else{
                UserMeta::where(['key'=>'designation', 'user_id' => $userId])->update(['value'=>$request->designation]);
            }


            $UserMetaAddress =  UserMeta::where(['key'=>'address', 'user_id' => $userId])->get();
            if (count($UserMetaAddress) < 1){
                $sendData = 'address';
                $this->checkUserDetails( $request ,$sendData);
            }else{
                UserMeta::where(['key'=>'address', 'user_id' => $userId])->update(['value'=>$request->address]);
            }

            $UserMetaDepartment = UserMeta::where(['key'=>'department', 'user_id' => $userId])->get();
            if (count($UserMetaDepartment) < 1){
                $sendData = 'department';
                $this->checkUserDetails( $request ,$sendData);
            }else{
                UserMeta::where(['key'=>'department', 'user_id' => $userId])->update(['value'=>json_encode($departments)]);
            }


            return ['status'=>'success','message'=>'Profile updated successfully!'];
        }else{
            return ['status'=>'error','message'=>'Unable to update profile!!'];
        }
    }
    protected function checkUserDetails($request, $sendData)
    {
        $userId = $request->user()->id;

            $data = new UserMeta;
            $data->key = $sendData;
            $data->value = $request->$sendData;
            $data->user_id = $userId;
            $data->save();
    }

    protected function validateProfile($request){
        if($request->has('name') && $request->has('phone') && $request->has('designation') && $request->has('address') && $request->has('department') && $request->has('ministry')){
            return true;
        }else{
            return false;
        }
    }
    public function profilePicUpdate(Request $request)
    {
        $userId = $request->user()->id;
        $name = $request->file('profile_pic')->getClientOriginalName();

        if($request->hasFile('profile_pic')){
            $path = 'profile_pic';
            $filename = date('Y-m-d-H-i-s')."-".$request->file('profile_pic')->getClientOriginalName();
            $request->file('profile_pic')->move($path, $filename);
            UserMeta::where(['key'=>'profile_pic', 'user_id' => $userId])->update(['value' => $filename]);
            return ['status'=>'success','message'=>'update successfully!'];
       }
        else{
             return ['status'=>'error','message'=>'Required fields are missing!'];
        }      
    }
    public function editProfile()
   {
      return View::make('apiusers.editProfile');
   }
}
