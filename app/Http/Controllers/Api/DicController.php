<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\User;
use App\library\DomParser;
 
use App\Models\GrammarUserAnswer;
use App\Models\GrammarUserQuestion;

use File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\CommonWord;
use App\Models\Dictionary;
use App\Models\DictionaryUser;
class DicController extends Controller {

  
    public function __construct() {
        
    }
    

    public function saveUserWord(Request $req){  
        $api_token = $req->get("api_token","no_token");       
        $user = User::where("api_token", $api_token)->first();
         if(!$user){
            return response("please login!",403);
        }
        $lookup_w = trim(Input::get('word'));
        $lang = trim(Input::get('lang'));
        $fav =  Input::get('favorite') ;
        $note = trim(Input::get('note'));
        $userWord = $this->_saveUserWord($user, $lookup_w, $fav, $note, $lang);
        return \Illuminate\Support\Facades\Response::json($userWord);        
    }    
    
    public function  test( ){  
        $api_token = Input::get("api_token","no_token");       
        $user = User::where("api_token", $api_token)->first();
         if(!$user){
            return response("please login!",403);
        }
        $lang = trim(Input::get('lang'));
        $lookup_ws = trim(Input::get('vocWords'));
        $words = explode(",",$lookup_ws );
        
        foreach($words as $word){
            $this->_saveUserWord($user, $word, "true", "", $lang);
        }
        $get_words = DictionaryUser::where("user_id", $user->id)->where("favorite",1)->get();
        $return_words = [];
        foreach($get_words as $w){
            $word = new \stdClass();
            $w->mean->word;
            $word = $w->mean;
            $word->note = $w->note;
//            $word->word = $w->mean->word;
            $return_words[] = $word;
        }
        return $return_words;
    }
    
    public function  syncUserWords(Request $req){  
        $api_token = $req->get("api_token","no_token");       
        $user = User::where("api_token", $api_token)->first();
         if(!$user){
            return response("please login!",403);
        }
        $lang = trim(Input::get('lang'));
        $lookup_ws = trim(Input::get('vocWords'));
        $words = explode(",",$lookup_ws );
        
        foreach($words as $word){
            if($word == ","){
                continue;
            }
            $this->_saveUserWord($user, urldecode($word), "true", "", $lang);
        }
        $get_words = DictionaryUser::where("user_id", $user->id)->where("favorite",1)->get();
        $return_words = [];
        foreach($get_words as $w){
			if(!$w->mean) continue;
            $word = new \stdClass();
            $w->mean->word;
            $word = $w->mean;
            $word->note = $w->note;
//            $word->word = $w->mean->word;
            $return_words[] = $word;
        }
        return $return_words;
    }
    
    private function _saveUserWord($user,$lookup_w, $fav, $note,$lang){
        if($lookup_w == "" || !$lookup_w){
            return null;
        }
        $word = CommonWord::where("word", $lookup_w)->first();
        if (!$word) {
            $word = new CommonWord();
            $word->count = 0;
            $word->word = $lookup_w;
            
        }
         
        $word->save();
        $m = Dictionary::where("word_id", $word->id)->where("lang", $lang)->first();
        if (!$m) {
            $m = new Dictionary();
            $m->lang = $lang;
            $m->word_id = $word->id;
            $m->save();           
        }
        
        $userWord = DictionaryUser::where("user_id", $user->id)->where("common_word_mean_id",$m->id)->first();
        if(!$userWord){
            $userWord = new DictionaryUser();
            $userWord->user_id = $user->id;
            $userWord->common_word_mean_id = $m->id;            
        }
        $userWord->favorite = $fav == "true" ? 1 : 0;
        if($note){
            $userWord->note = $note;
        }
        $userWord->save();
        
        return $userWord;
    }
}