<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\DatasetsList as DL;
use Yajra\Datatables\Datatables;
use Auth;
use Session;
use DB;
use Excel;
use MySQLWrapper;
use File;
use App\LogSystem as LOG;
use Carbon\Carbon AS TM;
use App\Department as D;
use Illuminate\Support\Facades\Schema;


class DataSetsController extends Controller
{
    protected $ipAdress;
    public function __construct(Request $request)
    { 
      $this->ipAdress =  $request->ip();
      DB::enableQueryLog();  
    }

    public function index(){
        ini_set('memory_limit', '-1');
        $plugins = [
                    'css' => ['datatables'],
                    'js'  => ['datatables','custom'=>['gen-datatables']]
                   ];
        return view('datasets.index',$plugins);
    }

    public function correctCsv()
    {
           // $filename ='output_new.csv';

         $filename ='23ch5.csv';

             echo "<pre>";

             $ar = array();
             $data = [];
                Excel::SetDelimiter("|");
             Excel::filter('chunk')->load($filename)->chunk(100, function($reader){

                foreach ($reader->toArray() as $row) {

                     $data[] = $row;
                }

                   
                for($i=0; $i< count($data); $i++ )
                {
                    echo "size $i <br>";
                    
                    if(count($data[$i])!=262)
                    {
                      echo "size $i ->".count($data[$i]);
                    }
                }

                 print_r($data);
                 
            });

    }




    public function indexData(){
        ini_set('memory_limit', '-1');
        $model = DL::orderBy('id','desc')->with('createdBy')->get();
        return Datatables::of($model)
            ->addColumn('actions',function($model){
                return view('datasets._actions',['model' => $model])->render();
            })->editColumn('user_id',function($model){
                 return $model->createdBy->name; //return ucfirst($model->userId->name);
            })->editColumn('dataset_records',function($model){
                try{
                    return DB::table($model->dataset_table)->count();
                }catch(\Exception $e){
                    return "0";
                }
            })->make(true);
    }
    public function apiExportDataset($dataset_id)
    {

         $model = DL::find($dataset_id);
         $table_name = $model->dataset_table; 
         $name     =  str_replace(" ","-", $model->dataset_name); 
        $datas =   DB::table($table_name)->get()->toArray();
        $model =   json_decode(json_encode($datas),true);

        return Excel::create($name, function($excel) use ($model) {
              $excel->sheet('mySheet', function($sheet) use ($model)
                {
                    $sheet->fromArray($model);
                });
            })->download('csv');
    }
    public function exportTable($type, $table)
    {   
        if(Schema::hasTable($table))
        {
             $dataA =  DB::table($table)->get()->toArray(); 
             $data = json_decode(json_encode($dataA), true);      
         return Excel::create($table, function($excel) use ($data) {
              $excel->sheet('mySheet', function($sheet) use ($data)
                {
                    $sheet->fromArray($data);
                });
            })->download($type);
        }
        else{
            Session::flash('error',"$table not exist!");
        }   
    }

    public function create(){
        $plugin = [
                   
                    'js' => ['custom'=>['dataset-create']],
                  ];
        return view('datasets.create',$plugin);
    }


    public function store(Request $request){
    
     $request->file('file')->getClientOriginalExtension(); 
      
      $this->modelValidate($request);
        if($request->source == 'file'){

             if($request->file('file')->getClientOriginalExtension()=='sql' )
             {
                    $path = 'sql';
             }else{
                    $path = 'datasets';
                }
            try {
                 if(!in_array($request->file('file')->getClientOriginalExtension(),['csv','sql'])){
                   Session::flash('error','Some thing goes wrong Try again!');
                }
            } catch (Exception $e) {
                return ['status'=>'error', 'Please Select a File to Upload'];
            }
            $file = $request->file('file');
            if($file->isValid()){
                $filename = date('Y-m-d-H-i-s')."-".$request->file('file')->getClientOriginalName();
                $uploadFile = $request->file('file')->move($path, $filename);
                $filePath = $path.'/'.$filename;               
            }
        }
        
        if($request->source == 'file_server'){
            $filePath = $request->filepath;
            $filep = explode('/',$filePath);
            $filename = $filep[count($filep)-1];
        }

        if($request->source == 'url'){
            $filePath = $request->fileurl;
            $filep = explode('/',$filePath);
            $filename = $filep[count($filep)-1];
        }
        DB::beginTransaction();
        try{
             if($request->select_operation == 'new'){

                $result = $this->storeInDatabase($filePath, $request->dataset_name, $request->source, $filename);

                }elseif($request->select_operation == 'replace'){
                    
               $result = $this->replaceDataset($request, $request->file('dataset_file')->getClientOriginalName(), $filePath);
                }elseif($request->select_operation == 'append'){

                $result = $this->appendDataset($request->dataset_name, $request->source, $filename, $filePath, $request);
            }
           
            DB::commit();
            Session::flash($result['status'], $result['message']);
            return redirect()->route('datasets.list');
        } catch(\Exception $e){
            DB::rollback();
            throw $e;
        }
        
        Session::flash('success','Successfully created!');
        return redirect()->route('datasets.list');
    }

