<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\User;
use App\Models\Playlist;
use App\Models\Video;
use App\Models\Ycat;

use App\Models\YcatEn;
use File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class YoutubeController extends AdminBaseController {

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('admin/home');
    }

    public function crawlAmazoneSub(){
        $yid = Input::get("yid");
        $video = Video::where("yid", $yid)->first();
        if(!$video){
            echo "no video";
            return;
        }
        $fileName = $yid.".txt";
        $link = "https://s3.amazonaws.com/soviosubtitles/truesubs/".$fileName;
        $status = false;
        if (!Storage::disk('ysubs')->has($fileName)) {
			echo "<b>sub:</b>".$fileName."<br>";;
            $status  = Storage::disk('ysubs')->put($fileName, file_get_contents($link));
            if($status){
                $video->has_sub = 2;
                $video->save();
            }
        }
        
        
        echo "<a href='".url("/ysubs/".$fileName)."' target='_blank'>Link Sub</a>";
         
    }

    public function crawlYoutubeSub(){
        $yid = Input::get("yid");
        $fileName = $yid.".txt";
        $video = Video::where("yid", $yid)->first();
        if(!$video){
            echo "no video";
            return;
        } 
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
                $video->has_sub = 2;
                $video->save();
            }
        }else{
            if($status){
                $video->has_sub = 2;
                $video->save();
            }
        }
        
        echo "<a href='".url("/ysubs/".$fileName)."' target='_blank'>Link Sub</a>";

    }
    
    /**
     * get Playlist
     */
    public function getYcat($id = 0) {
        $cat = null;
        if ($id) {
            $cat = Ycat::find($id);
        } else {
            
        }
        return view('admin/youtube/cat', ['cat' => $cat]);
    }
    public function getYcatEn($id = 0) {
        $cat = null;
        if ($id) {
            $cat = YcatEn::find($id);
        } else {
            
        }
        return view('admin/youtube/en_cat', ['cat' => $cat]);
    }
    /**
     * save cat
     */
    public function postYcat(Request $req) {
        $this->validate($req, [
            'title' => 'required|max:255',
        ]);

        if ($req->id) {
            $cat = Ycat::find($req->id);
        } else {
            $cat = new Ycat();
        }
        $cat->title = $req->title;
        $result = $cat->save();

        if ($result) {
            Session::flash('success', 'Youtube Category  saved successfully!');

            return Redirect::to('/admin/youtube/playlists/' . $cat->id);
        }
        Session::flash('error', 'Youtube Category failed to save!');

        Input::flash();
        return Redirect::to('/admin/youtube/cat/add');
    }
     public function postYcatEn(Request $req) {
        $this->validate($req, [
            'title' => 'required|max:255',
        ]);

        if ($req->id) {
            $cat = YcatEn::find($req->id);
        } else {
            $cat = new YcatEn();
        }
        $cat->title = $req->title;
        $result = $cat->save();

        if ($result) {
            Session::flash('success', 'Youtube Category  saved successfully!');

            return Redirect::to('/admin/youtube/en-playlists/' . $cat->id);
        }
        Session::flash('error', 'Youtube Category failed to save!');

        Input::flash();
        return Redirect::to('/admin/youtube/en-cat/add');
    }

    /**
     * 
     * @param type $catId
     * @return type
     */
    public function getPlaylists($catId) {
        $cat = Ycat::find($catId);
        Session::set("cat_id", $catId);
        return view('admin/youtube/playlists', ['playlists' => $cat->playlists, 'cat' => $cat]);
    }
     public function searchPlaylists() {
         $search = Input::get("search","%ted%");
        $cat = new Ycat();
        $cat->title = "Search Playlist";
        $playlists = Playlist::where("yid",$search)->orWhere("title","like",$search)->paginate(30);
        
        return view('admin/youtube/playlists', ['playlists' => $playlists, 'cat' => $cat,'search'=>$search]);
    }
     public function getEnPlaylists($catId) {
        $cat = YcatEn::find($catId);
        Session::set("en_cat_id", $catId);
        return view('admin/youtube/playlists', ['playlists' => $cat->playlists()->orderBy("view_count","DESC")->get(), 'cat' => $cat]);
    }
    public function getEnCats(){
        $cats = YcatEn::all();
        return view('admin/youtube/en_cats', compact('cats'));
    }
    /**
     * get Playlist
     */
    public function getPlaylist($id = 0) {
        $playlist = null;
        if ($id) {
            $playlist = Playlist::find($id);
        }
        $cats = Ycat::all();
        $enCats = YcatEn::all();
        return view('admin/youtube/editPlaylist', compact("cats","enCats", "playlist"));
    }

    /**
     * save playlist
     */
    public function postPlaylist(Request $req) {

        $yid = trim($req->get('yid'));
        $plid = $req->get("id");
        $message = null;         
        if (!$yid) {
            $message = "Please type a playlist id";
        } else {
            if ($req->crawl) {
                $playlist = $this->_getPlaylist($req, $yid);
                
            }else
              {
                $title = $req->get('title');
                    
                if ($req->id) {
                    $playlist = Playlist::find($req->id);
                } else {
                    $playlist = new Playlist();
                }
                $playlist->title = trim($title);
                if($req->get("thumb_url")){
                    $playlist->thumb_url = $req->get("thumb_url");
                }
                $playlist->yid = $yid;
                if ($req->status) {
                    $playlist->status = 1;
                } else {
                    $playlist->status = 0;
                }
                $playlist->item_count = $playlist->videos()->count();
                $playlist->cat_id = $req->get("cat_id");
                $playlist->en_cat_id = $req->get("en_cat_id");
                if($req->get("view_count",0) > 0){
                    $playlist->view_count = $req->get("view_count");
                }
                $playlist->save();
            }
            
            
             
        }
        
        Input::flash();
        $url = '/admin/youtube/playlist/add';
        if ($playlist->id) {
            $url = '/admin/youtube/playlist/edit/' . $playlist->id;
        }
        return Redirect::to($url)
                        ->with('error', $message)
                        ->withInput();
    }

    /**
     * get playlist from youtube playlist id
     * @param type $plId
     */
    private function _getPlaylist($req, $plId = "PLPSfPyOOcp3R9ZPLNjZkWxRy-BWCfNMrn") {
        $f = new \App\library\DomParser();
        $html = $f->file_get_html("https://www.youtube.com/playlist?list=" . $plId);
        $title = $req->get('title') ? $req->title : $html->find("#pl-header h1.pl-header-title", 0)->plaintext;
        $thumb_url = $req->get('thumb_url');
        if ($req->id) {
            $playlist = Playlist::find($req->id);
        }
        if (!$playlist) {
            $playlist = Playlist::where('yid', $plId)->first();
        }
        if (!$playlist) {
            $playlist = new Playlist();
            $playlist->yid = $plId;
        }

        $playlist->cat_id = $req->cat_id;
        $playlist->en_cat_id = $req->en_cat_id;

        $playlist->title = trim($title);
        $playlist->thumb_url = $thumb_url ? $thumb_url : "";

        if ($req->status) {
            $playlist->status = 1;
        } else {
            $playlist->status = 0;
        }
        $this->_getVideos($html, $playlist);

        $playlist->item_count = $playlist->videos()->count();
        $playlist->save();

        return $playlist;
    }

    public function crawlVideos($id){
        $f = new \App\library\DomParser();
        $playlist = Playlist::find($id);
        $html = $f->file_get_html("https://www.youtube.com/playlist?list=" . $playlist->yid);
        $this->_getVideos($html, $playlist);
    }

    /**
     * get Videos from playlist
     */
    public function videos($id) {
        if (@$_GET['sort_by']) {
            Session::put('vsort_by', $_GET['sort_by']);
            $dimen = @$_GET['sort_dimen'] ? $_GET['sort_dimen'] : 'asc';
            Session::put('sort_dimen', $dimen);
        }
        $sort_by = Session::get('vsort_by', 'updated_at');
        $sort_dimen = Session::get('sort_dimen', 'desc');
        
        $playlist = Playlist::find($id);
        
        $videos = $playlist->videos()->orderBy($sort_by, $sort_dimen)->paginate(20);
        $playlists = $playlist->cat->playlists()->orderBy("status","DESC")->orderBy("title","ASC")->get();
        foreach($playlists as $pl){
         //   $pl->count = $pl->videos()->count();
         //   $pl->count = $pl->videos()->count();
        }
         
        return view('admin/youtube/videos', compact('videos', 'playlist','playlists','sort_by','sort_dimen'));
    }
    
    /**
     * get Videos from playlist
     */
    public function searchVideos() {
        $search = Input::get("search","%ted%");

        if (@$_GET['sort_by']) {
            Session::put('vsort_by', $_GET['sort_by']);
            $dimen = @$_GET['sort_dimen'] ? $_GET['sort_dimen'] : 'asc';
            Session::put('sort_dimen', $dimen);
        }
        $sort_by = Session::get('vsort_by', 'title');
        $sort_dimen = Session::get('sort_dimen', 'asc');
        
        $videos = Video::where("yid",$search)->orWhere("title","like",$search)->orderBy($sort_by, $sort_dimen)->paginate(30);
        
        $playlists = Playlist::all();
        foreach($playlists as $pl){
             
            if($pl->videos){
                $pl->count = $pl->videos()->count();
            }else{
                $pl->count = 1;
            }
             
        }
        

        $playlist = $pl;
        return view('admin/youtube/videos', compact('videos', 'playlist','playlists','sort_by','sort_dimen','search'));
    }

    /**
     * get all videos of a playlist
     * @param type $html        html of playlist
     * @param type $playlist    current playlist
     */
    private function _getVideos($html, $playlist) {
        $playlist->item_count = 0;

        $table = $html->find("#pl-video-table", 0);
        $videos = $table->find("tr");
        foreach ($videos as $video_html) {
            $video = null;
            $id_text = "data-video-ids";
            $yid = $video_html->find(".addto-watch-queue-play-now,.addto-watch-later-button-sign-in", 0)->$id_text;
            if ($yid) {
                $video = Video::where("yid", $yid)->first();
            } else {
                continue;
            }
            if (!$video)
                $video = new Video();
            $video->yid = $yid;
            $video->title = trim($video_html->find("a.pl-video-title-link", 0)->innertext);
            echo $video->title . '<br>';
            if ($video->title == '[Video đã xóa]') {
                continue;
            }
            try {
                $video_time = $video_html->find(".timestamp span", 0);
                if ($video_time) {
                    $video->time = $video_time->plaintext;
                } else {
                    continue;
                }
            } catch (Exception $error) {
                continue;
            }
            $video->thumb_url = "http://i1.ytimg.com/vi/" . $yid . "/hqdefault.jpg";

            $video->save();
            if ($video->id) {
                if (!$playlist->thumb_url)
                    $playlist->thumb_url = $video->thumb_url;
                $Pl = $video->playlists()->find($playlist->id);
                if (!$Pl)
                    $video->playlists()->attach($playlist->id);
                $playlist->item_count ++;
            }
        }

        $playlist->save();
    }

    public function deleteVideo() {
        $id = Input::get("id");
        $video = Video::find($id);
        if ($video) {
            $video->playlists()->detach();
            $video->delete();
            return $video;
        } else {
            return json_encode(false);
        }
    }

    public function changePlaylist() {
        $id = Input::get("id");
        $playlist_id = Input::get("playlist_id");
        $video = Video::find($id);
        if ($video && $playlist_id) {
            $video->playlists()->sync([$playlist_id]);
            $video->touch();
            return $video;
        } else {
            return json_encode(false);
        }
    }

    public function video($id = 0) {
        $video = Video::find($id);
        if ($video) {
            $playlist = $video->playlists()->first();
        } else {
            $playlist = YCat::find(Session::get('cat_id', Session::get("cat_id", 5)))->playlists()->first();
        }
        return view('admin/youtube/video', compact('video', 'playlist'));
    }

    public function saveVideo(Request $req) {
        $this->validate($req, [
            'yid' => 'required|max:255', 'playlist' => 'required',
        ]);

        if ($req->get('id')) {
            $this->validate($req, [
                'title' => 'required|max:255',
            ]);
            $video = Video::find($req->get('id'));
            $video->update($req->all());
        } else {
            $video = Video::where("yid", $req->get("yid"))->first();
            if ($video) {
                Session::flash('error', 'Video is exist!');
                Input::flash();
                return Redirect::to('/admin/youtube/video/edit/' . $video->id);
            }
            $video = new Video();
            $info = $this->_getVideoInfo($req->get("yid"));
            $video->yid = $req->get("yid");
            $video->title = $req->get("title") ? $req->get("title") : $info['title'];
            $video->time = $info['time'];
            $video->save();
        }
        if (!$req->get("thumb_url")) {
            $video->thumb_url = "http://i1.ytimg.com/vi/" . $req->get("yid") . "/hqdefault.jpg";
        }
        $video->save();
        if ($video) {
            Session::flash('success', 'Youtube Video saved successfully!');

            $video->playlists()->sync([$req->get("playlist")]);

            return Redirect::to('/admin/youtube/video/edit/' . $video->id);
        }
        Session::flash('error', 'Youtube Video  saved fail!');
        Input::flash();
        return Redirect::to('/admin/youtube/video/add');
    }

    private function _getVideoInfo($yid) {
        try {
            $content = file_get_contents("http://youtube.com/get_video_info?video_id=" . $yid);
            parse_str($content, $ytarr);
            $length = @$ytarr['length_seconds'];
            $title = @$ytarr['title'];

            if (!$length) {
                return;
            }
            $time = $length % 60;
            if ($time < 10) {
                $time = "0" . $time;
            }
            $length = floor($length / 60);
            if ($length) {
                $tmp = ($length % 60);
                if ($tmp < 10) {
                    $tmp = "0" . $tmp;
                }
                $time = $tmp . ":$time";
                if ($length) {
                    $length = floor($length / 60);
                }
                if ($length) {
                    $tmp = ($length % 60);
                    if ($tmp < 10) {
                        $tmp = "0" . $tmp;
                    }
                    $time = $tmp . ":$time";
                }
            } else {
                $time = '00:' . $time;
            }
            if (strlen($time) == 2) {
                $time = "00:" . $time;
            }
        } catch (Exception $error) {
            
        }
        return compact('title', 'time');
    }

}
