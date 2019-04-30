<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Permission;
use App\User;
use App\library\DomParser;
use App\Models\Picvoc\Voc;
use App\Models\Picvoc\PicvocCat;
use App\Models\GrammarUserAnswer;
use App\Models\GrammarUserQuestion;

use File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class EnglishGrammarController extends Controller {

  
    public function __construct() {
        
    }
    
    public function cats() {    
        $cats = \App\Models\GrammarCat::where("published",1)->get();
        foreach($cats as &$cat){
          $cat->questions =    DB::table('engr_types_questions')
                ->select(DB::raw('count(question_id) as count'))
                     ->where('type_id', $cat->id)->orderBy("count","DESC")->first()->count;
        }
        return $cats;
    }
    
    public function lessons($cat_id = 1) {    
        $cat = \App\Models\GrammarCat::find($cat_id);
          
        $lessons = $cat->lessons;
         
        return $lessons;
    }
    
     public function lesson($lesson_id = 1) {    
        $lesson = \App\Models\GrammarLesson::find($lesson_id);
          
      
         
        return $lesson;
    }
    
    public function catTests() {    
        $cats =  DB::table('engr_types_questions')
                ->select(DB::raw('count(question_id) as count, type_id'))
                     ->groupBy('type_id')->orderBy("count","DESC")->get();
        $data = [];
        foreach($cats as &$cat){
            $c = \App\Models\GrammarCat::find($cat->type_id);
            if(@$c->title && $cat->count > 15){
            $cat->title = @$c->title;
            $cat->id = @$c->id;
            $data[] = $cat;
            }else{
                unset($cat);
            }
        }
        return $data;
    }
    public function numbCatQuestion($id = 1){
        try{
        $numb = \App\Models\GrammarCat::find($id)->questions()->count();
        }catch(Exection $e){
            $numb = 0;
        }
        return $numb;
    }
 public function numbLessonQuestion($id = 1){
        $numb = \App\Models\GrammarLesson::find($id)->questions()->count();
        return $numb;
    }
    
    public function getTest($id=1,$from=0){
        $test = \App\Models\GrammarCat::find($id)->questions()->orderBy("level","ASC")->orderBy("id","ASC")->skip($from)->take(15)->get();
//        return $test;
        return view('api/grammarTest', ["result" => $test]);
    }
    
     public function getUserTest(){
       $api_token = Input::get("api_token","no_token");  
     
        $user = User::where("api_token", $api_token)->first();
         if(!$user){
            return response("please login!",403); 
        }
     
       $test = $user->questions()->where("fail_count",">",1)->orderBy("updated_at","DESC")->take(15)->get();
//        $test = GrammarUserQuestion::find($id)->questions()->orderBy("level","ASC")->orderBy("id","ASC")->skip($from)->take(15)->get();
//        return $test;
        return view('api/grammarTest', ["result" => $test]);
    }
     public function getTotalUserTest(){
 
        $api_token = Input::get("api_token","no_token");  
     
        $user = User::where("api_token", $api_token)->first();
         if(!$user){
            return response("please login!",403); 
        }
        
       $test = $user->questions()->where("fail_count",">",1)->orderBy("updated_at","DESC")->count();
       return $test;
    }
    public function setVote(){
        $id = Input::get("id");
        $vote = Input::get("vote");
        if($vote > 0){
            \App\Models\GrammarLesson::where("id",$id)->increment('vote');
        }else{
             \App\Models\GrammarLesson::where("id",$id)->decrement('vote');
        }
       return \App\Models\GrammarLesson::find($id)->vote;
    }
    
    public function saveTest(Request $req){  
        $api_token = $req->get("api_token","no_token");       
        $user = User::where("api_token", $api_token)->first();
         if(!$user){
            return response("please login!",403);
        }
        
        $ansArrr = [];
        $answer_json = $req->get("anser");
        $answers = json_decode($answer_json);
        foreach ($answers as $answer_json){
            $answer = json_decode($answer_json);
            if(@$answer->id){
                $uQuestion = GrammarUserQuestion::find($answer->id);
            }else{
                $uQuestion = GrammarUserQuestion::where("qid", $answer->qid)->where("uid",$user->id)->first();
                if(!$uQuestion){
                    $uQuestion = new GrammarUserQuestion();
                }
                $uQuestion->qid =  $answer->qid;
                $uQuestion->uid = $user->id;
                $uQuestion->save();
            }
              
            $uAnswer = new GrammarUserAnswer();
            $uAnswer->qid =  $answer->qid;
            $uAnswer->uid = $user->id;
            $uAnswer->answer = @$answer->answer;
            
            if($answer->correct){
                $uAnswer->correct = 1;
            }else{
                $uAnswer->correct = 0;
                $uQuestion->increment('fail_count');
            }
            if(@$answer->answer){
                $uAnswer->save();
            }
            $uQuestion->increment('total');
            $answer->id = $uAnswer->id;
            $ansArrr[] = $answer;
        }
        return \Illuminate\Support\Facades\Response::json($ansArrr);
        
    }
    
     public function saveTestNote(Request $req){  
        $api_token = $req->get("api_token","no_token");       
        $user = User::where("api_token", $api_token)->first();
        if(!$user){
            return response("please login!",403);
        }
        $id = $req->get("id");
        $qid = $req->get("qid");
        $answer = $req->get("answer");
            
        if($id){
           $history =  GrammarUserAnswer::find($id);
        }else{
            
            $history = GrammarUserAnswer::where("qid",$qid)->where("uid",$user->id)->where("answer",$answer)->orderBy("updated_at","DESC")->first();
        }
        if(!$history){
            $history = new GrammarUserQuestion();
            $history->qid = $qid;
            $history->uid = $user->id;
            $history->answer = $answer;
        }
                 
        if($history){
            $note = $req->get("note");
            $history->note = $note;
            $history->save();
        }
        
        return \Illuminate\Support\Facades\Response::json($history);

     }
     
      public function getTestNote(Request $req){  
         
        $api_token = Input::get("api_token","no_token"); 
        
        $user = User::where("api_token", $api_token)->first();
        if(!$user){
            return response("please login!",403);
        }
        $uid = $user ? $user->id : "222";
        $qid = Input::get("qid","32");
        
        $history = GrammarUserAnswer::where("qid",$qid)->where("uid",$uid)->orderBy("updated_at","DESC")->get();

        
        return \Illuminate\Support\Facades\Response::json($history);

     }
}
