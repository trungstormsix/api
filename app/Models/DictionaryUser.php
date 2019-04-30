<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DictionaryUser extends Model
{
    var $table = 'common_word_user';      
    const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'updated_at';
    
    public function mean() {
        return $this->hasOne('App\Models\Dictionary','id', 'common_word_mean_id');
    }  
}