    public function runSqlFile($path){
      
        $sql =   file_get_contents($path);
        $lines = explode("\n", $sql); 
        $create_table = $status = $output = ""; 
        $linecount = count($lines); 
        $create=$next=0;
        for($i = 0; $i < $linecount; $i++) 
         { 
            if(starts_with($lines[$i], "CREATE") )
            {
                $create_table .= $lines[$i];
                $status .=1;
                $create=$i;
            }
            if(starts_with($lines[$i],'--'))
             {
                $create =0;
             }
            if($create>0 && $create<$i)
            {
                  $create_table .= $lines[$i];
            }
        if(starts_with($lines[$i], "INSERT") )
                {
                    $output .= $lines[$i];
                    $next = $i;
                    $status .=2;
                }
                 if($next>0 && $i>$next)
                {
                    if(str_contains($lines[$i], ['--','ALTER','ADD','/*','MODIFY']))
                         { $next=0;}
                     else{
                             $output .= $lines[$i];
                        }
                } 
         }            
            try{
            DB::select($create_table);
            }catch(\Exception $e)
            {
                if($e->getCode() =="42S01")
                {
                }
            }
            if($status !='12')
            {
                 
                $result['status'] = 'error';
                $result['message'] ="Not exist create & Insert";  
            } 
            else{   
                    try{
                        DB::select($output);

                        $result['status'] = 'success';
                        $result['message'] ="Sql file Import Successfully";

                    }catch(\Exception $e){ 
                        if($e->getCode()==23000)
                            {  
                                $result['status'] = 'error';
                                $result['message'] ="Sql file Duplicate entry";
                            }  
                    }
                } 

                return $result;      
    }

    protected function appendDataset($datasetName, $source, $filename, $filePath, $request){
       
        if($source == 'url'){
            $randName = 'downloaded_dataset_'.time().'.csv';
            $path = 'datasets/';
            copy($filename, $path.$randName);
            $filePath = 'datasets/'.$randName;
        }

        if(!File::exists($filePath)){
            return ['status'=>'false','id'=>'','message'=>'File not found on given path!'];
        }

        $tableName = 'table_temp_'.rand(5,1000);
        $model = new MySQLWrapper;
        $result = $model->wrapper->createTableFromCSV($filePath,$tableName,',','"', '\\', 0, array(), 'generate','\r\n');
        $tempTableData = DB::table($tableName)->get();

        $model_DL = DL::find($request->with_dataset);
        $oldTable = DB::table($model_DL->dataset_table)->get();
        
        $oldColumns = [];
        $new = (array)$tempTableData[0];
        $old = (array)$oldTable[0];
        
        if($new != $old){
            DB::select('DROP TABLE '.$tableName);
                        $result['status'] = 'error';
                        $result['message'] ="File columns are note same!";
           // return ['status'=>'false','message'=>'File columns are note same!'];
        }
        unset($new['id']);

        $appendColumns = implode(',', array_keys($new));
        DB::select('INSERT INTO `'.$model_DL->dataset_table.'` ('.$appendColumns.') SELECT '.$appendColumns.' FROM '.$tableName.' WHERE id != 1;');
        DB::select('DROP TABLE '.$tableName);
        
        $result['status'] = 'success';
        $result['message'] ="Dataset updated successfully!!";
      return $result;  
    }

   

