<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Playlist;
use App\Models\Video;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Models\CommonWord;
use App\Models\Dictionary;
use App\library\DomParser;

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
        $video = Video::take(100)->sortByDesc("updated_at");

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
            foreach ($playlists as $playlist) {
                $playlist->status = 1;
            }
            return $playlists;
        }
        if ($catid == 31) {
            $playlists = $query->take(80)->get();
        } else {
            $playlists = $query->get();
        }

        return $playlists;
    }
	public function getEnPlaylists($catid = 2) {
        $ver = Input::get("ver");
        $query = Playlist::where('en_cat_id', $catid)->orderBy('updated_at', 'desc');
//        if ($ver >= 21) {
//            $query->where("status", 0);
//            $playlists = $query->get();
//            foreach ($playlists as $playlist) {
//                $playlist->status = 1;
//            }
//            return $playlists;
//        }
//        if ($catid == 31) {
//            $playlists = $query->take(80)->get();
//        } else {
//            $playlists = $query->get();
//        }
        $playlists = $query->take(30)->get();
        return $playlists;
    }
    public function getVideos($id) {
        $playlist = Playlist::find($id);
        $playlist->videos = $playlist->videos->take(100);
        return $playlist->videos;
    }
	public function getEnVideos($id) {
		
        $playlist = Playlist::where("yid",$id)->first();
        $playlist->videos = $playlist->videos()->orderBy('updated_at', 'desc')->take(55)->get();
        return $playlist->videos;
    }
    
      public function crawlWordMean() {       
        
        $ms = Dictionary::where("crawl", 0)->orderBy("count", "desc")->paginate(20);
        foreach($ms as $m){
            $m->crawl = 1;
            $m->save();
        }
        return response()->json($ms);
    }
    
    public function lookedUp() {
        $lookup_w = trim(Input::get('word'));
        $lang = trim(Input::get('lang'));
        $html = @trim(Input::get('html'), "");
        
        $word = CommonWord::where("word", $lookup_w)->first();
        if (!$word) {
			if(!$html){
				return;
			}
            $word = new CommonWord();
            $word->count = 0;
            $word->word = $lookup_w;
        }
        $word->count++;
        $word->save();
        
        $m = Dictionary::where("word_id", $word->id)->where("lang", $lang)->first();
        if (!$m) {
            $m = new Dictionary();
            $m->lang = $lang;
            $m->word_id = $word->id;
        }
		 
        $m->save();
        $m->increment('count');
        if ($html && $m->get != 1) {
//        if ($html) {
           $m = $this->getGlobeWebFromHTML($html, $m);
        }
//        dd($m->mean);
		if(!@$m->mean){
			//$m->delete();
			$m->get = 0;
			$m->save();
		}else{
                        $examples = [];
                        if(@$m->example){
                            $example1s = json_decode($m->example);
                            foreach($example1s as $example){
                                if(!@$example->second || $example->second == ""){
                                    $m->get = 0;
                                    $m->save();
                                    break;
                                }else{
                                    $examples[] = $example;
                                }
                            }
                        }
                        if(@$m->mean){
                            $means = json_decode($m->mean);
                            foreach($means as $mean){
                                $example = new \stdClass();
                                if(@$mean->example && @$mean->example->example){
                                    $example->first = $mean->example->example;
                                    $example->second = $mean->example->mean;
                                    array_unshift($examples, $example);
                                }
                            }
                        }
			$word->tuc = @$m->mean ? json_decode($m->mean) : [];
			$word->examples = $examples;
		}
		$word->result = "ok";

        return response()->json($word);
    }

    public function getPronunciation() {
         $word_id = Input::get("word_id");
        if($word_id){
            $word = CommonWord::find($word_id);
//            dd($word);
            if($word){
                $this->crawlOxford($word);
            }
            exit;
        }
        $words = CommonWord::whereNull("en_us_pro")->orderBy('count', 'desc')->paginate(1);
        foreach ($words as $word) {
            $this->crawlOxford($word);
            $count = trim(Input::get('count', 0));
            if ($count != 0) {
                echo CommonWord::whereNull("en_us_pro")->orderBy('count', 'desc')->count();
            }
            exit;
        }
    }

    private function crawlOxford($voc) {
        $word = str_replace(" ", "+", $voc->word);
        $link = "http://www.oxfordlearnersdictionaries.com/definition/english/";
        echo "<a href='".$link . $word . '_1' . "' target='_blank'>Link</a><br>";

        $dom = new DomParser();
        $html = @$dom->file_get_html($link . $word . '_1');
		//echo $html;
        if (!$html) {
            $html = @$dom->file_get_html($link . $word);
        }
        if (!$html) {
            $html = @$dom->file_get_html($link . strtolower($word));
        }
        if (!$html) {
            $voc->count = 0 - $voc->count;
            $voc->save();
            echo "Crawl Fail";
            return;
        }

        $content = $html->find("#entryContent",0);


        if (!$content) {
            $voc->count = 0 - $voc->count;
            $voc->save();
            echo "no content h-g";
            return;
        }
        $type_html = $content->find(".top-g .pos", 0);
        if (!$type_html) {
            $voc->count = 0 - $voc->count;
            $voc->save();
            echo "no content pos <br>";
            //return;
        }else{
            $type = $type_html->plaintext;
        }
        //get pro uk
        $pron_uk_html = $content->find(".phons_br", 0);
        if (!$pron_uk_html) {
            $voc->count = 0 - $voc->count;
            $voc->save();
            echo "no pronunciation";
            return;
        }
        if ($pron_uk_html->find(".phon", 0)) {
            $pron_uk_text = trim(preg_replace("/BrE|\//", "", $pron_uk_html->find(".phon", 0)->plaintext));
            $pron_uk = $pron_uk_text;
        }

        if (!$pron_uk_html->find(".sound", 0))
            return;
        $voc->en_uk_pro = @$pron_uk ? $pron_uk : "";
        $mp3_uk_file = $pron_uk_html->find(".sound", 0)->getAttribute("data-src-mp3");
        $voc->en_uk_audio = $mp3_uk_file;

        //get pro us
        $pron_us_html = $content->find(".phons_n_am", 0);
        if (!$pron_us_html)
            return;
        if ($pron_us_html->find(".phon", 0)) {
            $pron_us_text = trim(preg_replace("/NAmE|\//", "", $pron_us_html->find(".phon", 0)->plaintext));
            $pron_us = $pron_us_text;
        }

        if (!$pron_us_html->find(".sound", 0))
            return;
        $voc->en_us_pro = @$pron_us ? $pron_us : "";
        $mp3_us_file = $pron_us_html->find(".sound", 0)->getAttribute("data-src-mp3");
        $voc->en_us_audio = $mp3_us_file;

        $mean = @$content->find('.sn-gs .gram-g', 0)->plaintext . @$content->find('.sn-gs .def', 0)->plaintext;
        
        /** save voc * */
        $voc->save();

        return true;
    }

    public function getGlobe() {
        $words = Dictionary::where("get", 0)->orderBy("count", "desc")->paginate(4);
        foreach ($words as $word) {
            $word_text = urlencode($word->word->word);
            if ($word->lang == "" || $word_text == "") {
                $word->get = 1;
                $word->save();
                return;
            }
			 
			if($word->lang == "null"){
                $nword = Dictionary::where("word_id", $word->word_id)->where("lang", "en")->first();
				if($nword){
                    $word->delete();
                      exit;
                }else{
                    $word->lang = "en";
                    $word->save();
                }
            }
            $link = "https://glosbe.com/gapi_v0_1/translate?from=eng&dest=$word->lang&format=json&phrase=" . $word_text . "&page=1&pretty=false&tm=true";
            echo "<a href='$link'>" . $link . "</a><br>";
            try {
                $json_text = @file_get_contents($link);
                if ($json_text == false) {
                    $this->getGlobeWeb();
                    exit;
                }
            } catch (Exception $e) {
                
            }
            $json = json_decode($json_text);
            if ($json->phrase == "") {
                $word->mean = "";
                $word->get = 1;
                $word->save();
                return;
            }
            if (@$json->tuc) {
                $mean = $json->tuc;

                $word->mean = json_encode($mean);
            }
            $example = $json->examples;
            $word->example = json_encode($example);
            $word->get = 1;
            $word->save();
        }
        exit;
    }

    public function getGlobeWebFromHTML($html, $word) {



        $word_text = urlencode($word->word->word);
        if ($word->lang == "" || $word_text == "") {
            $word->get = 1;
            $word->save();
            return;
        }
        //        echo "<a href='$link' target='_blank'>" . $link . "</a>  \t $word->count ";
//        echo "<a href='" . url("admin/dictionary/edit/" . $word->id) . "' target='_blank'><b>" . $word->word->word . "</b></a><br>";

        $dom = new DomParser();
        $html = @$dom->str_get_html($html);
        if (!$html) {

            $word->mean = "";
            $word->get = 2;
            $word->save();
            return $word;
        }
        $meanings_html = $html->find("li.phraseMeaning");
        $meanings = [];
		$count = 0;
        if ($meanings_html) {
            foreach ($meanings_html as $meaning_html) {
                $phrase = $this->getMeaningFromHtml($meaning_html, $word);
                if ($phrase) {
                    $meanings[] = $phrase;
                    if($count++ > 14){
                            break;
                    }
                }
            }
        } else {
            $meaning_html = $html->find(".sp_definitions,.meaningContainer", 0);
            $meanings = $this->getDefinitionFromHtml($meaning_html);
        }

        /**
         * examples
         */
        $examples_html = $html->find("#translationExamples .tableRow");
        $examples = [];
        $count = 0;
        foreach ($examples_html as $example_html) {
            $example = $this->getExampleFromHtml($example_html);
            if ($example && strlen($example->first) < 410) {
                if($word->lang == "en" || (@$example->second && $example->second != "")){
                    $examples[] = $example;
                    if($count++ > 9){
                        break;
                    }
                }
            }
        }
//        var_dump($examples);

//            dd($examples);

        if (!$meanings) {             
            $word->mean = "";
            $word->get = 2;
            $word->save();
            return $word;
        }
//        echo json_encode(array('text' => 'ارتباطات و اطلاع رسانی'), JSON_UNESCAPED_UNICODE);
//        exit;
        $word->mean = json_encode($meanings);
        $word->example = json_encode($examples, JSON_UNESCAPED_UNICODE);
        $word->get = 1;

        $word->save();
        return $word;
    }

    public function getGlobeWeb() {
//        $word = new \stdClass();
//        $word->word = "until";
//        $word->lang = "pt";
//        $html_txt = '<div class="sp_definitions hide-5th"><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/1" authorname="en.wiktionary.org" class="user-avatar-box" data-original-title="" title=""><noscript><img data-fbimgid="200030" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"/></noscript><img data-fbimgid="200030" class=" jsonly" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png" data-src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"><span title="author" class="user-avatar-box-name">en.wiktionary.org</span></div></aside></div></div><div>The first letter of the English alphabet, called <i>a</i> and written in the Latin script.</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/1" authorname="en.wiktionary.org" class="user-avatar-box" data-original-title="" title=""><noscript><img data-fbimgid="200030" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"/></noscript><img data-fbimgid="200030" class=" jsonly" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png" data-src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"><span title="author" class="user-avatar-box-name">en.wiktionary.org</span></div></aside></div></div><div>The ordinal number <b>first</b> , derived from this letter of the English alphabet, called <i>a</i> and written in the Latin script.</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/1" authorname="en.wiktionary.org" class="user-avatar-box" data-original-title="" title=""><noscript><img data-fbimgid="200030" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"/></noscript><img data-fbimgid="200030" class=" jsonly" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png" data-src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"><span title="author" class="user-avatar-box-name">en.wiktionary.org</span></div></aside></div></div><div>The highest rank on any of various scales that assign letters.</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/1" authorname="en.wiktionary.org" class="user-avatar-box" data-original-title="" title=""><noscript><img data-fbimgid="200030" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"/></noscript><img data-fbimgid="200030" class=" jsonly" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png" data-src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"><span title="author" class="user-avatar-box-name">en.wiktionary.org</span></div></aside></div></div><div>(education) The highest letter grade assigned (disregarding plusses and minuses).</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/1" authorname="en.wiktionary.org" class="user-avatar-box" data-original-title="" title=""><noscript><img data-fbimgid="200030" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"/></noscript><img data-fbimgid="200030" class=" jsonly" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png" data-src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"><span title="author" class="user-avatar-box-name">en.wiktionary.org</span></div></aside></div></div><div>(music) A tone three fifths above C in the cycle of fifths; the sixth tone of the C major scale; the reference tone that occurs at exactly 440 Hz.</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/1" authorname="en.wiktionary.org" class="user-avatar-box" data-original-title="" title=""><noscript><img data-fbimgid="200030" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"/></noscript><img data-fbimgid="200030" class=" jsonly" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png" data-src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"><span title="author" class="user-avatar-box-name">en.wiktionary.org</span></div></aside></div></div><div>(medicine) A blood type that has a specific antigen that aggravates the immune response in people with type B antigen in their blood. They may receive blood from type A or type O, but cannot receive blood from AB or B.</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/1" authorname="en.wiktionary.org" class="user-avatar-box" data-original-title="" title=""><noscript><img data-fbimgid="200030" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"/></noscript><img data-fbimgid="200030" class=" jsonly" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png" data-src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"><span title="author" class="user-avatar-box-name">en.wiktionary.org</span></div></aside></div></div><div>(vehicle-distinguishing signs) Austria</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/1" authorname="en.wiktionary.org" class="user-avatar-box" data-original-title="" title=""><noscript><img data-fbimgid="200030" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"/></noscript><img data-fbimgid="200030" class=" jsonly" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png" data-src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"><span title="author" class="user-avatar-box-name">en.wiktionary.org</span></div></aside></div></div><div>(Webster 1913) Adjective.</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/1" authorname="en.wiktionary.org" class="user-avatar-box" data-original-title="" title=""><noscript><img data-fbimgid="200030" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"/></noscript><img data-fbimgid="200030" class=" jsonly" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png" data-src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"><span title="author" class="user-avatar-box-name">en.wiktionary.org</span></div></aside></div></div><div>(often with ‘Q’ for “Question”) Answer</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/1" authorname="en.wiktionary.org" class="user-avatar-box" data-original-title="" title=""><noscript><img data-fbimgid="200030" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"/></noscript><img data-fbimgid="200030" class=" jsonly" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png" data-src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"><span title="author" class="user-avatar-box-name">en.wiktionary.org</span></div></aside></div></div><div>Asian</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/1" authorname="en.wiktionary.org" class="user-avatar-box" data-original-title="" title=""><noscript><img data-fbimgid="200030" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"/></noscript><img data-fbimgid="200030" class=" jsonly" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png" data-src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"><span title="author" class="user-avatar-box-name">en.wiktionary.org</span></div></aside></div></div><div>Admit</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/1" authorname="en.wiktionary.org" class="user-avatar-box" data-original-title="" title=""><noscript><img data-fbimgid="200030" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"/></noscript><img data-fbimgid="200030" class=" jsonly" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png" data-src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"><span title="author" class="user-avatar-box-name">en.wiktionary.org</span></div></aside></div></div><div>Application</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/1" authorname="en.wiktionary.org" class="user-avatar-box" data-original-title="" title=""><noscript><img data-fbimgid="200030" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"/></noscript><img data-fbimgid="200030" class=" jsonly" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png" data-src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"><span title="author" class="user-avatar-box-name">en.wiktionary.org</span></div></aside></div></div><div>asynchron</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/1" authorname="en.wiktionary.org" class="user-avatar-box" data-original-title="" title=""><noscript><img data-fbimgid="200030" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"/></noscript><img data-fbimgid="200030" class=" jsonly" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png" data-src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"><span title="author" class="user-avatar-box-name">en.wiktionary.org</span></div></aside></div></div><div>Augsburg</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/1" authorname="en.wiktionary.org" class="user-avatar-box" data-original-title="" title=""><noscript><img data-fbimgid="200030" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"/></noscript><img data-fbimgid="200030" class=" jsonly" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png" data-src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"><span title="author" class="user-avatar-box-name">en.wiktionary.org</span></div></aside></div></div><div>(physics) angstrom</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/1" authorname="en.wiktionary.org" class="user-avatar-box" data-original-title="" title=""><noscript><img data-fbimgid="200030" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"/></noscript><img data-fbimgid="200030" class=" jsonly" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png" data-src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"><span title="author" class="user-avatar-box-name">en.wiktionary.org</span></div></aside></div></div><div>(weaponry) atom</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/1" authorname="en.wiktionary.org" class="user-avatar-box" data-original-title="" title=""><noscript><img data-fbimgid="200030" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"/></noscript><img data-fbimgid="200030" class=" jsonly" src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png" data-src="/fb_img/hover-small/p3200030_170px-Wiktionary-logo-en.png"><span title="author" class="user-avatar-box-name">en.wiktionary.org</span></div></aside></div></div><div>(sports) An assist.</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[abbreviation] </i>Ace</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[abbreviation] </i>Acre</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[abbreviation] </i>Adult; as used in film rating</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[abbreviation] </i>Ammeter</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[abbreviation] </i>angstrom</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[abbreviation] </i>Answer</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[abbreviation] </i>An assist</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[abbreviation] </i>An asexual</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[abbreviation] </i>atom; atomic</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[abbreviation] </i>arsehole</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[Letter] </i><i>The first letter of the English alphabet, called [i]a</i> and written in the Latin script.[/i]</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>The highest rank on any of various scales that assign letters.</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>The highest letter grade assigned (disregarding plusses and minuses).</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>A tone three fifths above C in the cycle of fifths; the sixth tone of the C major scale; the first note of the minor scale of A minor; the reference tone that occurs at exactly 440 Hz; the printed or written note A; the scale with A as its keynote.&lt;ref name=SOED/&gt;&lt;ref name=OCD&gt;Christine A. Lindberg (editor), <i>The Oxford College Dictionary</i>, 2nd edition (Spark Publishing, 2007 [2002], ISBN 978-1-4114-0500-4), page 1&lt;/ref&gt;</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>A blood type that has a specific antigen that aggravates the immune response in people with type B antigen in their blood. They may receive blood from type A or type O but cannot receive blood from AB or B.</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>Mass number.</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>A universal affirmative suggestion.&lt;ref name=SOED&gt;Lesley Brown (editor), <i>The Shorter Oxford English Dictionary</i>, 5th edition (Oxford University Press, 2003 [1933], ISBN 978-0-19-860575-7), page 1&lt;/ref&gt;</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>acoustic source</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>actual weight of an aircraft</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>adulterer, adulteress</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>Alaska Steamship Company</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>Alcoa Steamship Company</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>allele dominant</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>alveolar gas</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>American Stock Exchange</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>ammunition examiner</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>Anchor Line</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>aspect ratio</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>Assembly Bill</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>Smallest of the brassiere cup sizes.</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>Chemical activity.</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>first van der Waals constant</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>Fraunhofer line for oxygen</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>hail</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>(in newspaper stock listings) includes extras</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>linear acceleration</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>mean sound absorption coefficient</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>Shoe size narrower than B</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>Single A league, one of the lowest professional leagues.</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/91945" authorname="en.wiktionary.2016" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__91945@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">en.wiktionary.2016</span></div></aside></div></div><div><i>[symbol] </i>Total acidity.</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/84" authorname="MicrosoftLanguagePortal" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__84@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__84@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__84@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">MicrosoftLanguagePortal</span></div></aside></div></div><div>A content descriptor developed by the Computer Entertainment Rating Organization (CERO).</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/84" authorname="MicrosoftLanguagePortal" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__84@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__84@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__84@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">MicrosoftLanguagePortal</span></div></aside></div></div><div>A feature that enables attendees to ask questions of presenters a meeting.</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/60172" authorname="omegawiki" class="user-avatar-box" data-original-title="" title=""><noscript><img data-fbimgid="258783" src="/fb_img/hover-small/lU258783_omegawiki.png"/></noscript><img data-fbimgid="258783" class=" jsonly" src="/fb_img/hover-small/lU258783_omegawiki.png" data-src="/fb_img/hover-small/lU258783_omegawiki.png"><span title="author" class="user-avatar-box-name">omegawiki</span></div></aside></div></div><div>A musical note between G and B.</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/93369" authorname="plwordnet-defs" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">plwordnet-defs</span></div></aside></div></div><div><i>[noun] </i>any of several fat-soluble vitamins essential for normal vision; prevents night blindness or inflammation or dryness of the eyes</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/93369" authorname="plwordnet-defs" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">plwordnet-defs</span></div></aside></div></div><div><i>[noun] </i>a metric unit of length equal to one ten billionth of a meter (or 0.0001 micron); used to specify wavelengths of electromagnetic radiation</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/93369" authorname="plwordnet-defs" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">plwordnet-defs</span></div></aside></div></div><div><i>[noun] </i>(biochemistry) purine base found in DNA and RNA; pairs with thymine in DNA and with uracil in RNA</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/93369" authorname="plwordnet-defs" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">plwordnet-defs</span></div></aside></div></div><div><i>[noun] </i>one of the four nucleotides used in building DNA; all four nucleotides have a common phosphate group and a sugar (ribose)</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/93369" authorname="plwordnet-defs" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">plwordnet-defs</span></div></aside></div></div><div><i>[noun] </i>the 1st letter of the Roman alphabet</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/93369" authorname="plwordnet-defs" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">plwordnet-defs</span></div></aside></div></div><div><i>[noun] </i>the basic unit of electric current adopted under the Systeme International d\'Unites; "a typical household circuit carries 15 to 50 amps"</div></div><div><div class="pull-right"><div class="source hover-small"><aside><div authorurl="/source/93369" authorname="plwordnet-defs" class="user-avatar-box" data-original-title="" title=""><noscript><img src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png"/></noscript><img class=" jsonly" src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png" data-src="//media.glosbe.com/a/_source__93369@glosbe.com-64.png"><span title="author" class="user-avatar-box-name">plwordnet-defs</span></div></aside></div></div><div><i>[noun] </i>the blood group whose red cells carry the A antigen</div></div><center><button class="btn hide-5th-toggle btn-mini"><span><i class="fa-down-open"></i> &nbsp;more </span></button></center></div>';
//        $dom = new DomParser();
//        $mean_html = @$dom->str_get_html($html_txt);
//        $this->getDefinitionFromHtml($mean_html);
//
//        exit;
		$word_id = Input::get("word_id", 0);
		if($word_id){
			$word = Dictionary::find($word_id);
			$word->mean = "";
			$word->example ="";
			$word->get = 0;
			$word->save();
			exit;
			$words = [];
			$words[] = $word;
		}else{
			$words = Dictionary::where("get", 0)->orderBy("count", "desc")->paginate(4);
		}
        foreach ($words as $word) {

            $word_text = urlencode($word->word->word);
            if ($word->lang == "" || $word_text == "") {
                $word->get = 1;
                $word->save();
                return;
            }
            $link = "https://glosbe.com/en/$word->lang/$word_text";
            echo "<a href='$link' target='_blank'>" . $link . "</a>  \t $word->count ";
            echo "<a href='" . url("admin/dictionary/edit/" . $word->id) . "' target='_blank'><b>" . $word->word->word . "</b></a><br>";

            //https://translate.googleapis.com/translate_a/t?anno=3&client=te&format=html&v=1.0&key&sl=en&tl=vi&sp=nmt&tc=1&tk=829652.670685&mode=1
            $dom = new DomParser();
            $html = @$dom->file_get_html($link);
            if (!$html) {
                echo "No meaning found here";
//                exit;
                $word->mean = "";
                $word->get = 2;
                $word->save();
                return;
            }
            $meanings_html = $html->find("li.phraseMeaning");
            $meanings = [];
            if ($meanings_html) {
                foreach ($meanings_html as $meaning_html) {
                    $phrase = $this->getMeaningFromHtml($meaning_html, $word);
                    if ($phrase) {
                        $meanings[] = $phrase;
                    }
                }
            } else {
                $meaning_html = $html->find(".sp_definitions,.meaningContainer", 0);
                $meanings = $this->getDefinitionFromHtml($meaning_html);
            }

            /**
             * examples
             */
			 
            $examples_html = $html->find("#translationExamples .tableRow");
            $examples = [];
            foreach ($examples_html as $example_html) {
                $example = $this->getExampleFromHtml($example_html);
                if ($example) {
                    $examples[] = $example;
                }
            }

//            dd($examples);

            if (!$meanings) {
                echo "No meaning found here";
//                exit;
                $word->mean = "";
                $word->get = 2;
                $word->save();
                return;
            }
            $word->mean = json_encode($meanings);
            $word->example = json_encode($examples);
            $word->get = 1;

            $word->save();
        }
    }

     private function getExampleFromHtml($example_html) {
        $example = new \stdClass();
        if (!$example_html || !$example_html->find(".span6", 0)) {
            return;
        }
        //get example
        $first = $example_html->find(".span6", 0)->find("span span", 0);
        if (!$first) {
            $first = $example_html->find(".span6", 0);
        }
		if(!$first->find(".tm-p-em", 0)) return;
//        $first->find("sup",0)->outertext = "";
        $first->find(".tm-p-em", 0)->outertext = "<strong>" . $first->find(".tm-p-em", 0)->innertext . "</strong>";
        $spans = $first->find("span");
        foreach ($spans as $span) {

            if ($span->class == "tm-p-em") {
                $span->outertext = "<strong class=\"keyword\">" . $span->innertext . "</strong>";
            } else {
                $span->outertext = $span->innertext;
            }
        }
        $example->first = trim($first->innertext);
        //get mean
		  
       
        $second = $example_html->find(".span6", 1)->find(".nobold", 0);
        if(!$second){
            $second = $example_html->find(".span6", 1)->find("span span", 0);
        }
        if (!$second) {
            $second = $example_html->find(".span6", 1);
            $ss = $second->find(".source,.visible-phone");
            foreach ($ss as $s) {
                $s->outertext = "";
            }
        }
        $has_mean  = 0;
        if($second->find(".tm-p-em", 0)){
		$second->find(".tm-p-em", 0)->outertext = "<strong>" . $second->find(".tm-p-em", 0)->innertext . "</strong>";
                $has_mean = 1;
        }
        		 

        $span1s = $second->find("span");
        foreach ($span1s as $span) {

            if ($span->class == "tm-p-em") {
                $span->outertext = "<strong class=\"keyword\">" . $span->innertext . "</strong>";
                $has_mean = 1;
            } else {
                $span->outertext = $span->innertext;
            }
        }
        
        if($has_mean){           
            $example->second = trim($second->innertext);
            $example->second  = strip_tags($example->second , "<i><strong><b>");

        }
        if(@$example->first){
            $example->first  = strip_tags($example->first , "<i><strong><b>");

        }
        return $example;
    }
	
    private function getDefinitionFromHtml($mean_html) {

        if (!$mean_html) {
            return [];
        }
        $ms = $mean_html->find(".pull-right");
        foreach ($ms as $m) {
            $m->outertext = "";
        }




        $phrase = new \stdClass();
        $subMean = new \stdClass();
        $subMean->language = "en";
        $subMean->text = strip_tags($mean_html->innertext,"<b><strong><i><div>");;

        $phrase->meanings[] = $subMean;
        $meanings[] = $phrase;

        return $meanings;
    }

    private function getMeaningFromHtml($mean_html, $word) {
//        echo $mean_html->outertext;
        $got = false;
        /*
         * for each phrase
         */
        $phrase = new \stdClass();
        //phrase
        if ($mean_html->find(".text-info", 0)) {
            $got = true;
            $phrase->phrase = new \stdClass();
            $phrase->phrase->text = trim($mean_html->find(".text-info", 0)->find(".phr", 0)->plaintext);
            $phrase->phrase->language = $word ? $word->lang : "pt";
            if ($mean_html->find(".text-info", 0)->find(".gender-n-phrase", 0)) {
                $gender = $mean_html->find(".text-info", 0)->find(".gender-n-phrase", 0)->plaintext;
                $gender = trim(str_replace(["{", "}"], "", $gender));
                $phrase->phrase->gender = $gender;
            }
        }
        //meaning

        $subMeans_html = $mean_html->find(".meaningContainer .phraseMeaning");
        if ($subMeans_html) {
            $got = true;
            $phrase->meanings = [];
            $i =0;
            foreach ($subMeans_html as $subMean_html) {
                //submean
                $submean = new \stdClass();
                $submean->language = "en";
                $submean->text = $subMean_html->plaintext;
                $phrase->meanings[] = $submean;
                if($i++ > 4){
                    break;
                }
            }
        }
        
        $example_html = $mean_html->find(".examples", 0);
        if ($example_html && $example_html->find(".span6", 0) && $example_html->find(".span6", 1)) {
            $phrase->example = new \stdClass();
            $ex = $example_html->find(".span6", 0);
            $removes = $ex->find(".pull-right,img");
            if($removes){
                foreach ($removes as &$span) { 
                        $span->outertext = "";                 
                }
            }
            $phrase->example->example = $ex->innertext;
             
            if ($example_html->find(".span6", 1)->find("div[dir='ltr']", 0)) {
                $phrase->example->mean = $example_html->find(".span6", 1)->find("div[dir='ltr']", 0)->innertext;
               
            } else {
                $mean = $example_html->find(".span6", 1);
                $removes = $mean->find(".pull-right,img");
                if($removes){
                    foreach ($removes as &$span) { 
                            $span->outertext = "";                 
                    }
                }
                $phrase->example->mean = $mean->outertext;
                
            }
        }
       
        if (!$got && $mean_html->plaintext) {
            $phrase->meanings = [];
            $subMean = new \stdClass();
            $subMean->language = "en";
            $subMean->text = $mean_html->plaintext;
            $phrase->meanings[] = $subMean;
        }
 
        return $phrase;
    }

}
