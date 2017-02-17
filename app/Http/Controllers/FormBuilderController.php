<?php

namespace App\Http\Controllers;
use Yajra\Datatables\Datatables;

use Illuminate\Http\Request;
use App\Surrvey;
use App\SurrveyQuestion as SQ;
USE Auth;
use Session;
use Carbon\Carbon as tm;
class FormBuilderController extends Controller
{
      public function surrvey_setting($id)
      {


        $model = Surrvey::where('id',$id)->first();

        $plugins = [
                        'js' => ['custom'=>['surrvey_setting']],
                        'model'=>$model
            ];
        return view('formbuilder.surrvey_setting', $plugins);

      }

      public function save_setting(Request $request,$id)
      {
          if($request->error_messages=="enable")
          {
            foreach ($request->mess as $key => $value) {
                    $er_msg[$key] = $value;
            }
          }
          foreach ($request->request as $key => $value) {
            
            if($key != '_token' && $key !='role' &&  $key !='individual' && $key!="mess" )
            {
             if($key == 'error_messages' && $value =="enable" )
              {
                  $array['error_message_value'] = json_encode($er_msg);
              }else{
                  $array['error_message_value'] = NULL;
              }
              $array[$key] = $value;
            }
        }

        if($request->response_limit_status =="disable")
          {
            $array['response_limit'] = Null;
          }
          if($request->timer_status =="disable")
          {
            $array['timer_type'] = Null;
          }
          if($request->timer_status =="disable" || $request->timer_type =="expire_time") 
            {
              $array['timer_durnation'] = Null;
            }
           
          if($request->scheduling =='enable')
            {
                $array['start_date'] =  tm::parse( $request->start_date)->format('Y-m-d');
                $array['expire_date'] =  tm::parse( $request->expire_date)->format('Y-m-d'); 

            }else{
                $array['start_date'] =  NUll;
                $array['expire_date'] =  Null;
            }
          if($request->authentication_required =="enable")
            {
              if($request->authentication_type=="individual_based")
              {
                $array['authorize'] = $request->individual;
              }
              elseif($request->authentication_type=="role_based")
              {
                  $array['authorize'] = json_encode($request->role);
              }
            }else{
                    $array['authentication_type'] =Null;
                    $array['authorize'] =Null;
                  }
      Surrvey::where('id',$id)->update($array);
      Session::flash('success','Setting Save  Successfully.');
              return redirect()->route('surrvey.index');    
  }
    public function create_surrvey()
    {
       return view('formbuilder.add');
    }

    public function surrvey_save(Request $request)
    {
        try{
            $surrvey = new Surrvey();
            $surrvey->name = $request->name;
            $surrvey->description = $request->description;
            $surrvey->created_by = Auth::user()->id;
            $surrvey->save();
            Session::flash('success','Surrvey Create Successfully');
            return redirect()->route('surrvey.index');
            }catch(\Exception $e)
            {
                Session::flash('error','Something goes wrong Try Again.');
            }   
    }

    public function index()
    {
        $plugins = [
                    'css' => ['datatables'],
                    'js' => ['datatables','custom'=>['gen-datatables']]
                   ];
        return view('formbuilder.index',$plugins);
    }
    public function index_data()
    {
       $model = Surrvey::orderBy('id','desc')->get();
       return Datatables::of($model)
            ->addColumn('actions',function($model)
            {
                return view('formbuilder._actions',['model'=>$model])->render();
            })->make('true');
    }
    public function surrvey_edit($id)
    {
        try{
            Surrvey::findORfail($id);
            $model =  Surrvey::where('id',$id)->first();
            return view('formbuilder.edit_surrvey',['model'=>$model]);  
            }catch(\Exception $e)
            {
                Session::flash('error','Data not found.');
              return redirect()->route('surrvey.index');    
            }    
    }
    public function surrvey_update(Request $request , $id)
    {
        try{
            Surrvey::findORfail($id);
            Surrvey::where('id',$id)->update(['name'=>$request->name, 'description'=>$request->description]);
            Session::flash('success','Surrvey update Successfully');
            return redirect()->route('surrvey.index');
        }catch(\Exception $e)
        {
            Session::flash('error','Something goes wrong Try again.');
              return redirect()->route('surrvey.index');    
        }
    }

    public function surrvey_del($id)
    {
       Surrvey::where('id',$id)->delete();
       Session::flash('success','Surrvey Delete Successfully');
        return redirect()->route('surrvey.index');        
    }

