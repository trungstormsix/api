<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListeningDialog extends Model {

    var $table = 'enli_dialogs';

    //    public $timestamps = false;
    const UPDATED_AT = 'updated';
    const CREATED_AT = 'updated';

    /**
     * The videos that belong to the playlist.
     */
    public function cats() {
        return $this->belongsToMany('App\Models\ListeningCat', 'enli_cat_dl', 'dl_id', 'cat_id')->withPivot('ordering');
    }

    public function grammars() {
        return $this->belongsToMany('App\Models\GrammarLesson', 'listening_grammar', 'dialog_id', 'lesson_id')->withPivot('ex');
    }

    public function questions() {
        $question_ids = json_decode($this->question);
        $questions = ListeningQuestion::whereIn('id', $question_ids)->get();
        return $questions;
    }
}
