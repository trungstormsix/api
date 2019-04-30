<?php

namespace App\Models\Pronunciation;

use Illuminate\Database\Eloquent\Model;

class Voc extends Model
{
    var $table = 'pronunciation_vocs';     
//    public $timestamps = false;
    const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'updated_at';
    public function vocs(){
        return $this->belongsTo('App\Models\Pronunciation\Cat', 'cat_id');
    }
    
}
