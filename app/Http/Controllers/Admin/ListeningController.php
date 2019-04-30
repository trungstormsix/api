<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\User;
use App\Models\ListeningCat;
use App\Models\ListeningDialog;
use File;
use Illuminate\Support\Facades\Session;
use App\Models\ListeningQuestion;

class ListeningController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * list all cats
     * @return type
     */
    public function index() {
        $cats = ListeningCat::all();
        return view('admin/listening/cats', ['cats' => $cats]);
    }

    /**
     * get all listening from a cat
     * @param type $cat_id
     * @return type
     */
    public function dialogs($cat_id) {
        $cat = ListeningCat::find($cat_id);

        if (@$_GET['sort_by']) {
            Session::put('sort_by', $_GET['sort_by']);
            $dimen = @$_GET['sort_dimen'] ? $_GET['sort_dimen'] : 'asc';
            Session::put('sort_dimen', $dimen);
        }
        $sort_by = Session::get('sort_by', 'liked');
        $dimen = Session::get('sort_dimen', 'desc');
        $dialogs = $cat->dialogs()->orderBy($sort_by, $dimen)->paginate(30);

        return view('admin/listening/dialogs', ['cat' => $cat, 'dialogs' => $dialogs, 'sort_by' => $sort_by, 'sort_dimen' => $dimen]);
    }

    /**
     * search listening
     * @return type
     */
   

    public function getDialog($id) {
        $dialog = ListeningDialog::find($id);
        $question_ids = json_decode($dialog->question);
        $questions = ListeningQuestion::whereIn('id', $question_ids)->get();

        return view('admin/listening/dialog', compact('dialog', 'questions'));
    }

    public function postQuestionAjax(Request $req) {
        $dl_id = $req->dlId;
        if (!$dl_id) {
            return response()->json(['success' => false, 'message' => "Can not found lesson"]);
        }
        $dialog = ListeningDialog::find($dl_id);
        if (!$dialog) {
            return response()->json(['success' => false, 'message' => "Can not found lesson"]);
        }
        $question_ids = [];
        $questions = null;
        if ($dialog->question && $dialog->question != "null") {
            $question_ids = json_decode($dialog->question);
            $questions = ListeningQuestion::whereIn('id', $question_ids)
                    ->get();
        }
        $q = trim($req->q);
        $c = trim($req->c);
        $ans_raw = $req->ans;
        $ans = [];
        foreach ($ans_raw as $a) {
            if ($a["value"]) {
                $ans[] = trim($a["value"]);
            }
        }
        $ans = array_unique($ans);
        if (!$q) {
            return response()->json(['success' => false, 'message' => "please add Question"]);
            ;
        }
        if (!in_array($c, $ans)) {
            return response()->json(['success' => false, 'message' => "please add correct answer in the answers"]);
            ;
        }
        if ($questions) {
            foreach ($questions as $eq) {
                if ($eq->question == $q) {
                    return response()->json(['success' => false, 'message' => "Question exist"]);
                    ;
                }
            }
        }

        $question = new ListeningQuestion();
        $question->question = $q;
        $question->correct = $c;
        $question->answers = json_encode($ans);
        $question->save();
        $question_ids[] = $question->id;
        $dialog->question = json_encode($question_ids);
        $dialog->save();
        return response()->json(['success' => true, 'message' => "Question added sucessfully"]);
    }

    public function postDialog(Request $req) {

        if ($req->id) {
            $answers = $req->questions_an;
            $corrects = $req->questions_correct;
            $questions = $req->questions;
            $questions_id = [];
            foreach ($questions as $qid => $q) {
                $qans = array_filter($answers[$qid]);
                $question = ListeningQuestion::find($qid);
                if (!$q) {
                    $question->delete();
                    continue;
                }
                $question->question = $q ? $q : $question->question;
                $question->correct = $corrects[$qid] ? $corrects[$qid] : $question->correct;
                if (!in_array($question->correct, $qans)) {
                    continue;
                }
                $question->answers = json_encode($qans);
                $question->save();
                $questions_id[] = $qid;
            }

            $dialog = ListeningDialog::find($req->id);
            $dialog->status = $req->status ? 1 : 0;
            $dialog->dialog = $req->dialog;
            $dialog->vocabulary = $req->vocabulary;
            $dialog->title = trim($req->title) ? trim($req->title) : $dialog->title;
            $dialog->question = json_encode($questions_id);
            $dialog->save();
            return Redirect::to('/admin/listening/dialog/' . $dialog->id);
        }
        Input::flash();
        return Redirect::to('/admin/listening/add-cat');
    }

    /**
     * get Playlist
     */
    public function getCat($id = 0) {
        $cat = null;
        if ($id) {
            $cat = IdiomCat::find($id);
        } else {
            
        }
        return view('admin/listening/cat', ['cat' => $cat]);
    }

    /**
     * save cat
     */
    public function postCat(Request $req) {
        if ($req->id) {
            $cat = IdiomCat::find($req->id);
        } else {
            $cat = IdiomCat::where('title', $req->title)->first();
            if (!$cat) {
                $cat = new IdiomCat();
            }
        }
        if ($req->title) {
            $cat->title = $req->title;
            $cat->save();
            return Redirect::to('/admin/listening/' . $cat->id);
        }
        Input::flash();
        return Redirect::to('/admin/listening/add-cat');
    }

    public function searchDialog() {
        $search = Input::get('search', "");
        if (@$_GET['sort_by']) {
            Session::put('sort_by', $_GET['sort_by']);
            $dimen = @$_GET['sort_dimen'] ? $_GET['sort_dimen'] : 'asc';
            Session::put('sort_dimen', $dimen);
        }
        $sort_by = Session::get('sort_by', 'status');
        $dimen = Session::get('sort_dimen', 'desc');
        $dialogs = ListeningDialog::where("title", "like", "%" . $search . "%")->orWhere("audio", "like", "%" . $search . "%")->orderBy($sort_by, $dimen)->paginate(20);
        if(!$dialogs || $dialogs->isEmpty() ){
             
            $dialogs = ListeningDialog::where("dialog", "like", "%" . $search . "%")->orderBy($sort_by, $dimen)->paginate(20);
        }
//        return view('admin/ielts/articles', ['articles' => $articles, "cat" => null, 'sort_by' => $sort_by, 'sort_dimen' => $dimen, 'search' => $search]);
        return view('admin/listening/dialogs', ['cat' => null, 'dialogs' => $dialogs, 'sort_by' => $sort_by, 'sort_dimen' => $dimen, 'search' => $search]);

        
        }

    /*     * *********************
     * ********* ajax ******
     * ******************** */

    public function removeCat() {
        $cat = Input::get('cat_id', '0');
        $dl_id = Input::get('main_id', '0');
        $dl = ListeningDialog::find($dl_id);
        $dl->cats()->detach($cat);
        return response()->json(['cat' => $cat, 'main_id' => $dl_id]);
    }

    public function ajaxAddCat() {
        $cat = Input::get('cat_id', '0');
        $dl_id = Input::get('dl_id', '0');
        $dl = ListeningDialog::find($dl_id);
        $changed = $dl->cats()->syncWithoutDetaching($cat);
        if ($changed) {
            $dl->touch();
        }
        return response()->json(['cat' => $cat, 'main_id' => $dl_id, 'changed' => $changed]);
    }

    public function ajaxRemoveGrammar() {
        $gr_id = Input::get('gr_id', '0');
        $dl_id = Input::get('main_id', '0');
        $dl = ListeningDialog::find($dl_id);
        $dl->grammars()->detach($gr_id);
        return response()->json(['gr_id' => $gr_id, 'main_id' => $dl_id]);
    }

    public function ajaxAddGrammar() {
        $gr_id = Input::get('gr_id', '0');
        $dl_id = Input::get('dl_id', '0');
        $dl = ListeningDialog::find($dl_id);
        $changed = $dl->grammars()->syncWithoutDetaching([$gr_id => ['ex' => Input::get('ex', null)]]);
        if ($changed) {
            $dl->touch();
        }
        return response()->json(['gr_id' => $gr_id, 'main_id' => $dl_id, 'changed' => $changed]);
    }

    public function ajaxGetGrammars() {
        $cat_term = Input::get('term', '');
        $cats = \App\Models\GrammarLesson::where('title', 'like', "%$cat_term%")->where('published', 1)->take(20)->get();

        $return = [];
        foreach ($cats as $cat) {
            $return[] = ['key' => $cat->id, 'value' => $cat->title];
        }

        return response()->json($return);
    }

    public function ajaxGetCats() {
        $cat_term = Input::get('term', '');

        $cats = ListeningCat::where('title', 'like', "%$cat_term%")->take(20)->get();

        $return = [];
        foreach ($cats as $cat) {
            $return[] = ['key' => $cat->id, 'value' => $cat->title];
        }
        return response()->json($return);
    }

    public function ajaxFixReport() {
        $report_id = Input::get('report_id', '0');
        if ($report_id) {
            $report = \App\Models\ListeningReport::find($report_id);
            $report->status = 1;
            $report->save();
            $dialog = $report->dialog;
            \Illuminate\Support\Facades\Mail::send('emails.listening_report', ['dialog' => $dialog, 'report' => $report], function ($m) use ($dialog, $report) {
                $m->from('supporter@ocodereducation.com', '[English Listening]');
                $m->replyTo("trungstormsix@gmail.com", "Admin");
                $m->cc("trungstormsix@gmail.com", "Admin");
                $m->to($report->email, $report->email)->subject('[English Listening] Fixed your problem!');
            });
        }
        return response()->json(['report_id' => $report_id]);
    }

    public function ajaxUpdateOrder() {
        $cat_id = Input::get('cat_id', '0');
        $ordering = Input::get('ordering', '0');
        $dialog_id = Input::get('dialog_id', '0');
        $dl = ListeningDialog::find($dialog_id);
        $changed = $dl->cats()->syncWithoutDetaching([$cat_id => ['ordering' => $ordering]]);
        if ($changed) {
            $dl->touch();
        }
        return response()->json(['cat_id' => $cat_id, 'dialog_id' => $dialog_id, 'changed' => $changed]);
    }

    public function reports() {
        $reports = \App\Models\ListeningReport::where("status", 0)->orderBy("updated", "DESC")->paginate(30);
        return view('admin/listening/reports', ['reports' => $reports]);
    }

}
