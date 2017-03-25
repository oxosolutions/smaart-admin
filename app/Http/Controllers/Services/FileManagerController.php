<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Surrvey;
use App\SurveyQuestion as SQ;
use Auth;
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

class FileManagerController extends Controller
{
    public function listFiles(){
        $dirList = [];
        $parentDir = 'shared'; 
        $newdata = [];
        foreach(scandir('shared') as $key => $paths){
            if ( $paths=='.' || $paths=='..' ) continue;
            if(is_dir('shared'.DIRECTORY_SEPARATOR.$paths)){
                $dirList[] = $paths;
            }
        }
        foreach(File::allFiles('shared') as $key => $value){
            $file_kb = $value->getSize()/1024;
            $file_mb = $file_kb/1024;
            $data = array(                  
                    'name'          =>  $value->getFilename(),
                    'type'          =>  $value->getExtension(),
                    'size'          =>  $value->getSize(),
                    'size_mb'       =>  $file_mb,
                    'size_kb'       =>  $file_kb,
                    'server_path'   =>  $value->getRealPath(),
                    'url'           =>  asset($value->getPathname()),
                    'modified_at'   =>  date('Y-m-d h:i:s',$value->getMTime()),
                    'permission'    =>  json_encode(array(
                                                    'readable'      => $value->isReadable(),
                                                    'writable'      => $value->isWritable()
                                                ))
                );
                
                $newdata[] = $data;
        }
        foreach($dirList as $key => $subDir){
            // dd($parentDir.'/'.$subDir);
            foreach(File::allFiles($parentDir.'/'.$subDir) as $key => $value){
                $file_kb = $value->getSize()/1024;
                $file_mb = $file_kb/1024;
                $data = array(                  
                        'name'          =>  $value->getFilename(),
                        'type'          =>  $value->getExtension(),
                        'size'          =>  $value->getSize(),
                        'size_mb'       =>  $file_mb,
                        'size_kb'       =>  $file_kb,
                        'server_path'   =>  $value->getRealPath(),
                        'url'           =>  asset($value->getPathname()),
                        'modified_at'   =>  date('Y-m-d h:i:s',$value->getMTime()),
                        'permission'    =>  array(
                                                        'readable'      => $value->isReadable(),
                                                        'writable'      => $value->isWritable()
                                                    )
                    );
                    
                    $newdata[] = $data;
                    return['status' => 'success','response' => $newdata];
            }
        }
    }

    //upload file
    public function uploadFile(Request $request){
        
        $path = './shared/';
        $filename = $request->file('file')->getClientOriginalName();
        $uploadFile = $request->file('file')->move($path, $filename);
        return ['status'=>'success','message'=>'Successfully uploaded!'];
    }
}
