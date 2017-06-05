<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneratedVisual as GV;
use App\DatasetsList as DL;
use DB;
use App\GeneratedVisualQuerie as GVQ;
use Auth;
use App\GlobalSetting as GS;
use App\Map;
use App\Embed;
use Session;
use App\GMap;
use App\VisualizationMeta as VisualMeta;
class VisualApiController extends Controller
{
    public function visualList(){
        
        $model = GV::all();
        //dump();
        $resultArray = [];
        foreach($model as $key => $value){
            $tempArray = [];
            $tempArray['id'] = $value->id;
            $tempArray['visual_name'] = $value->visual_name;
            try{
                $tempArray['dataset'] = array('dataset_id'=>$value->dataset_id,'dataset_name'=>$value->datasetName->dataset_name);
            }catch(\Exception $e){
                $tempArray['dataset'] = [];
            }
            
            $tempArray['created_by'] = "name-";//$value->createdBy->name;
            $tempArray['created_at'] = $value->created_at->format('Y-m-d H:i:s');
            $resultArray[] = $tempArray;
        }

        return ['status'=>'success','records'=>$resultArray];
    }

    /*public function visualById($id){

    	$model = GV::find($id);
    	$value = $model;
		$tempArray = [];
		$tempArray['id'] = $value->id;
		$tempArray['visual_name'] = $value->visual_name;
		$tempArray['dataset_id'] = $value->dataset_id;
		$datasetData = DL::find($value->dataset_id);
		$dataTableData = DB::table($datasetData->dataset_table)->select(json_decode($value->columns))->where('id',1)->first();
		$columnArray = [];
		foreach(json_decode($value->columns) as $colKey => $colValue){
			$columnArray[$colValue] = $dataTableData->{$colValue};
		}
        unset($dataTableData->id);
        $GVQModel = GVQ::where('visual_id',$id)->first();
		$tempArray['columns'] = $columnArray;
        $tempArray['all_columns'] = $dataTableData;
		$tempArray['gen_columns'] = json_decode($value->query_result);
        $tempArray['filters'] = json_decode($GVQModel->query);
		$tempArray['created_by'] = $value->createdBy->name;
		$tempArray['created_at'] = $value->created_at->format('Y-m-d H:i:s');

    	return ['status'=>'success','records'=>$tempArray];
    }*/

    /*public function visualById($id){
        $model = GV::find($id);
        $tempArray = [];
        $tempArray['id'] = $model->id;
        $ColumnsCount = json_decode($model->filter_counts);
        $data_columns = json_decode($model->columns);
        $datasetData = DL::find($model->dataset_id);
        $dataTableData = DB::table($datasetData->dataset_table)->where('id',1)->first();
        
        $columnsArray = [];
        foreach ($ColumnsCount as $key => $col) {
            $columnsArray[$key]['column_name'] = $dataTableData->{$key};
            foreach($col as $ikey => $icol){
                $columnsArray[$key]['column_data'][] = $icol->{$key};
            }
        }
        $response['filters'] = $columnsArray;
        foreach ($data_columns as $columnKey => $columnValue) {
            $response['column_names'][] = $dataTableData->{$columnValue};
        }
        $dataArary = [];
        foreach(json_decode($model->query_result) as $key => $value){
            $dataArary[$key]['column_name'] = $dataTableData->{$key};
            $dataArary[$key]['column_data'] = $value;
        }
       
        //Process Data 
        $temp_array = [];
        foreach($dataArary as $key => $value){
            $column_name = $value['column_name'];
            $column_data = [];
            foreach ( $value['column_data'] as $column_key => $column_value ) {
                $column_data[$column_value->$key] = $column_value->count;
                
            }
            $temp_array[$column_name] = $column_data;
        }
        
        //Process Data according to google charts
        $data_keys = [];
        foreach($temp_array as $key => $value){
            foreach ( $value as $k => $v ) {
                $data_keys[] = $k;
            }
        }
        
        $final_data = [];
        $final_data['variables'] = $data_keys;
        foreach($temp_array as $key => $value){
            $temp_data_row = [];
            foreach ( $data_keys as $k => $v ) {    
                if(isset($value[$v])){
                    $temp_data_row[] = $value[$v];
                } else{
                    $temp_data_row[] = 0;
                }
            }
            $final_data[$key] = $temp_data_row;
        }
        
        $chart_data = array();

        foreach ($final_data as $row => $columns) {
          foreach ($columns as $row2 => $column2) {
              $chart_data[$row2][$row] = $column2;
          }
        }
		
        //dd($chart_data);
        $response['data'] = $chart_data;
        return ['status'=>'success','records'=>$response];
    }*/

