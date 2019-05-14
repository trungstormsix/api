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
use Illuminate\Support\Facades\Storage;

class TiengNhatController extends Controller {

    public function __construct() {
        
    }
    public function getN2KanjiEnglish(){
        $cat_id = 25;
        $string = file_get_contents("n2_kanji-list.json");
        $json = json_decode($string);
        $kanjiList = $json->kanjilist->kanji;
//        dd($json->kanjilist->kanji);
        $i = 1;
        foreach($kanjiList as $kanji){
            $article = Articles::where('title', $kanji->char)->where("lang","en")->first();
            $title = $kanji->char;
            $content = "<p class='on'><b>Onyomi:</b> <br>" . $kanji->on . "</p>";
            $content .= "<p class='kun'><b>Kunyomi:</b> <br>" . $kanji->kun . "</p>";
            $content .= "<p class='mean'><b>Meaning:</b> <br>" . $kanji->meaning . "</p>";
            $content .= "<p class='han_viet'><b>Stroke count:</b> " . $kanji->stroke_count . "</p>";
            if($kanji->compound){
                $content .= "<p class='bo_thanh_phan'><b>Examples:</b> </p>";
                $content .= "<table><tr><th>Kanji</th><th>Hiragana</th><th>Meaning</th><th>Type</th></tr>";
                if(is_array($kanji->compound)) {
                    foreach($kanji->compound as $voc){
                        $content .= "<tr><td>$voc->kanji</td><td>$voc->kana</td><td>$voc->translation</td><td>$voc->type</td><tr>";
                    }
                }else{
                    $voc = $kanji->compound;
                    $content .= "<tr><td>$voc->kanji</td><td>$voc->kana</td><td>$voc->translation</td><td>$voc->type</td><tr>";
                }
                $content .= "</table>";
            }
//            echo $content;
            echo $i++ . ". " . $title . '<br>';
             if (!$article) {
                
                $this->updateArticleKanji($title, $content, "", $cat_id, "https://jlptstudy.net/N2/lists/n2_kanji-list.json","en");                
            }
            
//            exit;
        }
    }

    public function getJp4u($link = "") {
        if (!$link) {
            $link = Input::get('link', "");
        }
        if (!$link) {
            echo "no Link";
            exit;
        }
        $parser = new DomParser();
        $html = $parser->file_get_html($link);
        $content = $html->find("#content .entry ", 0);
        $c = $content->find(" > p");
        $i = 1;
        foreach ($c as $link) {
            $a = $link->find("a", 0);
            if ($a && trim($a->plaintext)) {
                echo $i++ . $link->plaintext . "  " . $a->href . '<br>';

                $url = $a->href;
                $title = trim($link->plaintext);

                $article = Articles::where('link', $url)->first();
                if (!$article && strpos($title, "PDF version") === false) {
                    echo $link . '<br>';
                    $result = $this->gẹtP4uLesson($url, $title);
                    if ($i == 2) {
                        exit;
                    }
                } else {
                    $cat_id = (int) Input::get('cat_id', 0);

                    $article->cat_id = $cat_id;
                    $article->save();
                }
            }
        }
        exit;
    }

    private function gẹtP4uLesson($link, $title = "") {
        $cat_id = (int) Input::get('cat_id', 0);
        if (!$cat_id) {
            echo "no CatId";
            exit;
        }
        $parser = new DomParser();
        $html = $parser->file_get_html($link);
        if (!$title) {
            $title_html = $html->find(".title", 0);
            if ($title_html) {
                $title = trim($title_html->plaintext);
            }
        }
        $content = $html->find("#content .entry ", 0);
        $rems = $content->find("style, script, div,img");
        $thumb = "";
        if ($content->find("img.aligncenter", 0)) {
            $thumb = $content->find("img.aligncenter", 0)->src;
        }
        foreach ($rems as $r) {

            if ($r->tag == "div") {
                $audio = $r->find("audio", 0);
                if ($audio) {
                    $audio_link = $audio->find("a", 0)->href;
                    if ($audio_link) {
                        $r->outertext = '<div class="ckeditor-html5-audio" style="text-align:center">
<audio controls="controls" src="' . $audio_link . '">&nbsp;</audio>
</div>';
                    } else {
                        $r->outertext = "";
                    }
                } else {
                    $r->outertext = "";
                }
            } else {
                $r->outertext = "";
            }
        }

        if (trim($content->find("p", 0)->plaintext == "")) {
            $content->find("p", 0)->outertext = "";
        }

        $result = $this->updateArticle($title, $link, trim($content->innertext), $thumb, $cat_id, "en");
        return $result;
    }

