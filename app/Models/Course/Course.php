<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Model;

class Course extends Model {

//    protected $connection = 'mysql2';
    var $table = 'courses';
  
    public function subjects(){
        return $this->hasMany('App\Models\Course', 'course_id');
    }
}
