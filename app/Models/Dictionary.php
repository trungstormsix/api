<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dictionary extends Model
{
    var $table = 'common_word_mean';      
   
    public function word() {
        return $this->hasOne('App\Models\CommonWord','id', 'word_id');
    }  
}
