<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoteGroup extends Model
{
    var $table = 'prm_groups';
    /**
     * The videos that belong to the playlist.
     */
     public function apps()
    {
         return $this->hasMany('App\Models\PromoteApp', 'group_id');
    }
    
}
