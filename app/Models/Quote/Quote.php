<?php

namespace App\Models\Quote;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model {

    var $table = 'quotes';

    const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'updated_at';

//    protected $fillable = ['title', 'alias', 'thumbnail', 'content', 'intro', 'cat_id', 'published', 'link', 'lang'];

    public function cats() {
        return $this->belongsToMany('App\Models\Quote\Quote', 'quote_cat', 'quote_id', 'cat_id');
    }
    public function getCatIdsAttribute() {
//        https://stackoverflow.com/questions/32089782/get-ids-array-from-related-laravel-model-which-is-having-belongstomany-relations
        return $this->cats()->pluck('quote_cat.cat_id')->all();
//         return $this->cats()->allRelatedIds();
    }
    public function tags() {
        return $this->belongsToMany('App\Models\Quote\Quote', 'quote_tag', 'quote_id', 'tag_id');
    }
    public function author() {
        return $this->belongsTo('App\Models\Quote\Author', 'auth_id');
    }

}
