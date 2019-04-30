<?php

namespace App\Models\Pronunciation;

use Illuminate\Database\Eloquent\Model;

class Question extends Model {

    var $table = 'engr_questions';
    public $timestamps = false;
    /**
     * The videos that belong to the playlist.
     */
    public function cats() {
        return $this->belongsToMany('App\Models\Pronunciation\Cat', 'pronunciation_question_cat','question_id','cat_id');
     }

    
}
