<?php

namespace App\Models\Picvoc;

use Illuminate\Database\Eloquent\Model;

class PicvocMean extends Model {

    var $table = 'picvoc_word_mean';
//    public $timestamps = false;

    const UPDATED_AT = 'updated';
    const CREATED_AT = 'updated';

    public function voc() {
        return $this->belongsTo('App\Models\Picvoc\Voc', 'voc_id');
    }

}
