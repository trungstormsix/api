<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;

class Test extends Model {

    var $table = 'test_tests';

    const CREATED_AT = 'updated_at';

    public function groups() {
        return $this->belongsToMany('App\Models\Test\Group', 'test_test_group','test_id','group_id');
    }
    
     public function cats() {
        return $this->belongsToMany('App\Models\Categories', 'test_test_category','test_id','cat_id');
    }
    
    public function questions() {
        $groups = $this->groups;
        foreach ($groups as &$group){
            $group->questions = $group->questions;
        }
    }
}
