<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TruyenTheLoai extends Model {

    protected $connection = 'mysql2';
    var $table = 'the_loai';

    /**
     * The videos that belong to the playlist.
     */
    public function stories() {
        return $this->belongsToMany('App\Models\TruyenNgan', 'theloai_truyenngan','the_loai','truyen_ngan');
    }

     

}
