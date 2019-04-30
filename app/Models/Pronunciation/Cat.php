<?php

namespace App\Models\Pronunciation;

use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    var $table = 'pronunciation_cats';     
//    public $timestamps = false;
    const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'updated_at';

    public function vocs(){
        return $this->hasMany('App\Models\Pronunciation\Voc', 'cat_id');
    }
    
     public function questions() {
        return $this->belongsToMany('App\Models\Pronunciation\Question', 'pronunciation_question_cat','cat_id','question_id');
     }

}
