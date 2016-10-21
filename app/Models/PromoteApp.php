<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoteApp extends Model
{
    var $table = 'prm_apps';
    public $timestamps = false;

    
     public function group()
    {
         return $this->belongsTo('App\Models\PromoteGroup', 'group_id');
    }
}