    protected function replaceDataset($request, $origName, $filename){
        ini_set('memory_limit', '2048M');
        $FileData = [];
        $data = Excel::load($filename, function($reader){ })->get();
        foreach($data as $key => $value){
            $FileData[] = $value->all();
        }
        $model = DL::find($request->dataset_list);
        $model->dataset_name = $origName;
        $model->dataset_records = json_encode($FileData);
        $model->user_id = Auth::user()->id;
        $model->uploaded_by = Auth::user()->name;
        $model->dataset_columns = null;
        $model->validated = 0;
        $model->save();
        if($model){

                        $result['status'] = 'success';
                        $result['message'] ="Dataset replaced successfully!";
        }else{
                        $result['status'] = 'error';
                        $result['message'] ="unable to replace dataset!";
        }
        return $result;
    }

 protected function storeInDatabase($filename, $origName, $source, $orName){
        
        $filePath = $filename;
        if($source == 'url'){
          
            $randName = 'downloaded_dataset_'.time().'.csv';
            $path = 'datasets/';
            copy($filename, $path.$randName);
            $filePath = 'datasets/'.$randName;
        }
        if(!File::exists($filePath)){

            $message['status'] = 'error';
            $message['message'] ="File not found on given path!";
        }

       if(File::extension($filename)=="sql")
       {
          $message =  $this->runSqlFile($filename);
       }else{

      
            DB::beginTransaction();
            $model = new MySQLWrapper();
            $tableName = 'data_table_'.time();
            
            $result = $model->wrapper->createTableFromCSV($filePath,$tableName,',','"', '\\', 0, array(), 'generate','\r\n');
            
            if($result){
                $model = new DL;
                $model->dataset_table = $tableName;
                $model->dataset_name = $origName;
                $model->dataset_file = $filePath;
                $model->dataset_file_name = $orName;
                $model->user_id = Auth::user()->id;
                $model->uploaded_by = Auth::user()->name;
                $model->dataset_records = '{}';
                $model->save();
                DB::commit();
                $message['status'] = 'success';
                $message['message'] ="Dataset upload successfully!";
                //return ['status'=>'true','id'=>$model->id,'message'=>'Dataset upload successfully!'];
            }else{
                DB::rollback();
                $message['status'] = 'error';
                $message['message'] ="unable to upload datsaet!";
                //return ['status'=>'false','id'=>'','message'=>'unable to upload datsaet!'];
            }
        }

        return $message;
    }

 


    protected function validateRequst($request){
        $errors = [];
        if($request->file('file') == '' || empty($request->file('file')) || $request->file('file') == null){
            $errors['file'] = 'File field should not empty!';
        }
         if($request->format == 'undefined' || empty($request->format) || $request->format  == null){
             $errors['format'] = 'Please select file format';
         }
        if($request->add_replace == 'undefined' || empty($request->add_replace) || $request->add_replace  == null){
            $errors['add_replace'] = 'Please select file format!';
        }
        if($request->add_replace == 'replace' || $request->add_replace == 'append'){
            if($request->with_dataset == '' || $request->with_dataset == 'undefined' || empty($request->with_dataset)){
                $errors['dataset'] = 'Please select dataset to '.$request->add_replace;
           }
        }
        if(count($errors) >= 1){
            $return = ['status' => 'false','error'=>$errors];
            return $return;
        }else{
            $return = ['status' => 'true','error'];
            return $return;
        }
    }
  
    protected function modelValidate($request){
        
        $rules = [
                
                'select_operation' => 'required',
                'dataset_name'     => 'required',
                'source'           =>'required'
               ];
        if($request->select_operation == 'append' || $request->select_operation == 'replace'){
            $rules['dataset_list'] = 'required';
        }
        $this->validate($request, $rules);
    }
    public function destroy($id){
        $model = DL::findOrFail($id);
        try{
            $model->delete();
            Session::flash('success','Successfully deleted!');
        }catch(\Exception $e){
            throw $e;
        }
        return redirect()->route('datasets.list');
    }
    public function __destruct() {
        parent::__destruct();
          
        }
    
}