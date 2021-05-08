<?php

namespace App\Http\Controllers\Api;
use DB;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\ListeningCat;
use App\Models\ListeningDialog;
use App\Models\GrammarLesson;
use App\Models\ListeningReport;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\library\OcoderHelper;
use App\library\MP3File;
class ListeningController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       
        $dialogs = ListeningDialog::where('updated','>',  Input::get('max_date', '0000-00-00 00:00:00'))->where('status',1)->take(20)->orderBy("updated", 'asc')->get();
        $return = [];
        foreach ($dialogs as $dialog) {
            $dl = clone $dialog;
            $cs = $dialog->cats;
            $cats = [];
            foreach($cs as $cat){
                $c = new \stdClass();
                $c->id = $cat->id;
                $c->title = $cat->title;
                $c->ordering = $cat->pivot->ordering;
                $cats[] = $c;
            }
             $dl->cats = $cats;
            $grammars = [];
            foreach ($dialog->grammars as $gr) {
                $grammar = new \stdClass();
                $grammar->id = $gr->id;
                $grammar->title = $gr->title;
                $grammar->sentence = $gr->pivot->ex;
                $grammars[] = $grammar;
            }
            $dl->grammars = $grammars;
            $dl->questions = $dialog->questions();
            $return[] = $dl;
        }

        return response()->json($return);
    }

    public function getDialog($id) {
            
        $dialog = ListeningDialog::find($id);
        $dl = clone $dialog;
        if(!$dl) return;
            $cs = $dialog->cats;
            $cats = [];
            foreach($cs as $cat){
                $c = new \stdClass();
                $c->id = $cat->id;
                $c->title = $cat->title;
                $c->ordering = $cat->pivot->ordering;
                $cats[] = $c;
            }
             $dl->cats = $cats;
            $grammars = [];
            foreach ($dialog->grammars as $gr) {
                $grammar = new \stdClass();
                $grammar->id = $gr->id;
                $grammar->title = $gr->title;
                $grammar->sentence = $gr->pivot->ex;
                $grammars[] = $grammar;
            }
            $dl->grammars = $grammars;
            $dl->questions = $dl->questions();

        return $dl;
    }
    
     public function setVote(){
        $id = Input::get("id");
        $vote = Input::get("vote");
        if($vote > 0){
            //ListeningDialog::where("id",$id)->increment('liked');
			if(ListeningDialog::find($id)->liked % 50 ==0){
				ListeningDialog::where("id",$id)->increment('liked');
			}else{
				DB::table('enli_dialogs') ->where('id', $id)->increment('liked');
			}
        }else{
            //ListeningDialog::where("id",$id)->decrement('liked');
			DB::table('enli_dialogs') ->where('id', $id)->decrement('liked');
        }
       return ListeningDialog::find($id)->liked;
    }
    
    public function cats(){
        $cats = ListeningCat::all();
        return $cats;
    }
    
    public function getDialogsOfCat($cat_id = 1){
        if(!$cat_id) return;
        $max_date = Input::get('max_date', '0000-00-00 00:00:01');
        $cat = ListeningCat::find($cat_id);
        $dialogs = $cat->dialogs()->where('updated','>',$max_date)->take(50)->orderBy("updated", 'asc')->get();
        
        $return = [];
        foreach ($dialogs as $dialog) {
            $dl = clone $dialog;     
            $grammars = [];
            foreach ($dialog->grammars as $gr) {
                $grammar = new \stdClass();
                $grammar->id = $gr->id;
                $grammar->title = $gr->title;
                $grammar->sentence = $gr->pivot->ex;
                $grammars[] = $grammar;
            }
            $dl->grammars = $grammars;
            $dl->questions = $dialog->questions();
            $return[] = $dl;
        }
        return $return;
    }
	
	
	public function setDucations(){
		?>
		<html>
		<head>
		<meta http-equiv="refresh" content="60" >

		</head>
		<body>
		<?php $story = ListeningDialog::where(function ($query) {
                $query->whereNull("duration")
                      ->orWhere('duration', '=', 0);
            })
		->where("status",0)->orderBy('updated', 'desc')->first(); 
		 
		echo $story->title;
		echo "<br>";
		echo "<a target='_blank' href='http://apiv1.ocodereducation.com/admin/listening/dialog/".$story->id."' >Link</a>"; 
		 
		$this->setDurationEn($story);
		echo " <br>Duaration: " . $story->duration;

		?>
		<br>
		Refresing
		</body>
		</html>
		<?php
		exit;
	}
	
	public function setDurationEn($story) {
        if($story->duration > 0){
            return;
        }
        $audio = Storage::disk('listening_audios')->getAdapter()->getPathPrefix();
		 
        $mp3file = new MP3File($audio . $story->audio); //http://www.npr.org/rss/podcast.php?id=510282
        $duration1 = @$mp3file->getDurationEstimate(); //(faster) for CBR only
        $duration2 = @$mp3file->getDuration(); //(slower) for VBR (or CBR)
        
        $duration = $duration1 > $duration2 ? $duration1 : $duration2;
		$size = filesize($audio.$story->audio);
		 
        if ($duration > 0) {
            $story->duration = $duration;
			$story->size = $size;
			$story->timestamps = false;

            $story->save();
        }
		clearstatcache();

    }
	 public function report(){
         $api_token = Input::get("api_token","no_token");      
         $message = Input::get("message","");
         $dl_id = Input::get("id","");
        $user = User::where("api_token", $api_token)->first();
         if(!$user){
            return response("please login!",403);
        }
        $report = new ListeningReport();
        $report->status = 0;
        $report->email = $user->email;
        $report->message = $message;
        $report->dl_id = $dl_id;
        if($dl_id && $message && strlen($message) > 2){
            $report->save();
        }
        
    }
	
	
    public function getSub(){
        $id = Input::get("id");
        
        $video = ListeningDialog::find($id);
        if(!$video || !$video->video_id){
            
            return;
        } 

        $yid = $video->video_id;
        $fileName = $yid.".txt";
        
        if (Storage::disk('ysubs')->has($fileName)) {
            $audio = Storage::disk('ysubs')->getAdapter()->getPathPrefix();
              echo file_get_contents($audio.$fileName);
        }
    }
}