    public function visualById(Request $request){

        $responseArray = [];
        $mapChartsArray = [];
        $visual = GV::find($request->id);
        
        $dataset_id = $visual->dataset_id;
        $columns = json_decode($visual->columns, true);
        $chartType = json_decode($visual->chart_type, true);
        $countCharts = '';
        if($columns == null){
            return ['status'=>'error','message'=>'No settings found!'];
        }
        /*if(array_key_exists('count', $columns) && is_array(@$columns['count'])){
            $countCharts = $columns['count'];
        }*/
        $responseArray['num_of_charts'] = count($columns['column_one']);
        $chartsArray = [];
        $datatableName = DL::find($dataset_id);
        if($request->type == 'filter'){ 
               
            $dbObj =  DB::table($datatableName->dataset_table);
            $range_filter = json_decode($request->range_filters,true);
            $filter_multi = json_decode($request->filter_array_multi, true);
            if($range_filter != null){
                foreach ($range_filter as $key => $value) {
                    $dbObj->whereBetween($key,[$value['min'],$value['max']]);
                }
            }
            if($filter_multi != null){
                foreach($filter_multi as $key => $mValue){
                    $firstWhere = 0;
                    $where = [];
                    $dbObj->where(function($query) use ($mValue, $key){
                        foreach($mValue as $vKey => $vVal){
                            $query->orWhere($key, $vVal);
                        }
                    });
                }
            }
            
            if(!empty($request->filter_array) && $request->filter_array!=null && $request->filter_array!= 'undefined')
            {
                $filter_array = json_decode($request->filter_array,true);
                $dbObj->where($filter_array);
            }
            $datasetData =  $dbObj->get()->toArray();
        }else{

        	$datasetData = DB::table($datatableName->dataset_table)->where('id','!=',1)->get()->toArray();
        }
        $dataProce = [];
        foreach($datasetData as $colKey => $value){
           
            $dataProce[] = (array)$value;
        
        }
        $datasetData = $dataProce;
        $datasetColumns = (array)DB::table($datatableName->dataset_table)->where('id',1)->first();
        foreach($columns['column_one'] as $key => $value){
            if($chartType[$key] == 'CustomMap'){
                $mapChartsArray[$key] = $this->createMaps($columns,$key);
            }
            $columnData = [];
            switch($columns['formula'][$key]){

                case'count':
                    $tempArray = [];
                    $resultData = [];
                    $resultData[$value] = $this->generateCountColumns($value,$datatableName->dataset_table,$request->type == 'filter'?true:false,json_decode($request->filter_array,true),json_decode($request->filter_array_multi, true),json_decode($request->range_filters,true));
                    if($chartType[$key] != 'CustomMap'){
	                    foreach($columns['columns_two'][$key] as $colKey => $colVal){
	                        $resultData[$colVal] = $this->generateCountColumns($colVal,$datatableName->dataset_table,$request->type == 'filter'?true:false,json_decode($request->filter_array,true),json_decode($request->filter_array_multi, true),json_decode($request->range_filters,true));
	                    }
	                }
                    $resultCorrectData = $this->correctDataforCount($resultData,$datasetColumns);
                    $columnData = $resultCorrectData;
                break;

                case'addition':
                    $additionData = $this->getDataforAddition($value, $key, $columns, $datatableName->dataset_table, $request);
                    $singleVal = array_column($additionData,$value);
                    array_unshift($singleVal,$datasetColumns[$value]);
                    $columnData[] = $singleVal;
                    foreach($columns['columns_two'][$key] as $k => $v){
                        $singleVal = array_column($additionData, $v);
                        array_unshift($singleVal,$datasetColumns[$v]);
                        $columnData[] = $singleVal;
                    }
                break;

                case'no':
                    $arrayData = array_column($datasetData, $value);
                    array_unshift($arrayData,$datasetColumns[$value]);
                    $columnData[] = $arrayData;
                    foreach($columns['columns_two'][$key] as $colKey => $colVal){
                        $arrayData = array_column($datasetData, $colVal);
                        array_unshift($arrayData,$datasetColumns[$colVal]);
                        if($chartType[$key] != 'CustomMap'){
                            $arrayData = array_merge(array($arrayData[0]),array_map('intval', array_slice($arrayData, 1)));
                        }
                        $columnData[] = $arrayData;
                    }
                    if($chartType[$key] == 'CustomMap'){
                        $extraData = array_column($datasetData, $columns['viewData'][$key]);
                        array_unshift($extraData,$datasetColumns[$columns['viewData'][$key]]);
                    }
                break;
            }
            $chartsArray[$key] = $columnData;
        }

        // if(!empty(json_decode($visual->filter_columns))){
        $filter_chk = json_decode($visual->filter_columns,true);
     
        if(!empty($filter_chk) && !empty($filter_chk["filter_1"]["column"]) && !empty($filter_chk["filter_1"]["type"]) ){
            $filtersArray = $this->getFIlters($datatableName->dataset_table, json_decode($visual->filter_columns, true), $datasetColumns);
        }else{
            $filtersArray = [];
        }
        $transposeArray = [];
        foreach($chartsArray as $tKey => $tValue){
            $transposeArray[$tKey] = $this->transpose($tValue);
        }
        $globalVisualSettings = GS::where('meta_key','visual_setting')->first();
        $responseArray['maps']  =   $mapChartsArray;
        $responseArray['map_display_val'] = @$extraData;
        $responseArray['chart_data'] = $transposeArray;
        $responseArray['filters'] = $filtersArray;
        $responseArray['chart_types'] = $visual->chart_type;
        $responseArray['default_settings'] = $globalVisualSettings->meta_value;
        $responseArray['settings'] = $columns['visual_settings'];
        $responseArray['titles'] = $columns['title'];
        $responseArray['status'] = 'success';
        $responseArray['visual_status'] = $visual->status;
        return $responseArray;

    }

