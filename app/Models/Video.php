<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
     var $table = 'yvideos';     
    /**
     * The playlist that belong to the video.
     */
    public function playlists()
    {
        return $this->belongsToMany('App\Models\Playlist', 'yvideo_playlist');
    }
}
