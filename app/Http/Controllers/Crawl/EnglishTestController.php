<?php

namespace App\Http\Controllers\Crawl;


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

class EnglishTestController extends Controller {

    var $source_url = 'http://www.english-test.net';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }
    public function getExample(){
        
    }

    public function index() {
        $cat = $this->getIdiomCat();
        foreach ($cat->links as $link){
            $testCat = $this->_getTestCat($cat->title);
            $this->_getTestLinks($link, $testCat->id);
        }
        exit;       
    }
    
    
    private function _getTestCat($title){
        $testCat = TestCat::where("title","=",$title)->first();
        if(!$testCat){
            $testCat = new TestCat();
            $testCat->title = $title;
            
        }
        $testCat->updated = date('Y-m-d H:i:s');
        $testCat->save();
        return $testCat;
    }
    private function getIdiomCat(){
        $cat = new \stdClass();
        $cat->title = "Idioms";
        $cat->links = ['http://www.english-test.net/esl/english-idioms.html',
            'http://www.english-test.net/esl/english-expressions.html',
            'http://www.english-test.net/esl/english-idiomatic-expressions.html'];
        return $cat;
    }
    private function getSynonymsCat(){
        $cat = new \stdClass();
        $cat->title = "Synonyms";
        $cat->links = ['http://www.english-test.net/esl/english-synonyms.html',
            'http://www.english-test.net/esl/synonym-finder.html',
            'http://www.english-test.net/esl/synonyms-lessons.html'];
        return $cat;
    }
    
    private function _getTestLinks($link, $catid){
        $domparser = new \App\library\DomParser();
        $html = $domparser->file_get_html($link);
        $links_html  = $html->find('td[bgcolor="white"] table[cellpadding="5"] tr td a');
        foreach ($links_html as $link_html){
            $tlink = $link_html->href;
            if(strpos('http', $tlink) === false){
                $tlink = $this->source_url.$tlink;
            }
            $testLink = $this->_getTestLink($tlink, $catid, $link_html);
            if($testLink->got != 1){
                $this->_getQuestions($testLink);
            }
             
        }
 
    }
    private function _getTestLink($link,$catid, $link_html){
        $testLink =  TestTest::where("link","=",$link)->first();
        if(!$testLink){
            $testLink = new TestTest();
            $testLink->link = $link;
            $testLink->cat_id = $catid;
            $testLink->title = trim(preg_replace('/^\d*\.|(&nbsp;)/','',$link_html->plaintext));
        }
        $testLink->updated = date('Y-m-d H:i:s');
        $testLink->save();

        return $testLink;
    }
    private function _getQuestions($testLink){
        $link = $testLink->link;
        $corrects = $this->_getCorrectAnswers($link);
        $parser = new \App\library\DomParser();
        $html = $parser->file_get_html($link);
        $questions_html = $html->find('form[name=testform] table[bgcolor="#ABD5F1"]');
        $questions = array();
        $i = 0;
        foreach ($questions_html as $question_html){
            $question = new \stdClass();
            $cidioms_html = $question_html->find('td .dv span[style="color:#000066"]',0);
            if($cidioms_html){
                $cidiom = trim($cidioms_html->innertext);
                $idiom = Idiom::where('word','=',$cidiom)->first();
                if($idiom){
                    echo $idiom->word.' : '.$idiom->mean.'<br>';
                }
            }
            $question->question = strip_tags($question_html->find('td .dv',0)->innertext,'<b><i><em>');
            //answers
            $answers_html = $question_html->find('td .y');
            $answers = array();
            foreach ($answers_html as $answer_html){
                $answer_html->find('span',0)->outertext = '';
                
                $answer_text = trim(trim(preg_replace('/^\(.*\)|(&nbsp;)/',' ', ($answer_html->plaintext))));
                $idiom = Idiom::where('word','=',$answer_text)->first();
                if($idiom){
                    echo $idiom->mean.'<br>';
                }
                $answers[] = $answer_text;                 
            }
            $question->answers = json_encode($answers);    
            $correct = $corrects[$i++];
            if(in_array($correct, $answers)){
                $question->correct = $correct;
            }
            $question->test_id = $testLink->id;
            $testQuestion = $this->_getTestQuestion($question);
            if($testQuestion){
                $questions[] = $question;
                $testLink->got = 1;
                $testLink->save();
            }
        }
        

    }
    private function _getTestQuestion($question){
        $testQuestion = TestQuestion::where("question","=",$question->question)->where("correct","=",$question->correct)->first();
        if(!$testQuestion){
            $testQuestion = new TestQuestion();
            $testQuestion->question = $question->question;
            $testQuestion->test_id = $question->test_id;
            $testQuestion->answers = $question->answers;
            $testQuestion->correct = $question->correct;            
           
        }
        $testQuestion->updated = date('Y-m-d H:i:s');
        $testQuestion->save();
        return $testQuestion;
    }
    /**
     * get answers of idioms
     * @param type $link
     */
    private function _getCorrectAnswers($link){
        $parser = new \App\library\DomParser();
        $query = http_build_query(array(
                    'startTime' => '1476029981',
                    'action'	=> 'check your score'
                ));
        $request = array(
            'http' => array(
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                 "Content-Length: ".strlen($query)."\r\n".
                "User-Agent:".$_SERVER['HTTP_USER_AGENT']."\r\n",
                'method' => 'POST',
                'content' => $query,
            )
        );

        $context = stream_context_create($request);

        $html = $parser->file_get_html($link,false,$context);
        $corects_html = $html->find('form[name=userform] table[bgcolor="#ABD5F1"]');
        $corrects = array();
        foreach ($corects_html as $corect_html){
            $correct = $corect_html->find('td .dv b span[style="color:#000066"]',0);
            if($correct){
                $corrects[] = trim($correct->plaintext);
            }
                
            
        }
        return $corrects;
    }

}
