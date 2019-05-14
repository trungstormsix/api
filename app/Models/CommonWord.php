<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommonWord extends Model
{
    var $table = 'common_words';     
 
   public function means() {
        return $this->hasMany('App\Models\Dictionary','word_id', 'id');
    } 
}