    protected function getDataforAddition($col_one, $key, $columns, $table, $request){

        $result = DB::table($table);
        $result->select($col_one);
        foreach($columns['columns_two'][$key] as $iKey => $iVal){
            $result->selectRaw('SUM('.$iVal.') as '.$iVal);
        }
        $result->groupBy($col_one);
        $result->where('id','!=',1);
        if($request->type == 'filter'){
            $result->where(json_decode($request->filter_array, true));
            if(json_decode($request->filter_array_multi, true) != null){
                foreach(json_decode($request->filter_array_multi, true) as $key => $mValue){
                    $firstWhere = 0;
                    foreach($mValue as $vKey => $vVal){
                        if($firstWhere == 0){
                            $result->Where($key,$vVal);
                        }else{
                            $result->orWhere($key,$vVal);
                        }
                        $firstWhere++;
                    }
                }
            }

            if(json_decode($request->range_filters,true) != null){
                foreach (json_decode($request->range_filters,true) as $key => $value) {
                    $result->whereBetween($key,[$value['min'],$value['max']]);
                }
            }
        }
        return json_decode(json_encode($result->get()->toArray()), true);
    }

    protected function createMaps($columnsData, $chart){
        $mapModel = null;
        try{
            $mapModel = Map::find($columnsData['mapArea'][$chart]);
        }catch(\Exception $e){

        }
        
        if($mapModel == null){
            $mapMd = GMap::find($columnsData['mapArea'][$chart]);
            return $mapMd->map_data;
        }else{
            return $mapModel->map_data;
        }
    }

    protected function transpose($array) {
        array_unshift($array, null);
        return call_user_func_array('array_map', $array);
    }


   
    public function getFIlters($table, $columns, $columnNames){
        
        $columnsWithType = $columns;
        $columns = (array)$columns;
        $columns = array_column($columns, 'column');
        $resultArray = [];
        $model = DB::table($table)->select($columns)->where('id','!=',1)->get()->toArray();
        $tmpAry = [];
        $max =0;
        foreach($model as $k => $v){
            
            $tmpAry[] = (array)$v;
        }
        
        
        $index = 1;
        foreach($columns as $key => $value){           
            $filter = [];
            if($columnsWithType['filter_'.$index]['type'] == 'range'){
               
                $allData = array_column($tmpAry, $value);
                $min = min($allData);
                $max = max($allData);
                $filter['column_name'] = $columnNames[$value];
                $filter['column_min'] = (int)$min;
                $filter['column_max'] = (int)$max;
                $filter['column_type'] = $columnsWithType['filter_'.$index]['type'];
            }else{
                $filter['column_name'] = $columnNames[$value];
                $filter['column_data'] = array_unique(array_column($tmpAry, $value));
                $filter['column_type'] = $columnsWithType['filter_'.$index]['type'];
            }
            
            $index++;
            $data[$value] = $filter;
        }
     
        return $data;
    }

