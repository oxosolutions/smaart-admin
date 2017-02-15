<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GlobalSetting as GS;
use Auth;
use Session;
use stdClass;
class GlobalSettingsController extends Controller
{
    public function index(){

    	$plugins = [

    			'css' => ['icheck'],
    			'js'  => ['icheck','custom'=>['settings']]
    	];
    	
    	$plugins['Reg_model']          = $this->setRegStdObjectValues();
    	$plugins['Forget_model']       = $this->setForgetStdObjectValues();
        $plugins['adminReg_model']     = $this->setAdminRegStdObjectValues();
        $plugins['userApprov_model']   = $this->setUserApprovalStdObjectValues();
        $plugins['dataset_model']      = $this->setDataStdObjectValues();
        $plugins['siteTitle_model']    = $this->getSiteSetting('site_title');
        $plugins['siteTagline_model']  = $this->getSiteSetting('site_tagline'); 
        $plugins['siteUrl_model']      = $this->getSiteSetting('site_url'); 
        $plugins['visual_setting']      = $this->getSiteSetting('visual_setting'); 


    	return view('settings.index',$plugins);
    }

    protected function getSiteSetting($key){
        return GS::select('id','meta_value as value')->where('meta_key',$key)->first();
    }

    public function saveNewUserRegisterSettings(Request $request){

    	$this->checkMetaExist('register_settings');
    	$registerSettings = [];
    	if(empty($request->send_register_email)){
    		$registerSettings['activate'] = 'false';
    	}else{
    		$registerSettings['activate'] = $request->send_register_email;
    	}
    	$registerSettings['subject'] 		= $request->register_subject;
    	$registerSettings['description'] 	= $request->register_description;
    	GS::where('meta_key','register_settings')->update(['meta_value'=>json_encode($registerSettings),'updated_by'=>Auth::user()->id]);
    	Session::flash('success','Settings Saved Successfuly!');
    	return redirect()->route('global.settings');
    }



    protected function checkMetaExist($metaKey){

    	$model = GS::where('meta_key',$metaKey)->first();
    	if(empty($model)){
    		$model = new GS;
    		$model->meta_key = $metaKey;
    		$model->updated_by = Auth::user()->id;
    		$model->save();
    		return true;
    	}else{
    		return true;
    	}
    }

    public function saveForgetEmailSettings(Request $request){

    	$this->checkMetaExist('forget_settings');
    	$forgetSettings = [];
    	if(empty($request->send_forget_email)){
    		$forgetSettings['activate'] = 'false';
    	}else{
    		$forgetSettings['activate'] = $request->send_forget_email;
    	}
    	$forgetSettings['subject'] 		= $request->forget_subject;
    	$forgetSettings['description'] 	= $request->forget_description;
    	GS::where('meta_key','forget_settings')->update(['meta_value'=>json_encode($forgetSettings),'updated_by'=>Auth::user()->id]);
    	Session::flash('success','Settings Saved Successfuly!');
    	return redirect()->route('global.settings');
    }

    protected function setRegStdObjectValues(){

    	$Reg_modelData = new stdClass;
    	$globalData = GS::where('meta_key','register_settings')->first();
    	if(!empty($globalData)){
    		$Reg_modelData->id = $globalData->id;
	    	if(json_decode($globalData->meta_value)->activate == 'false'){
	    		$activate = null;
	    	}else{
	    		$activate = true;
	    	}
	    	$Reg_modelData->send_register_email = $activate;
	    	$Reg_modelData->register_subject = json_decode($globalData->meta_value)->subject;
	    	$Reg_modelData->register_description = json_decode($globalData->meta_value)->description;
    	}else{
    		$Reg_modelData->id = '';
    		$Reg_modelData->send_register_email = '';
    		$Reg_modelData->register_subject = '';
    		$Reg_modelData->register_description = '';
    	}
    	return $Reg_modelData;
    }


    /**
     * [setForgetStdObjectValues to set or prefill forget email settings defaul values]
     */
    protected function setForgetStdObjectValues(){

        $Forget_modelData = new stdClass;
        $globalData = GS::where('meta_key','forget_settings')->first();
        if(!empty($globalData)){
            $Forget_modelData->id = $globalData->id;
            if(json_decode($globalData->meta_value)->activate == 'false'){
                $activate = null;
            }else{
                $activate = true;
            }
            $Forget_modelData->send_forget_email = $activate;
            $Forget_modelData->forget_subject = json_decode($globalData->meta_value)->subject;
            $Forget_modelData->forget_description = json_decode($globalData->meta_value)->description;
        }else{

            $Forget_modelData->id = null;
            $Forget_modelData->send_forget_email = '';
            $Forget_modelData->forget_subject = '';
            $Forget_modelData->forget_description = '';
        }
        return $Forget_modelData;
    }

    protected function setDataStdObjectValues(){
    try{
    	$Dataset_modelData = new stdClass;
    	$globalData = GS::where('meta_key','dataset_setting')->first();
    	if(!empty($globalData)){
    		$Dataset_modelData->id = $globalData->id;
	    	if(json_decode($globalData->meta_value)->activate == "false"){
	    		$activate = null;
	    	}else{
	    		$activate = true;
	    	}
	    	$Dataset_modelData->dataset_status = $activate;
	    	$Dataset_modelData->dataset_num_row = json_decode($globalData->meta_value)->num_row;
    	}else{

    		$Dataset_modelData->id = null;
    		$Dataset_modelData->dataset_status = '';
    		$Dataset_modelData->dataset_num_row = '';
    	}
    }catch(\Exception $e)
        {
        	 $Dataset_modelData="";
        }
     return $Dataset_modelData;
    }



