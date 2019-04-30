<?php

namespace App\Http\Controllers\Crawl;

use App\Http\Controllers\Controller;
use App\library\DomParser;
//model
use App\Models\Quote\Quote;
use App\Models\Quote\Author;
use App\Models\Quote\Categories;
use App\Models\Quote\Tags;

/**
 * Description of QuoteController
 *
 * @author Asus
 */
class QuoteController extends Controller {
    var $_content ="";
    //put your code here
    public function __construct() {
//        $this->middleware('auth');
    }

    public function index() {
        $cats = Categories::where("done",0)->get();
        foreach ($cats as $cat) {
            if ($cat->vid) {
                $result = true;
                $page = $cat->page;
                $break = $page + 5;
                do {
                    
                    $result = $this->getQuotesByCat($page, $cat);
                    $this->_content .=  "<br>";
                    $cat->page = $page;
                    $cat->save();
                    $page++;                    
                    if ($page > $break) {
                        $time = 17;
                        $title = "Get Quotes";
                        $content = "<h1>Đang lấy dữ liệu Quotes...</h1>" . $this->_content;
                        return view('auto_refresh', compact('time', "title", "content"));
                        exit;                      
                    }
                } while ($result);
                $this->_content .= "<b>done ".$cat->title."</b><br>";
                $cat->done = 1;
                $cat->save();
                continue;
            }
            echo $cat->link;
            $text = file_get_contents($cat->link);
            $temp = explode("VID='", $text);
            $temp1 = explode("';", $temp[1]);
            $vid = $temp1[0];

            $temp = explode('PG_DM_ID="', $text);
            $temp1 = explode('";', $temp[1]);
            $tid = $temp1[0];

            if (strlen($vid) > 32 || strlen($tid) > 20) {
                exit;
            }
            echo " tid: " . $tid;

            echo " vid: " . $vid;
            echo "<br>";
            $cat->vid = $vid;
            $cat->tid = $tid;
            $cat->save();
        }
        exit;
        $result = true;
        $page = 1;
        $break = $page + 15;
        do {
            $result = $this->getQuotes($page);
            echo $page . "<br>";
            $page++;
            if ($page == $break) {
                break;
            }
        } while ($result);
    }

    public function getCats() {
        $domparser = new DomParser();
        $html = $domparser->file_get_html("https://www.brainyquote.com/topics");
        $topics = $html->find(".topicIndexChicklet");
        foreach ($topics as $topic) {
            $fav = $topic->find(".bqPinIcon", 0);
            echo $topic->plaintext;
            $cat_txt = trim($topic->plaintext);
            $link = "https://www.brainyquote.com" . trim($topic->href);

            $cat = Categories::where("title", $cat_txt)->first();
            if (!$cat) {
                $cat = new Categories();
                $cat->title = $cat_txt;
                if ($fav) {
                    $cat->favorite = 100;
                }
                $cat->save();
            }
            $cat->link = $link;
            $cat->save();
        }
//        echo $html->plaintext;    
        exit;
    }

