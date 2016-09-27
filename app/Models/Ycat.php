<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ycat extends Model
{
    var $table = 'ycats';
    /**
     * The videos that belong to the playlist.
     */
     public function playlists()
    {
         return $this->hasMany('App\Models\Playlist', 'cat_id');
    }
    
     public function countVideos()
    {
        return $this->playlists()->selectRaw('video_id, count(*) as count')->groupBy('cat_id')->first();
    }
}
