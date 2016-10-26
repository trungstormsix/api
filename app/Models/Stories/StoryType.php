<?php

namespace App\Models\Stories;

use Illuminate\Database\Eloquent\Model;

class StoryType extends Model {

//    protected $connection = 'mysql2';
    var $table = 'est_categories';

    /**
     * The videos that belong to the playlist.
     */
    public function stories() {
        return $this->belongsToMany('App\Models\Stories\Story', 'est_cat_dl','cat_id','dl_id');
    }

     

}
