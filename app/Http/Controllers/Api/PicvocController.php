<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

use App\Role;
use App\Permission;
use App\User;
use App\library\DomParser;
use App\Models\Picvoc\Voc;
use App\Models\Picvoc\PicvocCat;
use File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PicvocController extends Controller {

  
    public function __construct() {
        
    }

    public function cats() {    
        $cats = PicvocCat::where("status",1)->where("parent_id",">",1)->orderBy("lft","DESC")->get();
        foreach($cats as &$cat){
            $cat->num_voc =    DB::table('picvoc_cat_voc')
                ->select(DB::raw('count(voc_id) as count'))
                     ->where('cat_id', $cat->id)->orderBy("count","DESC")->first()->count;
        }
        return $cats;
    }
 

    public function getVocByCat($cat_id = 1){
        if((int) $cat_id == 0){
            return;
        }
        
        $cat = PicvocCat::find($cat_id);
       
        $vocs = $cat->vocs()->where('updated','>', "2015-01-01 00:00:01")->take(80)->orderBy("updated", 'desc')->get();
		foreach($vocs as &$voc){
			$cats = $voc->cats;
			$cat_ids = [];
			foreach($cats as $cat){
				if($cat->id != $cat_id){
					$cat_ids[] = $cat->id;
				}
			}
			$voc->cat_ids = $cat_ids;
		}
        return $vocs;
    }
    
    
    public function setVote(){
        $id = Input::get("id");
        $vote = Input::get("vote");
        if($vote > 0){
            Voc::where("id",$id)->increment('liked');
        }else{
            Voc::where("id",$id)->decrement('liked');
        }
       return Voc::find($id)->liked;
    }
}
