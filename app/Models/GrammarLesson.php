<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrammarLesson extends Model {

    var $table = 'engr_articles';

     /**
     * The videos that belong to the playlist.
     */
    public function questions() {
        return $this->hasMany('App\Models\GrammarQuestion', 'type_id');
    }

    public function cat() {
        return $this->belongTo('App\Models\GrammarCat', 'engr_questions_articles');
    }  
}
