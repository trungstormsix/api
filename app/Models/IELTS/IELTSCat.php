<?php

namespace App\Models\IELTS;

use Illuminate\Database\Eloquent\Model;

class IELTSCat extends Model {

    var $table = 'il_categories';
    public $timestamps = false;

    public function articles() {
        return $this->hasMany('App\Models\IELTS\IELTSArticle', 'category');
    }
    
    public function vocs(){
        return $this->belongsToMany('App\Models\IELTS\IELTSVocabulary', 'il_cat_voc','cat_id','voc_id');
    }

}
