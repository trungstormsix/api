<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    var $table = 'categories';

    public function vocabularies()
    {
         return $this->hasMany('App\Models\Vocabularies', 'cate_id');
    }
}