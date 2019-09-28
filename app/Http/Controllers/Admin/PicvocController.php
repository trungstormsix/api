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
use App\Models\Picvoc\PicvocMean;
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
        $cats = PicvocCat::where("title", 'like', "%$term%")->where("parent_id", ">", 1)->get();
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
        $result = new \stdClass();
        $result->status = true;
        return response()->json($result);
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
    
    public function cats(){
        $cats = PicvocCat::where("parent_id",">",1)->get();
         if (@$_GET['sort_by']) {
            Session::put('pvc_sort_by', $_GET['sort_by']);
            $dimen = @$_GET['sort_dimen'] ? $_GET['sort_dimen'] : 'asc';
            Session::put('pvc_sort_dimen', $dimen);
        }
        $sort_by = Session::get('pvc_sort_by', 'liked');
        $sort_dimen  = Session::get('pvc_sort_dimen', 'desc');
         
        return view('admin/picvoc/cats', compact('cats','sort_by','sort_dimen'));
    }
    
    public function vocs($cat_id){
        if(!$cat_id) return;
        $cat = PicvocCat::find($cat_id);
         if (@$_GET['sort_by']) {
            Session::put('pvv_sort_by', $_GET['sort_by']);
            $dimen = @$_GET['sort_dimen'] ? $_GET['sort_dimen'] : 'asc';
            Session::put('pvv_sort_dimen', $dimen);
        }
        $sort_by = Session::get('pvv_sort_by', 'liked');
        $sort_dimen  = Session::get('pvv_sort_dimen', 'desc');
        $vocs = $cat->vocs()->orderBy($sort_by, $sort_dimen)->paginate(20);

        return view('admin/picvoc/vocs', compact('cat', 'vocs','sort_by','sort_dimen'));
    }
    
    public function voc($voc_id){
        if(!$voc_id) return;
        $voc = Voc::find($voc_id);
        $means = $voc->means;
        return view('admin/picvoc/voc', compact('voc','means'));
    }
    
    public function means(){
         if (@$_GET['sort_by']) {
            Session::put('pvm_sort_by', $_GET['sort_by']);
            $dimen = @$_GET['sort_dimen'] ? $_GET['sort_dimen'] : 'asc';
            Session::put('pvm_sort_dimen', $dimen);
        }
        $sort_by = Session::get('pvm_sort_by', 'updated');
        $sort_dimen  = Session::get('pvm_sort_dimen', 'desc');
        $means = PicvocMean::orderBy($sort_by, $sort_dimen)->paginate(30);
        return view('admin/picvoc/means', compact('means','sort_by','sort_dimen'));

    }
}
