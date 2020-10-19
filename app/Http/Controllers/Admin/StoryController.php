<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\User;
//use App\Models\ListeningCat;
//use App\Models\ListeningDialog;
use File;
use Illuminate\Support\Facades\Session;
//use App\Models\ListeningQuestion;
use Illuminate\Support\Facades\Storage;
 use App\Models\Stories\StoryType;
use App\Models\Stories\Story;
use App\library\MP3File;

class StoryController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }
    public function cats(){
        $cats = StoryType::where("lang","!=", 'es')->where("parent",">",0)->paginate(20);
        return view('admin/story/cats', ['cats' => $cats]);

    }
    public function stories($cat_id){
        $cat = StoryType::find($cat_id);
        if (@$_GET['sort_by']) {
            Session::put('sort_by', $_GET['sort_by']);
            $dimen = @$_GET['sort_dimen'] ? $_GET['sort_dimen'] : 'asc';
            Session::put('sort_dimen', $dimen);
        }
        $sort_by = Session::get('sort_by', 'liked');
        $dimen = Session::get('sort_dimen', 'desc');
        $dialogs = $cat->stories()->orderBy($sort_by, $dimen)->paginate(30);
        
        return view('admin/story/dialogs', ['cat' => $cat, 'dialogs' => $dialogs, 'sort_by' => $sort_by, 'sort_dimen' => $dimen]);

    }

    public function getStory($id){
        $story = Story::find($id);
        $cats = @$story->types;
        $cat = @$cats[0];
        if($cat){
        $strs = $cat->stories()->where("id",">",$id)->orderBy("id","ASC")->first();
        $next = $strs;
        if(!$next){     
            $cat = StoryType::where("lang","!=","es")->where("parent",">",0)->where("id",">",$cat->id)->orderBy("id","ASC")->first();            
            $next = $cat->stories()->orderBy("id","ASC")->first();
             
        }
        }else{
            $next = $story;
        }
        $yid = $story->video_id;
        $fileName = $yid.".txt";
        $sub ="";
        if (Storage::disk('ysubs')->has($fileName)) {
            $audio = Storage::disk('ysubs')->getAdapter()->getPathPrefix();
            $sub = file_get_contents($audio.$fileName);
        }
        $dialog = $story;
        return view('admin/story/dialog', compact('dialog', 'next','sub'));
        
    }
    public function deleteStory($id){
        $story = Story::find($id);
        $cats = $story->types;
        $cat = @$cats[0];
        $story->types()->detach();         
        $story->delete();
        if($cat){
            return Redirect::to('/admin/story/stories/' . $cat->id)->with('success',"Story ".$story->id." deleted completely");
        }
        return Redirect::to('/admin/story/cats')->with('success',"Story ".$story->id." deleted completely");
        
    }
    public function postStory(Request $req){
        $dl_id = $req->id;
        if (!$dl_id) {
            return response()->json(['success' => false, 'message' => "Can not found lesson"]);
        }
        $dialog = Story::find($dl_id);
        if (!$dialog) {
            return response()->json(['success' => false, 'message' => "Can not found lesson"]);
        }
        $vid = trim($req->video_id);
            if(strlen($vid) > 20){
                $tmp = explode("/", $vid);
                $vid = $tmp[sizeof($tmp) - 2];
            }
         
        $dialog->status = $req->status ? 1 : 0;
        $dialog->dialog = $req->dialog;
        $dialog->title = trim($req->title) ? trim($req->title) : $dialog->title;
        $oldVid = $dialog->video_id;
        $dialog->video_id = $vid;//trim($req->video_id);
        $dialog->save();
        if($oldVid != $vid){
                $this->_crawlYoutubeSub($req->id);

        }
        return Redirect::to('/admin/story/story/' . $dialog->id);
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
                $name = $dir.rand(sizeof($files), 7*sizeof($files)).".png";
                while(file_exists($name)){
                    $name = $dir.rand(sizeof($files), 7*sizeof($files)).".png";
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
    public function setStoryDuration($id){
	 
        $story = Story::find($id);

        $story->duration = 0;
        $story->size = 0;
        $story->save();
        $this->setDurationAndSize($story);
    }
    public function setDurationAndSize($story) {
        if($story->duration > 0 && $story->size > 0){
            return;
        }
        $audio = Storage::disk('audios')->getAdapter()->getPathPrefix();
                if($story->duration == 0){
                        $mp3file = new MP3File($audio . $story->audio); //http://www.npr.org/rss/podcast.php?id=510282
                        $duration1 = @$mp3file->getDurationEstimate(); //(faster) for CBR only
                        $duration2 = @$mp3file->getDuration(); //(slower) for VBR (or CBR)

                        $duration = $duration1 > $duration2 ? $duration1 : $duration2;
                        if ($duration > 0) {
                                $story->duration = $duration;

                        }
                }
                if($story->size == 0){
                        $size = filesize($audio.$story->audio);
                        $story->size = $size;
                }
                $story->save();
                echo "Size: $story->size Duration: $story->duration <br>";

    }
    public function createVideoId($id){
            $dialog = Story::find($id);
			
            $this->setDurationAndSize($dialog);
            $this->_chageImagesName();
            $base = "mp4Story/";
            $types = $dialog->types;
			   
            $cat = $types[0];
            $folder = $base.$cat->id."/";// "videos/$dialog->id/";
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
                mkdir($folder."txt/", 0777, true);
            }
			
            $fp = fopen($folder."txt/".$dialog->id.'.txt', 'w');
            $text = strip_tags($dialog->dialog,"<br>");
//            $text = str_replace("<br>", "\n", $text);
            $text = preg_replace('(<br\s*\/?>\s*)', "\n", $text);
            $text = html_entity_decode($text);
            $text = preg_replace('/[A-Za-z][,.]\s/', "$0\n", $text);
          
            fwrite($fp, $text);
            fclose($fp);
           
//            $this->htmlFileLink($folder.$dialog->id.'_z.html', "http://ocodereducation.com/apiv1/admin/listening/dialog/".$dialog->id);
            $title = preg_replace('/[^A-Za-z0-9\-\s]/', '-', ($dialog->title))." - ";
            foreach($types as $type){
                $title .= " c".$type->id;
            }
            
            $t = Input::get("t",35);
           
            
//            echo $text."<br>";
//            $command2="ffmpeg -f image2 -r 1/7 -i images/video/%d.png -i \"audios/listening/".$dialog->audio."\" -t ".($dialog->duration + 1000)." -vcodec mpeg4 -s 720x576 -vf fps=5 -y \"$folder".$dialog->id." - ".$dialog->title.".mp4\"";
            $command2="ffmpeg -f image2 -r 1/$t -i images/video/%d.png -i \"audios/estory/".$dialog->audio."\" -t ".($dialog->duration + 4)." -c:v mpeg4 -y \"$folder".$dialog->id." - ".$title.".avi\"";

            echo $command2."<br>";
            echo "D:\web\laravel\api\\".$folder;
            //command for every 5 second image change in video along with 004-07.mp3 playing in background
           $val =  exec($command2);
           
            $dialog->video = 1;
            $dialog->save();
    }
    public function createVideo(){      
        $cat_id = Input::get("cat_id");
        
        if($cat_id){
            $cats = StoryType::where("lang","!=", 'es')->where("id",$cat_id)->get();
        }else{
            $cats = StoryType::where("lang","!=", 'es')->where("parent",">",0)->get();
        }
//        echo  preg_replace('/[^A-Za-z0-9\-\s]/', '-', "This: is a Test?");
        $base = "mp4Story/";
       
        foreach($cats as $cat){
            if($cat->video_ok == 0 || $cat->thumb =="")                break;
        }
//         dd($cat);
        echo $cat->id." ".$cat->title."<br>";
        $stories = $cat->stories()->orderBY("id", "ASC")->get();
        foreach($stories as $dialog){
           
            $this->_chageImagesName();
            $folder = $base.$cat->id."/";// "videos/$dialog->id/";
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
                mkdir($folder."txt/", 0777, true);
            }
            $fp = fopen($folder."txt/".$dialog->id.'.txt', 'w');
            $text = strip_tags($dialog->dialog,"<br>");
//            $text = str_replace("<br>", "\n", $text);
            $text = preg_replace('(<br\s*\/?>\s*)', "\n", $text);
            $text = html_entity_decode($text);
            $text = preg_replace('/[A-Za-z][,.]\s/', "$0\n", $text);
            
            fwrite($fp, $text);
            fclose($fp);
            $types = $dialog->types;
           
//            $this->htmlFileLink($folder.$dialog->id.'_z.html', "http://ocodereducation.com/apiv1/admin/listening/dialog/".$dialog->id);
            $title = preg_replace('/[^A-Za-z0-9\-\s]/', '-', ($dialog->title))." - ";
            foreach($types as $type){
                $title .= " c".$type->id;
            }
            
            if($dialog->video == 1){
                echo $title;
                continue;
            }
//            echo $text."<br>";
//            $command2="ffmpeg -f image2 -r 1/7 -i images/video/%d.png -i \"audios/listening/".$dialog->audio."\" -t ".($dialog->duration + 1000)." -vcodec mpeg4 -s 720x576 -vf fps=5 -y \"$folder".$dialog->id." - ".$dialog->title.".mp4\"";
            $command2="ffmpeg -f image2 -r 1/8 -i images/video/%d.png -i \"audios/estory/".$dialog->audio."\" -t ".($dialog->duration + 4)." -c:v mpeg4 -y \"$folder".$dialog->id." - ".$title.".avi\"";

            echo $command2."<br>";
            //command for every 5 second image change in video along with 004-07.mp3 playing in background
           $val =  exec($command2);
           
            $dialog->video = 1;
            $dialog->save();
           
        }
         
        $cat->video_ok = 1;
        $cat->save();
        
        echo '<html>
        <head>
            <title>Create Video 55s</title>';
        echo '            <meta http-equiv="refresh" content="15" />';
        echo ' 
        </head>
        <body></body></html>';
       
    
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
        $this->_crawlYoutubeSub($id);

    }
    public function jpg2Png(){
          $dir = "images/videojpg/";
         $files = scandir($dir);
        echo sizeof($files)."<br>";
         foreach($files as $file){
            if(strlen($file) > 2){           
                imagepng(imagecreatefromstring(file_get_contents($dir.$file)), "images/videopng/".$file.".png");

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
        $video = Story::find($id);
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
            return view('admin/story/playlist');

        }
//        PLM4Mkja-PGjwRFO5yNWH-FIKd0ZRjI2-z
        $f = new \App\library\DomParser();
        $html = $f->file_get_html("https://www.youtube.com/playlist?list=" . $play_id);
         $table = $html->find("#pl-video-table", 0);
        $videos = $table->find("tr");
        foreach ($videos as $video_html) {
            
            $video = null;
            $id_text = "data-video-ids";
            
            $yid = $video_html->find(".addto-watch-queue-play-now,.addto-watch-later-button-sign-in", 0)->$id_text;
            
            $ytitle = trim($video_html->find(".pl-video-title-link",0)->plaintext);
             
            $dlIds = explode(" ", $ytitle);            
            $dlId = trim($dlIds[0]);
			$i = 0;
            if ($yid) {               
                $dialog = Story::find($dlId);
                if(!$dialog){ echo ($ytitle); continue; };
                if(!$yid || !$dialog->video_id){
                    $dialog->video_id = $yid;
                    $dialog->save();
                    $this->_crawlYoutubeSub($dlId);
                    echo " <br><a href='". \Illuminate\Support\Facades\URL::to('/')."/admin/story/story/$dlId' target='_blank' >".$dialog->id." ".$dialog->title."</a><br>";
					 
                } else{
                    echo $dialog->title. " <a href='". \Illuminate\Support\Facades\URL::to('/')."/admin/story/story/$dlId' target='_blank' >".$dialog->id." Link</a>"."<br>";
                }

                
            } else {
                continue;
            }
        }

    }
    
    
    
    /*     * *********************
     * ********* ajax ******
     * ******************** */

    public function removeCat() {
        $cat = Input::get('cat_id', '0');
        $dl_id = Input::get('main_id', '0');
        $dl = Story::find($dl_id);
        $dl->types()->detach($cat);
        return response()->json(['cat' => $cat, 'main_id' => $dl_id]);
    }
    public function ajaxGetCats() {
        $cat_term = Input::get('term', '');

        $cats = StoryType::where('title', 'like', "%$cat_term%")->take(20)->get();

        $return = [];
        foreach ($cats as $cat) {
            $return[] = ['key' => $cat->id, 'value' => $cat->title];
        }
        return response()->json($return);
    }
    public function ajaxAddCat() {
        $cat = Input::get('cat_id', '0');
        $dl_id = Input::get('dl_id', '0');
        $dl = Story::find($dl_id);
        $changed = $dl->types()->syncWithoutDetaching([$cat]);
        if ($changed) {
            $dl->touch();
        }
        return response()->json(['cat' => $cat, 'main_id' => $dl_id, 'changed' => $changed]);
    }

    
}
