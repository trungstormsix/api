<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
	protected $fillable =  ['name', 'display_name', 'description', 'created_at', 'updated_at'];

    var $table = 'permissions';
    /**
     * The videos that belong to the playlist.
     */
    public function Roles(){
    	return $this->belongsToMany('App\Models\Roles', 'permission_role');
    }
}