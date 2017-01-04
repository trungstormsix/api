<?php

namespace App\Models\IELTS;

use Illuminate\Database\Eloquent\Model;

class IELTSArticle extends Model {
    protected $fillable =  ['title', 'category',  'updated', 'status','article','is_pro'];

    var $table = 'il_articles';

    //    public $timestamps = false;
    const UPDATED_AT = 'updated';
    const CREATED_AT = 'updated';

    public function post() {
        return $this->belongsTo('App\Models\IELTS\IELTSArticle', 'category');
    }

}
