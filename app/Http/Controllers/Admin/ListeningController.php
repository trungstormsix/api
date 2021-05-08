<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\User;
use App\Models\ListeningCat;
use App\Models\ListeningDialog;
use File;
use Illuminate\Support\Facades\Session;
use App\Models\ListeningQuestion;
use Illuminate\Support\Facades\Storage;

class ListeningController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * list all cats
     * @return type
     */
    public function index() {
        $cats = ListeningCat::all();
        return view('admin/listening/cats', ['cats' => $cats]);
    }

    /**
     * get all listening from a cat
     * @param type $cat_id
     * @return type
     */
    public function dialogs($cat_id) {
        $cat = ListeningCat::find($cat_id);

        if (@$_GET['sort_by']) {
            Session::put('sort_by', $_GET['sort_by']);
            $dimen = @$_GET['sort_dimen'] ? $_GET['sort_dimen'] : 'asc';
            Session::put('sort_dimen', $dimen);
        }
        $sort_by = Session::get('sort_by', 'liked');
        $dimen = Session::get('sort_dimen', 'desc');
        $dialogs = $cat->dialogs()->orderBy($sort_by, $dimen)->paginate(30);

        return view('admin/listening/dialogs', ['cat' => $cat, 'dialogs' => $dialogs, 'sort_by' => $sort_by, 'sort_dimen' => $dimen]);
    }

    /**
     * search listening
     * @return type
     */
   

    public function getDialog($id) {
        $dialog = ListeningDialog::find($id);
        $question_ids = json_decode($dialog->question);
        $questions = ListeningQuestion::whereIn('id', $question_ids)->get();
		$next = ListeningDialog::where("id",">",$id)->where("status",1)->orderBy("id","ASC")->first();   
		$yid = $dialog->video_id;
		$fileName = $yid.".txt";
        $sub ="";
        if (Storage::disk('ysubs')->has($fileName)) {
            $audio = Storage::disk('ysubs')->getAdapter()->getPathPrefix();
            $sub = file_get_contents($audio.$fileName);
        }
        return view('admin/listening/dialog', compact('dialog', 'questions', 'next','sub'));   
	}

    public function postQuestionAjax(Request $req) {
        $dl_id = $req->dlId;
        if (!$dl_id) {
            return response()->json(['success' => false, 'message' => "Can not found lesson"]);
        }
        $dialog = ListeningDialog::find($dl_id);
        if (!$dialog) {
            return response()->json(['success' => false, 'message' => "Can not found lesson"]);
        }
        $question_ids = [];
        $questions = null;
        if ($dialog->question && $dialog->question != "null") {
            $question_ids = json_decode($dialog->question);
            $questions = ListeningQuestion::whereIn('id', $question_ids)
                    ->get();
        }
        $q = trim($req->q);
        $c = trim($req->c);
        $ans_raw = $req->ans;
        $ans = [];
        foreach ($ans_raw as $a) {
            if ($a["value"]) {
                $ans[] = trim($a["value"]);
            }
        }
        $ans = array_unique($ans);
        if (!$q) {
            return response()->json(['success' => false, 'message' => "please add Question"]);
            ;
        }
        if (!in_array($c, $ans)) {
            return response()->json(['success' => false, 'message' => "please add correct answer in the answers"]);
            ;
        }
        if ($questions) {
            foreach ($questions as $eq) {
                if ($eq->question == $q) {
                    return response()->json(['success' => false, 'message' => "Question exist"]);
                    ;
                }
            }
        }

        $question = new ListeningQuestion();
        $question->question = $q;
        $question->correct = $c;
        $question->answers = json_encode($ans);
        $question->save();
        $question_ids[] = $question->id;
        $dialog->question = json_encode($question_ids);
        $dialog->save();
        return response()->json(['success' => true, 'message' => "Question added sucessfully"]);
    }

    public function postDialog(Request $req) {

        if ($req->id) {
            $answers = $req->questions_an;
            $corrects = $req->questions_correct;
            $questions = $req->questions;
            $questions_id = [];
            foreach ($questions as $qid => $q) {
                $qans = array_filter($answers[$qid]);
                $question = ListeningQuestion::find($qid);
                if (!$q) {
                    $question->delete();
                    continue;
                }
                $question->question = $q ? $q : $question->question;
                $question->correct = $corrects[$qid] ? $corrects[$qid] : $question->correct;
                if (!in_array($question->correct, $qans)) {
                    continue;
                }
                $question->answers = json_encode($qans);
                $question->save();
                $questions_id[] = $qid;
            }
			$vid = trim($req->video_id);
            if(strlen($vid) > 20){
                $tmp = explode("/", $vid);
                $vid = $tmp[sizeof($tmp) - 2];
            }
            $dialog = ListeningDialog::find($req->id);
            $dialog->status = $req->status ? 1 : 0;
            $dialog->dialog = $req->dialog;
            $dialog->vocabulary = $req->vocabulary;
            $dialog->title = trim($req->title) ? trim($req->title) : $dialog->title;
			$oldVid = $dialog->video_id;
			$dialog->video_id = $vid;//trim($req->video_id);
			
            $dialog->question = json_encode($questions_id);
            $dialog->save();
			if($oldVid != $vid){
				$this->_crawlYoutubeSub($req->id);
				 
			}
            return Redirect::to('/admin/listening/dialog/' . $dialog->id);
        }
        Input::flash();
        return Redirect::to('/admin/listening/add-cat');
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
        return view('admin/listening/cat', ['cat' => $cat]);
    }

    /**
     * save cat
     */
    public function postCat(Request $req) {
        if ($req->id) {
            $cat = IdiomCat::find($req->id);
        } else {
            $cat = IdiomCat::where('title', $req->title)->first();
            if (!$cat) {
                $cat = new IdiomCat();
            }
        }
        if ($req->title) {
            $cat->title = $req->title;
            $cat->save();
            return Redirect::to('/admin/listening/' . $cat->id);
        }
        Input::flash();
        return Redirect::to('/admin/listening/add-cat');
    }

    public function searchDialog() {
        $search = Input::get('search', "");
        if (@$_GET['sort_by']) {
            Session::put('sort_by', $_GET['sort_by']);
            $dimen = @$_GET['sort_dimen'] ? $_GET['sort_dimen'] : 'asc';
            Session::put('sort_dimen', $dimen);
        }
        $sort_by = Session::get('sort_by', 'status');
        $dimen = Session::get('sort_dimen', 'desc');
        $dialogs = ListeningDialog::where("title", "like", "%" . $search . "%")->orWhere("audio", "like", "%" . $search . "%")->orderBy($sort_by, $dimen)->paginate(20);
        if(!$dialogs || $dialogs->isEmpty() ){
             
            $dialogs = ListeningDialog::where("dialog", "like", "%" . $search . "%")->orderBy($sort_by, $dimen)->paginate(20);
        }
//        return view('admin/ielts/articles', ['articles' => $articles, "cat" => null, 'sort_by' => $sort_by, 'sort_dimen' => $dimen, 'search' => $search]);
        return view('admin/listening/dialogs', ['cat' => null, 'dialogs' => $dialogs, 'sort_by' => $sort_by, 'sort_dimen' => $dimen, 'search' => $search]);

        
        }

    /*     * *********************
     * ********* ajax ******
     * ******************** */

    public function removeCat() {
        $cat = Input::get('cat_id', '0');
        $dl_id = Input::get('main_id', '0');
        $dl = ListeningDialog::find($dl_id);
        $dl->cats()->detach($cat);
        return response()->json(['cat' => $cat, 'main_id' => $dl_id]);
    }

    public function ajaxAddCat() {
        $cat = Input::get('cat_id', '0');
        $dl_id = Input::get('dl_id', '0');
        $dl = ListeningDialog::find($dl_id);
        $changed = $dl->cats()->syncWithoutDetaching([$cat]);
        if ($changed) {
            $dl->touch();
        }
        return response()->json(['cat' => $cat, 'main_id' => $dl_id, 'changed' => $changed]);
    }

    public function ajaxRemoveGrammar() {
        $gr_id = Input::get('gr_id', '0');
        $dl_id = Input::get('main_id', '0');
        $dl = ListeningDialog::find($dl_id);
        $dl->grammars()->detach($gr_id);
        return response()->json(['gr_id' => $gr_id, 'main_id' => $dl_id]);
    }

    public function ajaxAddGrammar() {
        $gr_id = Input::get('gr_id', '0');
        $dl_id = Input::get('dl_id', '0');
        $dl = ListeningDialog::find($dl_id);
        $changed = $dl->grammars()->syncWithoutDetaching([$gr_id => ['ex' => Input::get('ex', null)]]);
        if ($changed) {
            $dl->touch();
        }
        return response()->json(['gr_id' => $gr_id, 'main_id' => $dl_id, 'changed' => $changed]);
    }

    public function ajaxGetGrammars() {
        $cat_term = Input::get('term', '');
        $cats = \App\Models\GrammarLesson::where('title', 'like', "%$cat_term%")->where('published', 1)->take(20)->get();

        $return = [];
        foreach ($cats as $cat) {
            $return[] = ['key' => $cat->id, 'value' => $cat->title];
        }

        return response()->json($return);
    }

    public function ajaxGetCats() {
        $cat_term = Input::get('term', '');

        $cats = ListeningCat::where('title', 'like', "%$cat_term%")->take(20)->get();

        $return = [];
        foreach ($cats as $cat) {
            $return[] = ['key' => $cat->id, 'value' => $cat->title];
        }
        return response()->json($return);
    }

    public function ajaxFixReport() {
        $report_id = Input::get('report_id', '0');
        if ($report_id) {
            $report = \App\Models\ListeningReport::find($report_id);
            $report->status = 1;
            $report->save();
            $dialog = $report->dialog;
            \Illuminate\Support\Facades\Mail::send('emails.listening_report', ['dialog' => $dialog, 'report' => $report], function ($m) use ($dialog, $report) {
                $m->from('supporter@ocodereducation.com', '[English Listening]');
                $m->replyTo("trungstormsix@gmail.com", "Admin");
                $m->cc("trungstormsix@gmail.com", "Admin");
                $m->to($report->email, $report->email)->subject('[English Listening] Fixed your problem!');
            });
        }
        return response()->json(['report_id' => $report_id]);
    }

    public function ajaxUpdateOrder() {
        $cat_id = Input::get('cat_id', '0');
        $ordering = Input::get('ordering', '0');
        $dialog_id = Input::get('dialog_id', '0');
        $dl = ListeningDialog::find($dialog_id);
        $changed = $dl->cats()->syncWithoutDetaching([$cat_id => ['ordering' => $ordering]]);
        if ($changed) {
            $dl->touch();
        }
        return response()->json(['cat_id' => $cat_id, 'dialog_id' => $dialog_id, 'changed' => $changed]);
    }

    public function reports() {
        $reports = \App\Models\ListeningReport::where("status", 0)->orderBy("updated", "DESC")->paginate(30);
        return view('admin/listening/reports', ['reports' => $reports]);
    }
   public function htmlFileLink($file, $link){            
            $fp = fopen($file, 'w');
            $text = "<html><head>";
            $text .= '<meta http-equiv="refresh" content="0; url='.$link.'" />';
            $text .= "</head><body></body></html>";
            fwrite($fp, $text);
            fclose($fp);
        }
        
    public function crawlYoutubeSub(){
		?>
		
		<?php
        $id = Input::get("id");
		//$id = Session::get('next_crawl_sub_id', $id);
        $this->_crawlYoutubeSub($id);
		$next = ListeningDialog::where("id",">",$id)->where("status",1)->orderBy("id","ASC")->first();   
		//Session::put('next_crawl_sub_id', $next->id);

		echo "<br><a href='http://ocodereducation.com/apiv1/admin/listening/crawl-y-sub?id=$next->id'>next $next->id</a>";
		/*
		<html>
				 <head>
		  <meta http-equiv="refresh" content="8">
		</head> 
		<body>
		*/
    }
	
	 public function _crawlYoutubeSub($id){
         
        echo $id."<br>";
        $video = ListeningDialog::find($id);
        if(!$video || !$video->video_id){
            echo "no file";
            return;
        } 

        $yid = $video->video_id;
        $fileName = $yid.".txt";
        $lang = Input::get("lang","en");
        $ysub_link = "http://video.google.com/timedtext?type=track&v=".$yid."&id=0&lang=".$lang;
        $subs = [];
        $list_xml = simplexml_load_file($ysub_link);
        if($list_xml){
            foreach ($list_xml as $text){
                //var_dump($text);
              $att = $text->attributes();
               
                $sub = new \stdClass();
                $sub->from = intval(1000 * doubleval($att['start']));
                $sub->to = intval($sub->from + (doubleval($att['dur']) * 1000));
                $sub->text = html_entity_decode($text);
                $subs[] = $sub;
                
            }
        }
        $sub_json = new \stdClass();
        $sub_json->subs = $subs;
       
       
        $status = false;
        if (true || !Storage::disk('ysubs')->has($fileName)) {
			echo "<b>sub:</b>".$fileName."<br>";;
            $status  = Storage::disk('ysubs')->put($fileName, json_encode($sub_json));
            if($status){
                $video->has_sub = 1;
                $video->save();
            }
        }else{
            
                $video->has_sub = 1;
                $video->save();
             
        }
        
        echo "<a href='".url("/ysubs/".$fileName)."' target='_blank'>Link Sub</a>";

    }
	public function crawlPlayList(){
        $play_id = Input::get("playlist");
        if(!$play_id){
            return view('admin/listening/playlist');

        }
//        PLM4Mkja-PGjwRFO5yNWH-FIKd0ZRjI2-z
        $f = new \App\library\DomParser();
        $html = $f->file_get_html("https://www.youtube.com/playlist?list=" . $play_id);
         $table = $html->find("#pl-video-table", 0);
        $videos = $table->find("tr");
        foreach ($videos as $video_html) {
            
            $video = null;
            $id_text = "data-video-ids";
            $yid = $video_html->find(".addto-watch-queue-play-now", 0)->$id_text;
            $ytitle = trim($video_html->find(".pl-video-title-link",0)->plaintext);
            $dlIds = explode(" ", $ytitle);            
            $dlId = trim($dlIds[0]);
			$i = 0;
            if ($yid) {               
                $dialog = ListeningDialog::find($dlId);
				if(!$dialog) dd($ytitle);
                if(!$yid || !$dialog->video_id){
                    $dialog->video_id = $yid;
                    $dialog->save();
                    $this->_crawlYoutubeSub($dlId);
                    echo " <br><a href='". \Illuminate\Support\Facades\URL::to('/')."/admin/listening/dialog/$dlId' target='_blank' >".$dialog->id." ".$dialog->title."</a><br>";
					if($i++ == 5){
						exit;
					}
                } else{
					echo $dialog->title. " <a href='". \Illuminate\Support\Facades\URL::to('/')."/admin/listening/dialog/$dlId' target='_blank' >".$dialog->id." Link</a>"."<br>";
                }

                
            } else {
                continue;
            }
        }

    }
}
