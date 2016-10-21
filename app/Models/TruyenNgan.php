<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TruyenNgan extends Model {

    protected $connection = 'mysql2';
    var $table = 'truyen_ngan';
    const UPDATED_AT = 'date_edit';
    
    /**
     * The videos that belong to the playlist.
     */
    public function cats() {
        return $this->belongsToMany('App\Models\TruyenTheLoai', 'theloai_truyenngan','truyen_ngan','the_loai');
    }

     

}
