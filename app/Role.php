<?php

namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $fillable =  ['name', 'display_name', 'description', 'created_at', 'updated_at'];

    /**
     * The videos that belong to the playlist.
     */
    public function Permissions(){
    	return $this->belongsToMany('App\Models\Permissions', 'permission_role');
    }
}