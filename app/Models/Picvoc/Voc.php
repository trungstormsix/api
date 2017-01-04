<?php

namespace App\Models\Picvoc;

use Illuminate\Database\Eloquent\Model;

class Voc extends Model {

    var $table = 'picvoc_vocabularies';
//    public $timestamps = false;

    const UPDATED_AT = 'updated';
    const CREATED_AT = 'updated';

    public function cats() {
        return $this->belongsToMany('App\Models\Picvoc\PicvocCat', 'picvoc_cat_voc', 'voc_id', 'cat_id');
    }

}
