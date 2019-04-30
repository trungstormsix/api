<?php

namespace App\Models\Image;

use Illuminate\Database\Eloquent\Model;

class Images extends Model {
    var $table = 'img_items';

    protected $fillable = ['title',  'thumb', 'main_img', 'more_images', 'cat_id', 'published','description', 'link'];

    public function cat() {
        return $this->belongsTo('App\Models\Image\ImgCat', 'cat_id');
    }

}
