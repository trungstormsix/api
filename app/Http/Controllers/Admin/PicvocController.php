<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
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

    var $url = 'http://idioms.thefreedictionary.com/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    public function searchCat() {
        $term = Input::get('term', '0');
        $cats = PicvocCat::where("title", 'like', "%$term%")->get();

        $return = [];
        foreach ($cats as $cat) {
            $return[] = ['key' => $cat->id, 'label' =>$cat->title, 'value' => $cat->title];
        }
        return $return;
    }

    public function delete() {
        $voc_id = Input::get('voc_id', '0');
        $cat_id = Input::get('cat_id', '0');
        if ($voc_id && $cat_id) {
            $cat = PicvocCat::find($cat_id);
            $cat->vocs()->detach($voc_id);
        }
    }

    public function add() {
        $voc_id = Input::get('voc_id', '0');
        $cat_id = Input::get('cat_id', '0');
        if ($voc_id && $cat_id) {
            $cat = PicvocCat::find($cat_id);
            $result = $cat->vocs()->syncWithoutDetaching([$voc_id]);
            return $result;
        }
    }

}
