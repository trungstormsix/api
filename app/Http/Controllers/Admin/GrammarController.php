<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\User;
use App\Models\GrammarUserAnswer;
use App\Models\GrammarUserQuestion;
use App\Models\GrammarCat;
use App\Models\GrammarLesson;
use App\Models\GrammarQuestion;
use App\Models\Idiom;
use App\Models\IdiomCat;
use App\Models\IdiomExample;
use File;
use Illuminate\Support\Facades\Session;

class GrammarController extends AdminBaseController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct() {
//       
//    }



    public function index() {
        $cats = GrammarCat::all();
        return view('admin/grammar/cats', ['cats' => $cats]);
    }

    public function lessons($cat_id) {
        \Session::set('grm_cat_id', $cat_id);

        $cat = GrammarCat::find($cat_id);
        return view('admin/grammar/lessons', ['cat' => $cat, 'lessons' => $cat->lessons]);
    }
    public function createLesson() {
        return $this->getLesson(0);
    }

    public function getLesson($id = 0) {
        $lesson = null;
        $cat_ids = [];
        if ($id) {
            $lesson = GrammarLesson::find($id);
            if ($lesson->cat) {
                foreach ($lesson->cat as $cat) {
                    $cat_ids[] = $cat->id;
                }
            }
        } else {
            $cat_ids[] = \Session::get('grm_cat_id');
        }
        $categories = GrammarCat::all();
        
        return view('admin/grammar/edit_lesson', compact('lesson',"categories", "cat_ids"));
    }
    
    public function postLesson(Request $req) {
        $this->validate($req, [
            'title' => 'required|max:255'
             
        ]);
        $req->published = $req->published ? 1 : 0;
        if ($req->id) {
            $lesson = GrammarLesson::find($req->id);
        } else {
            $lesson = GrammarLesson::where('title', $req->title)->first();
            if (!$lesson) {
                $lesson = new GrammarLesson();
                $lesson->title = $req->title;
                $lesson->save();
            }
        }
        $lesson->update($req->all());
         
//        $lesson->save();
        if ($lesson->id)
            return redirect()->route('grammar.edit_lesson', ['lesson_id' => $lesson->id]);


        Input::flash();
        return redirect()->route('grammar.create_lesson');
    }
    
    /**
     * search idioms
     * @return type
     */
    public function search() {
        $search = \Illuminate\Support\Facades\Input::get('idiom');

        $idioms = Idiom::where('word', 'like', '%' . $search . '%')->paginate(30);

        return view('admin/idioms/idioms', ['idioms' => $idioms, 'search' => $search]);
    }

    public function createCat() {
        return $this->getCat(0);
    }

    public function getCat($id = 0) {
        $cat = null;
        if ($id) {
            $cat = GrammarCat::find($id);
        } else {
            
        }
        return view('admin/grammar/edit_cat', ['cat' => $cat]);
    }

    public function deleteCat($id = 0) {
        $cat = null;
        if ($id) {
            $cat = GrammarCat::find($id);
        } else {
            
        }
        return redirect()->route('grammar.index')->with('success', "Category deleted successfully!");
    }

    /**
     * save cat
     */
    public function postCat(Request $req) {
        $this->validate($req, [
            'title' => 'required|max:255'
        ]);
        if ($req->id) {
            $cat = GrammarCat::find($req->id);
        } else {
            $cat = GrammarCat::where('title', $req->title)->first();
            if (!$cat) {
                $cat = new GrammarCat();
            }
        }

        $cat->title = $req->title;
        $cat->title_display = $req->title_display;
        $cat->description = $req->description;
        $cat->published = $req->published;
        $cat->save();
        if ($cat->id)
            return redirect()->route('grammar.edit_cat', ['cat_id' => $cat->id]);


        Input::flash();
        return redirect()->route('grammar.create_cat');
    }

    public function listCatQuestions($cat_id) {
        $cat = GrammarCat::find($cat_id);
        if (!$cat) {
            return back()->with("error", "Category does not exist!");
        }
        $questions = $cat->questions;
        
        return view('admin/grammar/listQuestions', compact("questions"));
    }
    
