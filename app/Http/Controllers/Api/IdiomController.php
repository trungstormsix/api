<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Role;
use App\Permission;
use App\User;
use App\library\DomParser;
use App\Models\Idiom;
use App\Models\IdiomCat;
use App\Models\IdiomExample;
use File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class IdiomController extends Controller {

    public function __construct() {
        
    }

    public function cats() {
        $cats = IdiomCat::get();
        foreach ($cats as &$cat) {
            $cat->num_idiom = DB::table('id_cat_id')
                            ->select(DB::raw('count(id_id) as count'))
                            ->where('cat_id', $cat->id)->orderBy("count", "DESC")->first()->count;
        }
        return $cats;
    }

    public function getIdiomByCat($cat_id = 1) {
        if ((int) $cat_id == 0) {
            return;
        }

        $cat = IdiomCat::find($cat_id);
        $vocs = $cat->idioms;
        foreach ($vocs as &$voc) {
            $examples = $voc->examples();
            $example_text = "";

            if ($examples) {
                foreach ($examples as $ex) {
                    if ($ex) {
                        $example_text .= $ex->example . "<br>";
                    }
                }
            }else{
               
            }
            $voc->examples = $example_text;
        }
        return $vocs;
    }

    public function setVote() {
        $id = Input::get("id");
        $vote = Input::get("vote");
        if ($vote > 0) {
            Idiom::where("id", $id)->increment('liked');
        } else {
            Idiom::where("id", $id)->decrement('liked');
        }
        return Idiom::find($id)->liked;
    }

}