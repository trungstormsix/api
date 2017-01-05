<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Articles;
use App\Models\Categories;
use Illuminate\Support\Facades\Session;

class ArticlesController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $articles = Articles::paginate(10);
        return view('admin.articles.home', ['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $articles_all = Articles::all();
        $categories = Categories::all();
        $categories_level = Categories::where(['parent_id' => 0])->get();
        return view('admin.articles.edit', ['articles_all' => $articles_all, 'categories' => $categories, 'categories_level' => $categories_level]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        
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
    public function edit($id) {
        $articles = Articles::findOrFail($id);
        $categories = Categories::all();
        $categories_level = Categories::where(['parent_id' => 0])->get();
        return view('admin.articles.edit', compact('articles', 'categories', 'categories_level'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        $id = $request->get("id");
        $result = false;
        if ($id == 0) {
            $post_data = $request->all();
            $articles = new Articles();
            $articles->title = $post_data['title'];
            if (!$post_data['alias'] == '') {
                $articles->alias = $post_data['alias'];
            } else {
                $articles->alias = str_slug($post_data['title'], '-');
            }
            $articles->thumbnail = $post_data['thumbnail'];
            $articles->link = $post_data['link'];
            $articles->content = $post_data['content'];
            $articles->excerpt = $post_data['excerpt'];
            $articles->categories_id = $post_data['categories_id'];
            $articles->published = $request->published ? $request->published : 0;
            $result = $articles->save();
        } else {
            $articles = Articles::findOrFail($id);
            if ($articles) {
                $articles->published = $request->published ? $request->published : 0;
                $result = $articles->update($request->all());
            }
        }
        if ($result) {
            Session::flash('success', 'Article saved successfully!');
        } else {
            Session::flash('error', 'Article failed to save successfully!');
        }
        if ($articles && $articles->id) {
            return redirect('admin/articles/edit/' . $articles->id);
        }
        return redirect('admin/articles/create');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function delete($id) {
        $articles = Articles::find($id);
        $articles->delete();
        return redirect('admin/articles');
    }

}
