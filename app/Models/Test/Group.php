<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;

class Group extends Model {

    var $table = 'test_groups';

    public function questions() {
        return $this->hasMany('App\Models\Test\Question', 'group_id');
    }

    public function tests() {
        return $this->belongsToMany('App\Models\Test\Test', 'test_test_group','group_id','test_id');
    }
    
     public function articles() {
        return $this->belongsToMany('App\Models\Test\Test', 'test_group_article','group_id','article_id');
    }
}
