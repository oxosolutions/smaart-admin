<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;

class PagesApiController extends Controller
{
    public function getAllPages(){

        $model = Page::get();
        $index = 0;
        $responseArray = [];
        foreach($model as $key => $value){
        try{    
            $responseArray['pages'][$index]['page_title']   = $value->page_title;
            $responseArray['pages']['page_subtitle']        = $value->page_subtitle;
            $responseArray['pages'][$index]['page_slug']    = $value->page_slug;
            $responseArray['pages'][$index]['page_content'] = $value->content;
            $responseArray['pages'][$index]['page_image']   = asset('page_data').'/'.$value->page_image;
            $responseArray['pages'][$index]['page_status']  = $value->status;
            $responseArray['pages'][$index]['page_status']  = $value->createdBy->name;
            $index++;
            }catch(\Exception $e)
            {
                return ['status'=>'error','message'=>$responseArray];
        
            }
        }

        return ['status'=>'success','records'=>$responseArray];
    }

    public function getPageBySlug($slug){

        $model = Page::where(['page_slug'=>$slug,'status'=> 1])->first();
        try{
            $responseArray = [];
            $responseArray['pages']['page_title']       = $model->page_title;
            $responseArray['pages']['page_subtitle']    = $model->page_subtitle;
            $responseArray['pages']['page_slug']        = $model->page_slug;
            $responseArray['pages']['page_content']     = $model->content;
            $responseArray['pages']['page_image']       = asset('page_data').'/'.$model->page_image;
            $responseArray['pages']['page_status']      = $model->status;
            $responseArray['pages']['page_created_by']  = $model->createdBy->name;

            return ['status'=>'success','records'=>$responseArray];
        }catch(\Exception $e){

            return ['status'=>'error','message'=>'Page not found'];
        }
    }
}
