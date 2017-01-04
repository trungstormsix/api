<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Examples extends Model
{
    var $table = 'examples';

    public function vocabularies()
    {
		return $this->belongsTo('App\Models\Vocabularies');
    }
}