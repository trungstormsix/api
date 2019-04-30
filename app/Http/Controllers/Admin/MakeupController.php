<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Makeup\Type;
use App\Models\Makeup\Article;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

class MakeupController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $categories = Type::paginate(10);
        return view('admin.makeups.home', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editCat($id = 0) {
        $category = $id ? Type::findOrFail($id) : null;
        return view('admin.makeups.edit_cat', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCat(Request $request) {
        $id = $request->get("id");
        $result = false;

        if ($id == 0) {
            $post_data = $request->all();
            $categories = new Type();
            $categories->title = $post_data['title'];

            $categories->description = $post_data['description'];
            $categories->title_display = $post_data['title_display'];
            $categories->published = $request->published ? $request->published : 0;
            $result = $categories->save();
        } else {
            $categories = Type::findOrFail($id);
            if ($categories) {
                $categories->published = $request->published ? $request->published : 0;
                $result = $categories->update($request->all());
            }
        }
        if ($result) {
            Session::flash('success', 'Categories saved successfully!');
        } else {
            Session::flash('error', 'Categories failed to save!');
        }
        if ($categories && $categories->id) {
            return redirect('admin/makeup/cat/edit/' . $categories->id);
        }
        return redirect('admin/makeup/cat/create');
    }

    public function deleteCat($id) {
        $categories = Type::find($id);
        $result = false;
        if ($categories) {
            $categories->articles()->sync();
            $result = $categories->delete();
        }
        if ($result) {
            Session::flash('success', 'Categories deleted successfully!');
        } else {
            Session::flash('error', 'Categories failed to delete!');
        }
        return redirect('admin/makeup/cat');
    }

    /**
     * articles
     */
    public function articles($cat_id = 0) {
        Session::set("mk_cat_id", $cat_id);
        $cat = null;
        if ($cat_id) {
            $cat = Type::find($cat_id);
            $articles = $cat->articles()->orderBy("date_edit", "DESC")->paginate(10);
            $cat = Type::find($cat_id);
        } else {
            $articles = Article::orderBy("date_edit", "DESC")->paginate(10);
        }
        return view('admin.makeups.articles', compact('articles', 'cat'));
    }

    public function editArticle($id = 0) {

        $artilce = null;
        if ($id) {
            $article = Article::findOrFail($id);

            if ($article) {
                if ($article->cats) {
                    foreach ($article->cats as $cat) {
                        $cat_ids[] = $cat->id;
                    }
                }
            }
        }
        if (!@$cat_ids) {
            $cat_ids = [Session::get("mk_cat_id")];
        }

        $categories = Type::all();
        return view('admin.makeups.edit_article', compact('article', 'categories', 'cat_ids'));
    }

    public function updateArticle(Request $request) {
        $id = $request->get("id");
        $result = false;
        $this->validate($request, [
            'title' => 'required|max:255',
        ]);
        if ($id == 0) {
            $articles = new Article();
            $articles->title = $request->title;
            $articles->save();
        } else {
            $articles = Article::findOrFail($id);
            if ($articles) {
                $articles->published = $request->published ? $request->published : 0;
            }
        }

        $result = $articles->update($request->all());
        $cats = $request->get("categories_id") ? $request->get("categories_id") : [];
        $articles->cats()->sync($cats);

        if ($result) {
            Session::flash('success', 'Article saved successfully!');
        } else {
            Session::flash('error', 'Article failed to save!');
        }

        if ($articles && $articles->id) {
            return redirect('admin/makeup/article/edit/' . $articles->id);
        }
        return redirect('admin/makeup/article/create');
    }

    public function deleteArticle($id = 0) {
        $page = Input::get('page', 1);

        if (!@$cat_ids) {
            $cat_ids = [Session::get("mk_cat_id")];
        }

        $artilce = null;
        if ($id) {
            $article = Article::findOrFail($id);

            if ($article) {
                $article->cats()->sync([]);
                $article->delete();
            }
        }


        if ($cat_ids) {
            return redirect('admin/makeup/articles/' . $cat_ids[0] . "?page=$page");
        }

        return redirect('admin/makeup/articles' . "?page=$page");
    }

    public function publishArticle($id = 0) {
        $page = Input::get('page', 1);

        if (!@$cat_ids) {
            $cat_ids = [Session::get("mk_cat_id")];
        }

        $artilce = null;
        if ($id) {
            $article = Article::findOrFail($id);

            if ($article) {
           
                 $article->published = $article->published == 1 ? 0 : 1;
                 
                $article->save();
            }
        }

        if ($cat_ids) {
            return redirect('admin/makeup/articles/' . $cat_ids[0] . "?page=$page");
        }

        return redirect('admin/makeup/articles' . "?page=$page");
    }

}
