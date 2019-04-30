<?php

namespace App\Models\IELTS;

use Illuminate\Database\Eloquent\Model;

class IELTSVocabulary extends Model {
 
    var $table = 'il_vocabularies';

    //    public $timestamps = false;
    const UPDATED_AT = 'updated';
    const CREATED_AT = 'updated';
 
    public function cats() {
        return $this->belongsToMany('App\Models\IELTS\IELTSCat', 'il_cat_voc', 'voc_id', 'cat_id');
    }

}
