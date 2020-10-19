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
use Illuminate\Support\Str;
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
        $cat = null;
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
        
        return view('admin/grammar/edit_lesson', compact('lesson',"categories", "cat_ids","cat"));
    }
    public function searchLessons() {
        $search = \Illuminate\Support\Facades\Input::get('search');
        $plural = Str::plural($search,2);
        $singular = Str::plural($search,1);
     
        $orderByClause  = "CASE WHEN title LIKE '".$singular."%' THEN 0 ELSE 1 END,";
        $orderByClause  .= "CASE WHEN title LIKE '".$plural."%' THEN 0 ELSE 1 END,";
        $orderByClause .= "CASE WHEN content LIKE '%".$singular."%' THEN 0 ELSE 1 END,";
        $orderByClause  .= "CASE WHEN content LIKE '%".$plural."%' THEN 0 ELSE 1 END";
//        $orderByClause .= "CASE WHEN question LIKE '%".$singular."%' THEN 0 ELSE 1 END,";
//        $orderByClause .= "CASE WHEN question LIKE '%".$plural."%' THEN 0 ELSE 1 END";
        
        $lessons = GrammarLesson::where('title', 'like', '%' . $singular . '%')
                ->orWhere('title', 'like', '%' . $plural . '%')
                ->orWhere('content', 'like', '%' . $singular . '%')
                ->orWhere('content', 'like', '%' . $plural . '%')
                ->orderByRaw($orderByClause)
                ->paginate(30);
        $lessons->appends(['search' => $search]);
//        return view('admin/grammar/listQuestions', compact("questions","search"));
        return view('admin/grammar/lessons', ['cat' => null, 'lessons' => $lessons, 'search' => $search]);

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
        $cat_ids = $req->cat_ids;
//         dd($req->cat_ids);
        if($cat_ids){
            $lesson->cat()->sync($cat_ids);
        }else{
            $lesson->cat()->sync([]);
        }
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
        $questions = $cat->questions()->paginate(30);
        $title = $cat->title . " - Questions";
        return view('admin/grammar/listQuestions', compact("questions","title"));
    }
    
    public function listLessonQuestions($lesson_id) {
        $lesson = GrammarLesson::find($lesson_id);
        if (!$lesson) {
            return back()->with("error", "Lesson does not exist!");
        }
        $questions = $lesson->questions()->paginate(30);

        return view('admin/grammar/listQuestions', compact("questions", "lesson_id"));
    }
    /**
     * search idioms
     * @return type
     */
    public function searchQuestions() {
        $search = \Illuminate\Support\Facades\Input::get('search');
        $plural = Str::plural($search,2);
        $singular = Str::plural($search,1);
     
        $orderByClause  = "CASE WHEN correct LIKE '".$singular."%' THEN 0 ELSE 1 END,";
        $orderByClause  .= "CASE WHEN correct LIKE '".$plural."%' THEN 0 ELSE 1 END,";
        $orderByClause .= "CASE WHEN answers LIKE '%".$singular."%' THEN 0 ELSE 1 END,";
        $orderByClause  .= "CASE WHEN answers LIKE '%".$plural."%' THEN 0 ELSE 1 END,";
        $orderByClause .= "CASE WHEN question LIKE '%".$singular."%' THEN 0 ELSE 1 END,";
        $orderByClause .= "CASE WHEN question LIKE '%".$plural."%' THEN 0 ELSE 1 END";
        
        $questions = GrammarQuestion::where('question', 'like', '%' . $singular . '%')
                ->orWhere('question', 'like', '%' . $plural . '%')
                ->orWhere('answers', 'like', '%' . $singular . '%')
                ->orWhere('answers', 'like', '%' . $plural . '%')
                ->orderByRaw($orderByClause)
                ->paginate(30);
        $questions->appends(['search' => $search]);
        return view('admin/grammar/listQuestions', compact("questions","search"));
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
        $lessons = $cat->lessons;
        foreach ($lessons as $lesson){
            $changed = $lesson->questions()->detach([$question_id]);  
        }
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
            $question = GrammarQuestion::find($question_id);
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
            $question = new GrammarQuestion();
        }
        $question->question = $request->question;
        $question->type = $request->type;
        $question->correct = $request->correct;
        $question->published = $request->published ? $request->published : 0;
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
    
    public function crawlQuize(){
        $quizId = Input::get('quiz_id', '0');
        $lessonId = Input::get('lesson_id', '206');
        if(!$quizId || !$lessonId){
            echo "error"; exit;
        }
        $lesson = GrammarLesson::find($lessonId);
        $cats = $lesson->cat;
                
        $file = file_get_contents("https://quizizz.com/api/main/quiz/$quizId?cached=true&_=1599796645908");
        $quiz_data = json_decode($file);
        $data =  $quiz_data->data->quiz->info->questions;
        $questions = [];
        foreach ($data as $data_question){
            $q_text = strip_tags($data_question->structure->query->text, '<br>');;
            $question = GrammarQuestion::where('question',$q_text)->first();
            if($question){
                echo $q_text ." is exist<br>";
                 $question_id = $question->id;
 
//                exit;
            }else{
                $question = new GrammarQuestion();
                $question->question = $q_text;
                $question->type = 1;
                $question->published = 0;
                $question->level = 1;
                $ans = [];
                foreach ($data_question->structure->options as $a) {
                    if ($a) {
                        $ans[] = trim(strip_tags($a->text));
                    }
                }
                $ans = array_unique($ans);
                $question->answers = json_encode($ans);
                if(is_array( $data_question->structure->answer)){
                    continue;
                }
                echo $data_question->structure->answer;
                $question->correct = $ans[$data_question->structure->answer];
                $question->save();
                  
//                return redirect()->route('grammar.list_lesson_question', ['cat_id' => $lessonId]);
//
//dd($question);
            }
            
             $question_id = $question->id;
            
                foreach ($cats as $cat) {
                    $cat->questions()->syncWithoutDetaching([$question_id]);
                }
                $changed = $lesson->questions()->syncWithoutDetaching([$question_id]);    
                $questions[] = $question;
        }
        return redirect()->route('grammar.list_lesson_question', ['cat_id' => $lessonId]);
//        return view('admin/grammar/listQuestions', compact("questions"));

            
        dd($data);
        echo $file;exit;
    }
    
    public function deleteQuestion($question_id){
        $question = GrammarQuestion::find($question_id);
        if(!$question->published){
            foreach($question->cat as $cat){
                $changed = $cat->questions()->detach([$question_id]);    
            }
            foreach($question->article as $lesson){
                $changed = $lesson->questions()->detach([$question_id]);   
            }
            $question->delete();
            return redirect()->route('grammar.create_question')->with('success', "Question $question->id deleted successfully, you can create new one here!");
//            return redirect()->route('grammar.edit_question', ['question_id' => $question->id])->with('error', "Cat not delete publised question, please unpublish first");

        }else{
            return redirect()->route('grammar.edit_question', ['question_id' => $question->id])->with('error', "Cat not delete publised question, please unpublish first");
        }
    }
}
