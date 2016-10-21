<?php

namespace App;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
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