<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrammarQuestion extends Model {

    var $table = 'engr_questions';
    public $timestamps = false;
    /**
     * The videos that belong to the playlist.
     */
    public function cat() {
        return $this->belongsToMany('App\Models\GrammarCat', 'engr_types_questions','question_id','type_id');
     }

    public function article() {
        return $this->belongsToMany('App\Models\GrammarLesson', 'engr_questions_articles','id_questions','id_articles');
    }  
}
