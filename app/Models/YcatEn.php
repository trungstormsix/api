<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YcatEn extends Model
{
    var $table = 'ycats_english';
     const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'updated_at';
    /**
     * The videos that belong to the playlist.
     */
     public function playlists()
    {
         return $this->hasMany('App\Models\Playlist', 'en_cat_id');
    }
    
     public function countVideos()
    {
        return $this->playlists()->selectRaw('video_id, count(*) as count')->groupBy('en_cat_id')->first();
    }
}
