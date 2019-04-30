<?php

namespace App\Http\Controllers\Crawl;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Articles;
use Illuminate\Support\Facades\Session;
use App\library\DomParser;
use App\Models\Test\Test;
use App\Models\Test\Group;
use App\Models\Test\Question;
use Illuminate\Support\Facades\Input;

class GermanController extends Controller {

    public function __construct() {
        
    }
    var $_host = "";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
         
        $link = \Request::get("link","https://deutsch.lingolia.com/en/grammar/tenses");
        $result = parse_url($link);
        $this->_host = $result["scheme"]."://".$result["host"];
        
        $parent_id = \Request::get("parent_id",30);
        $return = $this->getLessons($link, $parent_id);   
    }
 
    public function getLessonByLink(){
        $link = \Request::get("link"," https://francais.lingolia.com/en/grammar/prepositions");
        $result = parse_url($link);
        $this->_host = $result["scheme"]."://".$result["host"];
        
        $cat_id = \Request::get("cat_id",30);
        $return = $this->getLesson($link, $cat_id);   
    }


    public function getLessons($link, $parent_cat = 30) {
//         tieng anh
        $parser = new DomParser();
        $lurl = $this->get_fcontent($link);
        if (!$lurl) {
            $html = $parser->file_get_html($link);
            $this->_content .= $link . '<br>';
            $this->_content .= $html;
            exit;
        }
        $html = $parser->str_get_html($lurl[0]);
       
        $cat_title = trim($html->find("#nav-side  dd.active span",0)->plaintext);
        $cat = \App\Models\Categories::where("name", $cat_title)->where("parent_id", $parent_cat)->first();
        if(!$cat){
            $cat = new \App\Models\Categories();
            $cat->name = $cat_title;
            $cat->parent_id = $parent_cat;
            $cat->alias = str_slug($cat_title, '-');
            $cat->save();
        }
        echo $cat->id.'<br>';
        $links = $html->find("#nav-side  dd.active a");
        foreach($links as $link){
             $a_link = $link->href;
            if(strpos("http", $a_link) === FALSE){
                $a_link = $this->_host.$a_link;
            }
            echo $a_link.'<br>';
            $this->getLesson($a_link, $cat->id);
        }
        exit;
        
    }
    
    public function getLesson($link, $cat_id){
        $parser = new DomParser();
        $lurl = $this->get_fcontent($link);
        if (!$lurl) {
            $html = $parser->file_get_html($link);
            $this->_content .= $link . '<br>';
            $this->_content .= $html;
            exit;
        }
        $html = $parser->str_get_html($lurl[0]);      
        $title = $html->find("#main h1",0)->plaintext;
        echo $title;
        $article = $html->find(".mod_article",0);
        $intro_img = "";
        foreach ($article->find("img") as $img){
            $img->src  = $this->_host."/".$img->src;
            if(!$intro_img){
                $intro_img = $img->src;
            }
        }
         foreach ($article->find(".tooltip-content") as $tooltip){
            $tooltip->innertext  = " (".$tooltip->innertext.") ";
        }
        
          foreach ($article->find("a") as $a){
            $a->outertext  =  $a->innertext ;
        }
        
        
        $links = $article->find(".ce_article_list",0);
        if($links){
            $links->outertext = "";
        }
        $content = $article->innertext;
     
        $this->updateArticle($title, $link, $content, $intro_img, $cat_id);
        
    }

    /**
     * save artice to database
     * @param type $title
     * @param type $link
     * @param type $content
     * @param type $intro_image
     * @param type $cat_id
     * @return boolean
     */
    public function updateArticle($title, $link, $content, $intro_image, $cat_id) {
        $result = false;

        $article = Articles::where("link", $link)->first();
        if (!$article) {
            $article = new Articles();
            $result = true;

            if ($title) {
                $article->title = $title;
            }
            if ($link) {
                $article->link = $link;
            }
            if ($content) {
                $article->content = $content;
            }
            if ($intro_image) {
                $article->thumbnail = $intro_image;
            }
            $article->cat_id = $cat_id;
            $article->published = 0;
            $article->save();
        }
        return $result;
    }

    function get_fcontent($url, $javascript_loop = 0, $timeout = 5) {
        $url = str_replace("&amp;", "&", urldecode(trim($url)));

        $cookie = tempnam("/tmp", "CURLCOOKIE");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    # required for https urls
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        $content = curl_exec($ch);
        $response = curl_getinfo($ch);
        curl_close($ch);

        if ($response['http_code'] == 301 || $response['http_code'] == 302) {
            ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");

            if ($headers = get_headers($response['url'])) {
                foreach ($headers as $value) {
                    if (substr(strtolower($value), 0, 9) == "location:")
                        return get_url(trim(substr($value, 9, strlen($value))));
                }
            }
        }

        if (( preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value) ) && $javascript_loop < 5) {
            return get_url($value[1], $javascript_loop + 1);
        } else {
            return array($content, $response);
        }
    }

 
}
