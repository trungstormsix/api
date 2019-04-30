<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articles extends Model {

    protected $fillable = ['title', 'alias', 'thumbnail', 'content', 'intro', 'cat_id', 'published', 'link', 'lang'];

    public function cat() {
        return $this->belongsTo('App\Models\Categories', 'cat_id');
    }

}
