<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Model;

class Example extends Model {

//    protected $connection = 'mysql2';
    var $table = 'examples';
  
      public function words() {
        return $this->belongsToMany('App\Models\Course\Word', 'word_example','example_id','word_id');
    }
}
