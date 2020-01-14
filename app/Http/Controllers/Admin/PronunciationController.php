<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Pronunciation\Cat;
use App\Models\Pronunciation\Voc;
use App\library\DomParser;

class PronunciationController extends AdminBaseController {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $cats = Cat::orderBy("pcat", "ASC")->orderBy("IC", "ASC")->get();
        return view('admin/pronunciation/cats', compact("cats"));
    }

    public function createCat() {
        return $this->getCat(0);
    }

    public function getCat($cat_id) {
        $cat = $cat_id ? Cat::find($cat_id) : null;
        return view('admin/pronunciation/edit_cat', compact("cat"));
    }

    public function deleteCat($cat_id) {
        $cat = $cat_id ? Cat::find($cat_id) : null;
        if ($cat->in_drawable == 0) {
            $vocs = $cat->vocs;
            foreach ($vocs as $voc) {
                $voc->delete();
            }
            $cat->delete();
        } else {
            return redirect("admin/pronunciation")->with('error', "Can not delete this cat!");
        }
        return redirect("admin/pronunciation")->with('success', "Category deleted successfully!");
    }

    public function postCat() {
        $cat_id = Input::get("id");
        $title = Input::get("title");
        $title_vi = Input::get("title_vi");
        $main_title = Input::get("main_title");
        $en = Input::get("en");
        $thumbnail = Input::get("thumbnail");
        $video = Input::get("video");
        $IC = Input::get("IC");
        $pcat = Input::get("pcat");
        $published = Input::get("published", 0);
        $max = 0;
        if ($pcat) {
            $max_cat = Cat::where("pcat", $pcat)->orderBy("IC", "DESC")->first();
            $max = $max_cat ? $max_cat->IC : 0;
        }
        $max = $max + 1;

        $cat = $cat_id ? Cat::find($cat_id) : new Cat();
        $cat->title = $title;
        $cat->title_vi = $title_vi;
        $cat->en = $en;
        $cat->thumbnail = $thumbnail;
        $cat->video = $video;
        $cat->pcat = $pcat;
        $cat->main_title = $main_title;

        $cat->published = $published;

        if (!$cat_id || ($cat && $pcat != $cat->pcat)) {
            $cat->IC = $max;
        } else {
            if ($IC > $cat->IC) {
                Cat::where("pcat", $pcat)->where("IC", ">", $cat->IC)->where("IC", "<=", $IC)->decrement('IC');
            } elseif ($IC < $cat->IC) {
                Cat::where("pcat", $pcat)->where("IC", ">=", $IC)->where("IC", "<=", $cat->IC)->increment('IC');
            }
            $cat->IC = $IC > $max ? $max : $IC;
        }
        $cat->save();
//         return back()->withInput();
        return redirect()->route('pronunciation.edit_cat', ['cat_id' => $cat->id]);
    }

    public function vocs($cat_id) {
        \Session::set('pro_cat_id', $cat_id);
        $cat = Cat::find($cat_id);
        $vocs = $cat->vocs;
        return view('admin/pronunciation/vocs', compact("cat", 'vocs'));
    }

    public function createVoc() {
        $cat_id = \Session::get('pro_cat_id', 0);

        return $this->getVoc(0);
    }

    public function getVoc($voc_id) {
        $cat_id = \Session::get('pro_cat_id', 0);
        $voc = $voc_id ? Voc::find($voc_id) : null;
        $categories = Cat::all();
        return view('admin/pronunciation/edit_voc', compact("voc", "categories", 'cat_id'));
    }

    public function postVoc(Request $request) {
        $this->validate($request, [
            'english' => 'required|max:255',
            'cat_id' => 'required',
        ]);
        $voc_id = Input::get("id");
        $cat_id = Input::get("cat_id");
        $english = Input::get("english");

        $pinyin = Input::get("pinyin");
        $vi = Input::get("vi");
        $mp3_link = Input::get("mp3_link");
        $mp3_link_uk = Input::get("mp3_link_uk");
        $pinyin_uk = Input::get("pinyin_uk");

        $voc = $voc_id ? Voc::find($voc_id) : new Voc();
        $voc->cat_id = $cat_id;
        $voc->english = $english;
        $voc->pinyin = $pinyin;
        $voc->vi = $vi;
        $voc->mp3_link = $mp3_link;
        $voc->type = trim(Input::get("type"));
        $voc->pinyin_uk = $pinyin_uk;
        $voc->mp3_link_uk = $mp3_link_uk;
        $voc->save();
        return redirect()->route('pronunciation.edit_voc', ['voc_id' => $voc->id]);
    }

    public function deleteVoc($voc_id) {
        $voc = $voc_id ? Voc::find($voc_id) : null;
        $cat_id = $voc->cat_id;
        if ($voc->in_drawable == 0) {
            $voc->delete();
        } else {
            return redirect()->route('pronunciation.vocs', ['cat_id' => $cat_id])->with('error', "Can not delete this voc!");
        }
        return redirect()->route('pronunciation.vocs', ['cat_id' => $cat_id])->with('success', "Vocabulary deleted successfully!");
    }

    public function getOxfords() {
        $vocs = Voc::whereNull("pinyin_uk")->orderBy('updated_at', 'ASC')->take(100)->get();
        $i = 1;
        foreach ($vocs as $voc) {

            echo $i++ . " <a href='" . \Illuminate\Support\Facades\URL::to('/') . "/admin/pronunciation/edit_voc/" . $voc->id . "' target='_blank' >" . $voc->id . " " . $voc->english . "</a><br>";

            $this->getOxford1($voc->id);
        }
    }
    public function getOxfordLink() {
        $voc_id = Input::get("voc_id");
        $link = Input::get("link");
        
        $this->getOxford1($voc_id,$link);
        return redirect()->route('pronunciation.edit_voc', ['voc_id' => $voc_id]);

    }
    public function getOxford1($voc_id,$default_link = "") {
        $voc = $voc_id ? Voc::find($voc_id) : null;
        if ($voc == null) {
            return;
        }

        $word = str_replace(" ", "+", strtolower(strip_tags($voc->english)));
        
        $link = "http://www.oxfordlearnersdictionaries.com/definition/english/";
        $getLink = $default_link ? $default_link : $link . $word . '_1';
//        echo $link . $word . '_1';
//        exit;
        $dom = new DomParser();
        $html = @$dom->file_get_html($getLink);
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

        if (!$pron_html) {
            echo $link . $word . '_1';
            exit;
            return;
        }

        $pron = trim(preg_replace("/BrE|\//", "", $pron_html->plaintext));
        $pron_uk = '/' . $pron . '/';
        if (!$content->find(".pron-gs .sound", 0))
            return;
        $mp3_link_uk = $content->find(".pron-gs .sound", 0)->getAttribute("data-src-mp3");

//        $mp3_file = \App\library\OcoderHelper::getFileName($mp3_link);
//        $audio =  $mp3_file;
        //get pro us
        $pron_us_html = $content->find(".pron-g", 1);
        if (!$pron_us_html) {
            echo $link . $word . '_1';
            exit;
            return;
        }
        if ($pron_us_html->find(".phon", 0)) {
            $pron_us_text = trim(preg_replace("/NAmE|\//", "", $pron_us_html->find(".phon", 0)->plaintext));
            $pron_us = $pron_us_text;
        }

        if (!$pron_us_html->find(".sound", 0)) {
            echo $link . $word . '_1';
            exit;
            return;
        }
        $pron_us = @$pron_us ? '/' . $pron_us . '/' : "";
        $mp3_us_link = $pron_us_html->find(".sound", 0)->getAttribute("data-src-mp3");


        //get audio
//        if (!Storage::disk('picvoc_audios')->has($audio)) {
//			echo "<b>Audio:</b>".$audio."<br>";;
//            $status  = Storage::disk('picvoc_audios')->put($audio, file_get_contents($mp3));
//        }
//        $mean = @$content->find('.sn-gs .gram-g', 0)->plaintext . @$content->find('.sn-gs .def', 0)->plaintext;
//		echo "<b>Mean:</b>".$mean."<br>";
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
        echo $pron_us;
        if (!$pron_us)
            exit;
        /** save voc * */
//        $example_str = implode("<br>", $examples);
        $voc->type = trim($type);
        $voc->pinyin = trim($pron_us);
        $voc->mp3_link = trim($mp3_us_link);
        $voc->pinyin_uk = trim($pron_uk);
        $voc->mp3_link_uk = trim($mp3_link_uk);
        $voc->in_drawable = 0;
        $voc->save();
    }

    public function getOxford($voc_id) {
        $this->getOxford1($voc_id);
        return redirect()->route('pronunciation.edit_voc', ['voc_id' => $voc_id]);
    }

}
