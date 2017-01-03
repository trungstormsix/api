<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListeningCat extends Model {

    var $table = 'enli_cat';

 
    public function dialogs() {
        return $this->belongsToMany('App\Models\ListeningDialog', 'enli_cat_dl','cat_id','dl_id')->withPivot('ordering');
    }  
}