    public function listLessonQuestions($lesson_id) {
        $lesson = GrammarLesson::find($lesson_id);
        if (!$lesson) {
            return back()->with("error", "Lesson does not exist!");
        }
        $questions = $lesson->questions;

        return view('admin/grammar/listQuestions', compact("questions"));
    }

    public function deleteLessonQuestion(){
        $lesson_id = Input::get('lesson_id', '0');
        $question_id = Input::get('question_id', '0');
        $lesson = GrammarLesson::find($lesson_id);
         
        $changed = $lesson->questions()->detach([$question_id]);        
        return response()->json(['url' => url()->route('grammar.edit_lesson', ['lesson_id' => $lesson->id]), 'main_id' => $question_id, 'changed' => $changed]);
    }
    
    public function deleteCatQuestion(){
        $cat_id = Input::get('cat_id', '0');
        $question_id = Input::get('question_id', '0');
        $cat = GrammarCat::find($cat_id);
         
        $changed = $cat->questions()->detach([$question_id]);        
        return response()->json(['url' => url()->route('grammar.lessons', ['cat_id' => $cat->id]), 'main_id' => $question_id, 'changed' => $changed]);
    }
    
     public function postAddLessonQuestion(){
        $lesson_id = Input::get('lesson_id', '0');
        $question_id = Input::get('question_id', '0');
        $lesson = GrammarLesson::find($lesson_id);
        $cats = $lesson->cat;
        foreach ($cats as $cat) {
            $cat->questions()->syncWithoutDetaching([$question_id]);
        }
        $changed = $lesson->questions()->syncWithoutDetaching([$question_id]);        
        return response()->json(['url' => url()->route('grammar.edit_lesson', ['lesson_id' => $lesson->id]), 'main_id' => $question_id, 'changed' => $changed]);
    }
    public function ajaxGetCats() {
        $cat_term = Input::get('term', '');

        $cats = GrammarCat::where('title', 'like', "%$cat_term%")->take(20)->get();

        $return = [];
        foreach ($cats as $cat) {
            $return[] = ['key' => $cat->id, 'value' => $cat->title];
        }
        return response()->json($return);
    }
    public function postAddCatQuestion(){
        $cat_id = Input::get('cat_id', '0');
        $question_id = Input::get('question_id', '0');
        $cat = GrammarCat::find($cat_id);
        $changed = $cat->questions()->syncWithoutDetaching([$question_id]);        
        return response()->json(['url' => url()->route('grammar.lessons', ['cat_id' => $cat->id]), 'main_id' => $question_id, 'changed' => $changed]);
    }
    
    public function ajaxPublishQuestion(){
        $question_id = Input::get('question_id', '0');
        
        if($question_id){
            $question = Question::find($question_id);
            if($question){
                $question->published = Input::get('published', '0');
                $question->save();
            }
        }
        return response()->json(['question_id' => $question_id, 'published' => $question->published]);

    }
    
    public function createQuestion() {
        return $this->getQuestion(0);
    }

    public function getQuestion($question_id) {
        $question = $question_id ? GrammarQuestion::find($question_id) : null;
        $cat_ids = [];
        if ($question) {
           
            if ($question->cats) {
                foreach ($question->cats as $cat) {
                    $cat_ids[] = $cat->id;
                }
            }
        }
         
        $categories = GrammarCat::all();
        return view('admin/grammar/edit_question', compact("question", "categories", "cat_ids"));
    }

    public function postQuestion(Request $request) {
        $this->validate($request, [
            'question' => 'required|max:1255'
        ]);
        $question = GrammarQuestion::find($request->id);
        if (!$question) {
            $question = new Question();
        }
        $question->question = $request->question;
        $question->type = $request->type;
        $question->correct = $request->correct;
        $question->published = $request->published;
        $question->level = $request->level;
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
        
        return redirect()->route('grammar.edit_question', ['question_id' => $question->id]);

    }
}
