<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    var $table = 'entest_question';    
     
    public function idioms(){
        return $this->hasMany('App\Models\Idiom', 'idiom_question');
    }
}
