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
        $next = ListeningDialog::where("id",">",$id)->orderBy("id","ASC")->first();   
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
    private function _chageImagesName(){
        $dir = "images/video/";
        $files = scandir($dir);
        //copy from bk folder if image is deleted
        if(sizeof($files) < 5){
             $filesBk = scandir("images/bk/");
             foreach($filesBk as $file){
            if(strlen($file) > 2){                 
                    copy("images/bk/".$file, $dir.$file);
                }
            }
        }
        //rename to random
        $files = scandir($dir);
        echo sizeof($files)."<br>";
         foreach($files as $file){
            if(strlen($file) > 2){                 
                $name = $dir.rand(sizeof($files), 5*sizeof($files)).".png";
                while(file_exists($name)){
                    $name = $dir.rand(sizeof($files), 5*sizeof($files)).".png";
                }
                rename($dir.$file, $name);
             }
          
        }
        //re-order file
        $files = scandir($dir);
      
        echo sizeof($files)."<br>";
        $i = 1;
        foreach($files as $file){
            if(strlen($file) > 2){               
                rename($dir.$file, $dir.$i.".png");
                $i++;
            }
        }
         
    }

    public function createVideo(){      
        $this->_chageImagesName();
  
        $dialogs = ListeningDialog::where("video", "0")->where("status", "1")->orderBy("id", "ASC")->paginate(1);
        foreach($dialogs as $dialog){
            $folder = "mp4s/";// "videos/$dialog->id/";
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }
            $fp = fopen($folder."txt/".$dialog->id.'.txt', 'w');
            $text = strip_tags($dialog->dialog,"<br>");
//            $text = str_replace("<br>", "\n", $text);
            $text = preg_replace('(<br\s*\/?>\s*)', "\n", $text);
            fwrite($fp, $text);
            fclose($fp);
            
            $this->htmlFileLink($folder.$dialog->id.'_z.html', "http://ocodereducation.com/apiv1/admin/listening/dialog/".$dialog->id);
//            echo $text."<br>";
//            $command2="ffmpeg -f image2 -r 1/7 -i images/video/%d.png -i \"audios/listening/".$dialog->audio."\" -t ".($dialog->duration + 1000)." -vcodec mpeg4 -s 720x576 -vf fps=5 -y \"$folder".$dialog->id." - ".$dialog->title.".mp4\"";
            $command2="ffmpeg -f image2 -r 1/5 -i images/video/%d.png -i \"audios/listening/".$dialog->audio."\" -t ".($dialog->duration + 4)." -c:v mpeg4 -y \"$folder".$dialog->id." - ".$dialog->title.".avi\"";

            echo $command2."<br>";
            //command for every 5 second image change in video along with 004-07.mp3 playing in background
            exec($command2);
           
            $dialog->video = 1;
            $dialog->save();
        }
        
        echo '<html>
        <head>
            <title>Create Video 55s</title>';
        echo '            <meta http-equiv="refresh" content="15" />';
        echo ' 
        </head>
        <body></body></html>';
       
    
    }
    
    
    public function createVideoId($id){  
        $dialog = ListeningDialog::find($id);
//        $this->setDurationAndSize($dialog);
            
        $this->_chageImagesName();
   
            $folder = "mp4s/";// "videos/$dialog->id/";
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }
            $fp = fopen($folder."txt/".$dialog->id.'.txt', 'w');
            $text = strip_tags($dialog->dialog,"<br>");
//            $text = str_replace("<br>", "\n", $text);
            $text = preg_replace('(<br\s*\/?>\s*)', "\n", $text);
            $text = html_entity_decode($text);
            $text = preg_replace('/[A-Za-z][?.]\s/', "$0\n", $text);
            fwrite($fp, $text);
            fclose($fp);
            
            $this->htmlFileLink($folder.$dialog->id.'_z.html', "http://ocodereducation.com/apiv1/admin/listening/dialog/".$dialog->id);
//            echo $text."<br>";
//            $command2="ffmpeg -f image2 -r 1/7 -i images/video/%d.png -i \"audios/listening/".$dialog->audio."\" -t ".($dialog->duration + 1000)." -vcodec mpeg4 -s 720x576 -vf fps=5 -y \"$folder".$dialog->id." - ".$dialog->title.".mp4\"";
            $command2="ffmpeg -f image2 -r 1/5 -i images/video/%d.png -i \"audios/listening/".$dialog->audio."\" -t ".($dialog->duration + 4)." -c:v mpeg4 -y \"$folder".$dialog->id." - ".$dialog->title.".avi\"";

            echo $command2."<br>";
            //command for every 5 second image change in video along with 004-07.mp3 playing in background
            exec($command2);
           
            $dialog->video = 1;
            $dialog->save();
         
        echo '<html>
        <head>
            <title>Create Video 55s</title>';
         echo ' 
        </head>
        <body><a href="'.
                 "file://D:\web\laravel\api\mp4s".'">link</a><br>'
                 . 'D:\web\laravel\api\mp4s<br>'
                 . 'D:\web\laravel\api\mp4s\txt<br>'
                 . '</body></html>';
       
    
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
        $id = Input::get("id");
        echo $id;
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
                $sub->from = $att['start'] * 1000;
                $sub->to = $sub->from + ($att['dur'] * 1000);
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
    
    
    public function jpg2Png(){
          $dir = "images/videojpg/";
         $files = scandir($dir);
        echo sizeof($files)."<br>";
         foreach($files as $file){
            if(strlen($file) > 2){           
                imagepng(imagecreatefromstring(file_get_contents($dir.$file)), "images/video/".$file.".png");

//                $name = $dir.rand(sizeof($files), 5*sizeof($files)).".png";
//                while(file_exists($name)){
//                    $name = $dir.rand(sizeof($files), 5*sizeof($files)).".png";
//                }
//                rename($dir.$file, $name);
             }
          
        }
        
    }
    
    
     public function _crawlYoutubeSub($id){
         
        echo $id;
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
                $sub->from = $att['start'] * 1000;
                $sub->to = $sub->from + ($att['dur'] * 1000);
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
					 
                } else{
					echo $dialog->title. " <a href='". \Illuminate\Support\Facades\URL::to('/')."/admin/listening/dialog/$dlId' target='_blank' >".$dialog->id." Link</a>"."<br>";
                }

                
            } else {
                continue;
            }
        }

    }
}
