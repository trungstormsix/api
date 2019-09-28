<?php

namespace App\Models\Picvoc;

use Illuminate\Database\Eloquent\Model;

class PicvocCat extends Model
{
    var $table = 'picvoc_categories';     
    public $timestamps = false;
    public function parent(){
        return $this->find($this->parent_id);
    }

    public function vocs(){
        return $this->belongsToMany('App\Models\Picvoc\Voc', 'picvoc_cat_voc','cat_id','voc_id');
    }
   
}
