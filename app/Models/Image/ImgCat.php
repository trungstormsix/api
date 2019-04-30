<?php

namespace App\Models\Image;

use Illuminate\Database\Eloquent\Model;

class ImgCat extends Model
{    
    var $table = 'img_cat';
    protected $fillable = ['name',  'description', 'parent_id', 'published' ];
  
}