    protected function generateCountColumns($column, $table, $filters = false, $filterArray, $filter_multi, $range_filter){

        $result = DB::table($table);
        $result->select([DB::raw('COUNT(id) as count'),$column]);
        if($filters == true){
            if($filterArray != null){
                $result->where($filterArray);
            }

            if($filter_multi != null){
                foreach($filter_multi as $key => $mValue){
                    $firstWhere = 0;
                    foreach($mValue as $vKey => $vVal){
                        if($firstWhere == 0){
                            $result->Where($key,$vVal);
                        }else{
                            $result->orWhere($key,$vVal);
                        }
                        $firstWhere++;
                    }
                }
            }

            if($range_filter != null){
                foreach ($range_filter as $key => $value) {
                    $result->whereBetween($key,[$value['min'],$value['max']]);
                }
            }

        }
        $result->groupBy($column)->where('id','!=',1);
        $result = $result->get()->toArray();
        /*if($filters == true && $filterArray != null){
        
            $result = DB::table($table)->select([DB::raw('COUNT(id) as count'),$column])->where($filterArray)->groupBy($column)->get()->toArray();
        }else{
            $result = DB::table($table)->select([DB::raw('COUNT(id) as count'),$column])->groupBy($column)->get()->toArray();
        }*/
        return $result;
    }

    protected function correctDataforCount($dataForProcess,$datasetColumns){
        
        if(empty($dataForProcess)){
            return [];
        }

        $dataProce = [];
        foreach($dataForProcess as $colKey => $value){
            $temp = [];
            foreach($value as $k => $v){
                $temp[] = (array)$v;
            }
            $dataProce[$colKey] = $temp;
        }
        $dataForProcess = $dataProce;

        $columns = [];
        $tempArray = [];
        foreach($dataForProcess as $ky => $vl){
            $tempArray[] = array_column($vl, $ky);
        }

        $columnOne = call_user_func_array('array_merge',$tempArray);
        $addColumn = $columnOne;

        array_unshift($addColumn, 'String');
        $columns[] = $addColumn;

        foreach($dataForProcess as $k => $v){
            $tempArray = [];
            foreach($columnOne as $key => $value){
                $key = array_search($value, array_column($v, $k));
                if($key === false){
                    $tempArray[] = 0;
                }else{
                    $tempArray[] = $v[$key]['count'];
                }
            }
            array_unshift($tempArray, $datasetColumns[$k]);
            $columns[] = $tempArray;
        }
        return $columns;
    }

    public function getColumnByDataset($id){

        $model = DL::find($id);
        $datasetTable = $model->dataset_table;
        $model = DB::table($datasetTable)->first();
        unset($model->id);
        return ['status'=>'success','columns'=>$model];
    }

