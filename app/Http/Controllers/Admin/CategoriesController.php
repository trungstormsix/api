<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Support\Facades\Session;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Categories::paginate(10);
        return view('admin.categories.home', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Categories::all();
        $categories_level = Categories::where(['parent_id' => 0])->get();
        return view('admin.categories.edit', ['categories' => $categories, 'categories_level' => $categories_level]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories_item = Categories::findOrFail($id);
        $categories = Categories::all();
        $categories_level = Categories::where(['parent_id' => 0])->get();
        return view('admin.categories.edit',compact('categories', 'categories_item', 'categories_level'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->get("id");
        $result = false;

        if ($id == 0) {
            $post_data = $request->all();
            $categories = new Categories();
            $categories->name = $post_data['name'];
            if(!$post_data['alias'] == '') {
                $categories->alias = $post_data['alias'];
            }
            else {
                $categories->alias = str_slug($post_data['name'], '-');
            }
            $categories->description = $post_data['description'];
            $categories->parent_id = $post_data['parent_id'];
            $categories->published = $request->published ? $request->published : 0;
            $result = $categories->save();
        }
        else {
            $categories = Categories::findOrFail($id); 
            if($categories) {
                $categories->published = $request->published ? $request->published : 0;
                $result = $categories->update($request->all());
            } 
        }
        if ($result) {
            Session::flash('success', 'Categories saved successfully!');
        } else {
            Session::flash('error', 'Categories failed to save successfully!');
        }
        if ($categories && $categories->id) {
            return redirect('admin/categories/edit/' . $categories->id);
        }
        return redirect('admin/categories/create');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function delete($id)
    {
        $categories = Categories::find($id);
        $categories->delete();
        return redirect('admin/categories');
    }
}
