<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ListeningCat;
use App\Models\ListeningDialog;
use App\Models\GrammarLesson;
use App\Http\Controllers\Controller;

class ListeningController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       
        $dialogs = ListeningDialog::where('updated','>',  Input::get('max_date', '0000-00-00 00:00:00'))->where('status',1)->take(20)->orderBy("updated", 'asc')->get();
        $return = [];
        foreach ($dialogs as $dialog) {
            $dl = clone $dialog;
            $cs = $dialog->cats;
            $cats = [];
            foreach($cs as $cat){
                $c = new \stdClass();
                $c->id = $cat->id;
                $c->title = $cat->title;
                $c->ordering = $cat->pivot->ordering;
                $cats[] = $c;
            }
             $dl->cats = $cats;
            $grammars = [];
            foreach ($dialog->grammars as $gr) {
                $grammar = new \stdClass();
                $grammar->id = $gr->id;
                $grammar->title = $gr->title;
                $grammar->sentence = $gr->pivot->ex;
                $grammars[] = $grammar;
            }
            $dl->grammars = $grammars;
            $dl->questions = $dialog->questions();
            $return[] = $dl;
        }

        return response()->json($return);
    }

    public function dialog($id) {
        $video = Video::all()->sortByDesc("updated_at");

        return $video;
    }

}
