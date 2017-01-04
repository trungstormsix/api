<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListeningReport extends Model {

    var $table = 'enli_reports';

    //    public $timestamps = false;
    const UPDATED_AT = 'updated';
    const CREATED_AT = 'updated';

    /**
     * The videos that belong to the playlist.
     */
    public function dialog() {
        return $this->belongsTo('App\Models\ListeningDialog', 'dl_id');
    }
}
