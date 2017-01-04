<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\User;
use App\Models\Idiom;
use App\Models\TestQuestion;
use App\Models\TestCat;
use App\Models\TestTest;
use App\Models\GrammarQuestion;
use App\Models\GrammarCat;
use App\Models\GrammarLesson;
use File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class GrammarTestController extends Controller {

    function index() {
        $cats = GrammarCat::orderBy('id', 'asc')->paginate(50);
        return view('front/grammarCats', ['cats' => $cats]);
    }

    function tests($id) {
        $f = fopen("xml/list.xml", 'w');
        fwrite($f, '<rss></rss>');
        fclose($f);
        $cat_xml = simplexml_load_file("xml/list.xml");
//        $xml = "<rss>";
        $cats = GrammarCat::where('published', 1)
//                ->where("description","ES")
                ->get();
        foreach ($cats as $cat) {
            $cat->title = trim(str_replace("(Es)", "", $cat->title));
            $questions = $cat->questions()->where('published', 1)->orderBy("level", "ASC")->paginate(15);
            if ($questions && sizeof($questions) > 3) {
                $count = sizeof($cat_xml->item);
                $cat_xml->item[$count]->title = $cat->title;
                $cat_xml->item[$count]->folder = 'test_' . $cat->id;
                mkdir("xml/" . 'test_' . $cat->id . "/");
                $f = fopen("xml/" . 'test_' . $cat->id . "/list.xml", 'w');
                fwrite($f, '<rss></rss>');
                fclose($f);
                $cat_list = simplexml_load_file("xml/" . 'test_' . $cat->id . "/list.xml");
                $this->_saveTestXML($questions, $cat, 1, $cat_list);
                //get all questions
                for ($i = 2; $i <= $questions->lastPage(); $i++) {
                    $qs = $cat->questions()->where('published', 1)->orderBy("level", "ASC")->paginate(15, ['*'], 'page', $i);
                    $this->_saveTestXML($qs, $cat, $i, $cat_list);
                }
                $cat_list->saveXML("xml/" . 'test_' . $cat->id . "/list.xml");
            }
        }
        $cat_xml->saveXML("xml/list.xml");
        exit;
        return view('front/grammarTest', ["title" => $cat->title, 'questions' => $questions]);
    }

    private function _saveTestXML($questions, $cat, $test, &$cat_xml) {
        if (!$questions) {
            return;
        }
        $helper = new \App\library\OcoderHelper();
        $xml = "<rss>" . PHP_EOL;
        $q = 1;
        $time = 0;
        foreach ($questions as $question) {

            $xml .= "<item>\r\n";
            $xml .= "   <idgroup>$q</idgroup>\r\n   ";
            $xml .= "   <loai>Reading</loai>\r\n";
            $xml .= "   <chc>khong</chc>\r\n";
            $xml .= "   <c>$question->question</c>\r\n";
            $xml .= "   <hinhanh>khong</hinhanh>\r\n";
            $xml .= "   <amthanh>khong</amthanh>\r\n";
            $xml .= "   <c1>$question->correct</c1>\r\n";
            $answers = json_decode($question->answers);
            $i = 2;
            foreach ($answers as $answer) {
                if ($answer != $question->correct) {
                    $xml .= "       <c$i>$answer</c$i>\r\n";
                    $i++;
                }
            }
            if ($i <= 4) {
                for ($i; $i <= 4; $i++) {
                    $xml .= "       <c$i>NULL</c$i>\r\n";
                }
            }
            $xml .= "   <exp>" . $helper->encrypt($question->explanation) . "</exp>" . PHP_EOL;
            foreach ($question->article as $article) {
                $xml .= "   <article id='$article->id'>$article->title</article>" . PHP_EOL;
            }
            $xml .= '</item>' . PHP_EOL;
            $time += 9 * $question->level;
            $q++;
        }
        $xml .= '</rss>';
        @Storage::disk('xml')->put('test_' . $cat->id . '/' . $test . '/data.xml', $xml);
        $count = sizeof($cat_xml->item);

        $cat_xml->item[$count]->type = "content";
        $cat_xml->item[$count]->title = $cat->title . ' - Test ' . $test;
        $cat_xml->item[$count]->folder = $test;
        $cat_xml->item[$count]->time = round($time / 60);
    }

    function postTests($id, Request $request) {
        $cat = GrammarCat::find($id);
        $questions = $cat->questions()->where('published', 1)->orderBy("level", "ASC")->paginate(15);
        $answered = array();
        $totalCorrect = 0;
        foreach ($questions as $question) {
            $answered[] = $request->get($question->id);
            $question->answered = $request->get($question->id);
            if ($request->get($question->id) == $question->correct) {
                $totalCorrect++;
            }
        }

        return view('front/grammarTest', ["title" => $cat->title, 'questions' => $questions, 'answered' => $answered, 'totalCorrect' => $totalCorrect]);
    }

    function questions() {
        $questions = GrammarQuestion::orderBy('id', 'asc')->paginate(50);
        return view('front/grammarTest', ['cats' => $cats]);
    }

}
