<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrammarCat extends Model {

    var $table = 'engr_types';

    /**
     * The videos that belong to the playlist.
     */
    public function questions() {
        return $this->belongsToMany('App\Models\GrammarQuestion', 'engr_types_questions','type_id','question_id');
    }

    public function lessons() {
        return $this->hasMany('App\Models\GrammarLesson', 'the_loai');
    }  
}
