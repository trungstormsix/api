<?php

namespace App\Http\Controllers\Admin\IELTS;

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
use App\Models\IELTS\IELTSCat;
use App\Models\IELTS\IELTSArticle;

class IELTSController extends Controller {

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
        $cats = IELTSCat::where("type", "article")->get(); //all();
        return view('admin/ielts/cats', ['cats' => $cats]);
    }

    public function vocabulary() {
        $cats = IELTSCat::where("type", "General")->orWhere("type", "Academic")->get(); //all();
        return view('admin/ielts/cats', ['cats' => $cats]);
    }

    /**
     * list all cats
     * @return type
     */
    public function editCat($id = 0) {
        $cat = IELTSCat::find($id); //all();
        return view('admin/ielts/cat', ['cat' => $cat]);
    }

    /**
     * save cat
     */
    public function postCat(Request $req) {
        if ($req->id) {
            $cat = IELTSCat::find($req->id);
        } else {
            $cat = IELTSCat::where('title', $req->title)->first();
            if (!$cat) {
                $cat = new IELTSCat();
            }
        }
        $cat->type = $req->type;

        if ($req->title) {
            Session::flash('success', 'IELTS Category saved successfully!');
            $cat->title = $req->title;
            $cat->save();
            return Redirect::to('/admin/ielts/edit-cat/' . $cat->id);
        }
        Session::flash('error', 'IELTS Category fail to save!');


        Input::flash();
        return Redirect::to('/admin/ielts/add-cat');
    }

    public function listAll($cat_id = 0) {
        $cat = IELTSCat::find($cat_id);
        if ($cat->type == "article") {
            return $this->_getArticles($cat);
        } else {
            $this->_getVocs($cat_id);
        }
    }

    private function _getArticles($cat) {
        if (@$_GET['sort_by']) {
            Session::put('sort_by', $_GET['sort_by']);
            $dimen = @$_GET['sort_dimen'] ? $_GET['sort_dimen'] : 'asc';
            Session::put('sort_dimen', $dimen);
        }
        $sort_by = Session::get('sort_by', 'status');
        $dimen = Session::get('sort_dimen', 'desc');
        $articles = $cat->articles()->orderBy($sort_by, $dimen)->paginate(20);

//        dd($articles);
        return view('admin/ielts/articles', ['articles' => $articles, "cat" => $cat, 'sort_by' => $sort_by, 'sort_dimen' => $dimen]);
    }

    public function editArticle($article_id) {
        $cats = IELTSCat::where("type", "article")->get();

        $article = IELTSArticle::find($article_id); //all();
        return view('admin/ielts/article', ['article' => $article, 'cats' => $cats]);
    }

    /**
     * save cat
     */
    public function postArticle(Request $req) {

        $this->validate($req, [
            'title' => 'required|max:255',
        ]);
        if ($req->id) {
            $article = IELTSArticle::find($req->id);
        } else {
            $this->validate($req, [
                'title' => 'required|unique:il_articles|max:255',
            ]);
            $article = new IELTSArticle();
            $article->save($req->all());
        }
        $req->status = $req->status ? 1 : 0;
        $article->status = $req->status ? $req->status : 0;
        $result = $article->update($req->all());
 
        if ($result) {
            Session::flash('success', 'IELTS Article saved successfully!');
            return Redirect::to('/admin/ielts/article/' . $article->id);
        }
        Session::flash('error', 'IELTS Article fail to save!');
        Input::flash();
        if ($article && $article->id) {
            return Redirect::to('/admin/ielts/article/' . $article->id);
        }
        return Redirect::to('/admin/ielts/article/add');
    }

}
