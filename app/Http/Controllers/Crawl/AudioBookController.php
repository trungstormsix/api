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

class AudioBookController extends Controller {

    var $lang = "en";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //$this->middleware('auth');
    }

       //http://etc.usf.edu/lit2go/books/
    public function index() {
//                $this->_getStoryETC("http://etc.usf.edu/lit2go/21/the-adventures-of-huckleberry-finn/99/chapter-1/", null, null);
//        $this->_getStoriesETC("http://etc.usf.edu/lit2go/21/the-adventures-of-huckleberry-finn/", null);
//                exit;
        $link = "http://etc.usf.edu/lit2go/books/";
        $parser = new DomParser();
        $html = $parser->file_get_html($link);
        $cats_htm = $html->find("#page_content #results figure");
        foreach ($cats_htm as $cat_html) {
//            echo $cat_html->innertext;
            $cat_title_html = $cat_html->find(".title a", 0);
            $cat_author = $cat_html->find(".author a", 0)->plaintext;
            $cat_description = $cat_html->find(".abstract",0)->innertext;
            $cat = StoryType::where("link", $cat_title_html->href)->orWhere("title", trim($cat_title_html->plaintext))->first();
            if ($cat) {                  
                if ($cat->parent == 11) {
					echo $cat->id." ". $cat->title. "<br><b>Complete</b><br>";
                   continue;
                } else {
                    $this->_getStoriesETC($cat->link, $cat);
                    $cat->parent = 11;
                    $cat->save();
                }
            } else {
                $cat = new StoryType();
                $cat->title = trim($cat_title_html->plaintext);
                $cat->link = trim($cat_title_html->href);
                $cat->author = $cat_author;
                $cat->description = $cat_description;
                $cat->save();
                $this->_getStoriesETC($cat->link, $cat);
                $cat->parent = 11;
                $cat->save();
            }
        }
        exit;
    }

    public function _getStoriesETC($link, $cat) {
        $parser = new DomParser();
        $html = $parser->file_get_html($link);
        $stories_html = $html->find("#column_primary dt a");
        $i = 1;
        foreach ($stories_html as $story_html) {
//            $stories_html = $stories_html->find("a",0);
            $story_title = trim($story_html->plaintext);
            $story_link = $story_html->href;
            echo $i++ . $story_title . " <br> " . $story_link . "<br>";
            $storyPart = Story::where("link", $story_link)->first();
            if(!$storyPart){
                $this->_getStoryETC($story_link, $story_title, $cat);
            }else{
				echo "exist";
			}
        }
    }
    var $i = 0;
    public function _getStoryETC($link, $title, $cat) {
        $parser = new DomParser();
        $html = $parser->file_get_html($link);
        $story_html = $html->find("#i_apologize_for_the_soup", 0);
        $audio_html = $story_html->find("audio source[type='audio/mpeg']", 0);
		$audio = "";
		if($audio_html){
			$audio_link = $audio_html->src;
		    $audio = "etc_".OcoderHelper::getFileName($audio_link);
			$story_html->find("audio", 0)->outertext = "";
		}else{
			echo "No Audio";
			//exit;
		}
//        echo $audio_link;
        
        $description = $story_html->innertext;
//        echo $description;
            
        $status = true;
        //get audio
        if ($audio && !Storage::disk('enstory_audios')->has($audio)) {
            $status &= Storage::disk('enstory_audios')->put($audio, file_get_contents($audio_link));
        }
        if (!$status) {
            echo "download fail";
            exit;
        }
        $new = false;

        $storyPart = Story::where("link", $link)->first();
        if (!$storyPart) {
            echo $audio."<br>";
            $storyPart = new Story();
            $storyPart->title = $title;
            $storyPart->audio = $audio;
            $storyPart->dialog = $description;
            $storyPart->link = $link;
            $storyPart->status = 0;
            $storyPart->save();
            $this->setDuration($storyPart);
            $new = true;
            $this->i++;
        }
        $c = $storyPart->types()->find($cat->id);
        if (!$c) {
            $storyPart->types()->attach($cat->id);
        }
        if ($new && $this->i >=2) {
            exit;
        }
    }

    public function indexLightup() {

        $title = "Audio Stories for Children";
        $link = "https://lightupyourbrain.com/stories/";
        $cat = StoryType::where("lang", $this->lang)->where(function ($query) use($title, $link) {
                    $query->where('link', $link)
                            ->orWhere('title', $title);
                })->first();

        if (!$cat) {
            $cat = new StoryType();
            $cat->title = $title;
            $cat->link = $link;
            $cat->lang = $this->lang;
            $cat->save();
        }
        $parser = new DomParser();
        $html = $parser->file_get_html($link);
        foreach ($html->find(".et_pb_section_1   a") as $a) {
            $title = trim($a->plaintext);
            $story_link = $link . str_replace("../stories/", "", $a->href);
            if ($title == "click here") {
                echo "continue";
                continue;
            }
            $this->_getStoriesLightup($story_link, $title, $cat);
            echo "<br>";
        }
        echo "complete";
        exit;
        $link = "https://lightupyourbrain.com/stories/audio-story-windswept/";
        $this->_getStoriesLightup($link);
    }

    private function _getStoriesLightup($link, $title, $cat) {
        $storyPart = Story::where("link", $link)->first();
        if ($storyPart) {
            echo $storyPart->title;
            echo "<br>";
            if (!$storyPart->duration) {
                $this->setDuration($storyPart);
            }
            return;
        }
        $parser = new DomParser();
        $html = $parser->file_get_html($link);
        $html_string = $html->find("body", 0)->innertext;

        //mp3
        $mp3_pos = strpos($html_string, "MP3jPLAYLISTS.MI_0 =");

        $mp3_text = substr($html_string, $mp3_pos + strlen("MP3jPLAYLISTS.MI_0 ="));
        $mp3_arrs = explode("</script>", $mp3_text);

        $mp3_json = trim(substr($mp3_arrs[0], strpos($mp3_arrs[0], ' mp3: "')));
        $mp3_json = substr($mp3_json, 0, strpos($mp3_json, '", counterpart:'));
        $mp3_json = str_replace('mp3: "', '', $mp3_json);
        if (strlen($mp3_json) < 3) {
            echo $link;
            exit;
        }

        $mp3_link = base64_decode($mp3_json);
        if (strpos($mp3_link, "ttp://") <= 0 && strpos($mp3_link, "ttps://") <= 0) {
            $mp3_link = "https://lightupyourbrain.com/wp-content/uploads/2017/" . $mp3_link;
        }

        //text
        $text = $html->find(".et_pb_section_1", 0);
        foreach ($text->find("ins, script, .sgmbShare, .dropdownWrapper ") as $ins) {
            $ins->outertext = "";
        }
        $description = $text->innertext;

        $audio_link = $mp3_link;
        $audio = OcoderHelper::getFileName($audio_link);

        $status = true;
        //get audio
        if (!Storage::disk('enstory_audios')->has($audio)) {
            $status &= Storage::disk('enstory_audios')->put($audio, file_get_contents($audio_link));
        }
        if (!$status) {
            echo "download fail";
            exit;
        }
        $new = false;
        $storyPart = Story::where("link", $link)->first();
        if (!$storyPart) {
            $storyPart = new Story();
            $storyPart->title = $title;
            $storyPart->audio = $audio;
            $storyPart->dialog = $description;
            $storyPart->link = $link;
            $storyPart->status = 0;
            $storyPart->save();
            $this->setDuration($storyPart);
            $new = true;
        }
        $c = $storyPart->types()->find($cat->id);
        if (!$c) {
            $storyPart->types()->attach($cat->id);
        }
        if ($new) {
            exit;
        }
    }

    public function indexLoyal() {
        $cats = StoryType::where("lang", 'es')->where('description', Null)->take(3)->get();
        if ($cats->count() == 0) {
            $this->_getStoriesLoyal('http://www.loyalbooks.com/language/Spanish', $this->lang);
            return;
        }
        foreach ($cats as $cat) {
            $this->_getStory($cat);
        }
    }

    private function _getStoriesLoyal($link, $lang) {

        $parser = new DomParser();
        $html = $parser->file_get_html($link);
        $cats = $html->find('table.layout2-blue td.layout2-blue[width="25%"]');

        foreach ($cats as $cat_html) {

            $removes = $cat_html->find('script, ins');
            foreach ($removes as $remove) {
                $remove->outertext = "";
            }
            $title = @trim($cat_html->find('a b', 0)->plaintext);
            if (!$title) {
                $title = @trim($cat_html->find('a', 0)->plaintext);
            }
            if (!$title) {
                continue;
            }
            $link = 'http://www.loyalbooks.com/' . $cat_html->find('a', 0)->href;
            $cat = StoryType::where("lang", $lang)->where(function ($query) use($title, $link) {
                        $query->where('link', $link)
                                ->orWhere('title', $title);
                    })->first();
            $thumb = "";
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
            if (!Storage::disk('enstory_audios')->has($audio)) {
                $status &= Storage::disk('enstory_audios')->put($audio, file_get_contents($audio_link));
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
        $audio = Storage::disk('enstory_audios')->getAdapter()->getPathPrefix();
        $mp3file = new MP3File($audio . $story->audio); //http://www.npr.org/rss/podcast.php?id=510282
        $duration1 = @$mp3file->getDurationEstimate(); //(faster) for CBR only
        $duration2 = @$mp3file->getDuration(); //(slower) for VBR (or CBR)

        $duration = $duration1 > $duration2 ? $duration1 : $duration2;
        if ($duration > 0) {
            $story->duration = $duration;
        }

        $audio = Storage::disk('enstory_audios')->getAdapter()->getPathPrefix();
        if ($story->size == 0) {
            $size = filesize($audio . $story->audio);
            $story->size = $size;
        }
        $story->save();
    }

    public function crawlDataFromOCoderEducation($cat_id){
        if(!$cat_id){
            return;
        }
        $json_text = file_get_contents("http://apiv1.ocodereducation.com/api/story/en/stories/".$cat_id);
        $json = json_decode($json_text);
        $status = 1;
        foreach ($json as $story){
            $audio = $story->audio;
            $audio_link =   "http://ocodereducation.com/apiv1/audios/estory/".$story->audio;
            $status = 1;
            if (!Storage::disk('enstory_audios')->has($audio)) {
                $status &= Storage::disk('enstory_audios')->put($audio, file_get_contents($audio_link));
            }
            echo $status." http://ocodereducation.com/apiv1/audios/estory/".$story->audio;
            echo "<br>";
        }
    }
}
