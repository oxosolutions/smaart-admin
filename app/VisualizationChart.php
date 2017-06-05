<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
use Auth;
class VisualizationChart extends Model
{
	protected $table;
    public function __construct()
    {
        parent::__construct();
       	if(Session::get('org_id') == null){
	        $this->table = Auth::user()->organization_id.'_visualization_charts';        
	    }else{
	        $this->table = Session::get('org_id').'_visualization_charts';
	    }
    }
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $softDelete = true;
    protected $fillable = ['visualization_id','chart_title','primary_column','secondary_column','chart_type','status'];

    public function meta(){

    	return $this->hasMany('App\VisualizationChartMeta','chart_id','id');
    }

}
