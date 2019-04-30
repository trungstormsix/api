<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\User;
use App\Models\Idiom;
use App\Models\IdiomCat;
use App\Models\IdiomExample;
 
use File;
use Illuminate\Support\Facades\Session;

class IdiomController  extends Controller {
 
    var $url = 'http://idioms.thefreedictionary.com/';
    /**
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }
    public function export(){
        $cat = IdiomCat::find(2);      
       
        $out = fopen('php://output', 'w');
        foreach($cat->idioms  as $line)
        {
            $outPut = [$line->id, $line->word, $line->mean, $line->example];
            fputcsv($out, $outPut);
        }
        fclose($out);
         return response(file_get_contents('php://output'))->header('Content-Type', 'application/csv')
                ->header('Content-Disposition:', 'attachment; filename="idioms.csv"')
                 ;
     }
    
    /**
     * list all cats
     * @return type
     */
    public function index(){
       $cats = IdiomCat::all();        
        return view('admin/idioms/cats',['cats' => $cats]);
    }
    /**
     * get all idioms from a cat
     * @param type $cat_id
     * @return type
     */
    public function idioms($cat_id){
        $cat = IdiomCat::find($cat_id);        
        return view('admin/idioms/idioms',['cat' => $cat, 'idioms' => $cat->idioms()->paginate(30)]);
    }
    /**
     * search idioms
     * @return type
     */
    public function search(){
        $search = \Illuminate\Support\Facades\Input::get('idiom');
        
        $idioms = Idiom::where('word','like','%'.$search.'%')->paginate(30);  
         
        return view('admin/idioms/idioms',['idioms' => $idioms,'search'=>$search]);
    }
    
    public function getIdiom($id){
        $idiom = Idiom::find($id);             
        return view('admin/idioms/idiom',['idiom'=>$idiom]);
    }
    
    public function createIdiom($id){
        return view('admin/idioms/idiom',['idiom'=>null]);
    }
        /**
     * get Playlist
     */
    public function getCat($id = 0) {
        $cat = null;
        if ($id) {
            $cat = IdiomCat::find($id);
        } else {
            
        }
        return view('admin/idioms/cat', ['cat' => $cat]);
    }
    
     /**
     * save cat
     */
    public function postCat(Request $req) {
        if ($req->id) {
            $cat = IdiomCat::find($req->id);
        } else {
            $cat = IdiomCat::where('title',$req->title)->first();
            if(!$cat){
                $cat = new IdiomCat();
            }
        }
        if ($req->title) {
            $cat->title = $req->title;
            $cat->save();
            return Redirect::to('/admin/idioms/'.$cat->id);
        }
        Input::flash();
        return Redirect::to('/admin/idioms/add-cat');
    }
    
    /***
     * ajax
     */
    
    public function ajaxChangWord() {
        $id = Input::get('id_id', '0');
        $word = trim(Input::get('word', ''));
        $idiom = Idiom::find($id);
        if($idiom->word != $word){
            $idiom->word = $word;
            $idiom->save();
        }
        return response()->json($idiom);
    }
}
