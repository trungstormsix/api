<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model {

    var $table = 'yplaylists';

    /**
     * The videos that belong to the playlist.
     */
    public function videos() {
        return $this->belongsToMany('App\Models\Video', 'yvideo_playlist');
    }

    public function countVideos() {
        return $this->videos()->selectRaw('video_id, count(*) as count')->groupBy('playlist_id')->first();
    }

    /**
     * Get the post that owns the comment.
     */
    public function cat() {
        return $this->belongsTo('App\Models\Ycat', 'cat_id');
    }

}
