<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrammarLesson extends Model {

    var $table = 'engr_articles';
    public $timestamps = false;

     /**
     * The videos that belong to the playlist.
     */
    public function questions() {
        return $this->belongsToMany('App\Models\GrammarQuestion', 'engr_questions_articles','id_articles','id_questions');
    }

    public function cat() {
        return $this->belongTo('App\Models\GrammarCat', 'engr_questions_articles');
    }  
}