    public function getVisualDetails($id){
        $mapArray = [];
        $default_setting = GS::select('meta_value')->where("meta_key","default_setting")->first();
        $visualMeta = VisualMeta::select(['key','value'])->where('visualization_id',$id)->get()->toArray();
        $model = GV::find($id);
     
        $vSettings = GS::where('meta_key','visual_setting')->first();
        $settings = json_decode($model->columns);
        $returnArray['visual_name'] = $model->visual_name;
        $returnArray['dataset_id'] = $model->dataset_id;
        $returnArray['columns'] = $model->columns;
        $returnArray['filter_columns'] = $model->filter_columns;
        $returnArray['visual_settings'] = @$settings->visual_settings;
        $returnArray['chart_types'] = $model->chart_type;
        $returnArray['visual_set'] = $vSettings;
        $returnArray['meta'] = json_encode($visualMeta);
        $returnArray['default_setting'] = $default_setting->meta_value;

        $adminMap = DB::table('maps')->select(['id','title'])->where('status','enable')->get();
        foreach ($adminMap as $key => $value) {
            foreach ($value as $nkey => $nvalue) {
                    $map[$nkey]  = $nvalue; 
                } 
                array_push($mapArray, $map);      
        }
        $mapData  = Map::orderBy('title','ASC')->select(['id','title'])->where('status','enable')->get()->toArray();
       
        foreach ($mapData as $mkey => $mvalue) {
             foreach ($mvalue as $k => $v) {
                $umap[$k]  = $v; 
                } 
                array_push($mapArray, $umap);      
        }

        return ['status'=>'success','data'=>$returnArray,'map_list'=> $mapArray];
    }
    public function saveVisualData(Request $request){
        $validate = $this->validateRequest($request);
        if($validate['status'] == 'false'){
            return ['status'=>'error','message'=>$validate['message']];
        }
         $columns = json_decode($request->columns, true);
         //dump()
        //unset($columns['formula']);
        $filterCOlumns = json_decode($request->filter_cols, true);
       
        $model = GV::find($request->visual_id);
        $model->visual_name = $request->visual_name;
        $model->dataset_id = $request->dataset_id;
        $model->columns = json_encode($columns);
        $model->filter_columns = $request->filter_cols;
        $model->chart_type = $request->chartTypes;
        $model->created_by = Auth::user()->id;
        
        $model->save();
        // VisualMeta
        $metaList = json_decode($request->theme_settigs);
        foreach($metaList as $key => $value){
            $visualMeta = VisualMeta::where('visualization_id',$request->visual_id)->where('key',$key)->first();
            if($visualMeta == null){
                $VM = new VisualMeta;
                $VM->visualization_id = $request->visual_id;
                $VM->key = $key;
                $VM->value = $value;
                $VM->save();
            }else{
                $visualMeta->value = $value;
                $visualMeta->save();
            }
        }
        return ['status'=>'success','message'=>'Visual update successfully!'];
    }

    public function validateRequest($request){

        if($request->has('dataset_id') && $request->has('visual_name') && $request->has('columns') && $request->has('filter_cols') && $request->has('visual_id')){
            if($request->dataset_id != 'undefined' || $request->visual_name != 'undefined' || $request->columns != 'undefined' || $request->filter_cols != 'undefined' || $request->visual_id != 'undefined'){
                $return = ['status'=>'true','message'=>''];
                return $return;
            }else{
                $return = ['status'=>'false','message'=>'Required fields are missing!'];
                return $return;
            }
        }else{
            $return = ['status'=>'false','message'=>'Required fields are missing!'];
            return $return;
        }
    }

    public function calculateVisuals($id){
        
        $model = GV::find($id);
        $dataset_id = $model->dataset_id;
        $datasetTable = DL::find($dataset_id)->dataset_table;
        $filterColumns = json_decode($model->filter_columns);
        $columnsUniqueData = [];
        foreach($filterColumns as $colKey => $column){

            $result = DB::table($datasetTable)->select($column)->where('id','!=',1)->groupBy($column)->get()->toArray();
            $columnsUniqueData[$column] = $result;
        }
        $correctArray = [];
        foreach ($columnsUniqueData as $key => $value) {
            foreach($value as $ikey => $ival){
                $correctArray[$key][] = $ival->{$key};
            }
        }
        function permutations(array $array, $inb = false){

            switch (count($array)){
                case 1:
                    return reset($array);
                    break;
                case 0:
                    throw new InvalidArgumentException('Requires at least one array');
                    break;
            }
            $keys = array_keys($array);
            $a = array_shift($array);
            $k = array_shift($keys);
            $b = permutations($array, 'recursing');
            $return = array();
            foreach ($a as $nk => $v) {
                if($v){
                    foreach ($b as $bk => $v2) {
                        if($inb == 'recursing')
                            $return[] = array_merge(array($v), (array) $v2);
                        else
                            $return[] = array($k => $v) + array_combine($keys, (array)$v2);
                    }
                }
            }
            return $return;
        }
        if(count($correctArray) != 1){
            $combinations = permutations($correctArray);
        }else{
            $correct = [];
            foreach($correctArray as $k => $v){
                foreach($v as $ik => $iv){
                    $correct[][$k] = $iv;
                }
            }
            $combinations = $correct;
        }
        $columns = json_decode($model->columns);
        foreach($combinations as $comKey => $comVal){
            $resultArray = [];
            foreach($columns as $key => $column){
                $index = 1;
                $model = DB::table($datasetTable)->select([DB::raw('COUNT(id) as count'),$column])->where($comVal)->where('id','!=',1)->groupBy($column)->get();
                foreach($model as $mKey => $mVal){
                    $resultArray[$column][$index] = $mVal;
                    $index++;
                }
            }

            $checkExistence = GVQ::where([]);
            echo json_encode($comVal);
            dd();
            /*echo json_encode($resultArray);
            exit;*/
            dump($comVal);
            dump($resultArray);
        }
    }