    public function create($id)
    {
         
       $model = SQ::where('surrvey_id',$id);
       $quesData ="";
      if($model->count()>0){
        $quesData = $model->get();
         }
         $plugins = [
                    'js' => ['custom'=>['builder']],
                    'surrvey_id'=>$id,
                    'question'=>$quesData
        ];
        //print_r($plugins);
       
        return view('formbuilder/create',$plugins);
    }
    public function save(Request $request)
     {  
         $sid = $request->surrvey_id;
         $SQ =  SQ::where('surrvey_id',$sid);
        if($SQ->count() > 0)
          {
            $SQ->delete();
          }
      foreach ($request->ques as $key => $ques)
    	{  if(isset($array['option']))
            {
                unset($array['option']);
            }

            $array['question_key'] = 'field_'.str_random(10);
            $array['type'] = $request->type[$key];
            $array['slug'] = $request->fieldname[$key];
            if(isset($request->instruction[$key]) && $request->instruction[$key] !="")
            {
                 $array['instruction'] = $request->instruction[$key];
            }
            if(isset($request->format[$key]) && $request->format[$key] !="")
            {
                 $array['format'] = $request->format[$key];
            }
            if(isset($request->minimum[$key]) && $request->minimum[$key] !="")
            {
                 $array['minimum'] = $request->minimum[$key];
            }
            if(isset($request->maximum[$key]) && $request->maximum[$key] !="")
            {
                 $array['maximum'] = $request->maximum[$key];
            }
            if(isset($request->message[$key]) && $request->message[$key] !="")
            {
                 $array['message'] = $request->message[$key];
            }
            if(isset($request->required[$key]) && $request->required[$key] !="")
            {
                 $array['required'] = $request->required[$key];
            }
            if(isset($request->placeholder[$key]) && $request->placeholder[$key] !="")
            {
                 $array['placeholder'] = $request->placeholder[$key];
            }
            if(isset($request->conditional_logic[$key]) && $request->conditional_logic[$key] !="")
            {
                 $array['conditional_logic'] = $request->conditional_logic[$key];
            }
            if(isset($request->question_desc[$key]) && $request->question_desc[$key] !="")
            {
                 $array['question_desc'] = $request->question_desc[$key];
            }
            if(isset($request->question_order[$key]) && $request->question_order[$key] !="")
            {
                 $array['question_order'] = $request->question_order[$key];
            }
            if(isset($request->media[$key]) && $request->media[$key] !="")
            {
                 $array['media'] = $request->media[$key];
            }
            if(isset($request->pattern[$key]) && $request->pattern[$key] !="")
            {
                 $array['pattern'] = $request->pattern[$key];
            }
            if(isset($request->extra_options[$key]) && $request->extra_options[$key] !="")
            {
                 $array['extra_options'] = $request->extra_options[$key];
            }
            if(isset($request->validation[$key]) && $request->validation[$key] !="")
            {
                 $array['validation'] = $request->validation[$key];
            }
              
              $optionIndex = $key;
             if(isset($request->option['key'][$optionIndex]))
            {
                foreach ($request->option['key'][$optionIndex] as $opkey => $value) {
                    # code...
                  $array['option']['value'][] =   $request->option['val'][$optionIndex][$opkey];
                  $array['option']['option_next'][] =   $request->option['option_next'][$optionIndex][$opkey];
                  $array['option']['option_status'][] =   $request->option['option_status'][$optionIndex][$opkey];
                  $array['option']['option_prompt'][] =   $request->option['option_prompt'][$optionIndex][$opkey];
                  
                  $array['option']['key'][] = $value;
                 
                }
            }

            $sq =  new SQ();
            $sq->surrvey_id = $sid;
            $sq->question = $ques;
            $sq->answer  = json_encode($array);
            $sq->save();
    	}
            

        Session::flash('success','Surrvey Question Create Successfully');
            return redirect()->route('surrvey.index');
    }

     public function get_ques($id)
     {
       return SQ::where('id',$id)->first();
     }

    public function surrvey_ques($id)
    {
     // $model = Surrvey::where('id',$id)->get();

            // foreach ($request->question as $key => $value)
            // { 
            //     $array['type'] = $request->type[$key];
            //     $array['slug'] = $request->slug[$key];

            //     if(isset($request->ans['option'][$key]))
            //     {
            //     $array['options'] = $request->ans['option'][$key];
            //     }

            //     $sq =  new SQ();
            //     $sq->surrvey_id = $sid;
            //     $sq->question = $value;
            //     $sq->answer  = json_encode($array);
            //     $sq->save();
            // }
    }

    public function addField(){

        return view('formbuilder.fields')->render();
    }
}
