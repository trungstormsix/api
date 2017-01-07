<?php

namespace App\Http\Controllers\Crawl\Test;

use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\User;
use App\Models\Idiom;
use App\Models\TestQuestion;
use App\Models\TestCat;
use App\Models\TestTest;
use File;
use Illuminate\Support\Facades\Session;
use App\library\DomParser;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function getExample() {
        
    }

    public function index() {
        if (!Storage::disk('xml')->has("toeic_test.json")) {
            $this->_getList();
        }
        $json = json_decode(Storage::disk('xml')->get("toeic_test.json"));
        $count = 0;
        $base_folder = "part4";
        $this->_createList($base_folder);
        $list_xml = simplexml_load_file("xml/$base_folder/list.xml");
        $time = 0;

        $get = 0;
        foreach ($json as &$j) {
            if ($j->get == true) {
                $count ++;
                continue;
            }
            $this->group = ($count % 10) + 1;
            $folder = floor($count / 10) + 1;

            if (($count % 10) == 0 || $get == 0) {
                $count_item = sizeof($list_xml->item);
                $get = 1;
                if ($count_item > 0 && ($count % 10) == 0) {
                    $list_xml->item[$count_item - 1]->time = 6;
                    $list_xml->saveXML("xml/$base_folder/list.xml");
                }
                $list_xml->item[$count_item]->type = "content";
                $list_xml->item[$count_item]->title = "Part 1 - test $folder";
                $list_xml->item[$count_item]->folder = $folder;
            }
            $folder = $base_folder . '/' . $folder;
            $time += $this->_getTest($j, $folder);
            $count++;
            $j->get = true;
            //save test as got
            @Storage::disk('xml')->put("toeic_test.json", json_encode($json));
        }
        $list_xml->item[$count_item]->time = $time;
        $list_xml->saveXML("xml/$base_folder/list.xml");
    }

    private function _createList($folder) {
        if (!Storage::disk('xml')->has("$folder/list.xml")) {
            @mkdir("xml/$folder/");
            $f = fopen("xml/$folder/list.xml", 'w');
            fwrite($f, '<rss></rss>');
            fclose($f);
        }
    }

    var $group = 1;

    private function _getList() {
        $domparser = new DomParser();
        $html = $domparser->file_get_html("http://www.english-test.net/toeic/listening/Talks.html");
        $lessons = $html->find("div[style='line-height:22px;'] span a");
        $arr = [];
        $tests = [];
        foreach ($lessons as $lesson) {
            if (!in_array($lesson->href, $arr)) {
                $test = new \stdClass();
                $test->title = trim($lesson->plaintext);
                $test->link = $lesson->href;
                $test->get = false;
                $tests[] = $test;
                $arr[] = $lesson->href;
            }
        }

        @Storage::disk('xml')->put("toeic_test.json", json_encode($tests));
    }

    /**
     * 
     * get toeic test
     */
    private function _getTest($obj, $folder) {
        $link = "http://www.english-test.net" . $obj->link;
        if (!Storage::disk('xml')->has("$folder/data.xml")) {
            @mkdir("xml/$folder/");
            $f = fopen("xml/$folder/data.xml", 'w');
            fwrite($f, '<rss></rss>');
            fclose($f);
        }
        $cat_xml = simplexml_load_file("xml/$folder/data.xml");


        $domparser = new DomParser();
        $html = $domparser->file_get_html($link);
        $audio = $html->find("a[title='Download file MP3']", 0);

        $mp3 = \App\library\OcoderHelper::getFileName($audio->href);
        if (!Storage::disk('xml')->has("$folder/$mp3")) {
            @Storage::disk('xml')->put("$folder/$mp3", file_get_contents($audio->href));
        }


        $imgs = $html->find(".article", 0)->find("table, div, img");
        $i = 0;
        $anh = "";
        foreach ($imgs as $img) {
            if ($i > 3) {
                break;
            }
            if ($img->tag == "img") {
                $anh = $img->src;
            }
            $i++;
        }
        if ($anh) {
            $file_img = \App\library\OcoderHelper::getFileName($anh);
            if (!Storage::disk('xml')->has("$folder/$file_img")) {
                @Storage::disk('xml')->put("$folder/$file_img", file_get_contents("http://www.english-test.net" . $anh));
            }
        }
        $form = $html->find(".article form[name='toeicform']", 0);
        $corrects = $form->find("input[name='corrtoeic']", 0);
        $correct_arr = explode(",", $corrects->value);
        $questions = [];
        $questions_html = $form->find("div");
        $question = null;
        $i = 0;
        foreach ($questions_html as $div) {
            if (!$div->find("input")) {
                if ($question) {
                    $question->answers = $answers;
                    $question->correct = $answers[$correct_arr[$i]];
                    $questions[] = $question;
                    $i++;
                }
                $question = new \stdClass();
                $question->question = trim($div->plaintext);
                $answers = [];
            } else {
                $answers[] = trim($div->plaintext);
            }
        }
        if ($question) {
            $question->answers = $answers;
            $question->correct = $answers[$correct_arr[$i]];
            $questions[] = $question;
        }
        //get transcript
        $trans = $html->find("a[name='TOEIC_Listening_Comprehension']", 0)->parent();
        $tables = $trans->find("table,i, div");
        $transcript = "";
        $get = FALSE;
        foreach ($tables as $table) {
            if ($get == true) {
                if ($table->tag == "table") {
                    break;
                }
                $transcript .= "<$table->tag>$table->plaintext</$table->tag>";
            }
            if ($table->tag == "i" && $table->plaintext == "Listening Comprehension Transcript") {
                $get = true;
            }
        }



        $set = true;
        foreach ($questions as $q) {
            $count = sizeof($cat_xml->item);
            $cat_xml->item[$count]->idgroup = $this->group;
            $cat_xml->item[$count]->loai = "Listening";
            $cat_xml->item[$count]->chc = "khong";
            if ($set) {
                if ($anh) {
                    $cat_xml->item[$count]->hinhanh = "$file_img";
                } else {
                    $cat_xml->item[$count]->hinhanh = "khong";
                }
                $cat_xml->item[$count]->amthanh = "$mp3";
                if ($transcript) {
                    $cat_xml->item[$count]->transcript = $transcript;
                }
                $set = false;
            }
            $cat_xml->item[$count]->c = "$q->question";
            $cat_xml->item[$count]->c1 = $q->correct;
            $tmp = 2;
            foreach ($q->answers as $answer) {
                if ($answer != $q->correct) {
                    $ci = 'c' . $tmp;
                    $cat_xml->item[$count]->{$ci} = $answer;
                    $tmp++;
                }
            }
        }

        $cat_xml->saveXML("xml/$folder/data.xml");
        $this->group++;
    }

}
