<?php

namespace App\Http\Controllers\Crawl;

use App\Http\Controllers\Controller;
use File;
use Illuminate\Support\Facades\Session;
use App\library\DomParser;
use Illuminate\Support\Facades\Storage;
use App\library\OcoderHelper;

class ListeningController extends Controller {

    var $answers = ["a" => 0, "b" => 1, "c" => 2, "d" => 3, "e" => 4, "f" => 5];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
//        $this->middleware('auth');
    }

    var $count = 0;

    public function index() {
//http://www.talkenglish.com/listening/listenbasic.aspx
//        http://www.talkenglish.com/listening/listenintermediate.aspx
        $parser = new DomParser();
        $html = $parser->file_get_html("http://www.talkenglish.com/listening/listenadvanced.aspx");
        $links = $html->find(".steps-learn div a");
        foreach ($links as $link) {
//            echo "http://www.talkenglish.com/listening/".$link->href.'<br>';
            $this->_getDialog("http://www.talkenglish.com/listening/" . $link->href);
        }
    }

    public function _getDialog($link) {
        $parser = new DomParser();
        $html = $parser->file_get_html($link);
        $title_html = $html->find("#GridView1 h1", 0);
        $mp3 = $html->find("#GridView1 b a", 0);
        $dialog_html = $html->find("#GridView1 #div3", 0);
        $questions_html = $html->find("#GridView1 #div1", 0);
        if ($title_html) {
            $title = $title_html->plaintext;
            $check = \App\Models\ListeningDialog::where('title', trim($title))->first();
            if ($check) {
                if ($this->count++ <= 15) {
                    $check->cats()->sync([4]);
                } else {
                    echo $this->count.'<br>';
                    $check->cats()->sync([5]);
                }
                return;
            }
            $lesson = new \App\Models\ListeningDialog();

            $lesson->title = trim($title);
            $lesson->link = $link;
            $mp3_link = $mp3->href;
            $mp3_file = OcoderHelper::getFileName($mp3_link);
            if (!Storage::disk('listening_audios')->has($mp3_file)) {
                @Storage::disk('listening_audios')->put($mp3_file, file_get_contents($mp3_link));
            }
            $lesson->audio = $mp3_file;
        } else {
            return;
        }
        if ($dialog_html) {
            $dialog = $dialog_html->innertext;
            $lesson->dialog = trim($dialog);
        }
        if ($questions_html) {
            $qs = $questions_html->innertext;
            $qss = explode('<br />', $qs);
            $questions = [];
            $question_ids = [];
            $question = null;
            $answer = null;
            $correct = $questions_html->find("input[name='CorrectAnswers']", 0)->value;
            $i = 0;
            foreach ($qss as $br) {
                if (!trim(strip_tags($br))) {
                    continue;
                }
                if (strpos($br, '<input') === false) {
                    if ($question && $answer) {
                        $question->answers = json_encode($answer);
                        $crk = $this->answers[$correct[$i]];
                        $question->correct = $answer[$crk];
                        $questions[] = $question;

                        $question->save();
                        $question_ids[] = $question->id;
                        $i++;
                    }
                    $question = new \App\Models\ListeningQuestion();
                    $question->question = trim($br);
                    $answer = [];
                } else {
                    $answer[] = trim(strip_tags($br));
                }
            }
            $lesson->question = json_encode($question_ids);
            $lesson->status = 1;
            $lesson->save();
            if ($this->count++ <= 15) {
                $lesson->cats()->syncWithoutDetaching([4]);
            } else {
                $lesson->cats()->syncWithoutDetaching([5]);
            }
            echo '<pre>';
            var_dump($questions);
            echo '</pre>';
        }
    }

}
