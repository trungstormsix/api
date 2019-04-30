<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;

class  Question extends Model
{
    var $table = 'test_questions';    
         const CREATED_AT = 'updated_at';

    public function group() {
        return $this->belongTo('App\Models\Test\Group' );
    }  
}
