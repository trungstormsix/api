<?php

namespace App\Models\Quote;

use Illuminate\Database\Eloquent\Model;

class Author extends Model {

    var $table = 'authors';

    const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'updated_at';

//    protected $fillable = ['name', 'alias', 'description', 'parent_id', 'published', 'en'];


    public function quotes() {
        return $this->hasMany('App\Models\Quote\Quote', 'auth_id');
    }

}