    public function index() {
        $cat_id = (int) Input::get('cat_id', 0);
        if ($cat_id <= 0) {
            echo "Vui lòng nhập cat_id";
            exit;
        }
        $articles = Articles::where("cat_id", $cat_id)->where("published", 0)->where("lang", 'vi')->get();
        $i = 1;
        foreach ($articles as $article) {
            echo $i++ . ". ";
            echo "<a href='http://api.dev/admin/articles/edit/$article->id'>$article->id</a>" . "<br>";

            $this->updateBikaeDetail($article);
        }
        $link = Input::get('link', ""); //"http://japanesetest4you.com/jlpt-n5-grammar-list/"
        if ($link <= "") {
            echo "Vui lòng nhập link";
            exit;
        }


        $links = $this->getJp4u($link);
    }

    public function updateBikaeDetail($article) {
        $link = $article->link;
        $parser = new DomParser();
        $lurl = $this->get_fcontent($article->link);
        if (!$lurl) {
            $html = $parser->file_get_html($link);
            $this->_content .= $link . '<br>';
            $this->_content .= $html;
            exit;
        }
        $html = $parser->str_get_html($lurl[0]);

        $title_html = $html->find("#main h1.entry-title", 0);
        if ($title_html) {
            $title = trim($title_html->plaintext);
            $titles = explode("]", $title);
            if (@$titles[1]) {
                $title = $titles[1];
            }
        } else {
            return;
        }

        $content_html = $html->find("#main .entry-content", 0);
        $content = $content_html->innertext;
        $content = explode("</div><!-- .entry-content --> ", $content);
        $content_html = $parser->str_get_html($content[0]);
        if ($content_html) {
            //intro image
            $thumb_url = $html->find(".entry-featuredImg img", 0);
            if ($thumb_url) {
                $img_url = $thumb_url->srcset;
                if ($img_url) {
                    $urls = explode(" ", $img_url);
                    $intro_image = $urls[0];
                }
            }
            //handle all images
            $imgs = $content_html->find("img");
            foreach ($imgs as $img) {
                $img_url = $img->srcset;
                if ($img_url) {
                    $urls = explode(" ", $img_url);
                    $src = $urls[0];

                    $img->src = str_replace("https", "http", $src);
                    foreach ($img->getAllAttributes() as $attr => $val) {
                        if (strtolower($attr) == 'src')
                            continue;
                        $img->removeAttribute($attr);
                    }
                    $img->outertext = $img->outertext . '<br/>';
                }
            }

            $removes = $content_html->find("p[style='color:red; font-weight: bold; text-align:center; font-size: 1.1em'],noscript, style,.shareImage, ins,.entry-sns-share-buttons");
            foreach ($removes as $remove) {

                $remove->outertext = "";
            }
//            $as = $content_html->find("a");
//            foreach ($as as $a) {
//                $a->outertext = $a->innertext;
//            }
            $content = $content_html->innertext;
//            echo $content;
//            exit;
        } else {
            echo "<a href='http://api.dev/admin/articles/edit/$article->id'>$article->id</a>" . "<br>";
            return;
        }
        echo "<a href='$article->link.'>" . $article->link . "</a><br>";

        $article->content = $content;

        $article->save();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexBikae() {

        $return = $this->getKanjiTuHocOnline("http://thiendang.vn/wp-admin/admin-ajax.php?action=search_kanji&level=n2&search=&order_by=id&order=asc&page=1&words_per_page=500", 25);

        exit;

        $cat_id = 11; //Tu Vung N2

        $links = $this->getCats("http://bikae.net/tag/tu-vung-n1/");
        $i = 0;
        foreach ($links as $link) {
            $article = Articles::where('link', $link)->first();
            if (!$article) {
                echo $link . '<br>';
                $result = $this->getEvaDetail($link, $cat_id);
                if ($i++ == 7) {
                    exit;
                }
            } else {
//                 echo $link . '<br>';
            }
        }
    }

    public function getKanjiTuHocOnline($link, $cat_id = 2) {
//         tieng anh
        $parser = new DomParser();
        $lurl = $this->get_fcontent($link);
        if (!$lurl) {
            $html = $parser->file_get_html($link);
            $this->_content .= $link . '<br>';
            $this->_content .= $html;
            exit;
        }
        $data = json_decode($lurl[0]);


        $i = 0;
        $return = false;
        foreach ($data->list as $kj) {

            $kanji = $kj->kanji;
            $content = "<p class='on'><b>Onyomi:</b> <br>" . $kj->on_yomi . "</p>";
            $content .= "<p class='kun'><b>Kunyomi:</b> <br>" . $kj->kun_yomi . "</p>";
            $content .= "<p class='mean'><b>Nghĩa:</b> <br>" . $kj->y_nghia . "</p>";
            $content .= "<p class='han_viet'><b>Hán Việt:</b> <br>" . $kj->han_viet . "</p>";
            $content .= "<p class='bo_thanh_phan'><b>Bộ:</b> <br>" . $kj->bo_thanh_phan . "</p>";



            $article = Articles::where("title", $kanji)->first();
            if (!$article) {
                echo $i++ . ". " . $kanji . '<br>';
                $result = $this->updateArticleKanji($kanji, $content, "", $cat_id, $link);
                $return = $result || $return;
            }
        }
        return $return;
    }

    public function getKanji($link, $link_viet, $cat_id = 2) {

        $parser = new DomParser();
        $lurl = $this->get_fcontent($link_viet);
        if (!$lurl) {
            $html = $parser->file_get_html($link);
            $this->_content .= $link . '<br>';
            $this->_content .= $html;
            exit;
        }
        $html = $parser->str_get_html($lurl[0]);
        //        $trs_html = $html->find("table tr");
        $table = $html->find("#contentright table", 1);
        $trs_html = $table->find("tr");
        $i = 0;
        $list = [];
        foreach ($trs_html as $tr_html) {
            if ($i++ == 0)
                continue;
            $tds = $tr_html->find("td");
            $kanji = new \stdClass();

            $kanji->title = trim($tds[0]->plaintext);
            $kanji->on = trim($tds[1]->plaintext);
            $kanji->kun = trim($tds[2]->plaintext);
            $kanji->mean = trim($tds[3]->plaintext);
            $list[$kanji->title] = $kanji;
        }

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
        $trs_html = $html->find("table tr");
        $i = 0;
        foreach ($trs_html as $tr_html) {
            if ($i == 0) {
                $i = 1;
                continue;
            }

            $tds = $tr_html->find("td");
            $kanji = new \stdClass();

            $kanji->title = trim($tds[1]->plaintext);
            $article = Articles::where("title", $kanji->title)->first();

            if ($article)
                continue;
            $kanji_vi = @$list[$kanji->title];

            $kanji->on = trim($tds[2]->plaintext);
            $kanji->kun = trim($tds[3]->plaintext);
            $kanji->mean = trim($tds[4]->plaintext);

            $temp = $tds[1]->find("a", 0);
            if ($temp) {
                $img_a_link = $temp->href;
            } else {
                echo $kanji->title;
                exit;
            }
            if ($img_a_link != "http://https://nihongoichiban.com/2013/02/21/kanji-card-%E8%B2%A7/") {
                $img_html = $parser->file_get_html($img_a_link);
                $t = $img_html->find(".entry-content .wp-caption img,.entry-content img.size-full,.entry-content .size-thumbnail", 0);

                $kanji->img_link = $img_html->find(".entry-content .wp-caption img,.entry-content img.size-full,.entry-content  .size-thumbnail", 0)->attr["data-orig-file"];
            } else {
                $kanji->img_link = "";
            }
            $content = "";
            if ($kanji_vi) {


                $content .= "<p class='on'><b>Onyomi:</b> <br>" . $kanji->on . '<br>' . $kanji_vi->on . "</p>";
                $content .= "<p class='kun'><b>Kunyomi:</b> <br>" . $kanji->kun . '<br>' . $kanji_vi->kun . "</p>";
                $content .= "<p class='mean'><b>Nghĩa:</b> <br>" . $kanji_vi->mean . '<br>' . "Tiếng Anh: " . $kanji->mean . "</p>";
            } else {
                $content .= "<p class='on'><b>Onyomi:</b> <br>" . $kanji->on . "</p>";
                $content .= "<p class='kun'><b>Kunyomi:</b> <br>" . $kanji->kun . "</p>";
                $content .= "<p class='mean'><b>Nghĩa:</b> <br>" . $kanji->mean . "</p>";
                echo $kanji->title . " notitle <br>";
            }

            $result = $this->updateArticleKanji($kanji->title, $content, $kanji->img_link, $cat_id, $img_a_link);

            echo $i . ". " . $kanji->title . '<br>';
            if ($i++ == 55)
                exit;
        }
    }

    public function crawlVocabulary() {
        $cat_id = (int) Input::get('cat_id', 0);
        if ($cat_id <= 0) {
            echo "Vui lòng nhập cat_id";
            exit;
        }
        $link = "https://nihongoichiban.com/2011/04/30/complete-list-of-vocabulary-for-the-jlpt-n5/";
        $link = Input::get('link', ""); //"http://japanesetest4you.com/jlpt-n5-grammar-list/"
        if ($link <= "") {
            echo "Vui lòng nhập link";
            exit;
        }
        $category = \App\Models\Categories::find($cat_id);
        $prefix = $category->en ? $category->en : $category->name;

        $parser = new DomParser();
        $lesson = Articles::where('link', $link)->where("lang", "en")->first();
        if (!$lesson) {
            $html = $parser->file_get_html($link);
            $content = $html->find("#content .entry-content", 0);
            $as = $content->find("a");
            foreach ($as as $a) {
                $a->outertext = $a->innertext;
            }
            $ps = $content->find("p");
            foreach ($ps as $a) {
                $a->outertext = "<div class='temp'>$a->innertext</div>";
            }
            $tables = $content->find("table");
            $i = 0;
            foreach ($tables as $d) {
                $p = $content->find("p", $i++);
                $title = $prefix . ": " . $p->find("strong", 0)->innertext;
                $content_txt = "<p>" . $p->find("strong", 0)->innertext . "</p>" . $d->outertext;

                $this->updateArticleKanji($title, trim($content_txt), "", $cat_id, $link, "en");
            }
            exit;
        }
    }

    public function crawlKanji() {
        $cat_id = (int) Input::get('cat_id', 0);
        if ($cat_id <= 0) {
            echo "Vui lòng nhập cat_id";
            exit;
        }

        $parser = new DomParser();
        $query = Articles::where('cat_id', $cat_id);
        $query->where("lang", "vi");
        $lessons = $query->get();
        foreach ($lessons as $lesson) {
            echo $lesson->title . " " . $lesson->link . '<br>';
            $article = Articles::where('title', $lesson->title)->where("lang", "en")->first();
            if ($lesson->link && !$article) {
                $html = $parser->file_get_html($lesson->link);
                $content = $html->find("#content .entry-content", 0);
                $rms = $content->find("script, style, .wpcnt, #jp-post-flair, .wp-caption, .pd-rating");
                foreach ($rms as $rm) {
                    $rm->outertext = "";
                }
                $as = $content->find("a");
                foreach ($as as $a) {
                    $a->outertext = $a->innertext;
                }
                if (trim($content->find("p", 0)->plaintext == "")) {
                    $content->find("p", 0)->outertext = "";
                }
                echo trim($content->innertext);
                $this->updateArticleKanji($lesson->title, trim($content->innertext), $lesson->thumbnail, $cat_id, $lesson->link, "en");
            }
        }
//        
//        
    }

    public function updateArticleKanji($title, $content, $intro_image, $cat_id, $link = "", $lang = "vi") {
        $result = false;

        $article = Articles::where("title", $title)->where("lang", $lang)->first();

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
            $article->lang = $lang;
            $article->save();
        }
        return $result;
    }

