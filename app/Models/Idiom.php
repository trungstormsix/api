<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Idiom extends Model
{
    var $table = 'id_idioms';     
    public $timestamps = false;

    public function questions(){
        return $this->belongsToMany('App\Models\VocQuestion', 'idiom_question');
    }
    
     public function cats(){
        return $this->belongsToMany('App\Models\IdiomCat', 'id_cat_id','id_id','cat_id');
    }
    
    public function examples(){
        $example_array = json_decode($this->example);
        if($example_array)
            return IdiomExample::whereIn('id', $example_array)->get();
        
        return null;
    }
}
