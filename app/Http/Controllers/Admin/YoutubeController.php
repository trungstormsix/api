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
use File;
use Illuminate\Support\Facades\Session;

class YoutubeController extends AdminBaseController {

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('admin/home');
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

    /**
     * get Playlist
     */
    public function getPlaylist($id = 0) {
        $playlist = null;
        if ($id) {
            $playlist = Playlist::find($id);
        }
        $cats = Ycat::all();
        return view('admin/youtube/editPlaylist', ['playlist' => $playlist, 'cats' => $cats]);
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
            if ($yid != "no_id") {
                $plid = $this->_getPlaylist($req, $yid);
            } else {
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
                $playlist->save();
            }
        }
        Input::flash();
        $url = '/admin/youtube/playlist/add';
        if ($plid) {
            $url = '/admin/youtube/playlist/edit/' . $plid;
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
        $playlist = Playlist::where('yid', $plId)->first();
        if (!$playlist) {
            $playlist = new Playlist();
            $playlist->yid = $plId;
        }

        $playlist->cat_id = $req->cat_id;
        $playlist->title = trim($title);
        $playlist->thumb_url = $thumb_url ? $thumb_url : "";

        if ($req->status) {
            $playlist->status = 1;
        } else {
            $playlist->status = 0;
        }
        $playlist->item_count = $playlist->videos()->count();
        $playlist->save();

        $this->_getVideos($html, $playlist);
        return $playlist->id;
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
        return view('admin/youtube/videos', compact('videos', 'playlist','sort_by','sort_dimen'));
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
            $yid = $video_html->find(".addto-watch-queue-play-now", 0)->$id_text;
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
            $playlist = YCat::find(Session::get('cat_id', 2))->playlists()->first();
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
                Session::flash('error', 'Video is exist <a href="/admin/youtube/video/edit/' . $video->id.'">here</a>!');
                Input::flash();
                return Redirect::to('/admin/youtube/video/add');
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
