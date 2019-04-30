<?php

namespace App\Models\Quote;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model {

    public $timestamps = false;
    var $table = 'quote_tags';

    const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'updated_at';

//    protected $fillable = ['name', 'alias', 'description', 'parent_id', 'published', 'en'];


    public function quotes() {
        return $this->belongsToMany('App\Models\Quote\Quote', 'quote_tag', 'tag_id', 'quote_id');
    }

}
