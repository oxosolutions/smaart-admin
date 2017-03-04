<?php
namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\carbon;
use Hash;
use Auth;
use App\UserMeta;
use App\organization as org;
class ProfileApiController extends Controller
{
    // public function getUserProfile(Request $request){
    //     $id = Auth::user()->id;        
    //     $user = User::where('id',$id)->get();
    //     $user_meta = UserMeta::where('user_id',$id)->get();

    //     $responseArray = [];
    //     $responseArray['user'] = $user;
    //     $responseArray['phone'] =  UserMeta::where(['user_id'=>$id,'key'=>'phone'])->get();
    //     $responseArray['address'] =  UserMeta::where(['user_id'=>$id,'key'=>'address'])->get();

    //     return ['status'=>'success','details'=>$responseArray];
    // }

    //old function is below





     public function getUserProfile(Request $request){

        $model = $request->user();
        // return $model;
        $responseArray = [];
        // $responseArray['departments'] = [];
        // $responseArray['ministries'] = [];
        // dump($model->organization->organization_name);
        // die;
        $responseArray['name']  = $model->name;
        $responseArray['email'] = $model->email;
        $responseArray['token'] = $model->api_token;

        $responseArray['organization'] = @$model->organization->organization_name;

        if($model->meta != null){

            foreach ($model->meta as $metaKey => $metaValue) {
                switch($metaValue->key){
                    
                    case'profile_pic':
                        if($metaValue->value == '' || $metaValue->value == null){
                            $responseArray[$metaValue->key] = asset('profile_pic/profile.jpg');
                        }else{
                            $responseArray[$metaValue->key] = asset('profile_pic/'.$metaValue->value);
                        }
                    break;
                    case'phone':
                        $responseArray['phone'] = $metaValue->value;

                    break;
                    case'address':
                        $responseArray['address'] = $metaValue->value;

                    break;
                    // case'organization': 
                    //     if($metaValue->value == '' || $metaValue->value == null){
                    //         $responseArray[$metaValue->key] = "null";
                    //     }else{
                    //         $responseArray[$metaValue->key] = org::select(['id','organization_name'])->where('id',$metaValue->value)->get();
                    //     }
                    // break;
                    //default:
                    //  $responseArray[$metaValue->key] = $metaValue->value;
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

//         if($request->hasFile('new_img')){
//             //$path = 'profile_pic';
//            echo $filename = date('Y-m-d-H-i-s')."-".$request->file('new_img')->getClientOriginalName();

//        // dd($request->request);
//       // dd($request->file('new_img'));
// }

        
       
              //dd($name = $request->file());
              //die;


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
        if($request->hasFile('new_img')){
            $path = 'profile_pic';
            $filename = date('Y-m-d-H-i-s')."-".$request->file('new_img')->getClientOriginalName();
            $request->file('new_img')->move($path, $filename);
            $profile_pic = UserMeta::where(['key'=>'profile_pic', 'user_id' => $userId]);
            if($profile_pic->count()==0)
            {
                $newProfilePic = new UserMeta;
                $newProfilePic->key = $sendData;
                $newProfilePic->value = $request->$sendData;
                $newProfilePic->user_id = $userId;
                $newProfilePic->save();
            }else{
                $old_pic = $profile_pic->first()->value; 
                $path = asset('/profile_pic/'.$old_pic);
               // unlink($path);
             $profile_pic->update(['value' => $filename]);
            }
        }
        if( $request->phone != 'undefined' || $request->address != 'undefined'){
           
            $UserMetaPhone = UserMeta::where(['key'=>'phone', 'user_id' => $userId])->get();
            if(count($UserMetaPhone) < 1){
                $sendData = 'phone';
                $this->checkUserDetails( $request ,$sendData);
            }else{
                UserMeta::where(['key'=>'phone', 'user_id' => $userId])->update(['value' => $request->phone]);
            }

            $UserMetaAddress =  UserMeta::where(['key'=>'address', 'user_id' => $userId])->get();
            if (count($UserMetaAddress) < 1){
                $sendData = 'address';
                $this->checkUserDetails( $request ,$sendData);
            }else{
                UserMeta::where(['key'=>'address', 'user_id' => $userId])->update(['value'=>$request->address]);
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
        if($request->has('name') && $request->has('phone') && $request->has('address') ){
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
