<?php

namespace App\Http\Controllers\Crawl;

use App\Http\Controllers\Controller;
use App\library\DomParser;
use App\Role;
use App\Permission;
use App\User;
use App\Models\TruyenNgan;
use App\Models\TruyenTheLoai;
use File;
use Illuminate\Support\Facades\Session;

class TruyenController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(\Illuminate\Http\Request $request) {
        //$this->_getMoralStories();
         
        $this->_getYourStoryClub();
        exit;
    }

    var $base_story_link = "";
    var $total = 0;

    private function _getYourStoryClub() {
        $theloai = $this->_getTheLoai("Funny Stories", 'en');
        $this->base_story_link = 'http://yourstoryclub.com/story-category/short-stories-funny/page/';
        $links = $this->_getTotal('http://yourstoryclub.com/story-category/short-stories-funny');
        
        foreach ($links as $link) {
            $this->_saveYourStory($link->title, $link->link, $theloai);
        }
        for ($i = 2; $i < $this->total; $i++) {
            $domParser = new DomParser();
            $html = $domParser->file_get_html('http://yourstoryclub.com/story-category/short-stories-funny/page/'.$i);
            $links = $this->_getStoriesLinks($html);
            foreach ($links as $link) {
                $this->_saveYourStory($link->title, $link->link, $theloai);
            }
        }
    }

    private function _getTotal($link) {
        $domParser = new DomParser();
        $html = $domParser->file_get_html($link);
        if ($this->total == 0) {
            $pag = $html->find('.content-sidebar-wrap .pagination ul', 0);
            $last = $pag->last_child()->prev_sibling()->find('a', 0);
            $this->total = trim($last->plaintext);
        }
        return $this->_getStoriesLinks($html);
    }

    //save your story club story
    private function _saveYourStory($storyTitle, $storyLink, $theloai) {
        $story = TruyenNgan::where('link', '=', $storyLink)->first();
        if (!$story) {
            $story = TruyenNgan::where('title', '=', $storyTitle)->first();
        }
        if (!$story) {
            $story = $this->_getYourStory($storyLink, $storyTitle);
        }
        if (trim(strip_tags($story->content)) == "") {
            $story = $this->_getYourStory($storyLink, $storyTitle);
        }
        $c = $story->cats()->find($theloai->id);
        if (!$c) {
            $story->cats()->attach($theloai->id);
        }
    }

    private function _getYourStory($storyLink, $title) {
        echo $storyLink . '<br>';
        $domParser = new DomParser();
        $html = $domParser->file_get_html($storyLink);
        $intro_img_html = $html->find('.entry-content .size-full',0);
        $intro_img = "";
        if($intro_img_html){
            $intro_img = $intro_img_html->src;
        }
        $tac_gia = "";
        $tac_gia_html = $html->find('.entry-author .entry-author-name',0);
        if($tac_gia_html){
            $tac_gia = $tac_gia_html->plaintext;
        }
        $content_html = $html->find('.entry-content', 0);
        if (!$content_html) {
            $content_html = $html->find('div.Liner span[eza*="span_added_for_text:yes;cwidth:0px;;cheight:0px;;wcalc_source:child;', 0);
        }
        $removes = $content_html->find('script, div');
        foreach ($removes as $remove) {
            $remove->outertext = "";
        }

        $content = $content_html->innertext;
        $content = preg_replace('/<(hr)([^>]*)>|<(b)(\s[^>]*)>/', "<$1$3>", $content);
        $content = preg_replace('/(<br\s*>|<br\s*\/>){3,}/', "<br><br>", $content);
        
        if (trim(strip_tags($content)) == "") {
            echo $storyLink . 'FAILLLLLL<br>';
        }
        $content = strip_tags($content, '<br>,<b>,<hr>');
        $story = TruyenNgan::where('link', '=', $storyLink)->first();

        if (!$story) {
            $story = new TruyenNgan();
        }
        $story->title = $title;
        $story->link = $storyLink;
        $story->content = $content;
        $story->published = 1;
        $story->intro_img = $intro_img;
        $story->tac_gia = $tac_gia;
        $story->save();
        echo $storyLink . 'Ok<br>';

        return $story;
    }

    private function _getStoriesLinks($html) {
        $stories_links = $html->find('.content-sidebar-wrap .content .post ');
        $links = [];
        foreach ($stories_links as $stories_link) {
            $meta = $stories_link->find('.entry-meta',0)->plaintext;
            if(strpos($meta, 'English ') === false){
                continue;
            }
            $story_info = new \stdClass();
            $story_info->title = $stories_link->find('a', 0)->plaintext;
            $story_info->link = $stories_link->find('a', 0)->href;
            $links[] = $story_info;
        }
        return $links;
    }

    private function _getTheLoai($catTitle, $lang) {
        $theloai = TruyenTheLoai::where("title", $catTitle)->first();
        if (!$theloai) {
            $theloai = new TruyenTheLoai();
            $theloai->title = $catTitle;
            $theloai->lang = $lang;
            $theloai->save();
        }
        return $theloai;
    }

    private function _saveMoralStory($storyTitle, $storyLink, $theloai) {
        $story = TruyenNgan::where('link', '=', $storyLink)->first();
        if (!$story) {
            $story = TruyenNgan::where('title', '=', $storyTitle)->first();
        }
        if (!$story) {
            $story = $this->_getMoralStory($storyLink, $storyTitle);
        }
        if (trim(strip_tags($story->content)) == "") {
            $story = $this->_getMoralStory($storyLink, $storyTitle);
        }
        $c = $story->cats()->find($theloai->id);
        if (!$c) {
            $story->cats()->attach($theloai->id);
        }
    }

    /**
     * moral stories
     */
    private function _getMoralStories() {
        $catTitle = "Moral Stories";
        $link = 'http://www.english-for-students.com/Moral-Stories.html';
        $theloai = $this->_getTheLoai($catTitle, "en");

        $domParser = new DomParser();
        $html = $domParser->file_get_html($link);
        $stories = $html->find('ol[cwidth="450"]', 0)->find('li a');
        foreach ($stories as $story_link) {
            $storyTitle = trim($story_link->plaintext);
            $storyLink = $story_link->href;
            $this->_saveStory($storyTitle, $storyLink, $theloai);
        }
        exit;
    }

    private function _getMoralStory($storyLink, $title) {
        echo $storyLink . '<br>';
        $domParser = new DomParser();
        $html = $domParser->file_get_html($storyLink);
        $content_html = $html->find('div.Liner div[align="justify"]"]', 0);
        if (!$content_html) {
            $content_html = $html->find('div.Liner span[eza*="span_added_for_text:yes;cwidth:0px;;cheight:0px;;wcalc_source:child;', 0);
        }
        $removes = $content_html->find('script, .ezoic-ad');
        foreach ($removes as $remove) {
            $remove->outertext = "";
        }

        $content = $content_html->innertext;
        $content = preg_replace('/<(hr)([^>]*)>|<(b)(\s[^>]*)>/', "<$1$3>", $content);
        $content = preg_replace('/(<br\s*>|<br\s*\/>){3,}/', "<br><br>", $content);
        if (trim(strip_tags($content)) == "") {
            echo $storyLink . 'FAILLLLLL<br>';
        }
        $content = strip_tags($content, '<br>,<b>,<hr>');
        $story = TruyenNgan::where('link', '=', $storyLink)->first();

        if (!$story) {
            $story = new TruyenNgan();
        }
        $story->title = $title;
        $story->link = $storyLink;
        $story->content = $content;
        $story->published = 1;
        $story->save();
        echo $storyLink . 'Ok<br>';

        return $story;
    }

}
