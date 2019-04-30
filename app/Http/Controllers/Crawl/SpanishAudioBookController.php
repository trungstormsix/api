<?php

namespace App\Http\Controllers\Crawl;

use App\Http\Controllers\Controller;
use App\Models\Stories\StoryType;
use App\Models\Stories\Story;
use File;
use Illuminate\Support\Facades\Session;
use App\library\DomParser;
use Illuminate\Support\Facades\Storage;
use App\library\OcoderHelper;
use App\library\MP3File;

class SpanishAudioBookController extends Controller {
    var $lang = "es";
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function getExample() {
        
    }

    public function index() {
            
        $cats = StoryType::where("lang", 'es')->where('description', Null)->take(3)->get();
 
        if ($cats->count() == 0) {
            $this->_getStories('http://www.loyalbooks.com/language/Spanish', 'es');
            return;
        }
        foreach ($cats as $cat) {
            $this->_getStory($cat);
        }
    }

    private function _getStories($link, $lang) {

        $parser = new DomParser();
        $html = $parser->file_get_html($link);
        $cats = $html->find('table.layout2-blue td.layout2-blue[width="25%"]');

        foreach ($cats as $cat_html) {

            $removes = $cat_html->find('script, ins');
            foreach ($removes as $remove) {
                $remove->outertext = "";
            }
            $title = @trim($cat_html->find('a b', 0)->plaintext);
            if(!$title){
                $title = @trim($cat_html->find('a', 0)->plaintext);
            }
              if(!$title){
                  continue;
              }
            $link = 'http://www.loyalbooks.com/' . $cat_html->find('a', 0)->href;
            $cat = StoryType::where("lang", $lang)->where(function ($query) use($title, $link) {
                        $query->where('link', $link)
                                ->orWhere('title', $title);
                    })->first();
            $thumb = "" ;
            if ($cat_html->find('img', 0)) {
                $img = 'http://www.loyalbooks.com/' . $cat_html->find('img', 0)->src;
                $thumb = OcoderHelper::getFileName($img);
                Storage::disk('images')->put(OcoderHelper::getFileName($img), file_get_contents($img));
            }
            
            if (!$cat) {
                $cat = new StoryType();
                $cat->title = $title;
                $cat->link = $link;
                $cat->lang = $lang;
                $cat->thumb = $thumb;
                $cat->save();
            }
        }
    }

    private function _getChapsCats($html) {
        $cats = $html->find('td div a');
        foreach ($cats as $cat_html) {
            $title = trim($cat_html->plaintext);
            $link = 'http://www.loyalbooks.com/' . $cat_html->href;
            $cat = StoryType::where("lang", $this->lang)->where(function ($query) use($title, $link) {
                        $query->where('link', $link)
                                ->orWhere('title', $title);
                    })->first();

            if (!$cat) {
                $cat = new StoryType();
                $cat->title = $title;
                $cat->link = $link;
                $cat->lang = $this->lang;
//                $cat->thumb = $thumb;
                $cat->save();
            }
        }
    }

    private function _getStory($cat) {
        $parser = new DomParser();
        $html = $parser->file_get_html($cat->link);
        $more_chaps = $html->find(".layout3", 0);
        if ($more_chaps) {
            $this->_getChapsCats($more_chaps, $this->lang);
        }
        echo '<a href="' . $cat->link . '" target="_blank">' . $cat->title . '</a><br>';
        $author_html = $html->find('.book .book-author span', 0);
        $image = $author = $description = "";
        if ($author_html) {
            $author = trim($author_html->plaintext);
        }
        $image_url = $html->find('.book img.cover', 0)->src;

        if ($image) {
            $image_url = 'http://www.loyalbooks.com/' . $image_url;
            $image = OcoderHelper::getFileName($image_url);
            if (!Storage::disk('images')->has($image)) {
                Storage::disk('images')->put($image, file_get_contents($image_url));
            }
        }
        $book_description = $html->find('.book .book-description');
        if ($book_description) {
            foreach ($book_description as $description_html) {
                $description .= $description_html->plaintext . '<br>';
            }
        }

        $cat->image = $image;
        $cat->description = $description;
        $cat->author = $author;
        $audios_html = $html->find('.innertube div table.book td.book #jquery_jplayer_1', 0);
        $a = explode('var audioPlaylist = new Playlist("1", [', $html->innertext);
        $audios_tmp = explode('], {', $a[1]);

        $audios_tmp = trim($audios_tmp[0]);
        $audios_tmp = str_replace(["name:", "free:", "mp3:"], ['"name":', '"free":', '"mp3":'], $audios_tmp);

        $audioes = json_decode('[' . $audios_tmp . ']');
        if (!$audioes) {
            echo '[' . $audios_tmp . ']';
            exit;
        }

        foreach ($audioes as $audio_object) {
            $title = $audio_object->name;
            echo $title . '<br>';
            $audio_link = $audio_object->mp3;
            echo $audio_link . '<br>';

            $audio = OcoderHelper::getFileAndFolderName($audio_link);
            $status = true;
            //get audio
            if (!Storage::disk('audios')->has($audio)) {
                $status &= Storage::disk('audios')->put($audio, file_get_contents($audio_link));
            }
            //save story part
            $storyPart = Story::where("title", $title)->where("audio", $audio)->first();
            if (!$storyPart) {
                $storyPart = new Story();
                $storyPart->title = $title;
                $storyPart->audio = $audio;
                $storyPart->status = 1;
                $storyPart->save();
                $this->setDuration($storyPart);
            }
            $c = $storyPart->types()->find($cat->id);
            if (!$c) {
                $storyPart->types()->attach($cat->id);
            }
        }
        if ($status) {
            $cat->save();
        } else {
            echo "Error";
        }
    }

    public function setDuration($story) {
        if ($story->duration > 0) {
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

}
