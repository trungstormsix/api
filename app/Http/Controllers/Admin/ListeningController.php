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
    public function search() {
        $search = \Illuminate\Support\Facades\Input::get('idiom');

        $listening = Idiom::where('word', 'like', '%' . $search . '%')->paginate(30);

        return view('admin/listening/listening', ['listening' => $listening, 'search' => $search]);
    }

    public function getDialog($id) {
        $dialog = ListeningDialog::find($id);
        return view('admin/listening/dialog', ['dialog' => $dialog]);
    }

    public function postDialog(Request $req) {
        echo $req->status;

        if ($req->id) {
            $dialog = ListeningDialog::find($req->id);
            $dialog->status = $req->status ? 1 : 0;
            $dialog->dialog = $req->dialog;
            $dialog->vocabulary = $req->vocabulary;
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