    public function getQuotesByCat($page, $cat) {
        $this->_content .= "<b>".$cat->title."</b>";
        $this->_content .= "<br>";
        $this->_content .= "<b>Page: " . $page . "</b> ";
        $tid = $cat->tid;
        $vid = $cat->vid;
        $curl = curl_init();
        $this->_content .= $tid;
        $this->_content .= " ";
        $this->_content .= $vid;
        $this->_content .= " ";
        $this->_content .= "<br>";
         
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.brainyquote.com/api/inf",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
//            CURLOPT_POSTFIELDS => "{\"typ\":\"topic\",\"langc\":\"en\",\"v\":\"8.7.2b:3181700\",\"ab\":\"b\",\"pg\":$page,\"id\":\"$tid\",\"vid\":\"$vid\",\"fdd\":\"d\",\"m\":0}",
            CURLOPT_POSTFIELDS => "{\"typ\":\"topic\",\"langc\":\"en\",\"v\":\"8.7.2b:3181700\",\"ab\":\"b\",\"pg\":$page,\"id\":\"$tid\",\"vid\":\"$vid\",\"fdd\":\"d\",\"m\":0}",
            CURLOPT_HTTPHEADER => array(
                "Postman-Token: e968401c-4c97-4302-b6f1-fa84464a3056",
                "accept: application/json, text/javascript, */*; q=0.01",
                "cache-control: no-cache",
                "content-length: 138",
                "content-type: application/json;charset=UTF-8",
                "cookie: __cfduid=dc86cb1040c06a1c0d3a7c351b78fc3db1547461593; __gads=ID=b12e142e2807e294:T=1547461596:S=ALNI_Mb8XU6ByChM9Au2M4iT-KBasBVszw; _ga=GA1.2.1181772131.1550626758; _gid=GA1.2.1516316315.1550626758; _gat=1; bq_sd=%7B%22abg%22%3A%22b%22%2C%22bqContNum%22%3A6%2C%22bqPvd%22%3A1%2C%22bqRnd%22%3A19%2C%22bqBcavg%22%3A400%2C%22lastsc%22%3A0%7D; JSESSIONID=4FE67B669E5E2BE7F1B8272AB97C8C66",
                "origin: https://www.brainyquote.com",
                "referer: https://www.brainyquote.com/topics/sympathy",
                "user-agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.109 Mobile Safari/537.36",
                "x-requested-with: XMLHttpRequest"
              ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
 
        curl_close($curl);

        if ($err) {
            $this->_content .= "cURL Error #:" . $err;
            return false;
        } else {
            $result = json_decode($response);
             
            $message = @$result->message;
            if($message == "Bad request, invalid page" || $message ==  "Bad Request Parameters"){
                $this->_content .= $message;
               //get done
               return false;
             
            }elseif($message){
                echo $message;
                exit;
            }
        }
//        echo $result->content;
        
        $domparser = new DomParser();
        $html = $domparser->str_get_html($result->content);
        $contents = $html->find(".bqQt");
        foreach ($contents as $content) {
            $id = $content->id;
            if (!$id) {
                continue;
            }
            $quote_txt = trim($content->find(".b-qt", 0)->plaintext);
            $author_txt = trim($content->find(".bq-aut", 0)->plaintext);
             
            if ($author_txt) {
                $author = Author::where("name", $author_txt)->first();
                if (!$author) {
                    $author = new Author();
                    $author->name = $author_txt;
                    $author->short_name = $author_txt;

                    $author->save();
                }
                if ($content->find(".bq-aut", 0)->href) {
                    $author->link = "https://www.brainyquote.com".trim($content->find(".bq-aut", 0)->href);
                    $author->save();
                }
            }
            if ($quote_txt) {
                $quote = Quote::where("quote", $quote_txt)->first();
                if (!$quote) {
                    $quote = new Quote();
                    $quote->quote = $quote_txt;
                    $quote->author_name = $author_txt;
                    if ($author) {
                        $quote->auth_id = $author->id;
                    }
                    $quote->save();
                }
            }

            if ($cat) {
                $quote->cats()->syncWithoutDetaching([$cat->id]);
            }
            $tags_html = $content->find(".oncl_list_kc");
            foreach ($tags_html as $tag_html) {
                $tag_txt = trim($tag_html->plaintext);
//                echo $tag_txt . ", ";
                $tag = Tags::where("title", $tag_txt)->first();
                if (!$tag) {
                    $tag = new Tags();
                    $tag->title = $tag_txt;
                    $tag->save();
                }
                $quote->tags()->syncWithoutDetaching([$tag->id]);
                $cat1 = Categories::where("title", $tag_txt)->first();
                if ($cat1) {
                    $quote->cats()->syncWithoutDetaching([$cat1->id]);
                }
            }
            $this->_content .= $quote->id . ". " . $quote_txt . " ";
            $this->_content .= $author_txt;
            $this->_content .= "<br>";
//            $this->_content .= "<br>";
             
//            echo $content->innertext;
        }
        if ($result->qCount < 25) {
            return false;
        }
        return true;
    }

    public function getQuotes($page) {
//        $page = 2;
        echo $page . " ";
        $id = "null";
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.brainyquote.com/api/inf",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n    \"typ\": \"home_page\",\n    \"langc\": \"en\",\n    \"v\": \"8.6.5b:3132759\",\n    \"ab\": \"b\",\n    \"pg\": $page,\n    \"id\": $id,\n    \"vid\": \"343dbd599aeceab78596c7b436c5b453\",\n    \"fdd\": \"d\",\n    \"m\": 0\n}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/plain",
                "Postman-Token: 9c08dad0-2258-44f3-b272-85d3df0a8dc2",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
            return false;
        } else {
            $result = json_decode($response);
        }
        $domparser = new DomParser();
        $html = $domparser->str_get_html($result->content);
        $contents = $html->find(".bqQt");
        foreach ($contents as $content) {
            $quote_txt = trim($content->find(".b-qt", 0)->plaintext);
            $author_txt = trim($content->find(".bq-aut", 0)->plaintext);
            if ($author_txt) {
                $author = Author::where("name", $author_txt)->first();
                if (!$author) {
                    $author = new Author();
                    $author->name = $author_txt;
                    $author->short_name = $author_txt;
                    $author->save();
                }
            }
            if ($quote_txt) {
                $quote = Quote::where("quote", $quote_txt)->first();
                if (!$quote) {
                    $quote = new Quote();
                    $quote->quote = $quote_txt;
                    $quote->author_name = $author_txt;
                    if ($author) {
                        $quote->auth_id = $author->id;
                    }
                }
                $quote->favorite = 50;
                $quote->save();
            }


            $tags_html = $content->find(".oncl_list_kc");
            foreach ($tags_html as $tag_html) {
                $tag_txt = trim($tag_html->plaintext);
//                echo $tag_txt . ", ";
                $tag = Tags::where("title", $tag_txt)->first();
                if (!$tag) {
                    $tag = new Tags();
                    $tag->title = $tag_txt;
                    $tag->save();
                }
                $quote->tags()->syncWithoutDetaching([$tag->id]);
                $cat = Categories::where("title", $tag_txt)->first();
                if ($cat) {
                    $quote->cats()->syncWithoutDetaching([$cat->id]);
                }
            }
            echo $quote->id . ". " . $quote_txt . " ";
            echo $author_txt;
            echo "<br>";

//            echo $content->innertext;
        }
        if ($result->qCount < 25) {
            return false;
        }
        return true;
    }

}
