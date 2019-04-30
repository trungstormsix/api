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
use App\Models\Pronunciation\Question;
use App\library\DomParser;

class QuestionController extends AdminBaseController {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $cats = Cat::orderBy("pcat", "ASC")->orderBy("IC", "ASC")->get();
        return view('admin/pronunciation/cats', compact("cats"));
    }

    public function listQuestions($cat_id) {
        $cat = Cat::find($cat_id);
        if (!$cat) {
            return back()->with("error", "Category does not exist!");
        }
        $questions = $cat->questions;
         
        return view('admin/pronunciation/listQuestions', compact("questions"));
    }

    public function createQuestion() {
        return $this->getQuestion(0);
    }

    public function getQuestion($question_id) {
        $question = $question_id ? Question::find($question_id) : null;
        if ($question) {
           
            if ($question->cats) {
                foreach ($question->cats as $cat) {
                    $cat_ids[] = $cat->id;
                }
            }
        }
         
        $categories = Cat::all();
        return view('admin/question/edit_question', compact("question", "categories", "cat_ids"));
    }

    public function postQuestion(Request $request) {
        $this->validate($request, [
            'question' => 'required|max:1255'
        ]);
        $question = Question::find($request->id);
        if (!$question) {
            $question = new Question();
        }
        $question->question = $request->question;
        $question->type = $request->type;
        $question->correct = $request->correct;
        $ans_raw = $request->answers;
        $ans = [];
        foreach ($ans_raw as $a) {
            if ($a) {
                $ans[] = trim($a);
            }
        }
        $ans = array_unique($ans);


        $question->answers = json_encode($ans);

        if (!in_array($question->correct, $ans)) {
            $validator = \Illuminate\Support\Facades\Validator::make([], []); // Empty data and rules fields
            $validator->errors()->add('correct', 'Correct Answer and Answers does not match!');
            $this->throwValidationException($request, $validator);
        }
                $question->explanation = $request->explanation;

        $question->save();
        $cats = $request->get("cat_ids") ? $request->get("cat_ids") : [];
        $question->cats()->sync($cats);
        return redirect()->route('GrmQuestion.edit_question', ['question_id' => $question->id]);

    }

}
