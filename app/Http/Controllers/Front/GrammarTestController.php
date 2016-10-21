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

class GrammarTestController extends Controller {

    function index(){
        $cats = GrammarCat::orderBy('id', 'asc')->paginate(50);       
        return view('front/grammarCats', ['cats' => $cats]);
    }

    function tests($id){
        $cat = GrammarCat::find($id);   
        $questions = $cat->questions()->paginate(5);       
        return view('front/grammarTest', ['questions' => $questions]);
    }
     function postTests($id,Request $request){      
        $cat = GrammarCat::find($id);   
        $questions = $cat->questions()->paginate(5);    
        $answered = array();
        $totalCorrect = 0;
        foreach ($questions as $question){
            $answered[] = $request->get($question->id);
            $question->answered  = $request->get($question->id);
            if($request->get($question->id) == $question->correct){
                $totalCorrect++;
             }
        }
        
        return view('front/grammarTest', ['questions' => $questions,'answered' => $answered,'totalCorrect'=>$totalCorrect]);
    }
    function questions(){
        $questions = GrammarQuestion::orderBy('id', 'asc')->paginate(50);       
        return view('front/grammarTest', ['cats' => $cats]);
    }
}
