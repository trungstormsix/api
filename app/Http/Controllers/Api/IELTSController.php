<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use DB;

use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\User;
use App\Models\ListeningCat;
use App\Models\ListeningDialog;
use File;
use Illuminate\Support\Facades\Session;
use App\Models\IELTS\IELTSCat;
use App\Models\IELTS\IELTSArticle;
use App\Models\IELTS\IELTSVocabulary;
use App\Models\IELTS\VocabularyUser;

class IELTSController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
     }

    /**
     * list all cats
     * @return type
     */
    public function index() {
        $cats = IELTSCat::where("type","!=", "article")->get(); //all();
		$return = [];
		foreach($cats as $cat){
			$c = new \stdClass();
			$c->cat_id = $cat->id;
			$c->title = $cat->title;
			$return[] = $c;
		}
         return response()->json($return);
    }
	
	public function getArticle($id) {
		 $article = null;
		if($id){
			$article = IELTSArticle::find($id);		 
			
		} 
		return response()->json($article);
    }
	
	public function getArticlesByCat($cat_id) {
		 
		$articles = null;
		$cat = IELTSCat::find($cat_id);
		$articles = $cat->articles()->paginate(20);
		 
		return response()->json($articles);
    }
	
	public function getArticles() {
		$maxDate = Input::get("maxDate");
		 
		 
		$articles = null;
		$articles = IELTSArticle::where("updated",">",$maxDate)->orderBy("updated", "ASC")->limit(20)->get();
 		 
		return response()->json($articles);
    }
    
    public function  test(){
        $api_token = Input::get("api_token","no_token");       
        $user = User::where("api_token", $api_token)->first();
         if(!$user){
            return response("please login!",403);
        }
        $fav_ids = trim(Input::get('fav_ids'));
        $words = explode(",",$fav_ids );
         
        foreach($words as $word){
            if($word == ","){
                continue;
            }
            $this->_saveUserWord($user, $word, 1,"");
        }
        $get_words = VocabularyUser::where("user_id", $user->id)->where("favorite",1)->get();
        $return_words = [];
        foreach($get_words as $w){
            $word = new \stdClass();
            
            $return_words[] = $w->word_id;
        }
        return implode(",", $return_words);
    }
    
     public function  syncUserNote(Request $req){
        $api_token = $req->get("api_token","no_token");       
        $user = User::where("api_token", $api_token)->first();
         if(!$user){
            return response("please login!",403);
        }
        $word_id = trim(Input::get('word_id'));
         $note = trim(Input::get('note'));      
        $userWord = VocabularyUser::where("user_id", $user->id)->where("word_id",$word_id)->first();
        if(!$userWord){
            $userWord = new VocabularyUser();
            $userWord->user_id = $user->id;
            $userWord->word_id = $word_id;            
        }
        
        $userWord->note = $note;
         
        $userWord->save();         
        return $userWord;
    }
    
    public function  syncUserWord(Request $req){
        $api_token = $req->get("api_token","no_token");       
        $user = User::where("api_token", $api_token)->first();
         if(!$user){
            return response("please login!",403);
        }
        $word_id = trim(Input::get('word_id'));
        $fav = trim(Input::get('favorite'));
        $note = trim(Input::get('note'));      
        $this->_saveUserWord($user, $word_id, $fav,$note);
        if($fav == 1){
            DB::table('il_vocabularies') ->where('id', $word_id)->increment('liked');
        }else{
            DB::table('il_vocabularies') ->where('id', $word_id)->decrease('liked');
        }
         return \App\Models\IELTS\IELTSVocabulary::find($word_id)->liked;
    }
    
 
    public function  syncUserWords(Request $req){
        $api_token = $req->get("api_token","no_token");       
        $user = User::where("api_token", $api_token)->first();
         if(!$user){
            return response("please login!",403);
        }
        $fav_ids = trim(Input::get('fav_ids'));
        $words = explode(",",$fav_ids );
        
        foreach($words as $word){
            if($word == ","){
                continue;
            }
            $this->_saveUserWord($user, $word, 1,"");
        }
        $get_words = VocabularyUser::where("user_id", $user->id)->where("favorite",1)->get();
        $return_words = [];
        foreach($get_words as $w){
            $word = new \stdClass();
            
            $return_words[] = $w->word_id;
        }
        return implode(",", $return_words);
    }
    
    private function _saveUserWord($user,$lookup_w, $fav, $note = ""){
        if($lookup_w == "" || !$lookup_w){
            return null;
        } 
        
        $userWord = VocabularyUser::where("user_id", $user->id)->where("word_id",$lookup_w)->first();
        if(!$userWord){
            $userWord = new VocabularyUser();
            $userWord->user_id = $user->id;
            $userWord->word_id = $lookup_w;            
        }
        $userWord->favorite = $fav ;
        if($note){
            $userWord->note = $note;
        }
        $userWord->save();
        
        return $userWord;
    }
    
     public function saveUserVoc(Request $req){
        $api_token = $req->get("api_token","no_token");       
        $user = User::where("api_token", $api_token)->first();
         
        $catId = trim(Input::get('catId'));
        $word = trim(Input::get('word'));
        $mean = trim(Input::get('mean'));
        $voc = IELTSVocabulary::where("en", $word)->first();
        if(!$voc){
            $voc = new IELTSVocabulary();
            $voc->en = $word;
            $voc->mean = $mean;
            $voc->status = 0;
            $voc->save();
            $voc->cats()->syncWithoutDetaching([$catId]);
        } 
     }
}
