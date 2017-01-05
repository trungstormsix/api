<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoteApp extends Model
{
    var $table = 'prm_apps';
    public $timestamps = false;
    protected $fillable = ['title', 'package', 'image','description', 'group_id', 'status', 'publish_up', 'publish_down','ad_rate','key_startapp'];

    
     public function group()
    {
         return $this->belongsTo('App\Models\PromoteGroup', 'group_id');
    }
}
