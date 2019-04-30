<?php

namespace App\Models\Makeup;

use Illuminate\Database\Eloquent\Model;

class Type extends Model {

    protected $connection = 'mysql2';
    var $table = 'mk_types';
    public $timestamps = false;
    protected $fillable =  ['title', 'title_display',  'description', 'published'];

    /**
     * The videos that belong to the playlist.
     */
    public function articles() {
        return $this->belongsToMany('App\Models\Makeup\Article', 'mk_types_articles','the_loai','truyen_ngan');
    }
     

}