    /**
     * [saveAdminRegEmailSettings description]
     * @param  Request $request [description]
     * @return [type]           [will redirect to global settings page]
     */
    public function saveAdminRegEmailSettings(Request $request){

        $this->checkMetaExist('adminreg_settings');
        $adminRegSettings = [];
        if(empty($request->send_adminReg_email)){
            $adminRegSettings['activate'] = 'false';
        }else{
            $adminRegSettings['activate'] = $request->send_adminReg_email;
        }
        $adminRegSettings['subject']      = $request->adminreg_subject;
        $adminRegSettings['description']  = $request->adminreg_description;
        $adminRegSettings['admin_email']  = $request->adminreg_email;
        GS::where('meta_key','adminreg_settings')->update(['meta_value'=>json_encode($adminRegSettings),'updated_by'=>Auth::user()->id]);
        Session::flash('success','Settings Saved Successfuly!');
        return redirect()->route('global.settings');
    }

    /**
     * [setAdminRegStdObjectValues To set the prefilled detials of register user email
     *  settings
     * ]
     */
    protected function setAdminRegStdObjectValues(){

        $AdminReg_modelData = new stdClass;
        $globalData = GS::where('meta_key','adminreg_settings')->first();
        if(!empty($globalData)){
            $AdminReg_modelData->id = $globalData->id;
            if(json_decode($globalData->meta_value)->activate == 'false'){
                $activate = null;
            }else{
                $activate = true;
            }
            $AdminReg_modelData->send_adminReg_email = $activate;
            $AdminReg_modelData->adminreg_subject = json_decode($globalData->meta_value)->subject;
            $AdminReg_modelData->adminreg_description = json_decode($globalData->meta_value)->description;
            $AdminReg_modelData->adminreg_email = json_decode($globalData->meta_value)->admin_email;
        }else{

            $AdminReg_modelData->id = null;
            $AdminReg_modelData->send_adminReg_email = '';
            $AdminReg_modelData->adminreg_subject = '';
            $AdminReg_modelData->adminreg_description = '';
            $AdminReg_modelData->adminreg_email = '';
        }
        return $AdminReg_modelData;
    }


    /**
     * [saveApproveUserSettings description]
     * @param  Request $request [description]
     * @return [type]           [redirecting global setting page]
     */
    public function saveApproveUserSettings(Request $request){

        $this->checkMetaExist('user_approvel_settings');
        $forgetSettings = [];
        if(empty($request->send_approveuser_email)){
            $forgetSettings['activate'] = 'false';
        }else{
            $forgetSettings['activate'] = $request->send_approveuser_email;
        }
        $forgetSettings['subject']      = $request->approvel_subject;
        $forgetSettings['description']  = $request->aprroved_description;
        GS::where('meta_key','user_approvel_settings')->update(['meta_value'=>json_encode($forgetSettings),'updated_by'=>Auth::user()->id]);
        Session::flash('success','Settings Saved Successfuly!');
        return redirect()->route('global.settings');
    }


    protected function setUserApprovalStdObjectValues(){

        $Forget_modelData = new stdClass;
        $globalData = GS::where('meta_key','user_approvel_settings')->first();
        if(!empty($globalData)){
            $Forget_modelData->id = $globalData->id;
            if(json_decode($globalData->meta_value)->activate == 'false'){
                $activate = null;
            }else{
                $activate = true;
            }
            $Forget_modelData->send_approveuser_email = $activate;
            $Forget_modelData->approvel_subject = json_decode($globalData->meta_value)->subject;
            $Forget_modelData->aprroved_description = json_decode($globalData->meta_value)->description;
        }else{

            $Forget_modelData->id = null;
            $Forget_modelData->send_approveuser_email = '';
            $Forget_modelData->approvel_subject = '';
            $Forget_modelData->aprroved_description = '';
        }
        return $Forget_modelData;
    }

    public function datasetNumRowSetting(Request $request)
    {
        try{
            if($request->dataset_status==true)
            {
                $num_row['activate'] ="true";
            }else{
                $num_row['activate'] ="false";
            }
               
            $num_row['num_row'] = $request->dataset_num_row;

          $count =  GS::where('meta_key','dataset_setting')->count();
          if($count==0)
          {
            print_r($num_row);
            $GS =  new GS();
            $GS->meta_key = 'dataset_setting';
            $GS->meta_value = json_encode($num_row);
            $GS->updated_by  = Auth::user()->id;
            $GS->save();
           
          }else{
           GS::where('meta_key','dataset_setting')->update(['meta_value'=>json_encode($num_row)]);
        }
            Session::flash('success','Dataset setings Saved Successfuly!');

           }catch(\Exception $e)
           { throw $e;} 

        return redirect()->route('global.settings');

    }
    public function siteValue(Request $request)
    {
        try{
                 $count =GS::where('meta_key',$request->meta_type)->count();
                 if($count==0)
                 {
                       $GS =  new GS;
                       $GS->meta_key = $request->meta_type;
                       $GS->meta_value = $request->value;
                        $GS->updated_by = Auth::user()->id;
                       $GS->save();
                 }else{
                    GS::where('meta_key',$request->meta_type)->update(['meta_value'=>$request->value]);
                 }



            }catch(\Exception $e)
            {
                throw $e;
            }
            return redirect()->route('global.settings');

    }
}