    /**
     * get test
     */
    var $_content = "";

    public function getTest() {
        $_content = "";

        $link = trim(Input::get('link'));
        $cat_id = (int) Input::get('cat_id');
        if (!$link || !$cat_id) {
            return 404;
        }

        $tests = $this->getTestLinks($link);
        if ($tests) {
            $i = 0;
            foreach ($tests as $test) {
                if ($test->status == 2) {
                    continue;
                }
                $this->saveTest($test, $cat_id);
                $i++;
                if ($i >= 1) {
                    $time = 30;
                    $title = "Get Tests";
                    $content = "<h1>Đang lấy dữ liệu bài test...</h1>" . $this->_content;
                    return view('auto_refresh', compact('time', "title", "content"));
                }
            }
        }
        return redirect('admin')->with('success', "Đã lấy xong các bài test từ link: $link");
    }

    public function getCats($link) {
        $i = 0;
        $parser = new DomParser();
        $html = $parser->file_get_html($link);
        $content = $html->find("#main ", 0);
//        $content = $html->find("#main .entry-content", 0);
//        $content = $parser->str_get_html('');
//        $content = $parser->file_get_html('http://api.dev/images/jpcr/n3.txt');

        $links = $content->find('h1 a');
        $str_links = [];
        foreach ($links as $link) {
            if (trim($link->plaintext)) {
                if (strpos($link->href, "http") !== false) {
                    $href = $link->href;
                } else {
                    $href = $link->href;
                }
                $str_links[] = $href;
            }
        }

        return $str_links;
    }

