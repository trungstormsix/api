<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vocabularies extends Model
{
    var $table = 'vocabularies';

    public function examples()
    {
         return $this->hasMany('App\Models\Examples', 'voc_id');
    }
}