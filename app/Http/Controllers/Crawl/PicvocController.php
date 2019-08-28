<?php

namespace App\Http\Controllers\Crawl;

use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\User;
use App\library\DomParser;
use App\Models\Picvoc\Voc;
use App\Models\Picvoc\PicvocCat;
use File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;

class PicvocController extends Controller {

    var $url = 'http://idioms.thefreedictionary.com/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function getCrawlQuizlet() {
        $categories = PicvocCat::where("parent_id", ">", 1)->get();
        $categories_level = PicvocCat::where(['parent_id' => 1])->get();
        return view('admin.picvoc.crawl', compact('articles', 'categories', 'categories_level'));
    }

    public function crawlQuizlet() {
        $html = Input::get("quizlet_html");
        $cat_id = Input::get("cat_id");
         $item_selector =  Input::get("item_selector",".SetPageTerms-term");
        $word_selector =  Input::get("word_selector",".SetPageTerm-wordText");
        $mean_selector =  Input::get("mean_selector",".SetPageTerm-definitionText");
        if (!$html || !$cat_id) {
            echo "no link";
            exit;
        }
//        echo $mean_selector;
//        exit;

        $dom = new DomParser();
        $html_dom = $dom->str_get_html($html);
        $words_html = $html_dom->find($item_selector);
        foreach ($words_html as $word_html) {
            $word = trim($word_html->find($word_selector, 0)->plaintext);
            if ($word != strtoupper($word)) {
                $word = strtolower($word);
            }

            $mean = trim($word_html->find($mean_selector, 0)->plaintext);
          
//            echo "word: " . $word;
//            echo " def: " . $mean;
//            echo "<br>";
//            exit;
            $voc = Voc::where("en_us", $word)->first();
            if (!$voc) {
                $voc = new Voc();
                $voc->en_us = $word;
                $voc->en_us_mean = $mean;
                $voc->status = 0;
                $voc->save();
            }
            if(!$voc->en_us_pr && $voc->en_us_mean){
                $this->updateWord($voc, FALSE);
            }
            $voc_id = $voc->id;
            if ($voc_id && $cat_id) {
                $cat = PicvocCat::find($cat_id);
                $result = $cat->vocs()->syncWithoutDetaching([$voc_id]);
            }
//            exit;
        }
        echo $html;
    }

    public function deleteVoc($id) {
        $voc = Voc::find($id);
        $voc->cats()->sync([]);
        $voc->delete();
        if ($id) {
            echo "Delete completed!";
        }
        exit;
    }

    public function getOxfordMeanOfWord($id) {
        $voc = Voc::find($id);
//        $vocs = Voc::where("status",0)->orderBy("updated","DESC")->orderBy("id","DESC")->paginate(50);
        if ($voc) {
            echo $voc->en_us . '<br>';
            $result = $this->updateWord($voc);
            if (!$result) {
                if (!$voc->status || $voc->status == 0 && $voc->liked == 0) {
                    $voc->cats()->sync([]);
                    $voc->delete();
                } else {
                    $voc->status = 2;
                    $voc->save();
                }
            }
        }
        exit;
    }

    public function getOxfordMean1() {
        $vocs = Voc::where("en_us_type", "")->where("status", 0)->orderBy("id", "DESC")->paginate(150);
//        $vocs = Voc::where("status",0)->orderBy("updated","DESC")->orderBy("id","DESC")->paginate(50);

        foreach ($vocs as $voc) {
            echo $voc->en_us . '<br>';
            $result = $this->updateWord($voc);
            if (!$result) {
                if (!$voc->status || $voc->status == 0 && $voc->liked == 0) {
                    $voc->cats()->sync([]);
                    $voc->delete();
                } else {
                    $voc->status = 2;
                    $voc->save();
                }
            }
        }
        exit;
    }

    public function getOxfordMean() {
        $vocs = Voc::where("en_us_mean", "")->where("en_us_ex", "")->where("status", 0)->orderBy("id", "DESC")->paginate(150);
//        $vocs = Voc::where("status",0)->orderBy("updated","DESC")->orderBy("id","DESC")->paginate(50);
        foreach ($vocs as $voc) {
            echo $voc->en_us . '<br>';
            $result = $this->updateWord($voc);
            if (!$result) {
                if (!$voc->status || $voc->status == 0 && $voc->liked == 0) {
                    $voc->cats()->sync([]);
                    $voc->delete();
                } else {
                    $voc->status = 2;
                    $voc->save();
                }
            }
        }
        exit;
    }

    private function updateWord($voc, $save_mean = true) {
        $word = str_replace(" ", "+", $voc->en_us);
        $link = "http://www.oxfordlearnersdictionaries.com/definition/english/";
        $dom = new DomParser();
        $html = @$dom->file_get_html($link . $word . '_1');
        if (!$html) {
            $html = @$dom->file_get_html($link . $word);
        }
        if (!$html)
            return;
        $content = $html->find(".h-g", 0);
        if (!$content)
            return;
        $type_html = $content->find(".webtop-g .pos", 0);
        if (!$type_html)
            return;
        $type = $type_html->plaintext;
        $pron_html = $content->find(".pron-gs .phon", 0);
        if (!$pron_html)
            return;
        $pron = trim(preg_replace("/BrE|\//", "", $pron_html->plaintext));
        $pron = '/' . $pron . '/';
        if (!$content->find(".pron-gs .sound", 0))
            return;
        $mp3 = $content->find(".pron-gs .sound", 0)->getAttribute("data-src-mp3");

        $mp3_file = \App\library\OcoderHelper::getFileName($mp3);
        $audio = $mp3_file;
        $status = true;
        //get audio
        if (!Storage::disk('picvoc_audios')->has($audio)) {
            echo "<b>Audio:</b>" . $audio . "<br>";

            $status = Storage::disk('picvoc_audios')->put($audio, file_get_contents($mp3));
        }

        $mean = @$content->find('.sn-gs .gram-g', 0)->plaintext . @$content->find('.sn-gs .def', 0)->plaintext;
        echo "<b>Mean:</b>" . $mean . "<br>";
        $example_content = $content->find('.sn-gs .x-gs .x-g');
        $examples = [];
        $i = 0;
        if ($example_content) {
            foreach ($example_content as $ex_html) {
                $ex = trim($ex_html->plaintext);
                if (strlen($ex) > 30) {
                    $examples[] = $ex_html->plaintext;
                    if ($i++ > 2) {
                        break;
                    }
                }
            }
        }

        /** save voc * */
        $example_str = implode("<br>", $examples);
        $voc->en_us_type = $type;
        $voc->en_us_pr = $pron;
        if ($save_mean) {
            $voc->en_us_mean = $mean;
        }
        $voc->en_us_audio = $mp3_file;
        $voc->en_us_ex = $example_str;
        $voc->status = 0;
        $voc->save();
        echo "<b>Examples:</b>" . $example_str . "<br>";

        return true;
    }

    private function delete($cat) {
        $vocs = $cat->vocs()->get();

        foreach ($vocs as $voc) {
            $voc->delete();
        }
        $cat->vocs()->detach();
    }

    public function getCommonWords() {

        $cat = PicvocCat::where("title", "1000 common words")->where('parent_id', 13)->first();
        if (!$cat) {
            $cat = new PicvocCat();
            $cat->title = "1000 common words";
            $cat->parent_id = 13;
            $cat->status = 1;
            $cat->save();
        }
// $this->delete($cat);
// exit;
        $link = "http://www.ef.com/english-resources/english-vocabulary/top-3000-words/";
        $dom = new DomParser();
        $html = $dom->file_get_html($link);
        $content = $html->find(".content .even p", 1);
        $words_string = $content->innertext;
        $words = explode("<br />", $words_string);
        $i = 1;
        foreach ($words as $word) {
            $word = trim($word);
            $voc = Voc::where('en_us', $word)->first();
            if (!$voc) {
                $voc = $this->getOxford($word, $cat->id);
            } else {
//                 $voc->cats()->syncWithoutDetaching([$cat->id]);
            }
        }
        exit;
    }

    public function getOxford($word, $cat_id) {
        echo $word . '<br>';
        $link = "http://www.oxfordlearnersdictionaries.com/definition/english/";
        $dom = new DomParser();
        $html = @$dom->file_get_html($link . $word . '_1');
        if (!$html) {
            $html = @$dom->file_get_html($link . $word);
        }
        if (!$html)
            return;
        $content = $html->find(".h-g", 0);
        if (!$content)
            return;
        $type_html = $content->find(".webtop-g .pos", 0);
        if (!$type_html)
            return;
        $type = $type_html->plaintext;
        $pron_html = $content->find(".pron-gs .phon", 0);
        if (!$pron_html)
            return;
        $pron = trim(preg_replace("/BrE|\//", "", $pron_html->plaintext));
        $pron = '/' . $pron . '/';
        if (!$content->find(".pron-gs .sound", 0))
            return;
        $mp3 = $content->find(".pron-gs .sound", 0)->getAttribute("data-src-mp3");
        $mp3_file = \App\library\OcoderHelper::getFileName($mp3);
        $audio = '' . $mp3_file;
        $status = true;
        //get audio
//        if (!Storage::disk('audios')->has($audio)) {
//            $status  = Storage::disk('audios')->put($audio, file_get_contents($mp3));
//        }

        $mean = @$content->find('.sn-gs .gram-g', 0)->plaintext . @$content->find('.sn-gs .def', 0)->plaintext;
        $example_content = $content->find('.sn-gs .x-gs .x-g');
        $examples = [];
        $i = 0;
        if ($example_content) {
            foreach ($example_content as $ex_html) {
                $ex = trim($ex_html->plaintext);
                if (strlen($ex) > 30) {
                    $examples[] = $ex_html->plaintext;
                    if ($i++ > 2) {
                        break;
                    }
                }
            }
        }

        /** save voc * */
        $example_str = implode("<br>", $examples);
        $voc = new Voc();
        $voc->en_us = $word;
        $voc->en_us_type = $type;
        $voc->en_us_pr = $pron;
        $voc->en_us_mean = $mean;
        $voc->en_us_audio = $mp3_file;
        $voc->en_us_ex = $example_str;
        $voc->status = 0;
        $voc->save();
        $voc->cats()->syncWithoutDetaching([$cat_id]);

        return $voc;
    }

}
