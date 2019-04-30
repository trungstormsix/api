<?php

namespace App\Models\Stories;

use Illuminate\Database\Eloquent\Model;

class Story extends Model {

//    protected $connection = 'mysql2';
    var $table = 'est_dialogs';
    const UPDATED_AT = 'updated';

    public function types() {
        return $this->belongsToMany('App\Models\Stories\StoryType', 'est_cat_dl','dl_id','cat_id');
    }

}