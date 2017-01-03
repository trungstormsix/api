<?php

namespace App\Models\Funny;

use Illuminate\Database\Eloquent\Model;

class Image extends Model {

    protected $connection = 'mysql2';
    var $table = 'funny_images';

    public function likes() {
        return $this->belongsToMany('App\User', 'funny_image_like')->withPivot('liked');
    }

}
