<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Pronunciation\Cat;
use App\Models\Pronunciation\Voc;

class PronunciationController extends Controller {

    public function __construct() {
        
    }
  public function cats() { 
//        $cats = Categories::paginate(10);
        $cats = Cat::where("published",1)->get();

        return response()->json($cats);
    }
    
    public function vocByCat($cat_id){
        $updated = Input::get("max_date","0000-00-00 00:00:01");
         
        $cat = Cat::find($cat_id);
        $vocs = $cat->vocs()->where("updated_at",">=",$updated)->orderBy("updated_at", "asc")->limit(60)->get();
        
        return response()->json($vocs);
    }
    
     public function setVoteVoc(){
        $id = Input::get("id");
        $vote = Input::get("vote");
        if($vote > 0){
            Voc::where("id",$id)->increment('numb_like');
        }else{
            Voc::where("id",$id)->decrement('numb_like');
        }
       return Voc::find($id)->numb_like;
    }
     public function setVoteCat(){
        $id = Input::get("id");
        $vote = Input::get("vote");
        if($vote > 0){
            Cat::where("id",$id)->increment('numb_like');
        }else{
            Cat::where("id",$id)->decrement('numb_like');
        }
       return Cat::find($id)->numb_like;
    }
}
