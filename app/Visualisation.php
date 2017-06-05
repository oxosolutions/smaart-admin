<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
use Auth;
class Visualisation extends Model
{
    protected $table;
    public function __construct()
    {
        parent::__construct();
       if(Session::get('org_id') == null){
        $this->table = Auth::user()->organization_id.'_visualisations';        
      }else{
        $this->table = Session::get('org_id').'_visualisations';
      }
    }
    use SoftDeletes;
    protected $fillable = ['name','description','dataset_id','created_by'];
    protected $dates = ['deleted_at'];
    protected $softDelete = true;

    function createdBy(){
    	return $this->belongsTo('App\User','created_by','id');
    }

    public function dataset(){
    	return $this->belongsTo('App\DatasetsList','dataset_id','id');
    }

    public function charts(){
        return $this->hasMany('App\VisualizationChart','visualization_id','id');
    }

    public function meta(){
        return $this->hasMany('App\VisualizationMeta','visualization_id','id');
    }

    public function chart_meta(){
        return $this->hasMany('App\VisualizationChartMeta','visualization_id','id');
    }

    static function visualisationCount()
    {
        return self::count();
    }


}
