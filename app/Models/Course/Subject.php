<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model {

//    protected $connection = 'mysql2';
    var $table = 'subjects';
  
    public function subjects(){
        return $this->hasMany('App\Models\Word', 'subject_id');
    }
}