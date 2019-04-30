<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Support\Facades\Session;
use App\Models\Test\Test;
use App\Models\Test\Group;
use App\Models\Test\Question;
use Illuminate\Support\Facades\Input;

class TestController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = 0) {
        $page = Input::get('page',1);
        $title = "Tests";
        if ($id) {
            $cat = Categories::find($id);
            $tests = $cat->tests()->paginate(10);
            $title = $cat->name;
        } else {
            $tests = Test::paginate(10);
        }
       
        return view('admin.test.home', compact('title','tests','cat','page'));
    }

    public function test($id = 0) {

        if ($id) {
            $test = Test::find($id);
            $groups = $test->groups()->get();
        } else {
            return redirect('admin/tests/7');
        }
        return view('admin.test.test', compact('test', 'groups'));
    }
 public function trim($id = 0) {
        echo "<pre>";
        if ($id) {
            $test = Test::find($id);
            $groups = $test->groups()->get();
            foreach ($groups as $group){
                $questions = $group->questions()->get();
                foreach($questions as $question){
                    var_dump($question);
                    $question->correct = trim($question->correct);
                    $answers = json_decode($question->answers);
                    var_dump($answers);
                    foreach ($answers as &$an){
                        $an = trim($an);
                    }
                    var_dump($answers);
                    $question->answers = json_encode($answers);
                    $question->save();
                }
                 
            }
        } else {
            return redirect('admin/tests/7');
        }
        return redirect('admin/tests/test/'.$id);
         
    }
	public function deleteQuestion($id){
		 $q = Question::find($id);
		 if($q){
			$q->delete();
			echo $q->id;
		 }
		 return back()->with("success", "Đã xóa câu hỏi");
	}
	public function ajaxSaveQuestion() {
        $qid = Input::get('qid', 1);
        $explaination = Input::get('explaination', "");
        $giai_thich = Input::get('giai_thich', "");
        $q = Question::find($qid);
        
        if(trim($explaination)){
            $q->explaination = trim($explaination);
        }
		 if(trim($giai_thich)){
            $q->giai_thich = trim($giai_thich);
        }
        $q->save();
        return  response()->json(array(
                    'success' => true ));                          
    }
    public function delete($id = 0) {
        $cat_id = Input::get('cat_id',1);
        $page = Input::get('page',1);
        if ($id) {
            $test = Test::find($id);
            $this->_deleteTest($test);
        }
       
        return redirect('admin/tests/'.$cat_id.'?page='.$page)->with('success',"Đã xóa thành công bài test: ".$test->title);
    }
    private function _deleteTest($test){
        
        $groups = $test->groups()->get();
        foreach($groups as $group){
            $questions = $group->questions()->get();
            foreach($questions as $question){
                $question->delete();
            }
            $group->articles()->sync([]);
            $group->delete();

        }
        $test->groups()->sync([]);
        $test->cats()->sync([]);
        $test->delete();
    }
    public function deleteAllTests($cat_id = 0) {
        
         if ($cat_id) {
           $cat = Categories::find($cat_id);
            $tests = $cat->tests;
            foreach ($tests as $test){
                $this->_deleteTest($test);
            }
            return redirect('admin/tests/'.$cat_id)->with('success',"Tất Cả Các Bài Tests trong mục này đã được xóa.");
        }
       return redirect('admin/categories')->with('error',"Không có quyền!");
        
    }

}
