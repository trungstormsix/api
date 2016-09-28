<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Playlist;
use App\Models\Video;
use App\Http\Controllers\Controller;

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
    public function getPlaylists($catid) {
        $playlists = Playlist::where('cat_id', $catid)->orderBy('updated_at', 'desc')->get();
        return $playlists;
    }

    public function getVideos($id){
        $playlist = Playlist::find($id);
        $playlist->videos = $playlist->videos;
        return $playlist->videos ;
    }
}
