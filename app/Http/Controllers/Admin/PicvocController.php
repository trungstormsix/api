<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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

    public function cats() {
        if (@$_GET['sort_by']) {
            Session::put('picvoc_sort_by', $_GET['sort_by']);
            $dimen = @$_GET['sort_dimen'] ? $_GET['sort_dimen'] : 'asc';
            Session::put('picvoc_sort_dimen', $dimen);
        }
        $sort_by = Session::get('picvoc_sort_by', 'parent_id');
        $sort_dimen = Session::get('picvoc_sort_dimen', 'desc');
        $cats = PicvocCat::where("parent_id",">",1)->orderBy($sort_by, $sort_dimen)->get();
        return view('admin.picvoc.cats', compact("cats","sort_by","sort_dimen"));
    }

     public function Vocabularies($cat_id) {
         $cat = PicvocCat::find($cat_id);
        if (@$_GET['sort_by']) {
            Session::put('picvoc_voc_sort_by', $_GET['sort_by']);
            $dimen = @$_GET['sort_dimen'] ? $_GET['sort_dimen'] : 'asc';
            Session::put('picvoc_voc_sort_dimen', $dimen);
        }
        $sort_by = Session::get('picvoc_voc_sort_by', 'liked');
        $sort_dimen = Session::get('picvoc_voc_sort_dimen', 'desc');
        $vocs = $cat->vocs()->orderBy($sort_by, $sort_dimen)->get();
        return view('admin.picvoc.vocs', compact("cat","vocs","sort_by","sort_dimen"));
    }
    public function Vocabulary($voc_id) {
       $voc = Voc::find($voc_id);
        return view('admin.picvoc.voc', compact("voc"));
    }
    public function SaveVoc(Request $request) {
        $voc_id = $request->get("id");
       $voc = Voc::find($voc_id);
       $voc->related = $request->get("related");
       $voc->save();
       return Redirect::to('/admin/picvoc/voc/' . $voc_id);
    }
    
    public function searchCat() {
        $term = Input::get('term', '0');
        $cats = PicvocCat::where("title", 'like', "%$term%")->where("parent_id", ">", 1)->get();

        $return = [];
        foreach ($cats as $cat) {
            $return[] = ['key' => $cat->id, 'label' => $cat->title, 'value' => $cat->title];
        }
        return $return;
    }

    public function delete() {
        $voc_id = Input::get('voc_id', Input::get('dl_id', '0'));
        $cat_id = Input::get('cat_id', '0');
        if ($voc_id && $cat_id) {
            $cat = PicvocCat::find($cat_id);
            $cat->vocs()->detach($voc_id);
        }

        $result = new \stdClass();
        $result->status = true;
        return response()->json($result);
    }

    public function add() {
        $voc_id = Input::get('voc_id', Input::get('dl_id', '0'));
        $cat_id = Input::get('cat_id', '0');
        if ($voc_id && $cat_id) {
            $cat = PicvocCat::find($cat_id);
            $result = $cat->vocs()->syncWithoutDetaching([$voc_id]);
            return $result;
        }
    }

}
