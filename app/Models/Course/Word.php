<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Model;

class Word extends Model {

    var $table = 'words';
  
     
    public function examples() {
        return $this->belongsToMany('App\Models\Course\Example', 'word_example','word_id','example_id');
    }
}
