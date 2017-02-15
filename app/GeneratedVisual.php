<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneratedVisual extends Model
{
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
