<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use Auth;
class VisualizationMeta extends Model
{
    protected $table;
    public function __construct()
    {
        parent::__construct();
        $this->table = Session::get('org_id').'_visualization_metas';
        if(Session::get('org_id') == null){
            $this->table = Auth::user()->organization_id.'_visualization_metas';        
        }else{
        $this->table = Session::get('org_id').'_visualization_metas';
      }

    }

    protected $fillable = ['visualization_id','key','value'];
}
