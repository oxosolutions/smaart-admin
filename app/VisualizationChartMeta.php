<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
use Auth;
class VisualizationChartMeta extends Model
{
    protected $table;
    public function __construct()
    {
        parent::__construct();
       	if(Session::get('org_id') == null){
	        $this->table = Auth::user()->organization_id.'_visualization_chart_metas';        
	    }else{
	        $this->table = Session::get('org_id').'_visualization_chart_metas';
	    }
    }
    /*use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $softDelete = true;*/
    protected $fillable = ['chart_id','key','value'];
}
