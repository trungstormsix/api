<?php

namespace App\Models\Makeup;

use Illuminate\Database\Eloquent\Model;

class Article extends Model {

    protected $connection = 'mysql2';
    var $table = 'mk_articles';
    const UPDATED_AT = 'date_edit';
    const CREATED_AT = 'date_edit';
    protected $fillable =  ['title', 'intro_img',  'tac_gia','link', 'content','vote','params','on_face'];

    /**
     * The videos that belong to the playlist.
     */
    public function cats() {
        return $this->belongsToMany('App\Models\Makeup\Type', 'mk_types_articles','truyen_ngan','the_loai');
    }

     

}
