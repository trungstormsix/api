<?php

namespace App\Http\Controllers\Crawl;

use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\User;
use App\Models\Idiom;
use App\Models\IdiomCat;
use App\Models\IdiomExample;
use File;
use Illuminate\Support\Facades\Session;

class IdiomController extends Controller {

    var $url = 'http://idioms.thefreedictionary.com/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function getExample() {

        $dom = new \App\library\DomParser();
        $idioms = Idiom::where('example', Null)->where('is_got', null)->take(5)->get();
        if (!$idioms) {
            echo 'finished';
            exit;
        }
        $data = [];
        foreach ($idioms as $idiom) {
            $idiom->word = strtolower($idiom->word);
            $idiom_world = str_replace(' ', '+', $idiom->word);
            $idiom->is_got = 0;
            $examples = $this->_crawlExample($this->url . $idiom_world);
            if ($examples) {
                $idiom->example = json_encode($examples);
                $idiom->is_got = 1;
                $idiom->updated = date('Y-m-d H:i:s');
            }
            $idiom->save();

            $data[] = $idiom->word . ' got: ' . $idiom->is_got;
        }
        $total = Idiom::where('is_got', 1)->count();
        $data[] = '<h2>Total Got: ' . $total . '</h2>';
        return view('layouts/autoRefresh', ['data' => $data]);
    }

    private function _crawlExample($url) {
        $dom = new \App\library\DomParser();
        @$exp_html = $dom->file_get_html($url);
        if ($exp_html) {
            $exps = $exp_html->find('#Definition .ds-single .illustration');
            $examples = [];
            foreach ($exps as $exp) {
                $example_text = $exp->plaintext;
                $example = IdiomExample::where('example', '=', $example_text)->first();
                if (!$example) {
                    $example = new IdiomExample();
                    $example->example = $example_text;
                    $example->save();
                }

                if (!in_array($example->id, $examples)) {
                    $examples[] = $example->id;
                }
            }
            return $examples;
        }
        return null;
    }

    public function getIdioms() {
        $cat_id = \Illuminate\Support\Facades\Input::get('cat_id', 91);

        if ($cat_id == 93) {
            $this->getTop200PhrasalVerbs();
        }
    }

    public function getTop50Idioms() {
        $cat_id = \Illuminate\Support\Facades\Input::get('cat_id', 91);

        $dom = new \App\library\DomParser();
        @$exp_html = $dom->file_get_html('http://www.smart-words.org/quotes-sayings/idioms-meaning.html');
        if ($exp_html) {
            $idioms_dt = $exp_html->find('#content dl dt');
            $i = 0;
            foreach ($idioms_dt as $dt) {
                $text = trim($dt->plaintext);
                $mean = $exp_html->find('#content dl dd', $i)->plaintext;
                $idiom = Idiom::where('word', 'like', "%$text%")->first();
                if (!$idiom) {
                    $idiom = new Idiom();
                    $idiom->word = $text;
                    $idiom->mean = $mean;
                    $idiom->save();
                }

                $idiom->cats()->syncWithoutDetaching([$cat_id]);
                echo $text . ' -> ' . $mean . '<br>';
                $i++;
            }
        }
        return;
    }

    public function getTop200PhrasalVerbs() {
        $cat_id = 93;
        $dom = new \App\library\DomParser();
        @$exp_html = $dom->file_get_html('https://www.englishclub.com/vocabulary/phrasal-verbs-list.htm');

        if ($exp_html) {
            $idioms_tr = $exp_html->find('#ec-main .ec-table tbody tr');

            foreach ($idioms_tr as $tr) {
                if (!$tr->find('td', 0)) {
                    continue;
                }
                $text = trim($tr->find('td', 0)->plaintext);
                if (strpos("sby", $text) || strpos('sthg', $text)) {
                    $text .= '/' . preg_replace("(sby.\s*|sth.\s*)", "", $text);
                }
                $text = str_replace("sby", "somebody", $text);
                $text = str_replace("sthg", "something", $text);

                $mean = trim($tr->find('td', 1)->plaintext);
                $example_text = trim($tr->find('td', 2)->plaintext);


                $idiom = Idiom::where('word', 'like', "%$text%")->first();
                if (!$idiom) {
                    $idiom = new Idiom();
                    $idiom->word = $text;
                    $idiom->mean = $mean;
                    //save example    
                    if ($example_text) {
                        $examples = [];
                        $example = IdiomExample::where('example', '=', $example_text)->first();
                        if (!$example) {
                            $example = new IdiomExample();
                            $example->example = $example_text;
                            $example->save();
                        }
                        $examples[] = $example->id;
                        $idiom->example = json_encode($examples);
                    }

                    $idiom->save();
                }

                $idiom->cats()->syncWithoutDetaching([$cat_id]);
                echo $text . ' -> ' . $mean . '<br>';
            }
        }
    }

    /*     * **
     * englishclub.com
     */

    public function getPhrasalVerbs() {

        $dom = new \App\library\DomParser();
        $html = $dom->file_get_html("https://www.englishclub.com/ref/Phrasal_Verbs/");
        $cats = $html->find("#wide_cats dl dt a");
        foreach ($cats as $cat) {
            if ($cat->plaintext != "Quizzes") {
                echo $cat->plaintext . ' ' . $cat->href;
                $this->_getAllPhrasalVerbs(trim($cat->href));
            }
        }
    }

    private function _getAllPhrasalVerbs($link) {
        $dom = new \App\library\DomParser();
        $html = $dom->file_get_html($link);
        $links = $html->find("#ec-main h3 a");
        foreach ($links as $catLink) {
            if (strpos($catLink->href, "http") !== false) {
                echo $catLink->href;
                $phrasal = trim($catLink->plaintext);
                $idiom = Idiom::where('word', 'like', "%$phrasal%")->first();
                if ($idiom) {
                    $idiom->cats()->syncWithoutDetaching([92]);

                    return;
                }
                echo '<br>';
                //get phrasal verb
                $this->_getPhrasalVerb(trim($catLink->href), 92); //uncategories
            }
        }
    }

    private function _getPhrasalVerb($link, $cat_id) {
        $dom = new \App\library\DomParser();
        $html = $dom->file_get_html($link);
        $title = trim($html->find("h1", 0)->plaintext);
        $mean = $html->find("#ec-main", 0);
        $mean->find("h1", 0)->outertext = "";
        $es = $mean->find("p, div, ul");
        $remove = false;
        foreach ($es as $e) {
            if ($e->class == "quickquiz") {
                $remove = true;
            }
            if ($remove || $e->class == "ECnoprint" || $e->class == "ec-panel-note") {
                $e->outertext = "";
            }
        }
        echo $title;
        echo "<br>";
        echo $mean->innertext;

        $idiom = Idiom::where('word', 'like', "%$title%")->first();
        if (!$idiom) {
            $idiom = new Idiom();
            $idiom->word = $title;
            $idiom->mean = $mean->innertext;
            ;
            $idiom->is_got = 0;
            $idiom->save();
        }

        $idiom->cats()->syncWithoutDetaching([$cat_id]);
    }

}
