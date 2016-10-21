<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable =  ['name', 'display_name', 'description', 'created_at', 'updated_at'];

    /**
     * The videos that belong to the playlist.
     */
    public function Permissions(){
    	return $this->belongsToMany('App\Models\Permissions', 'permission_role');
    }
}