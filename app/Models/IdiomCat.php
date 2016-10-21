<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdiomCat extends Model
{
    var $table = 'id_cats';     
    public $timestamps = false;
    
    public function idioms(){
        return $this->belongsToMany('App\Models\Idiom', 'id_cat_id','cat_id','id_id');
    }
   
}
