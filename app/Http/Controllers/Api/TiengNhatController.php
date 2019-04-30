<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Permission;
use App\User;
use App\library\DomParser;
 use File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

use App\Models\Articles;
use App\Models\Categories;

use App\Models\Test\Test;
use App\Models\Test\Group;
use App\Models\Test\Question;

class TiengNhatController extends Controller {
 
    public function __construct() {
        
    }
 
    public function cats(){
        $cats = Categories::where('parent_id',1)->get();
		$results = [];
		foreach($cats as $cat){
			$cat1s = Categories::where('parent_id',$cat->id)->get();
			$results[] = $cat;
			foreach($cat1s as $cat1){
				$results[] = $cat1;
			}
		}
        return response()->json($results); 
    }
    
     public function lessons($cat_id = 2){
        $lang = Input::get("lang","vi");     
        $query = Articles::where('cat_id',$cat_id);
        if($lang){
            $query->where("lang", $lang);
        }
        $lessons = $query->get();
        return response()->json($lessons); 
    }
    
     public function lesson($id = 1){
        $lesson = Articles::find($id);
        return response()->json($lesson); 
    }
    
    
    public function testsByCat($cat_id = 2){
        $cat = Categories::find($cat_id);
        $tests = $cat->tests ;
        return response()->json($tests); 
    }
     public function test($test_id = 1){
        $test = Test::find($test_id);
        $test->groups = $test->groups ;
        $test->questions();
        return response()->json($test); 
    }
	
	    public function setVote(){
        $id = Input::get("id");
        $vote = Input::get("vote");
        if($vote > 0){
            Articles::where("id",$id)->increment('liked');
        }else{
            Articles::where("id",$id)->decrement('liked');
        }
       return Articles::find($id)->liked;
    }
}
