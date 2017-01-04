<?php

namespace App\Http\Controllers\Crawl;

use App\Http\Controllers\Controller;
use File;
use Illuminate\Support\Facades\Session;
use App\library\DomParser;
use Illuminate\Support\Facades\Storage;
use App\library\OcoderHelper;
use App\Models\Playlist;
use App\Models\Video;
use App\Models\Ycat;

class YoutubeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
//        $this->middleware('auth');
    }

    var $count = 0;

    public function index() {
        $playlist = Playlist::find(31);
        if ($playlist->item_count == 0) {
            $playlist->item_count = $playlist->videos()->count();
            $playlist->save();
        }

        if (@Storage::disk('xml')->has("crawl.json")) {
            $json = @Storage::disk('xml')->get("crawl.json");
            $object = json_decode($json);
            $page = $object->video_page + 3;
        } else {
            $object = new \stdClass();
            $page = 7559;
        }
        if ($page <= 0) {
            return;
        }
        do {
            $result = $this->_getImages("http://uberhumor.com/page/" . $page);
            if ($result) {
                $page--;
            } else {
                $page = 0;
            }
            $object->video_page = $page;
            $json = @Storage::disk('xml')->put("crawl.json", json_encode($object));
        } while ($page != 0);
    }

    private function _getImages($link) {
        echo $link . '<br>';
        $parser = new DomParser();
        $html = $parser->file_get_html($link);
        $b = $html->find(".post .social-bookmark", 0);

        $images = $html->find('.post .homepostcontent');
        foreach ($images as $image_html) {
            $this->_getVideo($image_html);
            if ($this->count > 2) {
                exit;
            }
        }
        if (sizeof($images) > 0) {
            return true;
        }
    }

    private function _getVideo($html) {

        $title = html_entity_decode(trim($html->find(".homeposttitle", 0)->plaintext));
        $date = $html->find(".postdivider .date", 0)->plaintext . ' ' . $html->find(".postdivider .date_1", 0)->plaintext;
        if (strtotime($date . ' ' . date("Y")) > time()) {
            $date = $date . ' ' . (date("Y") - 1);
        }

        $img_html = $html->find(".homepagealin iframe", 0);
        if (!$img_html) {
            $img_html = $html->find("p iframe", 0);
        }
        if (!$img_html) {
            return;
        }
        //get youtube id
        $ylink = $img_html->src;
        $id = explode("/", $ylink);
        $id = $id[sizeof($id) - 1];
        $id = explode("?", $id);
        $yid = $id[0];

        $video = \App\Models\Video::where("yid", $yid)->first();
        if ($video) {
            $playlist = Playlist::find(31);
            if (!$playlist->thumb_url)
                $playlist->thumb_url = $video->thumb_url;

            $video->playlists()->syncWithoutDetaching([$playlist->id]);
            return;
        }


        $video = new Video();
        $video->yid = $yid;
        $video->title = trim(str_replace("Video:","",$title));
        echo $video->title . '<br>';

        try {
            $content = file_get_contents("http://youtube.com/get_video_info?video_id=" . $yid);
            parse_str($content, $ytarr);
            $length = @$ytarr['length_seconds'];
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
            if(strlen($time)  == 2){
                $time = "00:".$time;
            }
            echo $time;
            $video->time = $time;
        } catch (Exception $error) {
            
        }
        $video->thumb_url = "http://i1.ytimg.com/vi/" . $yid . "/hqdefault.jpg";

        $video->save();
        if ($video->id) {
            $playlist = Playlist::find(31);
            if (!$playlist->thumb_url) {
                $playlist->thumb_url = $video->thumb_url;
                $playlist->save();
            }
            $val = $video->playlists()->syncWithoutDetaching([$playlist->id]);
            if ($val) {
                $playlist->item_count = $playlist->videos()->count();
                $playlist->save();
            }

            $this->count++;
        }
        return true;
    }

}