    public function saveVisualSettings(Request $request){

        $model = GV::find($request->visual_id);
        $modelColumns = json_decode($model->columns,true);
        $modelColumns['visual_settings'][$request->chart][0] = $request->settings;
        $model->columns = json_encode($modelColumns);
        $model->save();
        return ['status'=>'success','message'=>'Settings saved successfully!'];
    }

    public function getVisualsFromDatsetID($dataset_id){

        $model = GV::select(['visual_name','id'])->where('dataset_id',$dataset_id)->get();

        $responseArray = [];
        $index = 0;
        foreach($model as $key => $value){
            $responseArray[$index]['id'] = $value->id;
            $responseArray[$index]['visual_name'] = $value->visual_name;
            $index++;
        }

        return ['status'=>'success','list_visuals'=>$responseArray];
    }

    


    public function EmbedVisualById(Request $request){
        $model = Embed::where('embed_token',$request->id)->first();
        $responseArray = [];
        $mapChartsArray = [];
        Session::put('org_id',$model->org_id);
        $visual = GV::find($model->visual_id);
        $dataset_id = $visual->dataset_id;
        $columns = json_decode($visual->columns, true);
        $chartType = json_decode($visual->chart_type, true);
        $embedCss = @$columns['embedCss'];
        $embedJS = @$columns['embedJS'];
        $countCharts = '';
        if($columns == null){
            return ['status'=>'error','message'=>'No settings found!'];
        }
        /*if(array_key_exists('count', $columns) && is_array(@$columns['count'])){
            $countCharts = $columns['count'];
        }*/

        $responseArray['num_of_charts'] = count($columns['column_one']);
        $chartsArray = [];
        $datatableName = DL::find($dataset_id);
        if($request->type == 'filter'){ 
               
            $dbObj =  DB::table($datatableName->dataset_table);
            $range_filter = json_decode($request->range_filters,true);
            $filter_multi = json_decode($request->filter_array_multi, true);
            if($range_filter != null){
                foreach ($range_filter as $key => $value) {
                    $dbObj->whereBetween($key,[$value['min'],$value['max']]);
                }
            }
            if($filter_multi != null){
                foreach($filter_multi as $key => $mValue){
                    $firstWhere = 0;
                    $where = [];
                    $dbObj->where(function($query) use ($mValue, $key){
                        foreach($mValue as $vKey => $vVal){
                            $query->orWhere($key, $vVal);
                        }
                    });
                }
            }
            
            if(!empty($request->filter_array) && $request->filter_array!=null && $request->filter_array!= 'undefined')
            {
                $filter_array = json_decode($request->filter_array,true);
                $dbObj->where($filter_array);
            }
            $datasetData =  $dbObj->get()->toArray();
        }else{

            $datasetData = DB::table($datatableName->dataset_table)->where('id','!=',1)->get()->toArray();
        }
        $dataProce = [];
        foreach($datasetData as $colKey => $value){
            $dataProce[] = (array)$value;       
        }
        $datasetData = $dataProce;
        $datasetColumns = (array)DB::table($datatableName->dataset_table)->where('id',1)->first();
        foreach($columns['column_one'] as $key => $value){
            if($chartType[$key] == 'CustomMap'){
                $mapChartsArray[$key] = $this->createMaps($columns,$key);
            }
            $columnData = [];
            switch($columns['formula'][$key]){

                case'count':
                    $tempArray = [];
                    $resultData = [];
                    $resultData[$value] = $this->generateCountColumns($value,$datatableName->dataset_table,$request->type == 'filter'?true:false,json_decode($request->filter_array,true),json_decode($request->filter_array_multi, true),json_decode($request->range_filters,true));
                    if($chartType[$key] != 'CustomMap'){
	                    foreach($columns['columns_two'][$key] as $colKey => $colVal){
	                        $resultData[$colVal] = $this->generateCountColumns($colVal,$datatableName->dataset_table,$request->type == 'filter'?true:false,json_decode($request->filter_array,true),json_decode($request->filter_array_multi, true),json_decode($request->range_filters,true));
	                    }
	                }
                    $resultCorrectData = $this->correctDataforCount($resultData,$datasetColumns);
                    $columnData = $resultCorrectData;
                break;

                case'addition':
                    $additionData = $this->getDataforAddition($value, $key, $columns, $datatableName->dataset_table, $request);
                    $singleVal = array_column($additionData,$value);
                    array_unshift($singleVal,$datasetColumns[$value]);
                    $columnData[] = $singleVal;
                    foreach($columns['columns_two'][$key] as $k => $v){
                        $singleVal = array_column($additionData, $v);
                        array_unshift($singleVal,$datasetColumns[$v]);
                        $columnData[] = $singleVal;
                    }
                break;

                case'no':
                    $arrayData = array_column($datasetData, $value);
                    array_unshift($arrayData,$datasetColumns[$value]);
                    $columnData[] = $arrayData;
                    foreach($columns['columns_two'][$key] as $colKey => $colVal){
                        $arrayData = array_column($datasetData, $colVal);
                        array_unshift($arrayData,$datasetColumns[$colVal]);
                        if($chartType[$key] != 'CustomMap'){
                            $arrayData = array_merge(array($arrayData[0]),array_map('intval', array_slice($arrayData, 1)));
                        }
                        $columnData[] = $arrayData;
                    }
                    if($chartType[$key] == 'CustomMap'){
                        $extraData = array_column($datasetData, $columns['viewData'][$key]);
                        array_unshift($extraData,$datasetColumns[$columns['viewData'][$key]]);
                    }
                break;
            }
            $chartsArray[$key] = $columnData;
        }

        // if(!empty(json_decode($visual->filter_columns))){
        $filter_chk = json_decode($visual->filter_columns,true);
     
        if(!empty($filter_chk) && !empty($filter_chk["filter_1"]["column"]) && !empty($filter_chk["filter_1"]["type"]) ){
            $filtersArray = $this->getFIlters($datatableName->dataset_table, json_decode($visual->filter_columns, true), $datasetColumns);
        }else{
            $filtersArray = [];
        }
        $transposeArray = [];
        foreach($chartsArray as $tKey => $tValue){
            $transposeArray[$tKey] = $this->transpose($tValue);
        }
        $globalVisualSettings = GS::where('meta_key','visual_setting')->first();
        $responseArray['maps']  =   $mapChartsArray;
        $responseArray['map_display_val'] = @$extraData;
        $responseArray['chart_data'] = $transposeArray;
        $responseArray['filters'] = $filtersArray;
        $responseArray['chart_types'] = $visual->chart_type;
        $responseArray['default_settings'] = $globalVisualSettings->meta_value;
        $responseArray['settings'] = $columns['visual_settings'];
        $responseArray['titles'] = $columns['title'];
        $responseArray['status'] = 'success';
        $responseArray['css_js'] = ['css'=>$embedCss,'js'=>$embedJS];
        return $responseArray;
    }

    public function getEmbedTokenFromVisualId(Request $request){
        $org_id = Auth::user()->organization_id;
        $model = Embed::where(['visual_id'=>$request->visual_id,'org_id'=>$org_id])->first();
        return ['status'=>'success','token'=>$model->embed_token];
    }

    public function createClone($visualID){

        $orgId = Auth::user()->organization_id;
        DB::select('CREATE TABLE cloning_visual as SELECT * FROM `'.$orgId.'_generated_visuals` WHERE id = '.$visualID);
        $newVisualID = DB::select('SELECT MAX(id) as maxId FROM `'.$orgId.'_generated_visuals`');
        $newVisualID = $newVisualID[0]->maxId + 1;
        DB::update('UPDATE cloning_visual SET id = '.$newVisualID);
        DB::insert('INSERT into '.$orgId.'_generated_visuals SELECT * FROM cloning_visual');
        DB::select('DROP TABLE cloning_visual');
        return ['status'=>'success','message'=>'Visualization cloned successfully!'];
    }
}
