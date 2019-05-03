<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrammarLesson extends Model {

    var $table = 'engr_articles';
//    public $timestamps = false;
    const UPDATED_AT = 'date_edit';
    const CREATED_AT = 'date_edit';
    protected $fillable = ["title","published","content","link","order","intro_img"];
     /**
     * The videos that belong to the playlist.
     */
    public function questions() {
        return $this->belongsToMany('App\Models\GrammarQuestion', 'engr_questions_articles','id_articles','id_questions');
    }

    public function cat() {
        return $this->belongsToMany('App\Models\GrammarCat', 'engr_types_articles',"truyen_ngan","the_loai");
        
    }  
}