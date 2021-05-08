<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stories\StoryType;
use App\Models\Stories\Story;
use File;
use Illuminate\Support\Facades\Session;
use App\library\DomParser;
use Illuminate\Support\Facades\Storage;
use App\library\OcoderHelper;
use App\library\MP3File;
use Illuminate\Support\Facades\Input;

class StoryController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    public function getExample() {
        
    }

    public function index() {

        $cats = StoryType::where("lang", 'es')->get();
        $return = array();
        $i = 1;
        foreach ($cats as $cat) {
            $stories = $cat->stories()->orderBY("id", "ASC")->get();
            foreach ($stories as $story) {
                $this->setDuration($story);
                $str = new \stdClass();
                $str->id = $story->id;
                $str->title = $story->title;
                $str->album = $cat->title;
                $str->artist = $cat->title;
                $str->genre = $cat->title;
                $str->source = $story->audio;
                $str->image = $cat->image ? $cat->image : $cat->thumb;
                $str->trackNumber = $i++;
                $str->totalTrackCount = $stories->count();
                $str->duration = $story->duration;
                $str->site = "";
                $return[] = $str;
            }
        }
        $r = new \stdClass();
        $r->music = $return;

        return response()->json($r);
    }

    public function setDuration($story) {
        if($story->duration > 0){
            return;
        }
        $audio = Storage::disk('audios')->getAdapter()->getPathPrefix();
        $mp3file = new MP3File($audio . $story->audio); //http://www.npr.org/rss/podcast.php?id=510282
        $duration1 = @$mp3file->getDurationEstimate(); //(faster) for CBR only
        $duration2 = @$mp3file->getDuration(); //(slower) for VBR (or CBR)
        
        $duration = $duration1 > $duration2 ? $duration1 : $duration2;
        if ($duration > 0) {
            $story->duration = $duration;
            $story->save();
        }
    }
	public function setDucations(){
		?>
		<html>
		<head>
		<meta http-equiv="refresh" content="40" >

		</head>
		<body>
		<?php $story = Story::whereNull("size")->first(); 
		
		echo $story->title;
		echo "<br>";
		echo "<a target='_blank' href='http://ocodereducation.com/admin/stories/update/".$story->id."' >Link</a>"; 
		$this->setDurationAndSize($story);
		echo " <br>Duaration: " . $story->duration;
		echo " <br>Size: " . $story->size;
		?>
		<br>
		Refresing
		</body>
		</html>
		<?php
		exit;
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
    }
	public function getLangCats() {
		$lang = Input::get("lang","es");
		 
        $cats = StoryType::where("lang",  $lang)->where("parent",">", '0')->get();         
        return response()->json($cats);
    }
	
	public function getCats() {
        $cats = StoryType::where("lang","!=", 'es')->where("parent",">", '0')->where("thumb","!=", '')->get();         
        return response()->json($cats);
    }
	
	public function getStoriesByCat($cat_id) {
		if(!$cat_id){
			
		}
        $cat = StoryType::find($cat_id);  
		$stories = $cat->stories()->get();
		 $return = array();
		 $i = 1;
		foreach ($stories as $story) {
                $this->setDurationEn($story);
                 
            }
        return response()->json($stories);
    }

	public function setDurationEn($story) {
        if($story->duration > 0){
            return;
        }
        $audio = Storage::disk('enstory_audios')->getAdapter()->getPathPrefix();
		 
        $mp3file = new MP3File($audio . $story->audio); //http://www.npr.org/rss/podcast.php?id=510282
        $duration1 = @$mp3file->getDurationEstimate(); //(faster) for CBR only
        $duration2 = @$mp3file->getDuration(); //(slower) for VBR (or CBR)
        
        $duration = $duration1 > $duration2 ? $duration1 : $duration2;
		$size = filesize($audio.$story->audio);
		 
        if ($duration > 0) {
            $story->duration = $duration;
			$story->size = $size;
            $story->save();
        }
		clearstatcache();

    } 
	
	public function setVote(){
        $id = Input::get("id");
        $vote = Input::get("vote");
        if($vote > 0){
            Story::where("id",$id)->increment('liked');
        }else{
            Story::where("id",$id)->decrement('liked');
        }
       return Story::find($id)->liked; 
    }
	
	public function getSub(){
        $id = Input::get("id");
        
        $video = Story::find($id);
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