    public function getEvaDetail($link, $cat_id = 2) {
        $parser = new DomParser();
        $lurl = $this->get_fcontent($link);
        if (!$lurl) {
            $html = $parser->file_get_html($link);
            $this->_content .= $link . '<br>';
            $this->_content .= $html;
            exit;
        }
        $html = $parser->str_get_html($lurl[0]);

        $title_html = $html->find("#main h1.entry-title", 0);
        if ($title_html) {
            $title = trim($title_html->plaintext);
            $titles = explode("]", $title);
            if (@$titles[1]) {
                $title = $titles[1];
            }
        } else {
            return;
        }

        $content_html = $html->find("#main .entry-content", 0);
        if ($content_html) {
            //intro image
            $thumb_url = $html->find(".entry-featuredImg img", 0);
            if ($thumb_url) {
                $img_url = $thumb_url->srcset;
                if ($img_url) {
                    $urls = explode(" ", $img_url);
                    $intro_image = $urls[0];
                }
            }
            //handle all images
            $imgs = $content_html->find("img");
            foreach ($imgs as $img) {
                $img_url = $img->srcset;
                if ($img_url) {
                    $urls = explode(" ", $img_url);
                    $src = $urls[0];

                    $img->src = str_replace("https", "http", $src);
                    foreach ($img->getAllAttributes() as $attr => $val) {
                        if (strtolower($attr) == 'src')
                            continue;
                        $img->removeAttribute($attr);
                    }
                    $img->outertext = $img->outertext . '<br/>';
                }
            }

            $removes = $content_html->find("p[style='color:red; font-weight: bold; text-align:center; font-size: 1.1em'], script,noscript, style,.shareImage, ins,.entry-sns-share-buttons");
            foreach ($removes as $remove) {
                $remove->outertext = "";
            }
            $as = $content_html->find("a");
            foreach ($as as $a) {
                $a->outertext = $a->innertext;
            }
            $content = $content_html->innertext;
//            echo $content;
//            exit;
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
    public function updateArticle($title, $link, $content, $intro_image, $cat_id, $lang = "vi") {
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
            $article->lang = $lang;
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

    public function getTestLinks($link) {
        $parser = new \App\library\DomParser();
        $html = $parser->file_get_html($link);
//        echo $html->find('#main',0)->innertext;
        $tests = [];
        $links = $html->find('.post  .title a');
        foreach ($links as $link_html) {
            $test = Test::where('link', $link_html->href)->first();
            if (!$test) {
                $test = new Test();
                $test->title = trim(str_replace("Japanese Language Proficiency Test", "", strip_tags($link_html->innertext)));
                $test->link = $link_html->href;
                $test->status = 0;
                $test->save();
            } else {
                if ($test->status == 1) {
                    continue;
                }
            }
            $tests[] = $test;
            if (sizeof($tests) > 2) {
                break;
            }
        }

        return $tests;
    }

    public function saveTest($test, $cat_id) {

        $link = $test->link;
        $this->_content .= $link . '<br>';
        $questions = $this->getQuestions($link);
        if (!$questions)
            return;
//  echo '<pre>';
//        var_dump($questions);
//        exit;
        $answers = array(
            'submit' => 'Submit Quiz',
        );
        $i = 1;
        foreach ($questions as $q) {
            $answers['quest' . $i] = 1;
            $i++;
        }

        $answers = $this->getAnswers($answers, $link);

        //map answers to question
        $i = 1;
        foreach ($questions as $q) {
            $ans = $q->answers;
            $q->answer = $ans[$answers['quest' . $i] - 1];
            $i++;
        }
//        echo '<pre>';
//        var_dump($questions);
//        exit;
        if ($questions) {
            foreach ($questions as $question) {
                $groupSql = Group::where('text', $question->group);
                if ($question->audio) {
                    $groupSql->where('audio', $question->audio);
                }
                if ($question->image) {
                    $groupSql->where('image', $question->image);
                }

                $group = $groupSql->first();

                if (!$group) {
                    $group = new Group();
                    $group->text = $question->group;
                    if ($question->audio) {
                        $group->audio = $question->audio;
                    }
                    if ($question->image) {
                        $group->image = $question->image;
                    }
                    $group->save();
                    $group->tests()->syncWithoutDetaching([$test->id]);
                }

                if (!$question->audio) {
                    $q = Question::where('question', $question->question)->where('correct', $question->answer)
                            ->where('answers', json_encode($question->answers))
                            ->where('group_id', $group->id)
                            ->first();
                } else {
                    $q = null;
                }
                if (!$q) {
                    $q = new Question();
                    $q->question = $question->question;
                    $q->answers = json_encode($question->answers);
                    $q->correct = $question->answer;
                    $q->group_id = $group->id;
                    $q->status = 1;
                    $q->save();
                    $this->_content .= $q->question . ' ' . $q->id . '<br>';
                }
            }

            $test->cats()->syncWithoutDetaching([$cat_id]);
            $test->status = 1;
            $test->save();

            $this->_content .= $test->title . '<br>';
        } else {
            $test->status = 2;
            $test->save();

            $this->_content .= 'False' . '<br>';
        }
    }

    public function getQuestions($link) {
        $parser = new \App\library\DomParser();

        $html = $parser->file_get_html($link);
        if (!$html->find('form')) {
            return;
        }
        $html->find('form div', 0)->outertext = '';
        $qs_html = $html->find('form > p, form > div,  form > audio');
        $questions = array();
        $group = "";
        $oldgroup = "";
        $audio = '';
        $image = '';
        $pre_is_question = false;
        foreach ($qs_html as $q) {
            if ($q->find('input[type="radio"]')) {
                $pre_is_question = true;
                $us = $q->find('.auto-style1');
                foreach ($us as $u) {
                    $u->outertext = '<u>' . $u->innertext . '</u>';
                }
                if ($q->find('img')) {
                    $image = $q->find('img', 0)->src;
                }
                $q_text = $q->innertext();
                $q_text = preg_replace("/\<br([^\>]*)>/", "<br/>", $q_text);
                $qs = explode('<br/>', $q_text);

                $question = new \stdClass();
                if (strpos($qs[0], '<input') !== false) {
                    $i = 0;
                    $question->question = $group;
                    $group = $oldgroup;
                } else {
                    $i = 1;
                    $question->question = trim(strip_tags(preg_replace("/(\d*)\./", "", $qs[0]), '<u><br><p>'));
                }


                $answers = array();
                for ($i; $i < sizeof($qs); $i++) {
                    $a = trim(strip_tags($qs[$i]));
                    if ($a) {
                        $answers[] = $a;
                    }
                }
                $question->answers = $answers;
                if ($question->answers) {
                    $questions[] = $question;
                } else {
                    continue;
                }
                if ($group) {
                    $question->group = $group;
                } else {
                    $question->group = $question->question;
                    $question->question = '';
                }
                if ($audio) {
                    $question->audio = $audio;
                } else {
                    $question->audio = null;
                }
                if ($image) {
                    $question->image = $image;
                } else {
                    $question->image = null;
                }
            } else {
                $us = $q->find('.auto-style1');
                foreach ($us as $u) {
                    $u->outertext = '<u>' . $u->innertext . '</u>';
                }
                if (trim($q->innertext())) {
                    if ($pre_is_question) {
                        $audio = null;
                        $image = null;
                        $oldgroup = $group;
                        $group = null;
                    }
                    if (trim(strip_tags($q->innertext())) != "Advertisement") {
                        if ($q->find('img')) {
                            $image = $q->find('img', 0)->src;
                        } elseif ($q->tag == 'audio') {


                            $audio = $q->find('source', 0)->src;
                        } else {
                            if (!$pre_is_question) {
                                if (trim($q->innertext()))
                                    $group .= '<p>' . trim($q->innertext()) . '</p>';
                            } else {
                                $group = trim($q->innertext());
                            }
                        }
                    }
                } else {
                    $oldgroup = $group;
                    $group = "";
                }
                $pre_is_question = false;
            }
        }
        return $questions;
    }

    public function getAnswers($answers, $link) {
        $parser = new \App\library\DomParser();
        $query = http_build_query($answers);
        $request = array(
            'http' => array(
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                "Content-Length: " . strlen($query) . "\r\n" .
                "User-Agent:" . $_SERVER['HTTP_USER_AGENT'] . "\r\n",
                'method' => 'POST',
                'content' => $query,
            )
        );

        $context = stream_context_create($request);

        $html = $parser->file_get_html($link, true, $context);
        $answers_html = $html->find('.clearfix .red,.clearfix .green');
        $i = 1;
        $correct = true;
        foreach ($answers_html as $answer) {
            if ($answer->class == 'red') {
                $answers['quest' . $i] ++;
                $correct = false;
            } else {
//                echo "correct" . '<br>';
            }
            $i++;
        }
        if (!$correct) {
            return $this->getAnswers($answers, $link);
        }
        return $answers;
    }

    public function crawlImagesAndAudios() {
        $group = Group::where("image", "like", "%japanesetest4you%")->orWhere("audio", "like", "%japanesetest4you%")->first();
        
        //mp3
        $mp3_link = $group->audio;
             

        if ($group->audio != null && strpos($mp3_link, "japanesetest") > 0) {
            $mp3_file = \App\library\OcoderHelper::getFileName($mp3_link);
            $audio = $group->id . $mp3_file;

            $status = true;
            //get audio
            if (!Storage::disk('jlpt_audios')->has($audio)) {
                echo "<b>Audio:</b>" . $audio . "<br>";
                ;
                $status = Storage::disk('jlpt_audios')->put($audio, file_get_contents($mp3_link));
            }
            echo $audio;
            if ($status) {
                $group->audio = "http://ocodereducation.com/apiv1/jlpt/audios/" . $audio;
            }
        }
        //image

        $img_link = $group->image;
        if ($group->image != null && strpos($img_link, "japanesetest") > 0) {
            $img_file = \App\library\OcoderHelper::getFileName($img_link);
            $img_file = $group->id . $img_file;
            $status = true;
            //get audio
            if (!Storage::disk('jlpt_images')->has($img_file)) {
                echo "<b>Image:</b>" . $img_link . "<br>";
                ;
                $status = Storage::disk('jlpt_images')->put($img_file, file_get_contents($img_link));
            }

            if ($status) {
                $group->image = "http://ocodereducation.com/apiv1/jlpt/images/" . $img_file;
            }
        }
        echo $group->image;
        echo "<br>";
        echo $group->audio;
        $group->save();
    }

}
