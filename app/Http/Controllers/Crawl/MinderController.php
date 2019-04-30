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
use App\Models\Course\Course;
use App\Models\Course\Subject;
use App\Models\Course\Word;
use App\Models\Course\Example;

class MinderController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
//        $this->middleware('auth');
    }

    public function courses() {
        $json_courese = file_get_contents("http://minder.vn/api/courses/courses?srcLang=104");
        $json = json_decode($json_courese);

        foreach ($json->Courses as $onlCourse) {

            $course = Course::find($onlCourse->id);
            if (!$course) {
                $course = new Course();
                $course->id = $onlCourse->id;
                $course->name = $onlCourse->name;
                $course->description = $onlCourse->desc;
                $course->srcLang = "jp";
                $course->desLang = "vi";
                $course->subject = $onlCourse->subject;
                $course->word = $onlCourse->word;
                if ($this->checkRemoteFile("http://data.minder.vn/courses/" . $course->id . ".jpg")) {
                    $course->img = "http://data.minder.vn/courses/" . $course->id . ".jpg";
                } else {
                    $course->img = "http://minder.vn/img/default/" . $onlCourse->srcLang . "_default.jpg";
                }
                $course->save();
            }
            echo $course->name . "<br>";
            if ($course->status == 0) {
                $this->subjects($course->id);
                $course->status = 1;
                $course->save();
            }
        }
    }

    public function subjects($course_id = 104000008) {
        $txt_subects = file_get_contents("http://minder.vn/api/subjects/subjects?id_course=" . $course_id);
        $json_subjects = json_decode($txt_subects);
        $i = 0;

        foreach ($json_subjects->Subjects as $onlSubject) {
            $subject = Subject::find($onlSubject->id);
            if (!$subject) {
                $subject = new Subject();
                $subject->id = $onlSubject->id;
                $subject->name = $onlSubject->name;
                $subject->mean = $onlSubject->mean ? $onlSubject->mean : "";
                $subject->course_id = $onlSubject->id_course;
                $subject->status = 1;
                $subject->word = $onlSubject->total;
                $jp_img = "http://data.minder.vn/Japanese/" . $course_id . "/images/lessons/" . $subject->id . ".jpg";

                if ($this->checkRemoteFile($jp_img)) {
                    $subject->img = $jp_img;
                } else {
                    $subject->img = "http://data.minder.vn/courses/" . $course_id . ".jpg";
                }
                $subject->save();
                $this->words($subject->id);
                $i++;
                // sleep for 10 seconds
                sleep(15);
                if ($i == 10) {
                    echo $i;
                    exit;
                }
            }
        }
        return;
    }

    public function words($subject_id = 104000029) {
        $txt_words = file_get_contents("http://minder.vn/api/words/words?id_subject=" . $subject_id);
        $json_words = json_decode($txt_words);
        $course_id = $json_words->Course->id;

        foreach ($json_words->Words as $onlWord) {
            $word = Word::find($onlWord->id);
            if (!$word) {
                $word = new Word();
                $word->id = $onlWord->id;
                $word->word = $onlWord->word;
                $word->mean = $onlWord->mean;
                $word->phonetic = $onlWord->phonetic;
                $word->description = $onlWord->des ? $onlWord->des : "";
                $word->example = $onlWord->example;
                $word->example_mean = $onlWord->example_mean;
                $word->subject_id = $subject_id;
                $word->status = 1;
                $jp_img = "http://data.minder.vn/Japanese/" . $course_id . "/images/words/" . $word->id . ".jpg";
                if ($this->checkRemoteFile($jp_img)) {
                    $word->img = $jp_img;
                } else {
                    $word->img = "http://data.minder.vn/courses/" . $course_id . ".jpg";
                }

                $mp3 = "http://data.minder.vn/Japanese/" . $course_id . "/audios/" . $word->id . ".mp3";

                if ($this->checkRemoteFile($mp3)) {
                    $word->audio = $mp3;
                }

                $word->save();
                $this->examples($word->id);
            }
        }
        return;
    }

    public function examples($word_id = 104000693) {
        $txt_examples = file_get_contents("http://minder.vn/api/examples/examples?id_word=" . $word_id);
        $json_examples = json_decode($txt_examples);


        foreach ($json_examples->examples as $onlExample) {
            $example = Example::find($onlExample->id);
            if (!$example) {
                $example = new Example();
                $example->id = $onlExample->id;
                $example->mean = $onlExample->example_mean;
                $example->example = $onlExample->example;
                $example->status = $onlExample->status;
                $example->save();
            }

            $example->words()->syncWithoutDetaching([$word_id]);
        }
        return;
    }

    function checkRemoteFile($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        curl_close($ch);
        if ($result !== FALSE) {
            return true;
        } else {
            return false;
        }
    }

}
