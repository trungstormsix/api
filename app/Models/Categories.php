<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
	public $timestamps = false;
    
    protected $fillable = ['name', 'alias', 'description', 'parent_id', 'published', 'en'];
    
    
      public function tests() {
        return $this->belongsToMany('App\Models\Test\Test', 'test_test_category','cat_id','test_id');
    }
}
