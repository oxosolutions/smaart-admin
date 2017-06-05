<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Surrvey;
use App\SurveyQuestion as SQ;
use Auth;
use App\User as US;
use App\UserMeta as um;
use DB;
use App\SurveyQuestionGroup as GROUP;
use App\SurveySetting as SSETTING;
use App\SurveyEmbed as SEMBED;
use Illuminate\Support\Facades\Schema;
use App\organization as org;
use Session;
use File;
use App\FileManager as FM;
use Storage;

class FileManagerController extends Controller
{
    // public function listFiles(){
       
    //     $dirList = [];
    //     $parentDir = 'shared'; 
    //     $newdata = [];
    //     foreach(scandir('shared') as $key => $paths){
    //         if ( $paths=='.' || $paths=='..' ) continue;
    //         if(is_dir('shared'.DIRECTORY_SEPARATOR.$paths)){
    //             $dirList[] = $paths;
    //         }
    //     }
    //     foreach(File::allFiles('shared') as $key => $value){
    //         $file_kb = $value->getSize()/1024;
    //         $file_mb = $file_kb/1024;
    //         $data = array(                  
    //                 'name'          =>  $value->getFilename(),
    //                 'type'          =>  $value->getExtension(),
    //                 'size'          =>  $value->getSize(),
    //                 'size_mb'       =>  round($file_mb,2),
    //                 'size_kb'       =>  $file_kb,
    //                 'media'         =>  'media_'.substr($value->getFilename(), 2,strlen($value->getFilename())-7),
    //                 'server_path'   =>  $value->getRealPath(),
    //                 'url'           =>  asset($value->getPathname()),
    //                 'modified_at'   =>  date('Y-m-d h:i:s',$value->getMTime()),
    //                 'permission'    =>  ''
    //             );
    //      $check =   FM::where('name',$data['name'])->count();
    //         if($check==0)
    //         {
    //         //to save the files into database
    //             $fm = FM::create($data);
    //             $fm->save();
    //             $newdata[] = $data;
    //         }
    //     }
    //     foreach($dirList as $key => $subDir){
    //         // dd($parentDir.'/'.$subDir);
    //         foreach(File::allFiles($parentDir.'/'.$subDir) as $key => $value){
    //             $file_kb = $value->getSize()/1024;
    //             $file_mb = $file_kb/1024;
    //             $data = array(                  
    //                     'name'          =>  $value->getFilename(),
    //                     'type'          =>  $value->getExtension(),
    //                     'size'          =>  $value->getSize(),
    //                     'size_mb'       =>  round($file_mb,2),
    //                     'size_kb'       =>  $file_kb,
    //                     'media'         =>  'media_'.rand('15',1000000),
    //                     'server_path'   =>  $value->getRealPath(),
    //                     'url'           =>  asset($value->getPathname()),
    //                     'modified_at'   =>  date('Y-m-d h:i:s',$value->getMTime()),
    //                     'permission'    => ''
    //                 );
    //             //to save the files into database
                
    //                 // $fm = FM::create($data);
    //                 // $fm->save();
    //                 $newdata[] = $data;
    //                 return['status' => 'success','response' => $newdata];
    //         }
    //     }
    // }
    
    public function listFiles()
    {
        $org_id = AUTH::user()->organization_id;
        $data = FM::where('org_id',$org_id)->get();
        $response = [];
        foreach ($data as $key => $value) {
             $audio_data = array(                  
                    'name'          =>  $value->name,
                    'type'          =>  $value->type,
                    'size'          =>  $value->size,
                    'size_mb'       =>  round($value->size/1024/1024,2),
                    'size_kb'       =>  round($value->size/1024,2),
                    'server_path'   =>  $value->server_path,
                    'url'           =>  $value->url,
                    'modified_at'   =>  $value->modified_at,
                    'permission'    =>  ''
                );
            $audio_data['media'] = $value->media;  
            $response[] = $audio_data;          
        }
        return ['status'=>'Success','response'=>$response];

    }

    //upload file
    //date time microtime
    public function uploadFile(Request $request){

        $org_id = AUTH::user()->organization_id;
        $path = public_path().'/shared/org_'.$org_id;
        if (!is_dir($path)){
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        $filename = $request->file('file')->getClientOriginalName();
        
        $type = $request->file('file')->getClientOriginalExtension();
        $file_name_new =  date('YmdHis').''.substr((string)microtime(), 2, 6).''.rand(1000,9999).'.'.$type;
        $size = $request->file('file')->getSize();
        $uploadFile = $request->file('file')->move($path, $file_name_new);
        if($type == 'mp3' || $type == 'wav'){
            $media = 'audio_'.rand(1000,9999).time();
        }elseif($type == 'jpg' || $type == 'jpeg' || $type == 'png'){
            $media = 'image_'.rand(1000,9999).time();
        }
        $data = array(                  
            'name'          =>  $filename,
            'type'          =>  $type,
            'size'          =>  $size,
            'media'         =>  $media,
            'server_path'   =>  public_path().'/shared/org_'.$org_id,
            'url'           =>  url('/shared/org_'.$org_id).'/'.$file_name_new,
            'modified_at'   =>  date('Y-m-d h:i:s'),
            'permission'    => '',
            'org_id'    =>  $org_id
        );
        $fm = FM::create($data);
        $fm->save();
        return ['status'=>'success','message'=>'Successfully uploaded!'];
        
    }
}
