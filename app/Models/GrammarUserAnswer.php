<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrammarUserAnswer extends Model {

    var $table = 'engr_user_answer';
    const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'updated_at';
     /**
     * The videos that belong to the playlist.
     */
    public function question() {
        return $this->belongTo('App\Models\GrammarQuestion', 'engr_questions',"qid");

    }
 
}