<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use Auth;
class GeneratedVisual extends Model
{
    protected $table;
    public function __construct()
    {
        parent::__construct();
        $this->table = Session::get('org_id').'_generated_visuals';
        if(Session::get('org_id') == null){
        foreach(Auth::user()->meta as $key => $value){
            if($value->key == 'organization'){
                $this->table = $value->value.'_generated_visuals';
                break;
            }
        }
      }else{
        $this->table = Session::get('org_id').'_generated_visuals';
      }

    }
    protected $fillable = ['visual_name','dataset_id','columns','query_result'];

    public function datasetName(){

    	return $this->belongsTo('App\DatasetsList','dataset_id','id');
    }

    function createdBy(){

    	return $this->belongsTo('App\User','created_by','id');
    }

    public static function visualList(){
    	
    	return self::orderBy('id')->pluck('visual_name','id');
    }

    public static function chartTypes(){
        return [

                'ColumnChart' => 'Column Chart',
                'BarChart' => 'Bar Chart',
                'AreaChart' => 'Area Chart',
                'PieChart' => 'Pie Chart',
                'LineChart' => 'Pie Chart',
                'BubbleChart' => 'Bubble Chart'
        ];
    }
}
