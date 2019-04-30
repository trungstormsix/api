<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image\Images;
use App\Models\Image\ImgCat;
use Illuminate\Support\Facades\Session;

class ImageController extends Controller {

 
  
    public function __construct() {
        
    }

     public function allCats() {    
        $cats = ImgCat::where("published",1)->get();
        foreach($cats as &$cat){
            $cat->num_imgs =    DB::table('img_items')
                ->select(DB::raw('count(id) as count'))
                     ->where('cat_id', $cat->id)->orderBy("count","DESC")->first()->count;
        }
        return $cats;
    }
 
    public function cats($cat_id = 2) {    
        $cats = ImgCat::where("published",1)->where("parent_id", $cat_id)->get();
        foreach($cats as &$cat){
            $cat->num_imgs =    DB::table('img_items')
                ->select(DB::raw('count(id) as count'))
                     ->where('cat_id', $cat->id)->orderBy("count","DESC")->first()->count;
        }
        return $cats;
    }

    public function getImagesByCat($cat_id = 1){
        if((int) $cat_id == 0){
            return;
        }
         
        $vocs = Images::where("cat_id", $cat_id)->get();
        return $vocs;
    }
    
    
    public function setVote(){
        $id = Input::get("id");
        $vote = Input::get("vote");
        if($vote > 0){
            Images::where("id",$id)->increment('liked');
        }else{
            Images::where("id",$id)->decrement('liked');
        }
       return Images::find($id)->liked;
    }

}
