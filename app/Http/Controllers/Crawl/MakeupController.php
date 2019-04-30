<?php

namespace App\Http\Controllers\Crawl;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Makeup\Type;
use App\Models\Makeup\Article;
use Illuminate\Support\Facades\Session;
use App\library\DomParser;

class MakeupController extends Controller {

    public function __construct() {
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $this->getEvaCat("http://eva.vn/duong-da-c112.html",2,1);//da
        $this->getEvaCat("http://eva.vn/tri-seo-c58e1947.html",2,1);//da
        $this->getEvaCat("http://eva.vn/toc-dep-c111.html",1,1);//tóc
        $this->getEvaCat("http://eva.vn/giam-can-c288.html",3,1);//Giảm Cân
        $this->getEvaCat("http://eva.vn/huong-dan-ve-nail-c58e373.html",4,1);//móng
        $this->getEvaCat("http://eva.vn/trang-diem-c108.html",5,1);//Mẹo trang điểm
        $this->getEvaCat("http://eva.vn/tu-van-lam-dep-c280.html",7,1);//Tư vấn làm đẹp       
    }
    public function getEvaCat($link, $cat_id = 2, $number_get = 2){
        $i = 0;       
        $parser = new DomParser();
        $html = $parser->file_get_html($link);
        $links = $html->find(".breakingNews-trangtrong h2 a,.news-title a, .newsListTitle  a");
      
        foreach ($links as $link) {
            if (trim($link->plaintext)) {
                if (strpos($link->href, "http") !== false) {
                    $href = $link->href;
                } else {
                    $href = 'http://eva.vn' . $link->href;
                }
              
                $result = $this->getEvaDetail($href, $cat_id);
                if($result){
                    $i++;
                }
                if($i >= $number_get){
                    return;
                }
//                echo $i.'<br>';
//                echo $link->plaintext . ' ' . $href . '<br>';
            }
        }
    }

    public function getEvaDetail($link, $cat_id = 2) {
        $parser = new DomParser();
        $html = $parser->file_get_html($link);
        $title_html = $html->find("#content_eva h1", 0);
        if ($title_html) {
            $title = trim($title_html->plaintext);
            echo "<a href='$link'>$title</a><br>";
        } else {
            return;
        }
        $content_html = $html->find("#baiviet-container", 0);
        if ($content_html) {
            $thumb_url = $content_html->find("img.news-image", 0);
            if ($thumb_url) {
                $intro_image = $thumb_url->src;
            }
            $removes = $content_html->find(".baiviet-bailienquan, script, style,.shareImage");
            foreach ($removes as $remove) {
                $remove->outertext = "";
            }
            $as = $content_html->find("a");
            foreach ($as as $a) {
                $a->outertext = $a->innertext;
            }

            $imgs = $content_html->find('img');
            foreach ($imgs as $img) {
                $scr = $img->src;
                $img->src = str_replace("https", "http", $scr);
                $img->onmouseover = null;
                $img->onclick = null;
                $img->class = null;
            }
            $content = $content_html->innertext;
        } else {
            return;
        }
        $result = $this->updateArticle($title, $link, $content, @$intro_image, $cat_id);
        return $result;
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

        $article = Article::where("link", $link)->first();
        if (!$article) {
            $article = new Article();
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
                $article->intro_img = $intro_image;
            }

            $article->save();
           
        }
        
        $cats = [$cat_id];
        $article->cats()->syncWithoutDetaching($cats);
        return $result;
    }

}
