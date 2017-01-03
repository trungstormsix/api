<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Playlist;
use App\Models\Video;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class ApiController extends Controller {

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
        $video = Video::all()->sortByDesc("updated_at");

        return $video;
    }

    /**
     * get all playlists
     */
    public function getPlaylists($catid = 2) {
        $ver = Input::get("ver");
        $query = Playlist::where('cat_id', $catid)->orderBy('updated_at', 'desc');
        if ($ver >= 21) {
            $query->where("status", 0);
            $playlists = $query->get();
            foreach($playlists as $playlist){
                $playlist->status = 1;
            }
            return $playlists;
        }
		if($catid == 31){
			$playlists = $query->take(80)->get();
		}else{
			$playlists = $query->get();
		}
        
        return $playlists;
    }

    public function getVideos($id) {
        $playlist = Playlist::find($id);
        $playlist->videos = $playlist->videos;
        return $playlist->videos;
    }

}
