<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
	protected $fillable =  ['name', 'display_name', 'description', 'created_at', 'updated_at'];

    var $table = 'roles';
    /**
     * The videos that belong to the playlist.
     */
    public function Permissions(){
    	return $this->belongsToMany('App\Models\Permissions', 'permission_role');
    }
}